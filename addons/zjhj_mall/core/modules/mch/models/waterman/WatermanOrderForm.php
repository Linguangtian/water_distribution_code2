<?php
namespace app\modules\mch\models\waterman;
use app\models\User;
use app\models\Waterman;
use app\models\WaterOrder;
use app\models\Order;
use app\models\Goods;
use app\models\GoodsPic;
use app\models\OrderDetail;
use GuzzleHttp\Psr7\_caseless_remove;
use yii\data\Pagination;
use app\modules\mch\models\MchModel;

class WatermanOrderForm   extends MchModel
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
    public $order_id;
    public $code;

    public function rules()
    {
        return [
            [['keyword', 'level', 'user_id', 'mobile', 'flag', 'date_start', 'date_end'], 'trim'],
            [['page', 'is_clerk','voucher_order_id','goods_id','user_id','change_type','order_id'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['platform','fields'], 'safe'],
            [['code'], 'string']
        ];
    }



    //送水员列表
    public function search()
    {

      $query=WaterOrder::find()
            ->alias('wo')
            ->leftJoin(['u'=>User::tableName()],'u.id=wo.receive_user_id')
            ->leftJoin(['o'=>Order::tableName()],'o.id=wo.order_id')
            ->leftJoin(['wm'=>Waterman::tableName()],'wm.user_id=wo.waterman_user_id')
            ->where(['u.store_id'=>$this->store_id]);

        if ($this->keyword) {
            $query->andWhere(['LIKE', 'wm.real_name', $this->keyword]);
        }

        if ($this->order_id) {
            $query->andWhere(['=', 'wo.order_id', $this->order_id]);
        }

        if ($this->code) {
            $query->andWhere(['=', 'wm.code', $this->code]);
        }


        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);


        $sql=$query->select(['wo.*', 'wm.*','o.order_no','o.name','o.address','o.mobile as order_mobile'])
            ->limit($pagination->limit)
            ->offset($pagination->offset)->createCommand()->getRawSql();
        $list=WaterOrder::findBySql($sql)->asArray()->all();

        foreach ($list as &$item){
            $item['goods_list'] = $this->getOrderGoodsList($item['order_id']);
        }


        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];
    }







    public function getOrderGoodsList($order_id)
    {
        $picQuery = GoodsPic::find()
            ->alias('gp')
            ->select('pic_url')
            ->andWhere('gp.goods_id = od.goods_id')
            ->andWhere(['is_delete' => 0])
            ->limit(1);
        $orderDetailList = OrderDetail::find()->alias('od')
            ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
            ->where([
                'od.is_delete' => 0,
                'od.order_id' => $order_id,
            ])->select(['od.num', 'od.total_price', 'od.attr', 'od.is_level', 'g.name', 'g.unit', 'goods_pic' => $picQuery])->asArray()->all();
        foreach ($orderDetailList as &$item) {
            $item['attr_list'] = json_decode($item['attr'], true);
        }

        return $orderDetailList;
    }



}
