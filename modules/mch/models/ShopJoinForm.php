<?php
/**
 * Created by PhpStorm.
 * User: thanatos
 * Date: 2018/11/22
 * Time: 4:25 PM
 */

namespace app\modules\mch\models;


use app\models\District;
use app\models\ShopJoin;
use app\models\User;
use yii\data\Pagination;

class ShopJoinForm extends MchModel
{
    public $status;
    public $store_id;
    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['store_id', 'status'], 'integer'],
            [['page'],'default','value'=>1],
            [['limit'],'default','value'=>20]
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return false;
        }

        $query = ShopJoin::find()->alias('s')->where(['s.store_id' => $this->store_id])
            ->leftJoin(User::tableName() . 'as u' , 'u.id = s.user_id')
            ->leftJoin(District::tableName() . 'as dp', 'dp.id = s.province')
            ->leftJoin(District::tableName() . 'as dc', 'dc.id = s.city')
            ->leftJoin(District::tableName() . 'as dd', 'dd.id = s.district');

        if ($this->status) {
            $query->andWhere(['s.status' => $this->status]);
        } else {
            // 默认查询未处理条目
            $query->andWhere(['s.status' => 0]);
        }

        $count = $query->count();
        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);
        $list = $query->select('s.*, u.nickname, dp.name as province_name, dc.name as city_name, dd.name as district_name')->offset($p->offset)->limit($p->limit)->orderBy(['s.created_at'=>SORT_DESC])->asArray()->all();

        return [
            'list'=>$list,
            'row_count'=>$count,
            'pagination'=>$p
        ];
    }

}