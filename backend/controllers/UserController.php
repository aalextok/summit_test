<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\web;
use yii\data\ActiveDataProvider;
use common\models\User;


class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public $enableCsrfValidation = false;
    
    public function actions(){
        $actions = parent::actions();
        
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        
        return $actions;
    }
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            //'except' => ['index', 'view'],
        ];

        return $behaviors;
    }
    
    public function beforeAction($action) {
        Yii::$app->user->enableSession = false;
        return parent::beforeAction($action);
    }
    
    public function prepareDataProvider(){
        $model = new $this->modelClass;
        
        $searchableStrFields = [
            'username',
            'firstname',
            'lastname',
            'gender',
            'email',
            'phone',
        ];
        
        $searchableNumFields = [
            'points',
            'summits',
            'meters_above_sea_level',
            'distance'
        ];
        
        $rank = Yii::$app->request->getQueryParam('rank');
        $operator = filter_var(Yii::$app->request->getQueryParam('greater'), FILTER_VALIDATE_BOOLEAN) === false ? '<=' : '>=';
        
//        $query = $model->find()->where([
//                'role' => 10
//            ])->andWhere([
//                'not in', 
//                'id', 
//                [Yii::$app->user->identity->id]
//            ]);
        
        $query = $model->find()->where([]);
        
        foreach($searchableStrFields as $field){
            $param = Yii::$app->request->getQueryParam($field); 
            if(isset($param)){
                //$query->andWhere(['like', $field, $param]);
                $query->orWhere(['like', $field, $param]);
            }
        }
        
        
        foreach($searchableNumFields as $field){
            $param = Yii::$app->request->getQueryParam($field);
            if(isset($param)){
                //$query->andWhere([$operator, $field, $param]);
                $query->orWhere([$operator, $field, $param]);
            }
        }
        
        if(!empty($rank)){
            //$query->andWhere(['rank' => $rank]);
            $query->orWhere(['rank' => $rank]);
        }
        
        $query->andWhere(['role' => 10]);
        $query->andWhere([
            'not in', 
            'id', 
            [Yii::$app->user->identity->id]
        ]);
        
        //$command = $query->createCommand();
        //$sql = $command->rawSql;
        
        $provider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $provider;
    }
    
    public function checkAccess($action, $model = null, $params = array()) {
        if($action == 'view' && !empty($model) && $model->role !== User::ROLE_USER){
            throw new web\ForbiddenHttpException;
        }
        
        parent::checkAccess($action, $model, $params);
    }
    
    public function actionUpdate($id){
        
        $model = User::find()->where(['id' => $id])->one();
        
        if($model->id != Yii::$app->user->identity->id){
            throw new web\ForbiddenHttpException;
        }
        
        foreach(User::$editableFields as $field){
            $val = Yii::$app->getRequest()->getBodyParam($field);
            if(isset($val)){
                $model->{$field} = $val;
            }
        }
        
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        
        if($model->hasErrors()){
            throw new web\HttpException(400, User::errorsToString($model->getErrors())); 
        }

        return $model;
    }
}
