<?php
namespace frontend\controllers;

use Yii;
use common\models\User;
use yii\helpers\Url;

class ProfileController extends \frontend\controllers\BaseController
{
    public function actionIndex()
    {
      
        $userId = Yii::$app->request->get('id');
        $user = User::findIdentity( $userId );
        
        return $this->render('index',[
            "id" => $userId,
            "user" => $user
         ]);
    }
    
    public function actionEdit()
    {
        if (Yii::$app->user->isGuest) {
          return $this->redirect( Url::toRoute("site/index") , 301);
        }
        
        $userId = User::getCurrentUserId();
        $user = User::findIdentity( $userId );
        
        return $this->render('edit',[
            "id" => $userId,
            "user" => $user
         ]);
    }
    
    public function actionSettings()
    {
      
        $userId = User::getCurrentUserId();
        $user = User::findIdentity( $userId );
        
        return $this->render('index',[
            "id" => $userId,
            "user" => $user
         ]);
    }
}
