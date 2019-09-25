<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%hjmall_user_water_bucket}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $goods_id
 * @property int $bucket
 * @property string $deposit
 */
class UserWaterBucket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_water_bucket}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'goods_id', 'bucket','deposit_bucket'], 'integer'],
            [['deposit'], 'number'],
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
            'bucket' => 'Bucket',
            'deposit' => 'Deposit',
            'deposit_bucket' => '押桶数',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
