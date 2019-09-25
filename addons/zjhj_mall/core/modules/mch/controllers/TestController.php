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

class TestController extends Controller
{
    public function actionIndex($type = 0)
    {
       /* $sql='select * from hjmall_mch where id=2';s

        $res=\yii::$app->db->createCommand($sql)->queryAll();
        $res=\yii::$app->db->createCommand()->getRawSql();
        var_dump($res);exit;*/


        $query = Test::find();
        $res= $query->select(['id'])->where(['id'=>2])->one();
        var_dump($res);exit;

    }


}