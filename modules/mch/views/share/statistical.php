<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 9:50
 */

use yii\widgets\LinkPager;

/** @var \app\models\Shop[] $shopList */

$urlManager = Yii::$app->urlManager;
$urlPlatform = Yii::$app->controller->route;
$statics = Yii::$app->request->baseUrl . '/statics';
$this->title = '门店订单';
$this->params['active_nav_group'] = 3;
$status = Yii::$app->request->get('status');
$user_id = Yii::$app->request->get('user_id');
$condition = ['user_id' => $user_id, 'clerk_id' => $_GET['clerk_id'], 'shop' => $_GET['shop']];
if ($status === '' || $status === null || $status == -1) {
    $status = -1;
}
?>
<style>
    .order-item {
        border: 1px solid transparent;
        margin-bottom: 1rem;
    }

    .order-item table {
        margin: 0;
    }

    .order-item:hover {
        border: 1px solid #3c8ee5;
    }

    .goods-item {
        /* margin-bottom: .75rem; */
        border: 1px solid #ECEEEF;
        padding: 10px;
        margin-top: -1px;
    }

    .goods-item:last-child {
        margin-bottom: 0;
    }

    .goods-pic {
        width: 5.5rem;
        height: 5.5rem;
        display: inline-block;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
        margin-right: 1rem;
    }

    .table tbody tr td {
        vertical-align: middle;
    }

    .goods-name {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .titleColor {
        color: #888888;
    }

    .order-tab-1 {
        width: 40%;
    }

    .order-tab-2 {
        width: 20%;
        text-align: center;
    }

    .order-tab-3 {
        width: 10%;
        text-align: center;
    }

    .order-tab-4 {
        width: 20%;
        text-align: center;
    }

    .order-tab-5 {
        width: 10%;
        text-align: center;
    }

    .status-item.active {
        color: inherit;
    }
</style>
<script language="JavaScript" src="<?= $statics ?>/mch/js/LodopFuncs.js"></script>
<script language="JavaScript" src="<?= $statics ?>/js/jQuery.print.min.js"></script>
<object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0 style="display: none">
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="p-4 bg-shaixuan">
                <form method="get">
                    <?php $_s = ['keyword', 'keyword_1', 'date_start', 'date_end', 'page', 'per-page'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>
                    <div flex="dir:left">
                        <div class="mr-3 ml-3">
                            <div class="form-group row">
                                <div>
                                    <label class="col-form-label">下单时间：</label>
                                </div>
                                <div>
                                    <div class="input-group">
                                        <input class="form-control" id="date_start" name="date_start"
                                               autocomplete="off"
                                               value="<?= isset($_GET['date_start']) ? trim($_GET['date_start']) : '' ?>">
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary" id="show_date_start" href="javascript:">
                                                <span class="iconfont icon-daterange"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div flex="dir:left">
                        <div class="mr-4">
                            <div class="form-group">
                                <button class="btn btn-primary mr-4">筛选</button>
                            </div>
                        </div>
                        <div class="mr-4">
                            <div class="form-group">
                                <button class="btn-primary btn mr-4" type="button" id="print">打印</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered bg-white" id="print-element" style="width: 500px">
            <thead>
            <tr>
                <th class="order-tab-2">产品名称</th>
                <th class="order-tab-3">总计</th>
            </tr>
            </thead>
            <?php foreach ($list as $item) : ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['num'] ?> (份)</td>
                </tr>
            <?php endforeach; ?>
        </table>

    </div>
</div>

<script>
    $("#print").click(function() {
        $("#print-element").print({
            title: "<?= $date ?>日订单统计"
        });
    });
</script>

<?= $this->render('/layouts/ss', [
    'exportList' => $exportList,
    'list' => $list
]) ?>

<script>
    var app = new Vue({
        el: '#price',
        data: {
            pay: '',
            express: ''
        },
    });
</script>

