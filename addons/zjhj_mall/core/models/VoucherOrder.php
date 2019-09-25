<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%hjmall_voucher_order}}".
 *
 * @property int $id
 * @property string $order_no
 * @property int $user_id
 * @property int $store_id
 * @property int $voucher_id
 * @property string $voucher_title
 * @property int $voucher_num
 * @property int $goods_id
 * @property int $pay_status 付款状态 2：完成
 * @property string $pay_type 付款方式
 * @property int $create_time
 * @property string $cost
 * @property string $dsc 描述
 */
class VoucherOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%voucher_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'store_id', 'voucher_id', 'goods_id'], 'required'],
            [['user_id', 'store_id', 'voucher_id', 'voucher_num', 'goods_id', 'pay_status', 'create_time'], 'integer'],
            [['cost'], 'number'],
            [['order_no', 'pay_type'], 'string', 'max' => 50],
            [['voucher_title', 'dsc'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_no' => 'Order No',
            'user_id' => 'User ID',
            'store_id' => 'Store ID',
            'voucher_id' => 'Voucher ID',
            'voucher_title' => 'Voucher Title',
            'voucher_num' => 'Voucher Num',
            'goods_id' => 'Goods ID',
            'pay_status' => 'Pay Status',
            'pay_type' => 'Pay Type',
            'create_time' => 'Create Time',
            'cost' => 'Cost',
            'dsc' => 'Dsc',
        ];
    }
}
