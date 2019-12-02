<?php

/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/1/26
 * Time: 15:28
 */
namespace app\modules\api\controllers\quick;

use app\modules\api\controllers\Controller;
use app\modules\api\models\quick\QuickForm;
use app\modules\api\models\CatListForm;
use app\modules\api\models\GoodsForm;
use app\modules\api\models\quick\QuickCarForm;

class QuickController extends Controller
{
    // 首页http://www.water2.com/addons/zjhj_mall/core/web/index.php?_acid=5&r=api/quick/quick/quick&cat_id=49&_version=2.8.9&_platform=wx
    public function actionQuick()
    {
        $form = new QuickForm();
        $form->store_id = $this->store_id;
        $form->cat_id=\Yii::$app->request->get('cat_id');

        $quick = $form->goods();
        return new \app\hejiang\BaseApiResponse($quick);
    }
}
