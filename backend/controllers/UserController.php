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
        
        $provider = new ActiveDataProvider([
            'query' => $model->find()->where(['role' => 10]), // do not see yourself
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
