<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%Hjmall_waterman}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $store_id
 * @property string $code
 * @property string $real_name
 * @property string $mobile
 * @property int $join_time
 * @property int $deliver_total
 * @property int $age
 */
class Waterman extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%waterman}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'store_id'], 'required'],
            [['user_id', 'store_id', 'join_time', 'deliver_total', 'age'], 'integer'],
            [['code', 'real_name'], 'string', 'max' => 50],
            [['mobile','wechat_code'], 'string', 'max' => 255],
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
            'code' => 'Code',
            'real_name' => 'Real Name',
            'mobile' => 'Mobile',
            'join_time' => 'Join Time',
            'deliver_total' => 'Deliver Total',
            'age' => 'Age',
            'wechat_code' => '微信号',
        ];
    }



}
