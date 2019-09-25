<?php


namespace app\modules\mch\controllers;


use app\modules\mch\models\waterman\WatermanForm;
use app\modules\mch\models\waterman\WatermanAddForm;
use app\models\Waterman;
use app\models\User;
use app\models\WaterOrder;

class WatermanController extends Controller
{

    //获取送水员列表
    public function actionIndex()
    {

        $form=new WatermanForm();
        $form->attributes=\Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $data = $form->search();
        return $this->render('index', [
            'row_count' => $data['row_count'],
            'pagination' => $data['pagination'],
            'list' => $data['list'],
        ]);
    }

    public function actionWatermanAdd(){

       //提交表单添加
        if (\Yii::$app->request->isPost) {
                $form=new WatermanAddForm();
                $form->attributes = \Yii::$app->request->post();
                $form->store_id = $this->store->id;
                return $form->save();
        }else{
            if (\Yii::$app->request->isAjax) {
                $keyword = trim(\Yii::$app->request->get('keyword'));
                $query = User::find()
                    ->alias('u')
                    ->leftJoin(['wm' => Waterman::tableName()], 'wm.user_id=u.id')->where([
                        'AND',
                        ['wm.id' => null],
                        ['u.store_id' => $this->store->id,],
                    ]);
                if ($keyword) {
                    $query->andWhere(['LIKE', 'u.nickname', $keyword]);
                }
                $list = $query->select('u.id,u.nickname,u.avatar_url')->asArray()
                    ->limit(20)->orderBy('u.nickname')->all();
                return [
                    'code' => 0,
                    'data' => $list,
                ];
            }
        }
        return $this->render('waterman-add');
    }




    //编辑用户
    public function actionWatermanEdit($id){
        $model=Waterman::findone(['id'=>$id,'store_id'=> $this->store->id]);
        $total='';
        if(!empty($model)){
            $unconfirm_total=WaterOrder::find()->where(['status'=>1,'waterman_user_id'=>$model['user_id']])->select(['count(1)']);
            $total=WaterOrder::find()->where(['status'=>2,'waterman_user_id'=>$model['user_id']])->select(['count(1) as confirm_total','unconfirm_total'=>$unconfirm_total])->asArray()->one();
        }

      //提交表单添加
        if (\Yii::$app->request->isPost) {
            $model->real_name=\Yii::$app->request->post('real_name');
            $model->mobile=\Yii::$app->request->post('mobile');
            $model->age=\Yii::$app->request->post('age');
            $model->wechat_code=\Yii::$app->request->post('wechat_code');
            $model->save();
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];

        }
        if(empty($model)){
            return [
                'code' => 0,
                'msg' => '配送员不存在',
            ];
        }
        return $this->render('waterman-edit', ['model' => $model,'total'=>$total]);

    }


    //删除送水员
    public function actionWatermanRemove($id){
        $model=Waterman::findone(['id'=>$id,'store_id'=> $this->store->id]);
        $unconfirm_total=WaterOrder::find()->where(['status'=>1,'waterman_user_id'=>$model['user_id']])->select(['count(1)'])->one();
        if($unconfirm_total>0){
            return [
                'code' => 1,
                'msg' => '存在未配送订单无法删除',
            ];
        }
        if(\Yii::$app->request->isAjax){
            $model->delete();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }


    //isAjax返回送水员
    public function actionWatermanList(){
        if(\Yii::$app->request->isAjax){
            $keyword=\Yii::$app->request->get('keyword');

            $unconfirm_total=WaterOrder::find()->where('waterman_user_id=wm.user_id')->andWhere(['status'=>1])->select(['count(1)']);
          $model=$model=Waterman::find()->alias('wm')->leftJoin(['u'=>User::tableName()],'u.id=wm.user_id')
                ->where(['or',['like','u.nickname', $keyword],['like','wm.code',$keyword]])
                ->orWhere(['or',['like','wm.mobile',$keyword],['like','wm.real_name',$keyword]])
                ->andWhere(['delete'=>0])
                ->limit(7)->select(['wm.*','u.nickname','u.avatar_url','unconfirm_total'=>$unconfirm_total])->asArray()->all();
            if(empty($model)) return [ 'code' =>1,  'data' => '配送员不存在' ];
            return [
                'code' => 0,
                'data' => $model,
            ];
        }

    }



}
