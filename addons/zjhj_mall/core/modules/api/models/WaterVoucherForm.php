<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/18
 * Time: 12:11
 */

namespace app\modules\api\models;


use app\models\Goods;
use app\models\Order;
use app\models\VoucherPackage;
use app\models\VoucherUsedLog;
use app\models\UserVoucher;
use app\models\User;
use luweiss\wechat\Wechat;
use yii\data\Pagination;
use app\hejiang\ApiResponse;


/**
 * @property User $user
 * @property Order $order
 */
class WaterVoucherForm  extends ApiModel
{
    public $store_id;
    public $type;
    public $user;
    public $goods_id;
    public $voucher_id;
    public $user_id;
    public $page;
    public $limit;

    /** @var  Wechat $wechat */
    private $wechat;
    private $order;
    private $water_order;
    private $voucher_info;
    private $use_no;

    public function rules()
    {
        return [
            [['type','goods_id','voucher_id'], 'required'],
            [['type'], 'in', 'range' => ['voucher']],
            [['goods_id','voucher_id','page','limit'], 'integer'],
        ];
    }

    //订单取消退还水票
    public function orderCancel($order_id,$type='4'){

       $order=VoucherUsedLog::find()->where(['order_id'=>$order_id,'user_id'=>$this->user_id,'store_id'=>$this->store_id])->asArray()->all();

       if(empty($order))return false;
        foreach ($order as $li){
            $arr=array('order_id'=>$li['order_id'],'exchangeDetail'=>cancelDetail.'['.$order_id.']');
            $this->ChangeWaterVoucher($li['goods_id'],$type,$li['change_num'],$arr);
        }

    }

    /*$change_typ 1增加 2减少
     *
     * type 类型  voucherbuy 1   voucherExchange',2   orderCancel 4
     *
     * */
    public  function ChangeWaterVoucher($goods_id,$type,$change_num,$arr=array()){

        $type_arr=[voucherExchange];
        if(is_array($type,$type_arr)){
            $change_used_number=$change_num;
            $change_num=-1*$change_num;
            $change_type=voucherRed;
        }else{
            $change_used_number=-1*$change_num;
            $change_type=voucherAdd;
        }

        $user_voucher=UserVoucher::find()->where(['store_id'=>$this->store_id,'user_id'=>$this->user_id,'goods_id'=>$goods_id])->one();

        $user_voucher->num+=$change_num;
        $user_voucher->used_number+=$change_used_number;
        $user_voucher->save();

        $voucher_log= new VoucherUsedLog();
        $voucher_log->user_id=$this->user_id;
        $voucher_log->store_id=$this->store_id;
        $voucher_log->goods_id=intval($goods_id);
        $voucher_log->change_num=abs($change_num);
        $voucher_log->change_type=$change_type;
        $voucher_log->type=intval($type);
        $voucher_log->create_time=time();
        $voucher_log->order_id=$arr['order_id'];
        $voucher_log->voucher_order=isset($arr['voucher_order'])?$arr['voucher_order']:0;
        $voucher_log->detail=$arr['exchangeDetail'];
        $voucher_log->current_total=$user_voucher->num;
        $voucher_log->save();
    }





    /*
     * 水票商品
     * */
    public function goodsList(){
     $query=VoucherPackage::find()->alias('vp')
            ->leftJoin(['g'=>Goods::tableName()],'g.id=vp.goods_id')
            ->leftJoin(['uv'=>UserVoucher::tableName()],'uv.goods_id=vp.goods_id and uv.user_id='.$this->user->id)
            ->where(['g.is_delete'=>0,'g.status'=>1])
            ->groupBy('vp.goods_id');

    $count = $query->count();
    $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
    $low_price=VoucherPackage::find()->alias('vp2')->where('vp2.goods_id=g.id')->select(['package_price'])->orderBy('package_price asc')->limit('1');
    $list = $query->select(['g.name','g.cover_pic','uv.num','vp.goods_id','low_price'=>$low_price])->limit($pagination->limit)->offset($pagination->offset)->orderBy('uv.num desc')->asArray()->all();


        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'row_count' => $count,
                'page_count' => $pagination->pageCount,
                'list' => $list,
            ],
        ];

    }


    /*
     * $goods_id  -> 水票信息
     * */

    public function voucherInfo(){
       $vp= VoucherPackage::findOne(['goods_id'=>$this->goods_id]);
        if(empty($vp)){
            return new ApiResponse(1, 'success', '');
        }

        $info=array();
        $data=VoucherPackage::find()->alias('vp')->leftJoin(['g'=>Goods::tableName()],'g.id=vp.goods_id')->where([
            'vp.goods_id'=>$this->goods_id,
            'g.setting_voucher'=>1
        ])->select(['g.name','g.cover_pic','g.price','vp.*'])->asArray()->all();

        $default_code=$vp->default;
        foreach ($data as $key=>$item){
            $data[$key]['cost_price']=$item['price']*$item['package_number'];
            if($default_code==$item['code']){
                $info['default_price']=$item['package_price'];
                $info['default_cost_price']= $data[$key]['cost_price'];
                $info['cover_pic']= $data[$key]['cover_pic'];
                $info['default_id']=$item['id'];
            }
        }
         $info['list']=$data;
         $info['low_price']=$vp->lowprice;
         $info['default']=$default_code;
         $info['sales']=$vp->salesvolume;
         $info['notis']=$data[0]['voucher_dsc'];
         $info['goods_name']=$data[0]['name'];






        return new ApiResponse(0, 'success', $info);
    }

}
