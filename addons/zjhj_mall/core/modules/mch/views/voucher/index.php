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
$this->title = '水票记录';
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
                <div flex="dir:left">
                    <div class="mr-3 ml-3">
                        <div class="form-group row">
                            <div>
                                <label class="col-form-label">下单时间：</label>
                            </div>
                            <div>
                                <div class="input-group">
                                    <input class="form-control" id="date_start" name="date_start"
                                           autocomplete="off"
                                           value="<?= isset($_GET['date_start']) ? trim($_GET['date_start']) : '' ?>">
                                    <span class="input-group-btn">
                                        <a class="btn btn-secondary" id="show_date_start" href="javascript:">
                                            <span class="iconfont icon-daterange"></span>
                                        </a>
                                    </span>
                                    <span class="middle-center input-group-addon" style="padding:0 4px">至</span>
                                    <input class="form-control" id="date_end" name="date_end"
                                           autocomplete="off"
                                           value="<?= isset($_GET['date_end']) ? trim($_GET['date_end']) : '' ?>">
                                    <span class="input-group-btn">
                                        <a class="btn btn-secondary" id="show_date_end" href="javascript:">
                                            <span class="iconfont icon-daterange"></span>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="middle-center mr-2">
                                <a href="javascript:" class="new-day btn btn-primary ml-2" data-index="7">近7天</a>
                                <a href="javascript:" class="new-day btn btn-primary ml-2" data-index="30">近30天</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ml-1">
                    <div style="display: flex;">


                        <div class="input-group  "style="flex-grow:1;height:30px; margin-right: 10px;" >
                            订单类型:
                            <select name="change_type" class="form-control" >
                                <option value="0">全部</option>
                                <option value="2" <?= $_GET['change_type'] == 2? "selected" : "" ?>>使用</option>
                                <option value="1" <?= $_GET['change_type'] == 1 ? "selected" : "" ?>>购买</option>
                            </select>
                        </div>


                        <div class="input-group" style="flex-grow:1; height:30px;">
                            <input class="form-control"
                                   placeholder="昵称/水票名称"
                                   name="keyword"
                                   autocomplete="off"
                                   value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary ml-3 mr-4">筛选</button>

                        <a class="btn btn-secondary export-btn" href="javascript:">批量导出</a>
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
                <th>数量/张</th>
                <th>类型</th>
                <th>当前数量</th>
                <th>创建时间</th>
                <th>备注</th>
                <th>操作</th>
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
                     <?php if($v['change_type']==1): ?>
                         <a style="color: limegreen">+
                     <?php else: ?>
                         <a style="color: red">-
                     <?php endif;?>
                        <?= $v['change_num'] ?></a>
                    </td>

                    <td>
                        <?php if ($v['type']==1): ?>
                           购买水票
                        <?php elseif ($v['type']==2) :?>
                            水票使用
                        <?php elseif ($v['type']==4) :?>
                            退款
                        <?php elseif ($v['type']==5) :?>
                            退货退款
                        <?php else :?>
                        其他
                        <?php endif; ?>
                    </td>

                    <td><?= $v['current_total'] ?></td>
                    <td><?= date('Y-m-d H:i:s', $v['create_time']); ?></td>

                    <td>
                        <div>
                            <label>说明：<?= $v['detail'] ?></label>
                        </div>
                    </td>

                    <td>
                        <?php if($v['voucher_order']) :?>
                            <a class="btn btn-sm btn-success showorder"
                               data-toggle="modal" data-target="#attrAddModal"
                               href="javascript:;"
                               data-id="<?= $v['voucher_order'] ?>">订单明细</a>

                        <?php endif; ?>
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








        <!-- 充值积分 -->
        <div class="modal fade" id="attrAddModal" data-backdrop="static">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">订单明细</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-success text-success mt-3" style="display: none">sss</div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right"><label class="col-form-label">名称</label></div>
                        <div class="col-9" style="overflow: hidden"><input name="order-name" class="form-control" disabled ></div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right"><label class="col-form-label">金额</label></div>
                        <div class="col-9" style="overflow: hidden"><input name="order-cost" class="form-control"  disabled ></div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right"><label class="col-form-label">付款</label></div>
                        <div class="col-9" style="overflow: hidden"><input name="order-pay" class="form-control" disabled ></div>
                    </div>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<?= $this->render('/layouts/ss', [
    'exportList' => $exportList
]) ?>


<script>


    $(document).on('click', '.showorder', function () {
        var order_id = $(this).data('id');
        if(!order_id) return false;
        $.ajax({
            url:"<?= Yii::$app->urlManager->createUrl(['mch/voucher/order-detail']) ?>",
            type:'get',
            dataType:'json',
            data:{
                voucher_order_id:order_id,
            },
            success:function (res) {
                if(res.code==0){
                    if(res.order_info.pay_type=='wechat'){
                        $('input[name="order-pay"]').val('微信支付')
                    }
                    $('input[name="order-name"]').val(res.order_info.voucher_title)
                    $('input[name="order-cost"]').val(res.order_info.cost)

                }else{

                    $('input[name="order-pay"]').val('')
                    $('input[name="order-name"]').val('')
                    $('input[name="order-cost"]').val('')
                }
            }
        })

    });




</script>



