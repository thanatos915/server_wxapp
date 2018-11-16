<?php
/**
 * @user: thanatos <thanatos915@163.com>
 */

namespace app\modules\api\models;


use app\hejiang\ApiResponse;
use app\models\Order;
use app\models\OrderDetail;
use app\models\User;
use yii\data\Pagination;

class BuyRecordListForm extends ApiModel
{
    public $goods_id;
    public $page = 1;
    public $limit = 20;

    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['page'], 'integer'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = Order::find()->select(['o.addtime', 'u.nickname', 'od.num', 'u.avatar_url'])->alias('o')->innerJoin(['od' => OrderDetail::tableName()], 'od.order_id = o.id and o.is_pay = 1 and o.is_send = 1 and o.is_confirm = 1')->leftJoin(['u' => User::tableName()], 'o.user_id = u.id')->where(['od.goods_id' => $this->goods_id]);

        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);

        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('o.addtime DESC')->asArray()->all();
        $data = [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'list' => $list,
        ];
        return new ApiResponse(0, 'success', $data);
    }

}