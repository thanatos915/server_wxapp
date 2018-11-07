<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
/** @var \app\models\DingshiGoods[]  $models */
$urlManager = Yii::$app->urlManager;
$this->title = '定时购商品';
$this->params['active_nav_group'] = 10;
?>

<div class="panel mb-3">
    <div class="panel-header">
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/dingshi/goods-edit']) ?>">添加定时商品</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>商品ID</th>
                <th>商品</th>
                <th>开售日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php if (!$models || count($models) == 0) : ?>
                <tr>
                    <td colspan="5" class="text-center p-5">
                        <a href="<?= $urlManager->createUrl(['mch/dingshi/goods-edit']) ?>">添加定时商品</a>
                    </td>
                </tr>
            <?php else :
    foreach ($models as $model) : ?>
                <tr>
                    <td><?= $model->goods_id ?></td>
                    <td>
                        <a href="<?= $urlManager->createUrl(['mch/dingshi/goods-detail', 'goods_id' => $model->goods_id]) ?>"><?= $model->goods->name ?></a>
                    </td>
                    <td>
                        <?= $model->open_date ?>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-danger delete-btn"
                           href="<?= $urlManager->createUrl(['mch/dingshi/goods-delete', 'goods_id' => $model->goods_id]) ?>">删除</a>
                    </td>
                </tr>
    <?php endforeach;
            endif; ?>
        </table>
    </div>
</div>

<script>
    $(document).on("click", ".delete-btn", function () {
        var url = $(this).attr("href");
        $.confirm({
            content: "确认删除？删除后该商品的所有秒杀设置将全部删除！",
            confirm: function () {
                $.loading();
                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    success: function (res) {
                        location.reload();
                    }
                });
            }
        });
        return false;
    });
</script>
