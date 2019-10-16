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
use app\models\UserCreditLog;
use app\models\UserExchangeIdentityLog;
use app\models\PtOrder;
use app\models\Share;
use app\models\UserCoupon;
use app\models\Shop;
use app\models\Store;
use app\models\User;
use app\models\UserCard;
use app\models\YyOrder;
use app\models\UserVoucher;
use app\modules\mch\extensions\Export;
use yii\data\Pagination;

class UserListForm extends MchModel
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
    public $user_id2;
    public $notes;


    public function rules()
    {
        return [
            [['keyword', 'level', 'user_id', 'user_id2','mobile', 'flag'], 'trim'],
            [['page', 'is_clerk'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['platform','fields'], 'safe'],
            [['notes'], 'string', 'max' => 255],
        ];
    }

    public function search()
    {
        $query = User::find()->alias('u')->where([
            'u.type' => 1,
            'u.store_id' => $this->store_id,
            'u.is_delete' => 0,
        ])->leftJoin(Shop::tableName() . ' s', 's.id=u.shop_id')
            ->leftJoin(Level::tableName() . ' l', 'l.level=u.level and l.store_id=' . $this->store_id)
            ->andWhere(['OR', [
                'l.is_delete' => 0], 'l.id IS NULL']);
        if ($this->keyword) {
            $query->andWhere(['LIKE', 'u.nickname', $this->keyword]);
        }
        if (isset($this->platform)) {
            $query->andWhere(['platform' => $this->platform]);
        }
        if ($this->mobile) {
            $query->andWhere([
                'or',
                ['LIKE', 'u.binding', $this->mobile],
                ['like', 'u.contact_way', $this->mobile]
            ]);
        }
        if ($this->is_clerk == 1) {
            $query->andWhere(['u.is_clerk' => 1]);
            $orderQuery = Order::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'is_cancel' => 0, 'is_recycle' => 0, 'mch_id' => 0])->andWhere('clerk_id = u.id')->select('count(1)');
            $cardQuery = UserCard::find()->where(['store_id' => $this->store_id, 'is_delete' => 0])->andWhere('clerk_id = u.id')->select('count(1)');
        } else {
            $orderQuery = Order::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'is_cancel' => 0, 'is_recycle' => 0, 'mch_id' => 0])->andWhere('user_id = u.id')->select('count(1)');
            $cardQuery = UserCard::find()->where(['store_id' => $this->store_id, 'is_delete' => 0])->andWhere('user_id = u.id')->select('count(1)');
        }





        if ($this->level || $this->level === '0' || $this->level === 0) {
            $query->andWhere(['l.level' => $this->level]);
        }

        $couponQuery = UserCoupon::find()->where(['is_delete' => 0])->andWhere('user_id = u.id')->select('count(1)');
        $voucherQuery = UserVoucher::find()->where(['store_id' => $this->store_id])->andWhere('user_id = u.id')->select('sum(num)');
        if ($this->flag === Export::EXPORT) {
            $orderConsumeQuery = Order::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'is_cancel' => 0])->andWhere('user_id = u.id')->select('sum(pay_price)');
            $ptOrderConsumeQuery = PtOrder::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'is_cancel' => 0])->andWhere('user_id = u.id')->select('sum(pay_price)');
            $yyOrderConsumeQuery = YyOrder::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'is_cancel' => 0])->andWhere('user_id = u.id')->select('sum(pay_price)');
            $msOrderConsumeQuery = MsOrder::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'is_cancel' => 0])->andWhere('user_id = u.id')->select('sum(pay_price)');
            $integralOrderConsumeQuery = IntegralOrder::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'is_cancel' => 0])->andWhere('user_id = u.id')->select('sum(pay_price)');

            $list = $query->select([
                'u.*', 's.name shop_name', 'l.name l_name', 'card_count' => $cardQuery, 'order_count' => $orderQuery,
                'coupon_count' => $couponQuery, 'orderConsume' => $orderConsumeQuery,
                'ptOrderConsume' => $ptOrderConsumeQuery, 'yyOrderConsume' => $yyOrderConsumeQuery,
                'msOrderConsume' => $msOrderConsumeQuery, 'integralOrderConsume' => $integralOrderConsumeQuery
            ])->orderBy('u.addtime DESC')->asArray()->all();
            $export = new ExportList();
            $export->fields = $this->fields;
            $export->UserExportData($list);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query->select([
            'u.*', 's.name shop_name', 'l.name l_name', 'card_count' => $cardQuery, 'order_count' => $orderQuery, 'coupon_count' => $couponQuery,'voucher_count'=>$voucherQuery
        ])->limit($pagination->limit)->offset($pagination->offset)->orderBy('u.addtime DESC')->asArray()->all();
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

    public function getShare()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $query = Share::find()->alias('s')
            ->where(['s.is_delete' => 0, 's.store_id' => $this->store_id, 's.status' => 1])
            ->leftJoin(['u' => User::tableName()], 'u.id=s.user_id')
            ->andWhere(['!=', 'u.id', $this->user_id])
            ->andWhere(['u.is_delete' => 0]);
        if ($this->keyword) {
            $query->andWhere([
                'or',
                ['like', 's.name', $this->keyword],
                ['like', 'u.nickname', $this->keyword]
            ]);
        }
        $list = $query->select('u.id,u.avatar_url,u.nickname,s.name')->limit(10)->asArray()->all();
        return new ApiResponse(0, '', [
            'list' => $list
        ]);
    }

    public function getClerk()
    {
        $query = User::find()->where([
            'type' => 1,
            'store_id' => $this->store_id,
            'is_clerk' => 1,
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
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('addtime DESC')->all();

        $newList = [];
        foreach ($list as &$item) {
            $newItem = [
                'id' => $item->id,
                'nickname' => $item->nickname,
                'shop' => $item->shop->name,
            ];
            $newList[] = $newItem;
        }
        unset($item);
        return $newList;
    }

    public function excelFields()
    {
        $list = [
            [
                'key' => 'id',
                'value' => '用户ID',
                'selected' => 0,
            ],
            [
                'key' => 'nickname',
                'value' => '昵称',
                'selected' => 0,
            ],
            [
                'key' => 'binding',
                'value' => '绑定手机号',
                'selected' => 0,
            ],
            [
                'key' => 'contact_way',
                'value' => '联系方式',
                'selected' => 0,
            ],
            [
                'key' => 'comments',
                'value' => '备注',
                'selected' => 0,
            ],
            [
                'key' => 'addtime',
                'value' => '加入时间',
                'selected' => 0,
            ],
            [
                'key' => 'identity',
                'value' => '会员身份',
                'selected' => 0,
            ],
            [
                'key' => 'order_count',
                'value' => '订单数',
                'selected' => 0,
            ],
            [
                'key' => 'consume_count',
                'value' => '总消费',
                'selected' => 0,
            ],
            [
                'key' => 'coupon_count',
                'value' => '优惠券总数',
                'selected' => 0,
            ],
            [
                'key' => 'card_num',
                'value' => '卡券总数',
                'selected' => 0,
            ],
            [
                'key' => 'integral',
                'value' => '积分',
                'selected' => 0,
            ],
            [
                'key' => 'money',
                'value' => '余额',
                'selected' => 0,
            ],
        ];

        return $list;
    }


    //用户身份交换
    public function exchangeIdentity(){
        $user1=User::findOne(['id'=>$this->user_id]);
        $user2=User::findOne(['id'=>$this->user_id2]);
        if(empty($user1)||empty($user2)){
            return [
                'code' => 0,
                'msg' => '会员不存在',
            ];
        }

        $user1_info=User::find()->where(['id'=>$this->user_id])->asArray()->one();
        $user1_info=json_encode($user1_info);
        $user2_info=User::find()->where(['id'=>$this->user_id2])->asArray()->one();
        $user2_info=json_encode($user2_info);

        $t = \Yii::$app->db->beginTransaction();

        $user3= clone $user1;
        $nickname1=$user3->nickname;
        $nickname2=$user2->nickname;
        $user1->nickname=$user2->nickname;
        $user1->avatar_url=$user2->avatar_url;
        $user1->wechat_open_id=$user2->wechat_open_id;
        $user1->access_token=$user2->access_token;
        $user1->save();

        $user2->nickname=$user3->nickname;
        $user2->avatar_url=$user3->avatar_url;
        $user2->wechat_open_id=$user3->wechat_open_id;
        $user2->access_token=$user3->access_token;
        if($user2->save()){
            $t->commit();
            $log=new UserExchangeIdentityLog();
            $log->create_date=time();
            $log->user_id1=$this->user_id;
            $log->user_id2=$this->user_id2;
            $log->nickname1=$nickname1;
            $log->nickname2= $nickname2;
            $log->user1_info=$user1_info;
            $log->user2_info=$user2_info;
            $log->notes=$this->notes;
            $log->save();
            return [
                'code' => 0,
                'msg' => '互换成功',
            ];
        }else{
            $t->rollBack();
            return [
                'code' => 1,
                'msg' => '失败',
            ];
        }
    }

    public function exchangeIdentityLog(){

        $query=UserExchangeIdentityLog::find();
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);

        $list =$query->select(['*'])
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->orderBy('id DESC')
            ->asArray()->all();

        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];

    }


    //用户账期记录
    public  function creditList(){
        $query=UserCreditLog::find()
            ->alias('ucl')
            ->leftJoin(['u'=>User::tableName()],'u.id=ucl.user_id')
            ->where(['ucl.store_id'=>$this->store_id]);


        if ($this->keyword) {
            $query->andWhere(['LIKE', 'u.nickname', $this->keyword]);
            $query->orWhere(['LIKE', 'wm.real_name', $this->keyword]);
            $query->orWhere(['LIKE', 'wm.mobile', $this->keyword]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list =$query->select(['ucl.*', 'u.avatar_url','u.nickname'])
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->orderBy('ucl.id DESC')
            ->asArray()->all();
        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];

    }




}
