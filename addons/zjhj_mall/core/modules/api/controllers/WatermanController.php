<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/3 0003
 * Time: 15:09
 */
namespace app\modules\api\controllers;

use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\models\Order;
use app\models\WaterOrder;
use app\models\UserWaterBucket;
use app\modules\api\models\VoucherPayDataForm;
use app\modules\api\models\UserVoucherForm;
use app\modules\api\models\waterman\WatermanForm;

class WatermanController   extends Controller{

    //送水员展示页
    public function  actionIndex(){
        $form = new WatermanForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $info=array(
            'info'=>$form->Info(),
            'order'=>$form->OrderList()
        );
        return new BaseApiResponse($info);
    }


    public function  actionList(){
        $form = new WatermanForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->OrderList());
    }

    public function  actionOrderDetail(){
        $query=WaterOrder::find()->alias('wo')
            ->leftJoin(['o'=>Order::tableName()],'o.id=wo.order_id')
            ->where([
                'wo.waterman_user_id'=>\Yii::$app->user->identity->id,
                'o.store_id'=>$this->store->id,
                'wo.status'=>0,
                'o.id'=>\Yii::$app->request->get('order_id')
            ])->select(['wo.*','o.id','o.order_no','wo.receive_user_id','o.name as addname','o.mobile as addmobile','o.address','o.total_price'])
            ->asArray()->one();
        if(empty($query)){
            return [
                'code' => 1,
                'msg' => '订单不存在',
            ];
        }
        $query['goods_list']=WaterOrder::getOrderGoodsList($query['order_id']);

       $bucket= UserWaterBucket::findOne(['user_id'=>$query['receive_user_id']]);

        $res=[
            'code' => 0,
            'data'=>$query,
            'bucket'=>$bucket,
            'msg' => 'success'
        ];
        return new BaseApiResponse($res);
    }


    //订单确认order_confirm
    public function  actionOrderConfirm(){

           $form = new WatermanForm();
           $form->attributes = \Yii::$app->request->post();
           $form->store_id = $this->store->id;
           $form->user = \Yii::$app->user->identity;
           return new BaseApiResponse($form->confirm());
    }






}