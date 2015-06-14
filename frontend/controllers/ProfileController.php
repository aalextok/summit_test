<?php
namespace frontend\controllers;

use Yii;
use common\models\User;

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
}
