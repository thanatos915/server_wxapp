<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 16:41
 */
defined('YII_ENV') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '申请列表';
$this->params['active_nav_group'] = 1;
$urlStr = get_plugin_url();
?>
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=key=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77"></script>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="float-left">
                <div class="dropdown float-right ml-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= isset($_GET['status']) ? ($_GET['status'] == 1 ? '已处理' : '已拒绝' ) : '未处理' ?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                         style="max-height: 200px;overflow-y: auto">
                        <a class="dropdown-item" href="<?= $urlManager->createUrl([$urlStr . '/shop-join']) ?>">未处理</a>
                        <a class="dropdown-item" href="<?= $urlManager->createUrl(array_merge([$urlStr . '/shop-join'], $_GET, ['status' => 1])) ?>">已处理</a>
                        <a class="dropdown-item" href="<?= $urlManager->createUrl(array_merge([$urlStr . '/shop-join'], $_GET, ['status' => 2])) ?>">已拒绝</a>
                    </div>
                </div>

            </div>
        </div>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>申请人</th>
                <th>联系方式</th>
                <th>昵称</th>
                <th>门店地址</th>
                <th>小区名称</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php foreach ($list as $item) : ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['mobile'] ?></td>
                    <td><?= $item['nickname'] ?></td>
                    <td><?= $item['province_name'] .' '. $item['city_name'] .' '.$item['district_name'] ?></td>
                    <td><?= $item['mobile'] ?></td>
                    <td><?php
                        if ($item['status'] == 1) {
                            echo '已处理';
                        } elseif($item['status' == 2]) {
                            echo '已拒绝';
                        } else {
                            echo '待处理';
                        }
                        ?></td>
                    <td>
                        <?php
                            if($item['status'] == 0) {
                                ?>
                                <a class="btn btn-primary btn-sm del" href="javascript:"
                                   data-url="<?= $urlManager->createUrl(['mch/store/shop-join-handle', 'status' => 1, 'id' => $item['id']]) ?>"
                                   data-content="请确认已手动添加该账户"
                                >通过</a>
                                <a class="btn btn-danger btn-sm del" href="javascript:"
                                   data-url="<?= $urlManager->createUrl(['mch/store/shop-join-handle', 'status' => 2, 'id' => $item['id']]) ?>"
                                   data-content="是否忽略该申请？">拒绝</a>
                                <?php
                            }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="text-center">
            <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination,]) ?>
            <div class="text-muted"><?= $row_count ?>条数据</div>
        </div>
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
