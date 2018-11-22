<?php
/**
 * Created by PhpStorm.
 * User: thanatos
 * Date: 2018/11/22
 * Time: 10:06 AM
 */

namespace app\modules\mch\models;


use app\models\Dingshi;
use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;

class ShopStatisticalForm extends MchModel
{
    public $date;
    public $store_id;

    public function rules()
    {
        return [
            [['date'], 'string'],
            ['store_id', 'integer']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return false;
        }

        // 计算订单开始和结束时间
        $dingshi = Dingshi::findOne(['store_id' => $this->store->id]);
        $nowTime = strtotime($this->date);
        $time = $nowTime ?: time();
        $hour = date('H');
        //TODO 开始和结束时间需要测试
        if ($dingshi->end_time < $hour && $hour <= 24) {
            $start_date = date('Y-m-d', $time);
            $end_date = strtotime('+1 days', $time);
        } else {
            $start_date = date('Y-m-d', strtotime('-1 days', $time));
            $end_date = date('Y-m-d', $time);
        }
        // 订单开始时间
        $start = strtotime($start_date);
        // 订单结束时间
        $end = strtotime($end_date);

        // 查出售出的所有商品
        $query = Order::find()->alias('o')->leftJoin(OrderDetail::tableName() . 'as od', 'o.id = od.order_id')
//            ->andWhere(['and', ['>=', 'o.addtime', $start], ['<', 'o.addtime', $end]])
            ->andWhere(['o.is_pay' => 1])
            ->leftJoin(Goods::tableName() . 'as g', 'g.id = od.goods_id')
            ->select('g.name,sum(od.num) as num')->orderBy(['o.addtime' => SORT_DESC])->groupBy('od.goods_id');

        $list = $query->asArray()->all();

        return [
            'list' => $list,
            'date' => $start_date
        ];

    }

}