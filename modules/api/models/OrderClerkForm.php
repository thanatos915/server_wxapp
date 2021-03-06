<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8
 * Time: 17:20
 */

namespace app\modules\api\models;

use app\models\MsOrder;
use app\models\Order;
use app\models\User;
use app\models\UserShareMoney;
use app\utils\PinterOrder;

class OrderClerkForm extends ApiModel
{
    public $order_no;
    public $order_id;
    public $store_id;
    public $user_id;

    public function save()
    {
        if (stripos($this->order_no, 'M') > -1) {
            $order = MsOrder::find()->where(['order_no' => $this->order_no, 'store_id' => $this->store_id])->andWhere(['or', ['is_pay' => 1], ['pay_type' => 2]])->one();
            $type = 1;
        } else {
            if ($this->order_id) {
                $order = Order::find()->where(['id' => $this->order_id, 'store_id' => $this->store_id])->andWhere(['or', ['is_pay' => 1], ['pay_type' => 2]])->one();
            } else {
                $order = Order::find()->where(['order_no' => $this->order_no, 'store_id' => $this->store_id])->andWhere(['or', ['is_pay' => 1], ['pay_type' => 2]])->one();
            }
            $type = 0;
        }
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '网络异常-1'
            ];
        }
        $user = User::findOne(['id' => $this->user_id]);
        $shopUser = $order->shop->user;
        if ($user->is_shop_admin == 0) {
            return [
                'code' => 1,
                'msg' => '没有权限核销订单'
            ];
        }
        /**TODO 核销员检测
        if ($user->is_clerk == 0) {
            return [
                'code' => 1,
                'msg' => '不是核销员'
            ];
        }*/
        if ($order->is_confirm== 1) {
            return [
                'code' => 1,
                'msg' => '订单已核销'
            ];
        }
        $order->clerk_id = $user->id;
        $order->is_send = 1;
//        $order->shop_id = $user->shop_id;
        $order->send_time = time();
        $order->is_confirm = 1;
        // 判断是否参与分销
        if ($order->user_id !== $shopUser->id) {
            $order->is_price = 1;
            // 计算分成金额
            $details = $order->detail;
            $share_price = 0;
            foreach ($details as $k => $item) {
                $commission = $item->goods->shop_share_commission ?: 15;
                $share_price += ($commission / 100) * $item->total_price;
            }
            $order->share_price = doubleval($share_price);
        }
        $order->confirm_time = time();
        if ($order->pay_type == 2) {
            $order->is_pay = 1;
            $order->pay_time = time();
        }
        if ($order->save()) {
            // 增加分销金额
            if ($order->is_price && $order->share_price > 0) {
                $shopUser->total_price += $order->share_price;
                $shopUser->price += $order->share_price;
                $shopUser->save();
                UserShareMoney::set($order->pay_price * 0.15, $shopUser->id, $order->id, 0, 4, $this->store_id, 2);
            }

            $printer_order = new PinterOrder($this->store_id, $order->id, 'confirm', $type);
            $res = $printer_order->print_order();
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常'
            ];
        }
    }
}
