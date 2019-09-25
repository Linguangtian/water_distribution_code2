<?php
namespace app\modules\mch\models\waterman;
use app\models\User;
use app\models\Waterman;
use app\models\UserWaterBucket;
use yii\data\Pagination;
use app\modules\mch\models\MchModel;

class BucketForm  extends MchModel
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

        $query=UserWaterBucket::find()
            ->alias('ub')
            ->leftJoin(['u'=>User::tableName()],'u.id=ub.user_id')
            ->where(['u.store_id'=>$this->store_id]);

        if ($this->keyword) {
            $query->andWhere(['LIKE', 'u.nickname', $this->keyword]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);

        $list =$query->select(['ub.*', 'u.avatar_url','u.nickname'])
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->groupBy('ub.user_id')
            ->asArray()->all();
        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];
    }

}
