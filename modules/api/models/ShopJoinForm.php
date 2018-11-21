<?php
/**
 * Created by PhpStorm.
 * User: thanatos
 * Date: 2018/11/21
 * Time: 3:21 PM
 */

namespace app\modules\api\models;


use app\models\ShopJoin;

class ShopJoinForm extends ApiModel
{
    public $community;
    public $province;
    public $name;
    public $mobile;
    public $district;
    public $city;

    public function rules()
    {
        return [
            [['province', 'city', 'district', 'name', 'mobile', 'community', 'city'], 'required'],
            [['province', 'city', 'district'], 'integer'],
            [['name', 'mobile', 'community'], 'string']
        ];
    }

    public function submit($params)
    {
        $this->load($params, '');

        if (!$this->validate()) {
            return false;
        }

        $model = new ShopJoin();
        $model->attributes = $this->attributes;
        if (!$model->save()) {
            return false;
        }

        return $model;

    }

}