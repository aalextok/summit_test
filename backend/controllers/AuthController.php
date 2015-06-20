<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\authclient\clients\Facebook;
use yii\authclient\OAuthToken;
use yii\filters\VerbFilter;
use yii\web;
use common\models\User;

class AuthController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public $enableCsrfValidation = false;
    
    public function actions(){
        $actions = parent::actions();
        unset($actions['index']);
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
            'except' => ['signup', 'login', 'password-reset', 'app-credentials'],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'login' => ['POST'],
                'signup' => ['POST'],
                'password-reset' => ['POST'], 
                'app-credentials' => ['GET'],
            ],  
        ];
        return $behaviors;
    }
    
    public function beforeAction($action) {
        Yii::$app->user->enableSession = false;
        return parent::beforeAction($action);
    }
    
    public function actionAppCredentials($auth_type = 'facebook'){
        if(!array_key_exists($auth_type, Yii::$app->params['authCredentials'])){
            throw new web\HttpException(400, "Authentication system '$auth_type' not supported."); 
        }
        
        return Yii::$app->params['authCredentials'][$auth_type];
    }

        public function actionSignup($auth_type = 'app'){
                
        $user = null;
        if($auth_type == 'facebook'){
            $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
            preg_match("/^Bearer\\s+(.*?)$/", $authHeader, $matches);
            
            if(empty($matches)){
                throw new web\HttpException(400, 'Authentication token absent or incorrect');
            }
            
            $token = $matches[1];
            $facebook = new Facebook(Yii::$app->params['authCredentials']['facebook']);
            $oauthToken = new OAuthToken();
            $oauthToken->setToken($token);
            $facebook->setAccessToken($oauthToken);
            $userAttributes = $facebook->getUserAttributes();
            $tokenAttr = $facebook->api('debug_token', 'GET', ['input_token' => $token, 'access_token' => $token]);
            $picture = $facebook->api('me/picture', 'GET', ['redirect' => 'false']);

            $user = User::findIdentityByEmail($userAttributes['email'], false);
            if($user){
                throw new web\HttpException(400, "User with email {$user->email} already exists.");
            }
            
            $user = new User();

            $user->username = $userAttributes['name'];
            $user->auth_key = $token;
            $user->auth_key_expires = $tokenAttr['data']['expires_at'];
            $user->email = $userAttributes['email'];
          
            $user->firstname = $userAttributes['first_name'];
            $user->lastname = $userAttributes['last_name'];
            $user->gender = in_array($userAttributes['gender'], ['male', 'female']) ? strtoupper($userAttributes['gender']) : 'OTHER';
            $user->facebook_id = $userAttributes['id'];
            
            $user->image = $picture['data']['url'];
            $user->image_hash = hash_file('md5', $user->image);
            $user->status = User::STATUS_ACTIVE;
            
        }
        else{
            $email = Yii::$app->request->post('email');

            $user = User::findIdentityByEmail($email, false);
            if($user){
                throw new web\HttpException(400, "User with email {$user->email} already exists.");
            }

            $user = new User();
            $user->email = $email;
            $user->phone = Yii::$app->request->post('phone');
            $user->username = Yii::$app->request->post('username');
            //$user->password = Yii::$app->request->post('password');
            
            $password = Yii::$app->request->post('password');
            if (empty($password)){
                throw new web\HttpException(400, "Password must be a string and cannot be empty.");
            }
            $user->password = $password;
            
            $user->firstname = Yii::$app->request->post('firstname');
            $user->lastname = Yii::$app->request->post('lastname');
            
            $user->gender = Yii::$app->request->post('gender');
            $user->gender = isset($user->gender) ? $user->gender : '';
            
            $user->generateAuthKey(); 
            $user->auth_key_expires = time() + Yii::$app->params['authKeyLifeTime']*60*60;
            $user->status = User::STATUS_ACTIVE;
            $user->image_hash = md5('');
        }    
        
        $user->upgradeRank();
        
        if($user->validate()){
            $user->save();
            
            Yii::$app->user->login($user);

            return [
                'user' => Yii::$app->user->identity
            ];
        }
        else{
            throw new web\HttpException(400, User::errorsToString($user->getErrors()));
        }
        
        throw new web\HttpException(500, "Unknown error");
    }
    
    public function actionLogin($auth_type = 'app'){
        $user = null;
        if($auth_type == 'facebook'){
            $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
            preg_match("/^Bearer\\s+(.*?)$/", $authHeader, $matches);
            $token = $matches[1];
            
            if(!empty($token)){
                $facebook = new Facebook(Yii::$app->params['authCredentials']['facebook']);
                $oauthToken = new OAuthToken();
                $oauthToken->setToken($token);
                $facebook->setAccessToken($oauthToken);
                $userAttributes = $facebook->getUserAttributes();
                $tokenAttr = $facebook->api('debug_token', 'GET', ['input_token' => $token, 'access_token' => $token]);
                
                $user = User::findOne(['facebook_id' => $userAttributes['id']]);
                
                
                if(isset($user)){
                    $user->auth_key = $token;
                    $user->auth_key_expires = $tokenAttr['data']['expires_at'];                  
                  }
                else {
                    throw new web\HttpException(404, 'User not found');
                }
            }
        }
        else {
            $email = Yii::$app->request->post('email');
            $password = Yii::$app->request->post('password');
            $user = User::findIdentityByEmail($email);
            if(empty($user)){
                throw new web\HttpException(404, 'User not found');
            }
            
            if(!$user->validatePassword($password)){
                throw new web\HttpException(401, 'Password incorrect');
            }
            
            $user->generateAuthKey();
            $user->auth_key_expires = time() + Yii::$app->params['authKeyLifeTime']*60*60;
            
        }
        
        // save device_token and platform
        $device_token = Yii::$app->request->post('device_token');
        $platform = strtolower(Yii::$app->request->post('platform'));
        $user->device_token = !empty($device_token) ? $device_token : $user->device_token;
        $user->platform = !empty($platform) ? $platform : $user->platform;
       
        $user->last_login = time();
        $user->login_count++;
        $user->updated_at = time();
        
        if(!empty($user) && $user->update()){
            return [
                        'message' => 'Successfully logged in',
                        'user' => $user,
                        'auth_key' => $user->auth_key,
                        'auth_key_expires' => $user->auth_key_expires,
                ];
        }
        else{
            throw new web\HttpException(400, User::errorsToString($user->getErrors()));
        }
        
        throw new web\HttpException(500, "Unknown error");      
    }
}
