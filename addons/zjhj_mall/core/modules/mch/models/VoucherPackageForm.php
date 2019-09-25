<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/7
 * Time: 12:59
 */

namespace app\modules\mch\models;
use app\models\VoucherPackage;
use Yii;
use yii\db\Query;

class  VoucherPackageForm extends MchModel
{
    public $goods_id;
    public $store_id;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'code'], 'trim'],
            [['store_id', 'goods_id', 'package_number', 'package_price', 'title', 'code',  'choice'], 'required'],
            [['store_id', 'goods_id', 'package_number'], 'integer'],
            [['package_price'], 'number', 'min' => 0.01, 'max' => 999999],
            [['title', 'code'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => '商城id',
            'goods_id' => '使用商品id',
            'package_number' => '套餐抵用券张数',
            'package_price' => '套餐费用',
            'title' => '套餐名称',
            'choice' => '是否默认项',
            'code' => '编码',
            'type' => '类型：1 普通商品水票 ',
        ];
    }


    public function getVoucherList(){
        $where=array(
            'goods_id'=>$this->goods_id,
            'store_id'=>$this->store_id
        );
        $query = new Query();
         $query_all=$query->select('*')
            ->from('hjmall_voucher_package')
            ->where($where)
            ->orderBy('choice')
            ->all();

        return $query_all;
    }


}
