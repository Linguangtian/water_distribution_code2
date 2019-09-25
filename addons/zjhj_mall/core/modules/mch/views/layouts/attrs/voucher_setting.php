<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

?>
<div class="step-block" flex="dir:left box:first"  >
    <div>
        <span>水票</span>
        <span class="step-location" id="step7"></span>
    </div>
    <div>
        <div class="form-group row">
            <div class="col-3 text-right">
                <label class="col-form-label">是否水桶滞留</label>
            </div>
            <div class="col-9">
                <label class="radio-label">
                    <input <?= $goods['is_water_bucket'] == 0 ? 'checked' : null ?>
                            value="0" name="model[is_water_bucket]" type="radio"
                            class="custom-control-input">
                    <span class="label-icon"></span>
                    <span class="label-text">关闭</span>
                </label>
                <label class="radio-label">
                    <input <?= $goods['is_water_bucket'] == 1 ? 'checked' : null ?>
                            value="1" name="model[is_water_bucket]" type="radio"
                            class="custom-control-input">
                    <span class="label-icon"></span>
                    <span class="label-text">开启</span>
                </label>

                <div class="fs-sm text-danger">不开启，不计算水桶数</div>
            </div>
        </div>


        <!-- 规格开关 -->
        <div class="form-group row" >
            <div class="col-3 text-right">
                <label class="col-form-label">是否使用水票</label>
            </div>
            <div class="col-9 col-form-label">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox"
                           name="setting_voucher"
                           value="1"
                        <?= $goods->setting_voucher ? 'checked' : null ?>
                           class="custom-control-input use-attr">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">使用水票</span>
                </label>
            </div>
        </div>

        <div class="form-group row setting-voucher-list"  style="display: <?= $goods['setting_voucher'] ? null : 'none' ?>">
            <div class="col-3 text-right">
                <label class=" col-form-label required">水票套餐</label>
            </div>

            <div class="col-9"><div>
                    <table class="table table-bordered attr-table">
                        <thead>
                        <tr>
                            <th>编号</th>
                            <th>标题</th>
                            <th>张数</th>
                            <th>价格</th>
                            <th>默认选中</th>
                            <th><a href="javascript:add_voucher_row()">+</a></th>
                        </tr>
                        </thead>
                        <tbody class="voucher-row">

                        <?php if (count($voucher_list) > 0): ?>
                            <?php foreach ($voucher_list as $item) : ?>
                                <tr>
                                    <td>
                                        <input type="text" min="0" step="1" value="<?= $item['code']?>" name="voucher_package[code][]" class="form-control form-control-sm" style="width:100px;">
                                    </td>
                                    <td>
                                        <input type="text" min="0" value="<?= $item['title']?>" step="1" name="voucher_package[title][]" class="form-control form-control-sm" style="width:100%;">
                                    </td>
                                    <td>
                                        <input type="number" min="0" step="1" value="<?= $item['package_number']?>" name="voucher_package[package_number][]" class="form-control form-control-sm" style="width: 70px;">
                                    </td>
                                    <td>
                                        <input name="voucher_package[package_price][]" value="<?= $item['package_price']?>"  min="0" type="number" class="form-control form-control-sm" style="width: 100px;">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="voucher_package[choice][]" value="<?= $item['choice']?$item['choice']:1?>"  <?= $item['choice'] ? 'checked' : null ?> >
                                    </td>
                                    <td>
                                        <a href="javascript:;"><span class="iconfont icon-close btn_trash" style="border:1px solid #ccc;">删除</span></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else : ?>
                            <tr>
                                <td>
                                    <input type="text" min="0" step="1" name="voucher_package[code][]" class="form-control form-control-sm" style="width:100px;">
                                </td>
                                <td>
                                    <input type="text" min="0" step="1" name="voucher_package[title][]" class="form-control form-control-sm" style="width:100%;">
                                </td>
                                <td>
                                    <input type="number" min="0" step="1" name="voucher_package[package_number][]" class="form-control form-control-sm" style="width: 70px;">
                                </td>
                                <td>
                                    <input name="voucher_package[package_price][]" min="0" type="number" class="form-control form-control-sm" style="width: 100px;">
                                </td>
                                <td>
                                    <input type="checkbox" name="voucher_package[choice][]" value="" >
                                </td>
                                <td>
                                    <a href="javascript:;"><span class="iconfont icon-close btn_trash" style="border:1px solid #ccc;">删除</span></a>
                                </td>
                            </tr>
                        <?php endif ?>

                        </tbody>
                    </table>
                    <div class="row" >
                        说明：
                        <div class="col-9"><input name="voucher_dsc" value="<?= $goods['voucher_dsc']?>" class="form-control short-row"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>