<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/3
 * Time: 13:52
 */

namespace app\modules\mch\models;

use app\hejiang\ApiResponse;
use app\models\IntegralOrder;
use app\models\Level;
use app\models\MsOrder;
use app\models\Order;
use app\models\PtOrder;
use app\models\Share;
use app\models\UserCoupon;
use app\models\UserVoucher;
use app\models\Shop;
use app\models\Store;
use app\models\User;
use app\models\UserCard;
use app\models\YyOrder;
use app\models\VoucherUsedLog;
use app\models\VoucherOrder;
use app\models\Goods;
use app\modules\mch\extensions\Export;
use yii\data\Pagination;

class UserVoucherForm   extends MchModel
{
    public $store_id;
    public $page;
    public $keyword;
    public $is_clerk;
    public $level;
    public $user_id;
    public $mobile;
    public $platform;
    public $fields;
    public $flag;
    public $date_start;
    public $date_end;
    public $voucher_order_id;
    public $goods_id;

    public function rules()
    {
        return [
            [['keyword', 'level', 'user_id', 'mobile', 'flag', 'date_start', 'date_end'], 'trim'],
            [['page', 'is_clerk','voucher_order_id','goods_id'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['platform','fields'], 'safe']
        ];
    }

    public function search()
    {
        $query=UserVoucher::find()
            ->alias('uv')
            ->leftJoin(['g'=>Goods::tableName()],'g.id=uv.goods_id')
            ->leftJoin(['u'=>User::tableName()],'u.id=uv.user_id')
            ->where(['uv.store_id'=>$this->store_id]);

        if ($this->keyword) {
            $query->andWhere(['LIKE', 'u.nickname', $this->keyword]);
        }
        if ($this->user_id) {
            $query->andWhere(['=', 'u.id', $this->user_id]);
        }
        if ($this->goods_id) {
            $query->andWhere(['=', 'uv.goods_id', $this->goods_id]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);

        $list =$query->select(['uv.*', 'g.name','u.nickname','g.cover_pic'])
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->orderBy('uv.num DESC')
            ->asArray()->all();

        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];

    }




    //平台总水票统计
    public function voucherCountList(){

        $query=UserVoucher::find()
            ->alias('uv')
            ->leftJoin(['g'=>Goods::tableName()],'g.id=uv.goods_id')
            ->where(['uv.store_id'=>$this->store_id])
            ->groupBy('uv.goods_id');

        if ($this->keyword) {
            $query->andWhere(['LIKE', 'g.name', $this->keyword]);
        }


        $count = $query->count();


        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);

        $list =$query->select(['SUM(uv.num) as all_num ','SUM(uv.total_number) as total_num ','SUM(uv.used_number) as total_used ','uv.goods_id', 'g.name','g.cover_pic'])
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->orderBy('uv.num DESC')
            ->asArray()->all();

        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];






    }


    public function getUser()
    {
        $query = User::find()->where([
            'type' => 1,
            'store_id' => $this->store_id,
            'is_clerk' => 0,
            'is_delete' => 0
        ]);
        if ($this->keyword) {
            $query->andWhere([
                'or',
                ['LIKE', 'nickname', $this->keyword],
                ['LIKE', 'wechat_open_id', $this->keyword]
            ]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('addtime DESC')->asArray()->all();
//        $list = $query->orderBy('addtime DESC')->asArray()->all();

        return $list;
    }


}
