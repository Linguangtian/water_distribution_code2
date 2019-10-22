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
use app\models\UserWaterBucket;

use app\modules\api\models\VoucherPayDataForm;
use app\modules\api\models\UserVoucherForm;
use app\modules\api\models\WaterVoucherList;
use app\modules\api\models\WaterVoucherForm;
use app\modules\api\models\watervoucher\WaterVoucherListForm;
use app\modules\api\behaviors\UseridBehavior;


class WatervoucherController extends Controller{


    public function behaviors()
    {
       return array_merge(parent::behaviors(), [
            'Userid' => [
                'class' => UseridBehavior::className(),
            ],
        ]);
    }




    //购买水票支付
    public function  actionVoucherPay(){

        $form = new VoucherPayDataForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->buyingVoucher());

    }


    //水票商品列表信息
    public function  actionGoodsListInfo(){


        $form = new WaterVoucherForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $goodsList=$form->goodsList();
        $mybucket=UserWaterBucket::findOne(['user_id'=>\Yii::$app->user->identity->id]);
        $res=[
            'goodsList'=>$goodsList,
            'mybucket'=>$mybucket
        ];
        return new BaseApiResponse($res);
    }



    //水票商品列表
    public function  actionGoodsList(){
        $form = new WaterVoucherForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->goodsList());
    }



    //我的水票列表
    public function  actionUserList(){
        $form = new UserVoucherForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->waterVoucherList());
    }


    //水票的操作记录
    public function actionUserLog(){
        $form = new UserVoucherForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->usedVoucherList());
    }


    public function actionInfo($goods_id){
        $form = new WaterVoucherForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->goods_id = $goods_id;
        return $form->voucherInfo();
    }



}