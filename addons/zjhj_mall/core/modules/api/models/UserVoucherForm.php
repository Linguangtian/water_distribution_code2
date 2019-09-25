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
use yii\data\Pagination;
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
class UserVoucherForm  extends ApiModel
{
    public $store_id;
    public $type;
    public $user;
    public $goods_id;
    public $voucher_id;
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
            [['type'], 'required'],
            [['goods_id','voucher_id','type','page'], 'integer'],
            [['page',], 'default', 'value' => 1],
            [['limit',], 'default', 'value' => 20],
        ];
    }


    public function waterVoucherList(){
       $list=UserVoucher::find()->alias('uv')
            ->leftJoin(['g'=>Goods::tableName()],'g.id=uv.goods_id')
            ->where(['uv.user_id'=>$this->user->id,'uv.store_id'=>$this->store_id])->andWhere(['>','uv.num','0'])
            ->select(['uv.num','uv.goods_id', 'g.name','g.cover_pic','g.price'])
          ->asArray()->all();
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'list' => $list,
            ],
        ];

    }
    /*
     * type：2 已使用的
     * type：1 购买
     * type：3 其他
     * type  0操作记录
    */
    public function usedVoucherList(){

        if(!$this->validate()){
            return $this->getErrorResponse();
        }
        $query=VoucherUsedLog::find()->alias('vul')
            ->leftJoin(['g'=>Goods::tableName()],'g.id=vul.goods_id')
            ->where(['vul.user_id'=>$this->user->id,'vul.store_id'=>$this->store_id]);

        if(!empty($this->type)){
            $query ->andWhere(['vul.type'=>$this->type]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $res = $query->select(['vul.change_num','vul.type','vul.change_type','vul.current_total','vul.goods_id', 'g.name','g.cover_pic','vul.create_time'])->limit($pagination->limit)->offset($pagination->offset)->orderBy('create_time Desc')->asArray()->all();

        $list=array();
        $arr=['其他','购买','兑换','平台赠送'];
        foreach ($res as $key=>$item){
            $list[$key]=$item;
            $list[$key]['create_time']= date('Y-m-d H:i:s', $item['create_time']);
            $list[$key]['type']=$arr[$item['type']];
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
