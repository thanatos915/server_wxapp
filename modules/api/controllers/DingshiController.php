<?php
/**
 * @user: thanatos <thanatos915@163.com>
 */

namespace app\modules\api\controllers;


use app\hejiang\BaseApiResponse;
use app\modules\api\models\dingshi\DetailsForm;
use app\modules\api\models\DingshiCatListForm;
use app\modules\api\models\DingshiGoodsListForm;

class DingshiController extends Controller
{

    public function actionCatList()
    {
        $form = new DingshiCatListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        return $form->search();
    }

    public function actionGoodsList()
    {
        $form = new DingshiGoodsListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        return $form->search();
    }

    /**
     * 秒杀商品详情
     */
    public function actionDetails()
    {
        $form = new DetailsForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        if (!\Yii::$app->user->isGuest) {
            $form->user_id = \Yii::$app->user->id;
        }
        return new BaseApiResponse($form->search());
    }

}