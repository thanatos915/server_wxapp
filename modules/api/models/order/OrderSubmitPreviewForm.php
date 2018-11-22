<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/26 15:42
 */


namespace app\modules\api\models\order;


use app\models\User;

class OrderSubmitPreviewForm extends OrderForm
{
    public function rules()
    {
        return parent::rules();
    }

    public function search()
    {
        if (!$this->validate())
            return $this->getErrorResponse();
        try{
            $mchList = $this->getMchListData();
            if($mchList['code'] == 1){
                return $mchList;
            }
        }catch(\Exception $e){
            return [
                'code'=>1,
                'msg'=>$e->getMessage()
            ];
        }
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        return [
            'code' => 0,
            'msg' => 'OK',
            'data' => [
                'pay_type_list' => $this->getPayTypeList(),
                'address' => $this->address,
                'level' => $this->getLevelData(),
                'mch_list' => $mchList,
                'integral' => $this->integral,
                'is_shop_admin' => ($user->is_shop_admin && $user->shop_id) ? true : false
            ],
        ];
    }
}