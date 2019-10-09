<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */
$urlManager = Yii::$app->urlManager;
$this->title = '会员交接';
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
        <span><?= $this->title ?></span>   <span style="float: right"><a href="<?=$urlManager->createUrl(['mch/user/exchange-identity-log'])?>">查看记录</a></span>
    </div>
    <div class="panel-body">
        <form class="auto-form" method="post"
              return="<?= Yii::$app->request->referrer ? Yii::$app->request->referrer : '' ?>"
              onsubmit="return checkSubmit();"
        >
          <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">小程序用户1</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="user-id" type="hidden" name="user_id" readonly>
                        <input class="form-control user-nickname" readonly>
                        <span class="input-group-btn">
                        <a href="javascript:" class="btn btn-secondary" data-toggle="modal"
                           data-target="#searchUserModal">查找</a>
                        </span>
                    </div>
                </div>
            </div>






            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">小程序用户2</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="user-id2" type="hidden" name="user_id2" readonly>
                        <input class="form-control user-nickname2" readonly>
                        <span class="input-group-btn">
                        <a href="javascript:" class="btn btn-secondary" data-toggle="modal"
                           data-target="#searchUserModal2">查找</a>
                        </span>
                    </div>
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right ">
                    <label class="col-form-label ">备注</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">  <input   name="notes"  style="width: 100%;" value=""></div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <input class="btn btn-primary "  type="submit" value="身份互换">
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

        <!-- Search User Modal -->



<input  class="res" value="<?= $res ?>" hidden>




        <div class="modal fade" id="searchUserModal2" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">查找用户</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="search-user-form2">
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
                                            <a class="btn btn-sm btn-secondary select-user2" href="javascript:"
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
    function checkSubmit(){
        var user2=$('.user-id2').val();
        var user1=$('.user-id').val();
        console.info(user2);
        console.info(user1);
        if(!user2||!user1){
            alert('请选择用户');
            return false;
        }

        if(user2==user1){
            alert('请选择两位不同的用户');
            return false;
        }

        if(confirm('确定身份互换？')){
            return true;
        }else{
            return false;
        }

    }

    $(function () {
        var res=$('.res').val();
        if(res){
            alert(res);
        }
    })


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
            url: "<?=$urlManager->createUrl(['mch/mch/index/add'])?>",
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



    $(document).on('submit', '.search-user-form2', function () {
        var form = $(this);
        var btn = form.find('button');
        btn.btnLoading();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/mch/index/add'])?>",
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


  $(document).on('click', '.select-user2', function () {
        var index = $(this).attr('data-index');
        var user = app.user_list[index];
        $('.user-id2').val(user.id);
        $('.user-nickname2').val(user.nickname);
        $('#searchUserModal2').modal('hide');
    });

</script>