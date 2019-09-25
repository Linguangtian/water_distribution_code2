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
$this->title = '用户水票列表';
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
                <th>昵称</th>
                <th>水票</th>
                <th>当前剩余数量/张</th>
                <th>总购买量/张</th>
                <th>累计使用/张</th>
            </tr>
            </thead>
            <?php foreach ($list as $v) : ?>
                <tr style="<?= $v['flag'] == 1 ? 'color:#ff4544' : '' ?>">
                    <td><?= $v['id'] ?></td>
                    <td>
                        <?= $v['nickname'] ?>
                    </td>
                    <td>
                        <a href="<?= $v['cover_pic'] ?>" target="_blank"> <img src="<?= $v['cover_pic'] ?>" style="max-width:50px;max-height:50px;"></a>  [ <?= $v['name'] ?>-水票 ]
                    </td>
                    <td>
                        <a class="btn btn-sm btn-link"
                           href="<?= $urlManager->createUrl(['mch/voucher/index', 'user_id' => $v['user_id'],'goods_id'=>$v['goods_id']]) ?>">
                          <?= $v['num'] ?>
                        </a>
                    </td>

                    <td>
                        <a class="btn btn-sm btn-link"
                           href="<?= $urlManager->createUrl(['mch/voucher/index', 'user_id' => $v['user_id'],'goods_id'=>$v['goods_id'],'change_type'=>'1']) ?>">
                        <?= $v['total_number'] ?>
                        </a>
                    </td>

                    <td>
                        <a class="btn btn-sm btn-link"
                           href="<?= $urlManager->createUrl(['mch/voucher/index', 'user_id' => $v['user_id'],'goods_id'=>$v['goods_id'],'change_type'=>'2']) ?>">
                            <?php if($v['used_number']>0): ?>
                                <?= $v['used_number'] ?>
                            <?php else: ?>
                                0
                            <?php endif; ?>

                        </a>
                    </td>


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






