<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9
 * Time: 18:06
 */

namespace app\modules\mch\models\mch;

use app\models\Mch;
use app\models\MchCat;
use app\models\Model;
use app\models\MchAccountLog;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class MchAccountLogForm extends MchModel
{
    public $store_id;
    public $mch_id;
    public $page;
    public $limit;

    public function rules()
    {
        return [
            [[ 'page', 'limit'], 'integer'],
            [['page',], 'default', 'value' => 1,],
            [['limit',], 'default', 'value' => 20,],
        ];
    }
     public function attributes()
        {
            return [
                [[ 'mch_id', 'limit'], 'integer'],

            ];
        }

    public function search()
    {

        $query=MchAccountLog::find()->alias('mal')->leftJoin(['m' => Mch::tableName()], 'm.id=mal.mch_id')->where([ 'mal.store_id' => $this->store_id]);
        if($this->mch_id>0){
            $query->andWhere([ 'mal.mch_id' => $this->mch_id]);
        }else{
            $query->andWhere(['>', 'mal.mch_id', 0]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $query->limit($pagination->limit)->offset($pagination->offset);

        $list=$query->select('mal.*,m.name,m.logo')->asArray()->all();
        return [
            'code' => 0,
            'data' => [
                'list' => $list,
                'pagination' => $pagination,
                'adminUrl' => $this->getAdminUrl('mch')
            ],
        ];
    }
}
