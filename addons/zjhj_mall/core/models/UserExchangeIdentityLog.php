<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%hjmall_user_exchange_identity_log}}".
 *
 * @property int $id
 * @property int $create_date
 * @property int $user_id1
 * @property int $user_id2
 * @property string $notes
 * @property string $user1_info
 * @property string $user2_info
 */
class UserExchangeIdentityLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_exchange_identity_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_date', 'user_id1', 'user_id2'], 'integer'],
            [['user_id1', 'user_id2'], 'required'],
            [['user1_info', 'user2_info','nickname2','nickname1'], 'string'],
            [['notes'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_date' => 'Create Date',
            'user_id1' => 'User Id1',
            'user_id2' => 'User Id2',
            'notes' => 'Notes',
            'user1_info' => 'User1 Info',
            'user2_info' => 'User2 Info',
            'nickname2' => 'nickname2',
            'nickname1' => 'nickname1',
        ];
    }
}
