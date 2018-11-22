<?php
/**
 * @user: thanatos <thanatos915@163.com>
 */

namespace app\modules\mch\models;


use app\models\Dingshi;
use app\models\Order;
use app\models\Shop;
use app\modules\api\models\ApiModel;

class ShopSendForm extends ApiModel
{
    public $id;


    public function rules()
    {
        return [
            ['id', 'integer']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $shop = Shop::findOne(['id' => $this->id, 'store_id' => $this->store->id, 'is_delete' => 0]);
        $dingshi = Dingshi::findOne(['store_id' => $this->store->id]);
        // 检测当前时间
        $time = date('H');
        if ($dingshi->end_time < $time && $time <= 24) {
            $start_date = date('Y-m-d');
            $end_date = strtotime('+1 days');
        } else {
            $start_date = date('Y-m-d', strtotime('-1 days'));
            $end_date = date('Y-m-d');
        }
        // 订单开始时间
        $start = strtotime($start_date);
        // 订单结束时间
        $end = strtotime($end_date);
        $query = Order::find();
        $query->andWhere(['shop_id' => $shop->id])
            ->andWhere(['and', ['>=', 'addtime', $start], ['<', 'addtime', $end]])
            ->andWhere(['is_pay' => 1]);

        $order_list = $query->all();

        $goods = [];
        foreach ($order_list as $order) {
            foreach ($order->goods as $good) {
                $goods[$good->id]['name'] = $good->name;
            }
        }

        foreach ($order_list as $order) {
            foreach ($order->detail as $detail) {
                // 增加购买数量
                $goods[$detail->goods_id]['num'] += $detail->num;
                // 增加购买记录
                if (is_array($goods[$detail->goods_id]['buys'][$order->user_id])) {
                    $goods[$detail->goods_id]['buys'][$order->user_id]['num'] += 1;
                } else {
                    $goods[$detail->goods_id]['buys'][$order->user_id] = [
                        'username' => $order->name,
                        'num' => $detail->num
                    ];
                }
            }
        }

        $list = [];
        foreach ($goods as $v) {
            $list[] = $v;
        }
        return [
            'start' => $start_date,
            'shop' => $shop,
            'list' => $list
        ];

    }

}