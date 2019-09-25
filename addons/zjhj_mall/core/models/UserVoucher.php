<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/3 0003
 * Time: 16:58
 */
namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

class UserVoucher extends ActiveRecord{


    public static function tableName()
    {
        return '{{%user_voucher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num','goods_id'], 'integer'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'num' => '拥有抵用有数量',
            'user_id' => 'User ID',
            'goods_id' => '商品ID',
            'total_number' => 'Total Number',
            'used_number' => 'Used Number',
        ];
    }



}