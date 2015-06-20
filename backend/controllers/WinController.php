<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

use yii\data\ActiveDataProvider;

class WinController extends ActiveController
{
    public $modelClass = 'backend\models\Win';
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
        ];

        return $behaviors;
    }
    
    public function beforeAction($action) {
        Yii::$app->user->enableSession = false;
        return parent::beforeAction($action);
    }
    
    public function prepareDataProvider(){
        $model = new $this->modelClass;
        $query = $model::find()->where([
            'user_id' => Yii::$app->user->identity->id,
        ]);
        
        $provider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $provider;
    }
}