<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

class ActivityController extends ActiveController
{
    public $modelClass = 'backend\models\Activity';
    public $enableCsrfValidation = false;
    
    public function actions(){
        $actions = parent::actions();
        
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);

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
    
}
