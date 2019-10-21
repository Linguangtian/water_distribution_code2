<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%hjmall_voucher_used_log}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $goods_id
 * @property int $store_id
 * @property int $change_num 操作的数量
 * @property int $change_type 增加还是减少 1加 2减少
 * @property int $type 类型 1  买卷   2支付使用  3 平台赠送 4 取消订单 5 退货 6 部分退货
 * @property int $creta_time
 * @property string $detail
 * @property int $voucher_order 购买，水票订单详情
 * @property int $goods_order 使用时，普通订单ID
 */

define('voucherAdd',1);
define('voucherRed',2);


define('voucherExchange',2); define('exchangeDetail','购水抵用');  //兑换

//增加抵用券
define('voucherbuy',1);
define('orderCancel',4);//退款
define('VOUCHER_RETURN_GOODS',5);//退货退款
define('cancelDetail','订单取消');//兑换

class VoucherUsedLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%voucher_used_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'store_id', 'change_type', 'type'], 'required'],
            [['user_id', 'goods_id', 'store_id', 'change_num', 'change_type', 'type', 'voucher_order','current_total'], 'integer'],
            [['deduction_money'], 'number'],
            [['detail'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'goods_id' => 'Goods ID',
            'store_id' => 'Store ID',
            'change_num' => 'Change Num',
            'change_type' => 'Change Type',
            'type' => 'Type',
            'create_time' => 'create Time',
            'detail' => 'Detail',
            'voucher_order' => 'Voucher Order',
            'current_total' => '当前剩余总数',
        ];
    }
}
