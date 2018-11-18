<?php
/**
 * Created by PhpStorm.
 * User: thanatos
 * Date: 2018/11/18
 * Time: 6:50 PM
 */

namespace app\modules\api\models\dingshi;


use app\hejiang\ApiResponse;
use app\models\Order;
use app\models\OrderDetail;
use app\models\User;
use app\modules\api\models\ApiModel;
use yii\data\Pagination;

class DingshiRecordForm extends ApiModel
{

    public $store_id;
    public $limit;
    public $goods_id;
    public $page;

    public function rules()
    {
        return [
            [['store_id', 'limit', 'page', 'goods_id'], 'integer'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = Order::find()->alias('o')->innerJoin(OrderDetail::tableName() . 'as d', 'd.order_id = o.id')->where(['d.goods_id' => $this->goods_id, 'o.store_id' => $this->store_id])->groupBy('o.id')->orderBy(['addtime' => SORT_DESC])->leftJoin(User::tableName() . 'as u', 'o.user_id = u.id')->leftJoin(OrderDetail::tableName() . 'as od', 'od.order_id = o.id and od.goods_id = '. $this->goods_id);


        if ($this->limit) {
            $query->limit($this->limit);
        }



        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);

        $list = $query->select('u.nickname,u.avatar_url,o.addtime,od.num')->limit($pagination->limit)
            ->offset($pagination->offset)->asArray()->all();

        $data = [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'list' => $list,
        ];
        return new ApiResponse(0, 'success', $data);

    }

}