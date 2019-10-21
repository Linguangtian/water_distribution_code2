<?php


namespace app\modules\mch\controllers;


use app\models\UserWaterBucket;
use app\modules\mch\models\waterman\WatermanOrderForm;
use app\modules\mch\models\waterman\WatermanAddForm;
use app\modules\mch\models\waterman\BucketForm;
use app\models\Waterman;
use app\models\User;

class WatermanOrderController  extends Controller
{

    //获取水票订单列表
    public function actionIndex()
    {
        $form=new WatermanOrderForm();
        $form->attributes=\Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $data = $form->search();
        return $this->render('index', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
        ]);
    }

    //空桶管理


    public function actionBucket()
    {
        $form=new BucketForm();
        $form->attributes=\Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $data = $form->search();
        return $this->render('bucket', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
        ]);
    }

    //编辑
    public function actionBucketEdit($user_id){

        $user_bucketr=UserWaterBucket::findOne(['user_id'=>$user_id]);
        if(empty($user_bucketr)){
            $user_bucketr=  new UserWaterBucket();
        }
        if(\Yii::$app->request->isPost){
            $user_bucketr->bucket=\Yii::$app->request->post('bucket');
            $user_bucketr->deposit_bucket=\Yii::$app->request->post('deposit_bucket');
            $user_bucketr->deposit=\Yii::$app->request->post('deposit');
            $user_bucketr->save();
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        }
        $user_info['nickname']=$user_bucketr->user->nickname;
        $user_info['agatar_url']=$user_bucketr->user->avatar_url;

        return $this->render('bucket-edit', ['model' => $user_bucketr,'user_info'=>$user_info]);
    }



}
