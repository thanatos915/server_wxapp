<?php
defined('YII_ENV') or exit('Access Denied');
/** @var \app\models\Dingshi $model */
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '定时购';
$this->params['active_nav_group'] = 10;
?>

<div class="alert alert-info rounded-0">
    <div>注：限定每天什么时间段内可以进行购买</div>
    <div>当前设置是，每天<?= $model->start_time ?>点开始, <?= $model->end_time ?>点结束</div>
</div>
<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
    </div>
    <div class="panel-body">
        <form class="auto-form" method="post" autocomplete="off">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">开始时间</label>
                </div>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="start_time" placeholder="请填写时间如 08:30" value="<?= $model->start_time ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">结束时间</label>
                </div>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="end_time" placeholder="请填写时间如 08:30" value="<?= $model->end_time ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>
