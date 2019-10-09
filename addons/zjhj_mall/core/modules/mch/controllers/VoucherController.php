<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 10:56
 */

namespace app\modules\mch\controllers;


use app\modules\mch\models\VoucherLogListForm;
use app\modules\mch\models\UserVoucherForm;
use app\modules\mch\models\ExportList;

use Hejiang\Event\EventArgument;
use yii\web\HttpException;

/**
 * Class GoodController
 * @package app\modules\mch\controllers
 * 商品
 */
class VoucherController extends Controller
{

        //获取抵用券操作历史记录
        public function actionIndex($is_offline = null)
        {


            // 获取可导出数据
            $f = new ExportList();
            $exportList = $f->getWaterAction();


            $form=new VoucherLogListForm();
            $form->attributes=\Yii::$app->request->get();
            $form->attributes=\Yii::$app->request->post();
            $form->store_id = $this->store->id;
            $form->is_offline = \Yii::$app->request->get('is_offline');
            $data = $form->search();
            return $this->render('index', [
                'row_count' => $data['row_count'],
                'pagination' => $data['pagination'],
                'list' => $data['list'],
                'exportList' => \Yii::$app->serializer->encode($exportList),
            ]);

        }



















         public function actionOrderDetail(){
            $form=new VoucherLogListForm();
            $form->attributes=\Yii::$app->request->get();
            $form->store_id = $this->store->id;
            $data = $form->orderDetail();
            return  [
                'code' => empty($data)?1:0,
                'order_info' => $data,
            ];

        }


        //用户抵用券列表
        public function actionUserVoucherList(){
            $form=new UserVoucherForm();
            $form->attributes=\Yii::$app->request->get();
            $form->attributes=\Yii::$app->request->post();
            $form->store_id = $this->store->id;
            $data = $form->search();
            return $this->render('uservoucherlist', [
                'row_count' => $data['row_count'],
                'pagination' => $data['pagination'],
                'list' => $data['list'],
            ]);
        }


        //平台水票统计列表
        public function actionVoucherList(){

            $form=new UserVoucherForm();
            $form->attributes=\Yii::$app->request->get();
            $form->attributes=\Yii::$app->request->post();
            $form->store_id = $this->store->id;
            $data = $form->voucherCountList();

            return $this->render('voucherlist', [
                'row_count' => $data['row_count'],
                'pagination' => $data['pagination'],
                'list' => $data['list'],
            ]);
    }


}