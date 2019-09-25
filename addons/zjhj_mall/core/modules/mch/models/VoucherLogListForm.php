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

class VoucherLogListForm  extends MchModel
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

    public function search()
    {
       $query=VoucherUsedLog::find()
           ->alias('vl')
           ->leftJoin(['g'=>Goods::tableName()],'g.id=vl.goods_id')
           ->leftJoin(['u'=>User::tableName()],'u.id=vl.user_id')
           ->where(['vl.store_id'=>$this->store_id]);

        if ($this->keyword) {
            $query->andWhere(['LIKE', 'u.nickname', $this->keyword]);
            $query->orWhere(['LIKE', 'g.name', $this->keyword]);
        }
        if ($this->date_start) {
            $query->andWhere(['>', 'vl.create_time', strtotime($this->date_start)]);
        }
        if($this->date_end){
            $query->andwhere(['<','vl.create_time',strtotime($this->date_end+1)]);
        }
        if($this->user_id){
            $query->andwhere(['=','vl.user_id',$this->user_id]);
        }
        if($this->goods_id){
            $query->andwhere(['=','vl.goods_id',$this->goods_id]);
        }

        if($this->change_type){
            $query->andwhere(['=','vl.change_type',$this->change_type]);
        }
       $count = $query->count();
       $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);

        $list =$query->select(['vl.*', 'g.name','u.nickname','g.cover_pic'])
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->orderBy('vl.create_time DESC')
            ->asArray()->all();

        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];

    }


    public function orderList(){



    }





    public function orderDetail(){

       $voucher_order= VoucherOrder::find()->where(['id'=>$this->voucher_order_id])->asArray()->one();
        return $voucher_order;
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
