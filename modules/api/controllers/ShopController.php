<?php
/**
 * @user: thanatos <thanatos915@163.com>
 */

namespace app\modules\api\controllers;


use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\models\Option;
use app\models\Order;
use app\models\Setting;
use app\models\Shop;
use app\models\User;
use app\models\UserShareMoney;
use Yii;
use app\modules\api\behaviors\LoginBehavior;
use yii\data\Pagination;

class ShopController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }

    public function actionIndex()
    {
        // 查询店铺信息
        if (!Yii::$app->user->identity->is_shop_admin) {
            return new BaseApiResponse([
                'code' => 1,
                'data' => 0
            ]);
        }

        /** @var Shop $shop */
        $shop = Yii::$app->user->identity->shop;
        /** @var User $user */
        $user = Yii::$app->user->identity;
        // 店铺总订单金额
        $total_price = Order::find()->where(['shop_id' => $shop->id, 'store_id' => $this->store->id, 'is_pay' => 1, 'is_send' => 1, 'is_confirm' => 1])->sum('total_price');


        $data = [
            'total_price' => $total_price,
            'commission' => $user->price,
            'total_commission' => $user->total_price
        ];
        return new BaseApiResponse([
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'shop' => $data,
            ],
        ]);

    }

    public function actionAccount()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $data = [
            'header_bg' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/shop/img/mch-account-header-bg.png',
            'account_money' => $user->price,
        ];
        $setting = Setting::findOne(['store_id' => $this->store->id]);
        $data['min_money'] = $setting->min_money;
        $option = Option::findAll(['store_id' => $this->store->id, 'group' => 'share']);
        foreach ($option as $k => $v) {
            $data[$v['name']] = $v['value'];
        }
        $data['desc'] = '每笔最少提现' . $setting->min_money . '元，每日上限' . $data['cash_max_day'] . '提现手续费为' . $data['cash_service_charge'] . '%';
        return new BaseApiResponse([
            'code' => 0,
            'msg' => 'success',
            'data' => $data,
        ]);
    }

    public function actionAccountLog()
    {

        $params = Yii::$app->request->get();

        $query = UserShareMoney::find()->alias('us')->where(['us.user_id' => Yii::$app->user->id, 'us.is_delete' => 0, 'us.store_id' => $this->store->id])->leftJoin(['o' => Order::tableName(), 'o.id' => 'us.order_id'])->select(['us.money', 'us.addtime', 'o.pay_price', 'o.name']);

        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $params['limit'], 'page' => $params['page'] - 1]);

        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('o.addtime DESC')->asArray()->all();
        $data = [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'list' => $list,
        ];
        return new ApiResponse(0, 'success', $data);
    }

}