<?php

namespace app\models;

use Yii;
use app\models\Goods;
use app\models\GoodsPic;
use app\models\OrderDetail;
/**
 * This is the model class for table "{{%hjmall_water_order}}".
 *
 * @property int $id
 * @property int $order_id
 * @property int $waterman_user_id
 * @property int $goods_id
 * @property int $receive_user_id
 * @property string $create_time
 * @property int $order_bucket
 * @property int $real_bucket
 * @property int $back_bucket
 * @property int $receive_time
 * @property int $is_deposit
 * @property string $deposit_money
 * @property int $status
 * @property string $message
 */
define('produce_water_order',0);
define('water_order_confirm',2);
class WaterOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%water_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'waterman_user_id',  'receive_user_id'], 'required'],
            [['order_id', 'waterman_user_id',  'receive_user_id', 'order_bucket', 'real_bucket', 'back_bucket',  'is_deposit', 'status'], 'integer'],
            [['create_time','receive_time'], 'safe'],
            [['deposit_money'], 'number'],
            [['message'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'waterman_user_id' => 'Waterman ID',

            'receive_user_id' => 'Receive User ID',
            'create_time' => 'Create Time',
            'order_bucket' => 'Order Bucket',
            'real_bucket' => 'Real Bucket',
            'back_bucket' => 'Back Bucket',
            'receive_time' => 'Receive Time',
            'is_deposit' => 'Is Deposit',
            'deposit_money' => 'Deposit Money',
            'status' => 'Status',
            'message' => 'Message',
        ];
    }

   static public function getOrderGoodsList($order_id)
    {
        $picQuery = GoodsPic::find()
            ->alias('gp')
            ->select('pic_url')
            ->andWhere('gp.goods_id = od.goods_id')
            ->andWhere(['is_delete' => 0])
            ->limit(1);
        $orderDetailList = OrderDetail::find()->alias('od')
            ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
            ->where([
                'od.is_delete' => 0,
                'od.order_id' => $order_id,
            ])->select(['od.num', 'od.total_price', 'od.attr', 'od.is_level', 'g.name', 'g.unit', 'goods_pic' => $picQuery])->asArray()->all();
        foreach ($orderDetailList as &$item) {
            $item['attr_list'] = json_decode($item['attr'], true);
        }

        return $orderDetailList;
    }


    public function getOrderDetail(){
        return $this->hasOne(OrderDetail::className(), ['order_id' => 'order_id'])->alias('od')
            ->leftJoin(['g' => Goods::tableName()], 'g.id=od.goods_id')->select(['od.*', 'g.name' , 'g.attr goods_attr','g.is_delete','g.cat_id']);
    }



}
