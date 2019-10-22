<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22 0022
 * Time: 10:23
 */
namespace app\modules\api\behaviors;

use app\hejiang\ApiResponse;
use yii\base\ActionFilter;
use yii\web\Controller;


class UseridBehavior extends ActionFilter{

    public function beforeAction($e)
    {
        if (\Yii::$app->user->identity) {
            return true;
        }
        \Yii::$app->response->data = new ApiResponse(-1, '请先授权登入,');
        return false;
    }


}

