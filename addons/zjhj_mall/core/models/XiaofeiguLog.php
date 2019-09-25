<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;


class XiaofeiguLog extends \yii\db\ActiveRecord
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
        return '{{%xiaofeigu_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'store_id', 'amount', 'change_type', 'shore_desc', 'create_time','current_amount'], 'required'],
            [['user_id', 'store_id', 'order_id', 'change_type','type','proportion'], 'integer'],
            [['shore_desc','change_desc'], 'string'],
            [[ 'shore_desc','change_desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => '店铺id',
            'user_id' => '用户id',
            'order_id' => '订单id',
            'amount' => '数量',
            'change_desc' => '描述',
            'shore_desc' => '简述',
            'create_time' => '创建时间',
            'current_amount' => '当前数量',
            'change_type' => '明细类型 （0 其他 1 完成订单赠送 2 平台操作）',
            'type' => '类型 （ 1 增加 2 减少）',
            'proportion' => '消费股比例',
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

  /*  public function addXiaofeiguLog($user_id,$change_type $changedAttributes){

        $store_id = $this->store->id;

        $xiaofeiguLog->change_type = 2;
        $xiaofeiguLog->shore_desc = $explain?trim($explain):'平台操作无备注';
        $xiaofeiguLog->create_time = date("Y-m-d H:i:s",time());

        if ($changeType == '2') {
            $xiaofeiguLog->amount = $amount*-1;
        } elseif ($changeType == '1') {
            $xiaofeiguLog->amount = $amount;
        }

        $xiaofeiguLog->type = $changeType;
        $current_amount=$user->xiaofeigu_amount+$xiaofeiguLog->amount;


        if($current_amount<0)$current_amount=0;
        $xiaofeiguLog->current_amount = $current_amount;


        if ($this->is_we7) {
            $admin = \Yii::$app->user->identity;
        } elseif ($this->is_ind) {
            $admin = \Yii::$app->admin->identity;
        } else {
            $admin = \Yii::$app->mchRoleAdmin->identity;
        }
        $xiaofeiguLog->change_desc ="管理员：" . $admin->username . "后台操作账号" . $user->nickname . " 消费股操作：". $xiaofeiguLog->amount .'股';
        $xiaofeiguLog->save();

    }*/





}
