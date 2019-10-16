<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/27
 * Time: 1:05
 */

namespace app\modules\api\behaviors;

use app\hejiang\ApiResponse;
use yii\base\ActionFilter;
use yii\web\Controller;

class LoginBehavior extends ActionFilter
{
    public function beforeAction($e)
    {
        $access_token = \Yii::$app->request->get('access_token');
        if (!$access_token) {
            $access_token = \Yii::$app->request->post('access_token');
        }
        if ($access_token) {
            if (\Yii::$app->user->loginByAccessToken($access_token)) {
                return true;
            }
        }
        return true;
    }
}
