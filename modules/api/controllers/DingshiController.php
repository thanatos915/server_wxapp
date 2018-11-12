<?php
/**
 * @user: thanatos <thanatos915@163.com>
 */

namespace app\modules\api\controllers;


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

}