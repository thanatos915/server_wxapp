<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 16:41
 */
/** @var \app\models\Shop $shop */
defined('YII_ENV') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '今日订单详情';
$this->params['active_nav_group'] = 1;
$statics = Yii::$app->request->baseUrl . '/statics';
?>
<script language="JavaScript" src="<?= $statics ?>/js/jQuery.print.min.js"></script>
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=key=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77"></script>
<div class="panel mb-3">
    <div class="mb-3 clearfix">
        <div class="p-4 bg-shaixuan">
            <form method="get">
                <div flex="dir:left">
                    <div class="mr-4">
                        <div class="form-group">
                            <button class="btn-primary btn mr-4" type="button" id="print">打印</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body" id="print-element" style="width: 700px">
        <h3><?= $shop->name ?> <?= $start ?> 日订货清单</h3>
        <table class="table table-bordered bg-white" >
            <thead>
            <tr>
                <th>序号</th>
                <th>产品名称</th>
                <th>详情</th>
                <th>总计</th>
            </tr>
            </thead>
            <?php foreach ($list as $k =>$item) : ?>
                <tr>
                    <td><?= $k+1 ?></td>
                    <td><?= $item['name'] ?></td>
                    <td>
                        <?php
                        $i = 0;
                        foreach ($item['buys'] as $v) {
                            $i++;
                            ?>
                        <?= $v['username'] ?> X  <?= $v['num'] ?><br />
                        <?php } ?>
                    </td>
                    <td><?= $item['num'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<script>
    $("#print").click(function() {
        $("#print-element").print({
            title: "<?= $shop->name ?> <?= $start ?> 日订货清单",
        });
    });
    $(document).on('click', '.del', function () {
        var a = $(this);
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {
                            $.myAlert({
                                title: "提示",
                                content: res.msg
                            });
                        }
                    }
                });
            }
        });
        return false;
    });

    function upDown(id, type) {
        $.loading();
        var text = '';
        if (type == 1) {
            text = "开启默认门店";
        } else {
            text = '关闭默认门店';
        }

        var url = "<?= $urlManager->createUrl(['mch/store/shop-up-down']) ?>";
        if (confirm("是否" + text + "？")) {
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                data: {id: id, type: type},
                success: function (res) {
                    if (res.code == 0) {
                        window.location.reload();
                    }
                    if (res.code == 1) {
                        alert(res.msg);
                        if (res.return_url) {
                            location.href = res.return_url;
                        }
                    }
                }
            });
        }
        return false;
    }
</script>
