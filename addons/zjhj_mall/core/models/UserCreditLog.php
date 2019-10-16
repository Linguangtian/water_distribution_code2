<?php

namespace app\models;

use Yii;
define('CREDI_REPAY','1');
define('CREDI_TYPE_REPAY','1');

/**
 * This is the model class for table "{{%hjmall_user_credit_log}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $store_id
 * @property int $change_type
 * @property string $create_time
 * @property int $order_id
 * @property string $explain
 */
class UserCreditLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_credit_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'store_id', 'change_type', 'create_time'], 'required'],
            [['user_id', 'store_id', 'change_type', 'order_id','create_time','type'], 'integer'],
            [['credit_money', 'current_credit_cost'], 'number'],
            [['explain'], 'string', 'max' => 255],
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
            'store_id' => 'Store ID',
            'change_type' => 'Change Type',
            'create_time' => 'Create Time',
            'order_id' => 'Order ID',
            'explain' => 'Explain',
            'type' => '类型',
            'credit_money' => 'credit_money',
            'current_credit_cost' => 'current_credit_cost',
        ];
    }
}
