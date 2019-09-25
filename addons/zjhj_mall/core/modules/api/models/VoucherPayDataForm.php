<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/18
 * Time: 12:11
 */

namespace app\modules\api\models;

use Alipay\AlipayRequestFactory;
use app\hejiang\ApiResponse;
use app\models\common\api\CommonOrder;
use app\models\FormId;
use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderUnion;
use app\models\OrderWarn;
use app\models\Setting;
use app\models\VoucherOrder;
use app\models\VoucherPackage;
use app\models\VoucherUsedLog;
use app\models\UserVoucher;
use app\models\User;
use luweiss\wechat\Wechat;

/**
 * @property User $user
 * @property Order $order
 */
class VoucherPayDataForm extends ApiModel
{
    public $store_id;
    public $type;
    public $user;
    public $goods_id;
    public $voucher_id;

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
            [['goods_id','voucher_id'], 'integer'],
        ];
    }

    public function buyingVoucher()
    {
        $this->wechat = $this->getWechat();
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        //判断商品是否开启抵用券购买
        $this->voucher_info=voucherpackage::find()
                     ->select(['v.*','g.name'])
                        ->alias('v')
                        ->leftJoin(['g'=>Goods::tableName()],'g.id=v.goods_id')
                        ->where(['v.id'=>$this->voucher_id,'g.id'=>$this->goods_id,'g.setting_voucher'=>1])
                        ->asArray()
                        ->one();

        if(empty($this->voucher_info)){
            return [
                'code' => 1,
                'msg' => '购买失败'
            ];
        }

        if(empty($this->voucher_info['package_price'])||$this->voucher_info['package_price']=='0'||$this->voucher_info['package_price']<'0'){
            return [
                'code' => 1,
                'msg' => '目前不支持0元购'
            ];
        }

    /*  $this->use_no=$this->getOrderUnionNo();
        $res = $this->unifiedOrder();
        if (isset($res['code']) && $res['code'] == 1) {
            return $res;
        }*/

        //付款成功后
        $my_voucher=UserVoucher::findOne(['store_id'=>$this->store_id,'goods_id'=>$this->goods_id,'user_id'=>$this->user->id]);
        if($my_voucher){
            $my_voucher->num+=$this->voucher_info['package_number'];
            $my_voucher->total_number+=$this->voucher_info['package_number'];
        }else{
            $my_voucher=new UserVoucher();
            $my_voucher->user_id=$this->user->id;
            $my_voucher->store_id=$this->store_id;
            $my_voucher->goods_id=intval($this->goods_id);
            $my_voucher->total_number=$this->voucher_info['package_number'];
            $my_voucher->num=$this->voucher_info['package_number'];
        }
        $my_voucher->save();//  $result=$my_voucher->save();

        //增加抵用券订单
        $voucher_order= new VoucherOrder();
        $voucher_order->order_no=$this->getOrderUnionNo();
        $voucher_order->user_id=$this->user->id;
        $voucher_order->store_id=$this->store_id;
        $voucher_order->goods_id=$this->goods_id;
        $voucher_order->voucher_id=$this->voucher_id;
        $voucher_order->voucher_num=$this->voucher_info['package_number'];
        $voucher_order->pay_status='2';
        $voucher_order->voucher_title=$this->voucher_info['name'].'-'.$this->voucher_info['title'];
        $voucher_order->create_time=time();
        $voucher_order->pay_type='wechat';
        $voucher_order->cost=$this->voucher_info['package_price'];
        $voucher_order->dsc='购买水票:'.$this->voucher_info['name'].'-'.$this->voucher_info['title'].'['.$this->voucher_info['package_number'].'张/￥'.$this->voucher_info['package_price'].']';
        $voucher_order->insert();


        //增加抵用券记录
        if($voucher_order->attributes['id']){
            $voucher_user_log= new VoucherUsedLog();
            $voucher_user_log->user_id=$this->user->id;
            $voucher_user_log->goods_id=$this->goods_id;
            $voucher_user_log->store_id=$this->store_id;
            $voucher_user_log->change_num=$this->voucher_info['package_number'];
            $voucher_user_log->change_type=voucherAdd;
            $voucher_user_log->type=voucherbuy;
            $voucher_user_log->create_time=time();
            $voucher_user_log->detail='水票购买';
            $voucher_user_log->current_total=$my_voucher->num;
            $voucher_user_log->voucher_order=$voucher_order->attributes['id'];
            $voucher_user_log->insert();
        }





        //
        $pay_data = [
            'appId' => $this->wechat->appId,
            'timeStamp' => '' . time(),
            'nonceStr' => md5(uniqid()),
            'package' => 'prepay_id=' . 110,
            'signType' => 'MD5',
        ];
        $pay_data['paySign'] = $this->wechat->pay->makeSign($pay_data);
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => (object)$pay_data,
            'res' => 1,
            'body' => $this->voucher_info->name.'-水票',
        ];



                //记录prepay_id发送模板消息用到
                FormId::addFormId([
                    'store_id' => $this->store_id,
                    'user_id' => $this->user->id,
                    'wechat_open_id' => $this->user->wechat_open_id,
                    'form_id' => $res['prepay_id'],
                    'type' => 'prepay_id',
                    'order_no' => $this->order->order_no,
                ]);

                $pay_data = [
                    'appId' => $this->wechat->appId,
                    'timeStamp' => '' . time(),
                    'nonceStr' => md5(uniqid()),
                    'package' => 'prepay_id=' . $res['prepay_id'],
                    'signType' => 'MD5',
                ];
                $pay_data['paySign'] = $this->wechat->pay->makeSign($pay_data);
                return [
                    'code' => 0,
                    'msg' => 'success',
                    'data' => (object)$pay_data,
                    'res' => $res,
                    'body' => $this->voucher_info->name.'-水票',
                ];
    }




    public function addVoucherOrder(){


    }


    //微信支付下单
    private function unifiedOrder($goods_names='my_test')
    {
        $res = $this->wechat->pay->unifiedOrder([
            'body' =>$this->voucher_info->name.'-水票',
            'out_trade_no' => $this->use_no,
            'total_fee' => $this->voucher_info->price * 100,
            'notify_url' => pay_notify_url('/pay-notify.php'),
            'trade_type' => 'JSAPI',
            'openid' => $this->user->wechat_open_id,
        ]);

        if (!$res) {
            return [
                'code' => 1,
                'msg' => '支付失败',
            ];
        }
        if ($res['return_code'] != 'SUCCESS') {
            return [
                'code' => 1,
                'msg' => '支付失败，' . (isset($res['return_msg']) ? $res['return_msg'] : ''),
                'res' => $res,
            ];
        }
        if ($res['result_code'] != 'SUCCESS') {
            if ($res['err_code'] == 'INVALID_REQUEST') { //商户订单号重复
                $this->order->order_no = (new OrderSubmitForm())->getOrderNo();
                $this->order->save();
                return $this->unifiedOrder($goods_names);
            } else {
                return [
                    'code' => 1,
                    'msg' => '支付失败，' . (isset($res['err_code_des']) ? $res['err_code_des'] : ''),
                    'res' => $res,
                ];
            }
        }
        return $res;
    }



    /**
     * 购买成功首页提示
     */
    private function buyData($order_no, $store_id, $type)
    {
        $order = Order::find()->select(['u.nickname', 'g.name', 'u.avatar_url', 'od.goods_id'])->alias('c')
            ->where('c.order_no=:order', [':order' => $order_no])
            ->andwhere('c.store_id=:store_id', [':store_id' => $store_id])
            ->leftJoin(['u' => User::tableName()], 'u.id=c.user_id')
            ->leftJoin(['od' => OrderDetail::tableName()], 'od.order_id=c.id')
            ->leftJoin(['g' => Goods::tableName()], 'od.goods_id = g.id')
            ->asArray()->one();

        $key = "buy_data";
        $data = (object)null;
        $data->type = $type;
        $data->store_id = $store_id;
        $data->order_no = $order_no;
        $data->user = $order['nickname'];
        $data->goods = $order['goods_id'];
        $data->address = $order['name'];
        $data->avatar_url = $order['avatar_url'];
        $data->time = time();
        $new = json_encode($data);
        $cache = \Yii::$app->cache;
        $cache->set($key, $new, 300);
    }


   /*单号*/
    public function getOrderUnionNo()
    {
        $order_no = null;
        while (true) {
            $order_no = 'U' . date('YmdHis') . mt_rand(10000, 99999);
            $exist_order_no = OrderUnion::find()->where(['order_no' => $order_no])->exists();
            if (!$exist_order_no) {
                break;
            }
        }
        return $order_no;
    }




}
