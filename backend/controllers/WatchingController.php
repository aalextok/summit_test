<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;


class WatchingController extends ActiveController
{
    public $modelClass = 'backend\models\Watching';
    public $enableCsrfValidation = false;
    
    public function actions(){
        $actions = parent::actions();
        unset($actions['create']);
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
        
        $provider = new ActiveDataProvider([
            'query' => $model->find()->where(['user_id' => Yii::$app->user->identity->id]),
        ]);
        
        return $provider;
    }
    
    public function actionCreate(){
        $model = new $this->modelClass;
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->user_id = Yii::$app->user->identity->id;
        
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }
    
}
