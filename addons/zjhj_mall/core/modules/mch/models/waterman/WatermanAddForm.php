<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/3
 * Time: 13:52
 */

namespace app\modules\mch\models\waterman;


use app\models\User;
use app\models\Waterman;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class WatermanAddForm extends MchModel
{

    public $store_id;
    public $user_id;
    public $code;
    public $real_name;
    public $mobile;
    public $join_time;
    public $deliver_total;
    public $age;
    public $delete;
    public $wechat_code;


    public function rules()
    {
        return [
            [['user_id', 'store_id'], 'required'],
            [['user_id', 'store_id', 'join_time', 'deliver_total', 'age'], 'integer'],
            [['code', 'real_name'], 'string', 'max' => 50],
            [['mobile','wechat_code'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'store_id' => 'Store ID',
            'code' => 'Code',
            'real_name' => 'Real Name',
            'mobile' => 'Mobile',
            'join_time' => 'Join Time',
            'deliver_total' => 'Deliver Total',
            'age' => 'Age',
            'wechat_code' => '微信号',
        ];
    }
    public function save(){
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $model=new Waterman();
        $model->store_id =$this->store_id;
        $model->user_id =$this->user_id;
        $model->code =$this->code;
        $model->real_name =$this->real_name;
        $model->mobile =$this->mobile;
        $model->join_time =time();
        $model->deliver_total=0;
        $model->age =$this->age;
        $model->delete =0;
        $model->wechat_code =$this->wechat_code;
        if ($model->insert()) {
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }

    }







}
