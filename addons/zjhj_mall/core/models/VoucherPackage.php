<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attr}}".
 *
 * @property integer $id
 * @property integer $attr_group_id
 * @property string $attr_name
 * @property integer $is_delete
 * @property integer $is_default
 */

define('CommonGoods','1');
class VoucherPackage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */


    public static function tableName()
    {
        return '{{%voucher_package}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '使用商品id',
            'package_number' => '套餐水票张数',
            'package_price' => '套餐费用',
            'title' => '套餐名称',
            'sort' => '是否默认项',
            'code' => '编码',
            'type' => '类型：1 普通商品水票 ',
        ];
    }
    //销量
    public function getSalesVolume(){
       $res= VoucherOrder::find()->where('goods_id='.$this->goods_id)->select(['sum(voucher_num) as total'])->asArray()->one();

        return $res['total'];
    }

    //最低价格
    public function getLowPrice(){
      $res=VoucherPackage::find()->alias('vp2')->where(['vp2.goods_id'=>$this->goods_id])->select(['package_price'])->orderBy('package_price asc')->limit('1')->one();
        return $res->package_price;
    }
    public function getDefault(){
        $res= VoucherPackage::find()->alias('vp2')->where(['vp2.goods_id'=>$this->goods_id])->select(['code'])->orderBy('choice asc')->limit('1')->one();
        return $res->code;
    }


}
