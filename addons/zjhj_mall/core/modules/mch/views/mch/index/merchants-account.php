<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */
$this->title = '店铺账户';
$urlManager = Yii::$app->urlManager;
$urlPlatform = Yii::$app->controller->route;
?>

<div class="panel mb-3">
    <div class="panel-header" >

        <ul class="nav ">
            <li class="nav-item">
                <a class="nav-link active" href="#">   <?= $this->title ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="<?= $urlManager->createUrl(['mch/mch/index/mch-account-log']) ?>">资金明细</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="/addons/zjhj_mall/core/web/index.php?r=mch%2Fmch%2Findex%2Fapply&amp;review_status=2">提现记录</a>
            </li>
        </ul>
    </div>
    <!--<div class="panel-header">
        <form class="form-inline d-inline-block float-right" style="margin: -.25rem 0" method="get">
            <input type="hidden" name="r" value="mch/mch/index/index">
            <div class="input-group">
                <div class="dropdown float-right ml-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php /*if ($_GET['platform'] === '1') :
                            */?>支付宝
                        <?php /*elseif ($_GET['platform'] === '0') :
                            */?>微信
                        <?php /*elseif ($_GET['platform'] == '') :
                            */?>全部商户
                        <?php /*else : */?>
                        <?php /*endif; */?>
                    </button>
                    <div class="dropdown-menu" style="min-width:8rem">
                        <a class="dropdown-item" href="<?/*= $urlManager->createUrl([$urlPlatform]) */?>">全部商户</a>
                        <a class="dropdown-item"
                           href="<?/*= $urlManager->createUrl([$urlPlatform, 'platform' => 1]) */?>">支付宝</a>
                        <a class="dropdown-item"
                           href="<?/*= $urlManager->createUrl([$urlPlatform, 'platform' => 0]) */?>">微信</a>
                    </div>
                </div>
                <a class="btn btn-primary mr-3" href="<?/*= Yii::$app->urlManager->createUrl(['mch/mch/index/add']) */?>">添加商户</a>
                <input class="form-control" name="keyword" value="<?/*= $get['keyword'] */?>" placeholder="店铺/用户/联系人">
                <span class="input-group-btn">
                    <button class="btn btn-secondary">搜索</button>
                </span>
            </div>
        </form>
    </div>-->
    <div class="panel-body">
        <?php if (!$list || count($list) == 0) : ?>
            <div class="p-5 text-center text-muted">无记录</div>
        <?php else : ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>店铺</th>
                    <th>用户</th>
                    <th>联系人</th>
                    <th>账户余额</th>
                     <th>已提现金额</th>
                   <!-- <th>开业</th>
                    <th>好店推荐</th>-->
                    <th>操作</th>
                </tr>
                </thead>
                <?php foreach ($list as $item) : ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td>
                            <img src="<?= $item['logo'] ?>"
                                 style="width: 25px;height: 25px;margin: -.5rem .5rem -.5rem 0">
                            <?= $item['name'] ?>
                        </td>
                        <td>
                            <img src="<?= $item['avatar_url'] ?>" style="width: 25px;height: 25px;margin: -.5rem .5rem -.5rem 0">
                            <?= $item['nickname'] ?>
                            <?php if (isset($item['platform']) && intval($item['platform']) === 0): ?>
                                <span class="badge badge-success">微信</span>
                            <?php elseif (isset($item['platform']) && intval($item['platform']) === 1): ?>
                                <span class="badge badge-primary">支付宝</span>
                            <?php else: ?>
                                <span class="badge badge-default">未知</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $item['realname'] ?>（<?= $item['tel'] ?>）</td>
                        <td><?=  $item['account_money']   ?></td>
                        <td>3</td>

                        <td>
                            <a href="<?= $urlManager->createUrl(['mch/mch/index/report-forms', 'mch_id' => $item['id'],'name'=>$item['name']]) ?>">销售报表</a>
                            |
                            <a href="<?= $urlManager->createUrl(['mch/mch/index/mch-account-log', 'mch_id' => $item['id']]) ?>">资金明细</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination]) ?>
        <?php endif; ?>
    </div>
</div>
<script>
    $(document).on('change', 'input[class=is_switch]', function () {
        var id = $(this).attr('data-id');
        var name = $(this).attr('name');
        var status = 0;
        if ($(this).prop('checked'))
            status = 1;
        else
            status = 0;
        $.loading();
        $.ajax({
            url: '<?=Yii::$app->urlManager->createUrl(['mch/mch/index/set-status'])?>',
            dataType: 'json',
            data: {
                id: id,
                status: status,
                type: name
            },
            success: function (res) {
                $.toast({
                    content: res.msg,
                });
            },
            complete: function () {
                $.loadingHide();
            }
        });
    });
</script>