<?php
namespace app\modules\mch\models\waterman;
use app\models\User;
use app\models\Waterman;
use app\models\WaterOrder;
use yii\data\Pagination;
use app\modules\mch\models\MchModel;

class WatermanForm  extends MchModel
{
    public $store_id;
    public $page;
    public $keyword;
    public $is_clerk;
    public $level;
    public $user_id;
    public $goods_id;
    public $mobile;
    public $platform;
    public $fields;
    public $flag;
    public $date_start;
    public $date_end;
    public $voucher_order_id;
    public $change_type;

    public function rules()
    {
        return [
            [['keyword', 'level', 'user_id', 'mobile', 'flag', 'date_start', 'date_end'], 'trim'],
            [['page', 'is_clerk','voucher_order_id','goods_id','user_id','change_type'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['platform','fields'], 'safe']
        ];
    }


    //送水员列表
    public function search()
    {

        $query=Waterman::find()
            ->alias('wm')
            ->leftJoin(['u'=>User::tableName()],'u.id=wm.user_id')
            ->where(['wm.store_id'=>$this->store_id,'delete'=>0]);

        if ($this->keyword) {
            $query->andWhere(['LIKE', 'u.nickname', $this->keyword]);
            $query->orWhere(['LIKE', 'wm.real_name', $this->keyword]);
            $query->orWhere(['LIKE', 'wm.mobile', $this->keyword]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);

        $confirm_total=WaterOrder::find()->where(['status'=>2])->andWhere('waterman_user_id=wm.user_id')->select(['count(1)']);
       $unconfirm_total=WaterOrder::find()->where('waterman_user_id=wm.user_id')->andWhere(['status'=>0])->select(['count(1)']);
        $list =$query->select(['wm.*', 'u.avatar_url','u.nickname','confirm_total'=>$confirm_total,'unconfirm_total'=>$unconfirm_total])
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->orderBy('wm.id DESC')
            ->asArray()->all();
        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];
    }

}
