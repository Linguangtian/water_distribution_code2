<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */
$this->title = '添加送水员';
$url_manager = Yii::$app->urlManager;
?>
<style>
    .user-item {
        border-bottom: 1px solid #e3e3e3;
        padding: .5rem 0;
    }

    .user-item:first-child {
        border-top: 1px solid #e3e3e3;
    }
</style>
<div class="panel mb-3" id="app">
    <div class="panel-header">
        <span><?= $this->title ?></span>
    </div>
    <div class="panel-body">
        <form class="auto-form" method="post"
              return="<?= Yii::$app->request->referrer ? Yii::$app->request->referrer : '' ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">员工编号</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control"  value="<?= $model['code']?>" disabled>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">姓名</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="real_name" value="<?= $model['real_name']?>" >
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">联系电话</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="mobile" value="<?= $model['mobile']?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">微信号</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="wechat_code" value="<?= $model['wechat_code']?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">年龄</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="age" value="<?= $model['age']?>">
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">加入时间</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control"  value="<?= date('Y-m-d H:i:s ',$model['join_time'])?>" disabled>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">未配送订单</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control"  value="<?= $total['unconfirm_total']?>" disabled>
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">累积配送次数</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control"  value="<?= $total['confirm_total']?>" disabled>
                </div>
            </div>



            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    <input type="button" class="btn btn-default ml-4"
                           name="Submit" onclick="javascript:history.back(-1);" value="返回">
                </div>
            </div>
        </form>

        <!-- Search User Modal -->
        <div class="modal fade" id="searchUserModal" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">查找用户</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="search-user-form">
                            <div class="input-group mb-3">
                                <input class="form-control">
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary">查找</button>
                                </span>
                            </div>
                            <div>
                                <template v-if="user_list && user_list.length">
                                    <div v-for="(u,i) in user_list" class="user-item"
                                         flex="dir:left box:last cross:center">
                                        <div>
                                            <img :src="u.avatar_url"
                                                 style="width: 1.5rem;height: 1.5rem;border-radius: .15rem;">
                                            <span>{{u.nickname}}</span>
                                        </div>
                                        <div>
                                            <a class="btn btn-sm btn-secondary select-user" href="javascript:"
                                               :data-index="i">选择</a>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            user_list: [],
        },
    });



    $(document).on('click', '.picker-district', function () {
        $.districtPicker({
            success: function (res) {
                $('input[name=province_id]').val(res.province_id);
                $('input[name=city_id]').val(res.city_id);
                $('input[name=district_id]').val(res.district_id);
                $('.district-text').val(res.province_name + "-" + res.city_name + "-" + res.district_name);
            },
            error: function (e) {
                console.log(e);
            }
        });
    });

    $(document).on('submit', '.search-user-form', function () {
        var form = $(this);
        var btn = form.find('button');
        btn.btnLoading();
        $.ajax({
            dataType: 'json',
            data: {
                keyword: form.find('input').val(),
            },
            success: function (res) {
                if (res.code == 0) {
                    app.user_list = res.data;
                }
            },
            complete: function () {
                btn.btnReset();
            }
        });
        return false;
    });

    $(document).on('click', '.select-user', function () {
        var index = $(this).attr('data-index');
        var user = app.user_list[index];
        $('.user-id').val(user.id);
        $('.user-nickname').val(user.nickname);
        $('#searchUserModal').modal('hide');
    });

</script>