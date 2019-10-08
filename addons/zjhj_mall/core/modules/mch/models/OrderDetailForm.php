<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9
 * Time: 16:18
 */

namespace app\modules\mch\models;

use app\models\Goods;
use app\models\Mch;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderForm;
use app\models\OrderRefund;
use app\models\VoucherUsedLog;
use app\models\WaterOrder;
use app\models\Waterman;
use app\models\User;

class OrderDetailForm extends MchModel
{
    public $store_id;
    public $order_id;

    public function search()
    {
        $order = Order::find()->where(['store_id' => $this->store_id, 'id' => $this->order_id])->asArray()->one();
        if (!$order) {
            return [
                'code'=>1,
                'msg'=>'fail'
            ];
        }
        $order['integral_arr'] = json_decode($order['integral'], true);

        $order['get_integral'] = OrderDetail::find()
            ->andWhere(['order_id' => $order['id'], 'is_delete' => 0])
            ->select([
                'sum(integral)'
            ])->scalar();

        $form = new OrderListForm();
        $goods_list = $form->getOrderGoodsList($order['id']);
        $user = User::find()->where(['id' => $order['user_id'], 'store_id' => $this->store_id])->asArray()->one();
        $order_form = OrderForm::find()->where(['order_id' => $order['id'], 'is_delete' => 0, 'store_id' => $this->store_id])->asArray()->all();
        $order_refund = OrderRefund::findOne(['store_id' => $this->store_id, 'order_id' => $order['id'], 'is_delete' => 0]);
        if ($order_refund) {
            $order['refund'] = $order_refund->status;
        }
        if ($order['mch_id'] > 0) {
            $mch = Mch::findOne(['store_id' => $this->store_id, 'id' => $order['mch_id']]);
        }

        if($order['is_water_voucher']==1){
            $order['water_voucher']=VoucherUsedLog::find()->alias('vul')->leftJoin(['g'=>Goods::tableName()],'g.id=vul.goods_id')->
            where(['vul.order_id'=>$order['id'],'vul.store_id' => $this->store_id])->select(['g.name','vul.*'])->asArray()->all() ;
        }


        return [
            'order' => $order,
            'goods_list' => $goods_list,
            'user' => $user,
            'order_form' => $order_form,
            'mch' => $mch

        ];
    }


    public function getWaterInfo(){
       return WaterOrder::find()->alias('wo')->leftJoin(['wm'=>Waterman::tableName()],'wm.user_id=wo.waterman_user_id')
            ->where(['wo.order_id'=>$this->order_id])
            ->select(['wm.*','wo.order_bucket'])->asArray()->one();
    }
}
