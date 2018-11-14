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
?>
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=key=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77"></script>
<div class="panel mb-3">
    <div class="panel-header"><h3><?= $shop->name ?> <?= $start ?> 日订货清单</h3></div>
    <div class="panel-body">
        <table class="table table-bordered bg-white">
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
                        <?= $i . '. ' .$v['username'] ?> X <?= $v['num'] ?> 份 <br />
                        <?php } ?>
                    </td>
                    <td><?= $item['num'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<script>
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
