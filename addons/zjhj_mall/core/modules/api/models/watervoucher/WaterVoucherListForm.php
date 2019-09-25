<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/16 0016
 * Time: 09:30
 */

namespace app\modules\api\models\watervoucher;
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
class WaterVoucherListForm  extends ApiModel
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

    public function search(){

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






}
