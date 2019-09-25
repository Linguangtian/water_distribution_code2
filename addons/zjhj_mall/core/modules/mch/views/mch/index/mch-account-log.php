<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */

$urlManager = Yii::$app->urlManager;
$urlPlatform = Yii::$app->controller->route;
?>

<div class="panel mb-3">
    <div class="panel-header" >

        <ul class="nav ">
            <li class="nav-item">
                <a class="nav-link " href="<?= $urlManager->createUrl(['mch/mch/index/merchants-account']) ?>">   店铺账户
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#">资金明细</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="/addons/zjhj_mall/core/web/index.php?r=mch%2Fmch%2Findex%2Fapply&amp;review_status=2">提现记录</a>
            </li>
        </ul>
    </div>

    <div class="panel-body">
        <?php if (!$list || count($list) == 0) : ?>
            <div class="p-5 text-center text-muted">无记录</div>
        <?php else : ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>店铺</th>
                    <th>金额</th>
                    <th>描述</th>
                    <th>添加时间</th>
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
                            <?php if ( $item['type'] == 1): ?>
                                <a style="color: #29BC0A">+  <?= $item['price'] ?></a>
                            <?php elseif  ( $item['type'] == 2): ?>
                                <a style="color: red">-  <?= $item['price'] ?></a>
                            <?php else: ?>
                                <?= $item['price'] ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $item['desc'] ?></td>
                        <td><?= date('Y-m-d H:i', $item['addtime']);?> </td>



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