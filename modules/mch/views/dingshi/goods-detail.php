<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '定时购商品详情';
$this->params['active_nav_group'] = 10;
?>
<style>
    .miaosha-item:hover {
        box-shadow: 0 1px 1px 1px rgba(0, 0, 0, .2);
    }
</style>
<div class="panel mb-3">
    <div class="panel-header">定时购商品详情：<?= $list[0]['name'] ?></div>
    <div class="panel-body">
        <form method="get" class="input-group mb-3" style="max-width: 30rem;">
            <input type="hidden" name="r" value="<?= Yii::$app->request->get('r') ?>">
            <input type="hidden" name="goods_id" value="<?= Yii::$app->request->get('goods_id') ?>">
            <span class="input-group-addon">日期查找</span>
            <input class="form-control" id="date_begin" value="<?= $date_begin ?>" name="date_begin">
            <span class="input-group-addon">~</span>
            <input class="form-control" id="date_end" value="<?= $date_end ?>" name="date_end">
            <span class="input-group-btn">
                    <button class="btn btn-secondary">查找</button>
                </span>
        </form>
        <?php foreach ($list as $item) : ?>
            <?php $item['attr'] = json_decode($item['attr'], true); ?>
            <table class="card-block table bg-white table-bordered">
                <thead>
                <tr>
                    <td colspan="<?= count($item['attr'][0]['attr_list']) + 2 ?>">
                        <span class="mr-3">开售日期：<?= $item['open_date'] ?></span>
                        <a class="btn btn-sm btn-danger delete-btn float-right"
                           href="<?= $urlManager->createUrl(['mch/dingshi/dingshi-delete', 'id' => $item['id']]) ?>">删除</a>
                    </td>
                </tr>
                <tr>
                    <th colspan="<?= count($item['attr'][0]['attr_list']) ?>">规格</th>
                    <th>秒杀价</th>
                </tr>
                </thead>
                <?php foreach ($item['attr'] as $index => $attr_item) : ?>
                    <tr>
                        <?php foreach ($attr_item['attr_list'] as $attr) : ?>
                            <td><?= $attr['attr_name'] ?></td>
                        <?php endforeach; ?>
                        <td>
                            <input type="number" data-id="<?=$item['id']?>" data-value="<?=$attr_item['dingshi_price']?>" data-index="<?=$index?>" class="miaosha_price" step="0.01" value="<?= $attr_item['dingshi_price'] ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>
    </div>
</div>

<script>
    $(document).on("click", ".delete-btn", function () {
        var url = $(this).attr("href");
        $.confirm({
            content: "确认删除？",
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

    $.datetimepicker.setLocale('zh');

    $(function () {
        $('#date_begin').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $('#date_end').val() ? $('#date_end').val() : false
                })
            },
            timepicker: false
        });
        $('#date_end').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    minDate: $('#date_begin').val() ? $('#date_begin').val() : false
                })
            },
            timepicker: false
        });
    });

</script>