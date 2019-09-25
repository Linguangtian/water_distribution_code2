<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */


?>

<div class="panel mb-3">
    <div class="panel-header">用户水桶详情</div>
    <div class="panel-body">
        <form class="auto-form" method="post" >
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">用户</label>
                </div>
                <div class="col-sm-6">
                      <input class="form-control cat-name"  value="<?= $user_info['nickname'] ?>" disabled>
                    <img src="<?= $user_info['agatar_url'] ?>" height="150"width="150">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">当前欠桶数</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control cat-name" name="bucket"
                           value="<?= $model['bucket'] ?>">
                </div>
            </div>
            <input class="form-control cat-name" name="user_id"
                   value="<?= $model['user_id'] ?>" hidden>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">押桶数</label>
                </div>

                <div class="col-sm-6">
                    <input class="form-control cat-name" name="deposit_bucket"
                           value="<?= $model['deposit_bucket'] ?>">
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">押金</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control cat-name" name="deposit" type="number"
                           value="<?= $model['deposit'] ?>">
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

<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.config.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.all.min.js"></script>
<script>

</script>