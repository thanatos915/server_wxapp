<?php
/**
 * @user: thanatos <thanatos915@163.com>
 */

namespace app\modules\mch\controllers;


use app\models\Dingshi;
use app\models\DingshiGoods;
use app\models\Goods;
use app\models\GoodsSearch;
use app\models\GoodsSearchForm;
use app\modules\mch\models\DingshiGoodsEditForm;

class DingshiController extends Controller
{
    public function actionIndex()
    {
        $model = Dingshi::findOne([
            'store_id' => $this->store->id,
        ]);
        if (!$model) {
            $model = new Dingshi();
            $model->store_id = $this->store->id;
        }
        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post(), '');
            $model->save();
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    public function actionGoods()
    {
        $models = DingshiGoods::find()->groupBy('goods_id')->all();

        return $this->render('goods', ['models' => $models]);
    }

    public function actionGoodsEdit()
    {
        $model = new DingshiGoods();
        $dingshi = Dingshi::findOne([
            'store_id' => $this->store->id,
        ]);
        if (\Yii::$app->request->isPost) {
            $form = new DingshiGoodsEditForm();
            $form->load(\Yii::$app->request->post(), '');
            $form->store_id = $this->store->id;
            return $form->save();
        } else {
            return $this->render('goods-edit', [
                'model' => $model,
                'dingshi' => $dingshi,
            ]);
        }
    }

    /**
     *  搜索商品
     * @param null $keyword
     * @param int $page
     * @return mixed
     * @author thanatos <thanatos915@163.com>
     */
    public function actionGoodsSearch($keyword = null, $page = 1)
    {
        $form = new GoodsSearch();
        $form->keyword = $keyword;
        $form->page = $page;
        $form->store_id = $this->store->id;
        return $form->search();
    }

    public function actionGoodsDetail($goods_id)
    {
        $date_begin = \Yii::$app->request->get('date_begin', date('Y-m-d', strtotime('-30 days')));
        $date_end = \Yii::$app->request->get('date_end', date('Y-m-d', strtotime('+1 days')));
        $query = DingshiGoods::find()->alias('mg')->leftJoin(['g' => Goods::tableName()], 'mg.goods_id=g.id')
            ->where(['mg.goods_id' => $goods_id])->asArray()->select('mg.*,g.name')->orderBy('mg.open_date ASC');

        $query->andWhere([
            'AND',
            ['>=', 'mg.open_date', $date_begin],
            ['<=', 'mg.open_date', $date_end],
        ]);
        $count = $query->count();
        $list = $query->all();
        return $this->render('goods-detail', [
            'list' => $list,
            'count' => $count ? $count : 0,
            'date_begin' => $date_begin,
            'date_end' => $date_end,
        ]);
    }

    //删除单个秒杀记录
    public function actionGoodsDelete($goods_id)
    {
        DingshiGoods::deleteAll([
            'id' => $goods_id,
            'store_id' => $this->store->id,
        ]);
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    //删除单个秒杀记录
    public function actionDingshiDelect($id)
    {
        DingshiGoods::deleteAll([
            'id' => $id,
            'store_id' => $this->store->id,
        ]);
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

}