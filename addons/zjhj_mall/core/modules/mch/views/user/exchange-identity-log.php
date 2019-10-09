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
$this->title = '交接记录';

?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?>
        <span style="float: right;margin-right:3%" ><a href="javascript:history.go(-1)">返回</a></span>
    </div>
    <div class="panel-body">

        <div class="text-danger"></div>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>交换用户1</th>
                <th>交换用户2</th>
                <th>操作时间</th>
                <th>备注</th>
            </tr>
            </thead>
            <?php foreach ($list as $key=>$v) : ?>
                <tr >
                    <td><?= $key ?></td>

                    <td> <?= $v['nickname1'] ?>  </td>
                    <td> <?= $v['nickname2'] ?> </td>
                    <td> <?= date('Y-m-d H:i:s',$v['create_date']) ?> </td>
                    <td> <?= $v['notes'] ?> </td>


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





