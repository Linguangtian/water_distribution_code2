<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/17
 * Time: 9:23
 */
defined('YII_ENV') or exit('Access Denied');

use \app\models\User;
use yii\widgets\LinkPager;
use yii;


$urlManager = Yii::$app->urlManager;
$this->title = '账期列表';
$this->params['active_nav_group'] = 4;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <form method="get">
                <?php $_s = ['keyword', 'date_start', 'date_end', 'page', 'per-page'] ?>
                <?php foreach ($_GET as $_gi => $_gv) :
                    if (in_array($_gi, $_s)) {
                        continue;
                    } ?>
                    <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                <?php endforeach; ?>
                <div class="row ml-1">
                    <div>
                        <div class="input-group">
                            <input class="form-control"
                                   placeholder="昵称"
                                   name="keyword"
                                   autocomplete="off"
                                   value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary ml-3 mr-4">筛选</button>
                    </div>

                </div>
            </form>
        </div>
        <div class="text-danger"></div>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>用户</th>
                <th>类型</th>
                <th>账期欠款</th>
                <th>当前欠款</th>

                <th>操作时间</th>
                <th>订单号</th>
                <th>备注</th>

            </tr>
            </thead>
            <?php foreach ($list as $key=>$v) : ?>
                <tr style="<?= $v['flag'] == 1 ? 'color:#ff4544' : '' ?>">
                    <td><?= $key ?></td>

                    <td>
                        <a href="<?= $v['avatar_url'] ?>" target="_blank"> <img src="<?= $v['avatar_url'] ?>" style="max-width:50px;max-height:50px;"></a>  [ <?= $v['nickname'] ?>]
                    </td>
                    <td>       <?php if($v['type']==1) :?>  还款 <?php else: ?>  消费 <?php endif; ?> </td>


                    <td>   <?php if($v['change_type']==1) :?> <a style="color: green"> - </a> <?php else: ?> <a style="color: red"> + </a>   <?php endif; ?>  <?= $v['credit_money'] ?>  </td>

                    <td> <?= $v['current_credit_cost'] ?> </td>


                    <td> <?= date("Y-m-d H:i:s",$v['create_time'] )?> </td>

                    <td> <a href="<?= $urlManager->createUrl(['mch/order/detail','order_id'=>$v['order_id']]) ?>"> <?= $v['order_id'] ?>  </a></td>

                    <td > <?= $v['explain'] ?></td>

                </tr>
            <?php endforeach; ?>
        </table>
        <div class="text-center">
            <nav aria-label="Page navigation example">
                <?php echo LinkPager::widget([
                    'pagination' => $pagination,
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'maxButtonCount' => 5,
                    'options' => [
                        'class' => 'pagination',
                    ],
                    'prevPageCssClass' => 'page-item',
                    'pageCssClass' => "page-item",
                    'nextPageCssClass' => 'page-item',
                    'firstPageCssClass' => 'page-item',
                    'lastPageCssClass' => 'page-item',
                    'linkOptions' => [
                        'class' => 'page-link',
                    ],
                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                ])
                ?>
            </nav>
            <div class="text-muted">共<?= $row_count ?>条数据</div>
        </div>

    </div>
</div>

<?= $this->render('/layouts/ss', [
    'exportList' => $exportList
]) ?>


<script>
    $(document).on('click', '.del', function () {
        if (confirm("是否删除？")) {
            $.ajax({
                url: $(this).attr('href'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    alert(res.msg);
                    if (res.code == 0) {
                        window.location.reload();
                    }
                }
            });
        }
        return false;
    });


</script>





