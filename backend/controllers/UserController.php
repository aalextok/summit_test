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
        if($action == 'view' && !empty($model) && $model->role !== 10){
            throw new web\ForbiddenHttpException;
        }
        
        parent::checkAccess($action, $model, $params);
    }
}
