<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/16 0016
 * Time: 09:30
 */

namespace app\modules\api\models\waterman;
use app\models\User;
use app\models\UserWaterBucket;
use app\modules\api\models\ApiModel;
use app\hejiang\ApiResponse;
use app\modules\api\models\wxbdc\WXBizDataCrypt;
use Curl\Curl;
use app\hejiang\ApiCode;
use app\models\Waterman;
use app\models\WaterOrder;
use app\models\Order;
use yii\data\Pagination;
class WatermanForm extends ApiModel
{
    public $wechat_app;
    public $code;
    public $store_id;
    public $store;
    public $user_id;
    public $user;
    public $page;
    public $limit;
    public $order_id;
    public $return_bucket;

    public function rules()
    {
        return [
            [['store_id','page','limit','order_id','return_bucket'], 'integer'],
        ];
    }


    public function info()
    {
        if(!$this->isWarterman()){
            return [
              'code'=>1,
              'message'=>'当前用户非配送员',
            ];
        }
        $info=Waterman::find()->alias('wm')->leftJoin(['u'=>User::tableName()],'u.id=wm.user_id')
             ->where(['wm.user_id'=>$this->user->id,'wm.store_id'=>$this->store_id])->select(['wm.*','u.avatar_url'])->asArray()->one();
        return $info;
    }

    public function OrderList(){

        $query=WaterOrder::find()->alias('wo')
            ->leftJoin(['o'=>Order::tableName()],'o.id=wo.order_id')
            ->where([
                'wo.waterman_user_id'=>$this->user->id,
                'o.store_id'=>$this->store_id,
                'wo.status'=>0
            ]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $list = $query->select(['wo.*','o.id','o.order_no','o.name as addname','o.mobile as addmobile','o.address','o.total_price'])
            ->limit($pagination->limit)->offset($pagination->offset)->orderBy('wo.id Desc')->asArray()->all();


        foreach ($list as $key=>$item){
            $list[$key]['goods_list']=WaterOrder::getOrderGoodsList($item['order_id']);
        }

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




    public function isWarterman(){
        $waterman=Waterman::findOne(['store_id'=>$this->store_id,'user_id'=>$this->user->id]);
        return !empty($waterman)?1:0;
    }


    public function confirm(){

        if($this->return_bucket<0||!is_int(intval($this->return_bucket))){
            return[
                'code'=>1,
                'msg'=>'输入不正确！'
            ];
        }

       $Order= WaterOrder::find()->where(['waterman_user_id'=>$this->user->id,'order_id'=>$this->order_id])->andWhere(['!=','status',water_order_confirm])->one();
        if(!empty($Order)){
            $receive_user=UserWaterBucket::findOne(['user_id'=>$Order->receive_user_id]);
            if(!empty($receive_user)){
                $num=$Order->order_bucket-$this->return_bucket;
                $receive_user->bucket+=$num;
               $res=$receive_user->save();
            }else{
             $userWaterbucket=  new UserWaterBucket;
             $userWaterbucket->user_id=$Order->receive_user_id;
             $userWaterbucket->deposit_bucket=$Order->order_bucket;
                $res=$userWaterbucket->insert();
            }
            if($res){
                $Order= WaterOrder::findone(['order_id'=>$this->order_id]);
                $Order->receive_time=date('Y-m-d H:i:s',time());
                $Order->status=water_order_confirm;
                $Order->back_bucket=$this->return_bucket;
               if($Order->save()) {
                    return([
                        'code'=>0,
                        'msg'=>'订单确认成功！'
                    ]);
               }
            }
            return[
                'code'=>1,
                'msg'=>'操作失败！'
            ];

        }else{
            return[
                'code'=>1,
                'msg'=>'单号不支持操作！'
            ];

        }


    }


}
