<?php
/**
 * Created by PhpStorm.
 * User: thanatos
 * Date: 2018/11/20
 * Time: 10:08 AM
 */

namespace app\modules\mch\models;


use app\models\Order;
use app\models\Shop;
use app\models\User;
use yii\data\Pagination;

class ShopOrderListForm extends MchModel
{

    public $date_start;
    public $date_end;
    public $status;
    public $shop;//所属门店
    public $limit;
    public $page;

    public function rules()
    {
        return [
            [['date_start', 'date_end'], 'trim'],
            [['status', 'shop', 'limit', 'page'], 'integer']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = Shop::find()->alias('s')
            ->leftJoin(Order::tableName() . 'as o', 'o.shop_id = s.id')
            ->select('sum(o.pay_price) as total_price, count(o.id) as num, sum(o.share_price) as share_price, s.*')->groupBy('s.id')
            ->andWhere(['s.store_id' => $this->store->id]);
        $newQuery = Order::find();

        switch ($this->status) {
            case 0:
                $query->andWhere(['o.is_pay' => 0]);
                $newQuery->andWhere(['is_pay' => 0]);
                break;
            case 1:
                $query->andWhere([
                    'o.is_send' => 0,
                ])->andWhere(['or', ['o.is_pay' => 1], ['o.pay_type' => 2]]);
                $newQuery->andWhere([
                    'is_send' => 0,
                ])->andWhere(['or', ['is_pay' => 1], ['pay_type' => 2]]);
                break;
            case 2:
                $query->andWhere([
                    'o.is_send'    => 1,
                    'o.is_confirm' => 0,
                ])->andWhere(['or', ['o.is_pay' => 1], ['o.pay_type' => 2]]);
                $newQuery->andWhere([
                    'is_send'    => 1,
                    'is_confirm' => 0,
                ])->andWhere(['or', ['is_pay' => 1], ['pay_type' => 2]]);
                break;
            case 3:
                $query->andWhere([
                    'o.is_send'    => 1,
                    'o.is_confirm' => 1,
                ])->andWhere(['or', ['o.is_pay' => 1], ['o.pay_type' => 2]]);
                $newQuery->andWhere([
                    'is_send'    => 1,
                    'is_confirm' => 1,
                ])->andWhere(['or', ['is_pay' => 1], ['pay_type' => 2]]);
                break;
            case 4:
                break;
            case 5:
                break;
            case 6:
                $query->andWhere(['o.apply_delete' => 1]);
                $newQuery->andWhere(['apply_delete' => 1]);
                break;
            default:
                break;
        }

        if ($this->date_start) {
            $query->andWhere(['>=', 'o.addtime', strtotime($this->date_start)]);
            $newQuery->andWhere(['>=', 'addtime', strtotime($this->date_start)]);
        }
        if ($this->date_end) {
            $query->andWhere(['<=', 'o.addtime', strtotime($this->date_end) + 86400]);
            $newQuery->andWhere(['<=', 'addtime', strtotime($this->date_end) + 86400]);
        }

        if ($this->shop) {
            $query->andWhere(['s.id' => $this->shop]);
        }

        // 查询平台总计数据
        $newQuery->select('sum(pay_price) as total_price, count(id) as num, sum(share_price) as share_price');
        $sumData = $newQuery->asArray()->one();

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1, 'route'=>\Yii::$app->requestedRoute]);

        $list = $query->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();



        return [
            'row_count'  => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list'       => $list,
            'sumData' => $sumData
        ];
    }

}