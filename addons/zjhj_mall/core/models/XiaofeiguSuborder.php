<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;


class XiaofeiguSuborder extends \yii\db\ActiveRecord
{
    /**
     * 数据类型：积分
     */
    const TYPE_INTEGRAL = 0;

    /**
     * 数据类型：金额
     */
    const TYPE_BALANCE = 1;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%xiaofeigu_suborder}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['suborder', 'amount_total', 'ordernum_total', 'store_id'], 'required'],
           
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'suborder' => '子订单',
            'amount_total' => '总金额',
            'ordernum_total' => '数量',
            'amount' => '数量',
            'store_id' => '店铺',
            
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, 0, $data, $this->id);
    }







}
