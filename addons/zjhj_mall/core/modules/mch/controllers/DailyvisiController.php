<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17 0017
 * Time: 11:15
 */

namespace app\modules\mch\controllers;
use app\models\Test;
use yii\db\Query;
use app\utils\SendMail;



class DailyvisiController  extends Controller
{
    public function actionIndex($uniacid = 0)
    {
      if($uniacid != 0){

          $yesterday = date('Ymd', strtotime('-1 days'));
          $sql='select * from ims_wxapp_general_analysis where uniacid=\''.$uniacid.'\' and ref_date=\''.$yesterday.'\'';
          $data=\yii::$app->db->createCommand($sql)->queryAll();
            var_dump($data);exit;

        exit;
          $mail = new SendMail();
          $mail->SendMailVisi($data);

      }

    }

//
}