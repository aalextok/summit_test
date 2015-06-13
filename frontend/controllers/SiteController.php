<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\authclient\clients\Facebook;

use yii\helpers\Url;
use backend\models\Activity;
use common\models\User;

/**
 * Site controller
 */
class SiteController extends \frontend\controllers\BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
              'class' => 'yii\authclient\AuthAction',
              'successCallback' => [
                $this, 'successCallback'
              ]
            ],
        ];
    }

    public function actionIndex()
    {
        
        if (!Yii::$app->user->isGuest) {
          return $this->redirect( Url::toRoute("site/dashboard") , 301);
        }
        
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionDashboard()
    {
        $allActivities = Activity::find()->all();
        
        return $this->render('dashboard', array(
            "allActivities" => $allActivities
        ));
    }
    
    public function successCallback($client)
    {
      $userAttributes = $client->getUserAttributes();
      
      $check = User::findIdentityByEmail($userAttributes['email'], false);
      
      if($check){
        Yii::$app->user->login($check);
      
        return [
          'user' => Yii::$app->user->identity
        ];
      } else {
        //create and login
        $oauthToken = $client->getAccessToken();
        $token = $oauthToken->getToken();
        
        //Yii::$app->components['authClientCollection']['clients']['facebook']['clientId']
        $facebook = new Facebook();
        
        $facebook->setAccessToken( $oauthToken );
        $userAttributes = $facebook->getUserAttributes();
        
        $tokenAttr = $facebook->api('debug_token', 'GET', ['input_token' => $token, 'access_token' => $token]);
        $picture = $facebook->api('me/picture', 'GET', ['redirect' => 'false']);
        
        $user = new User();
        //
        $user->username = $userAttributes['name'];
        $user->auth_key = $token;
        $user->auth_key_expires = $tokenAttr['data']['expires_at'];
        $user->email = $userAttributes['email'];
        //$user->birthday = date("Y-m-d", strtotime($userAttributes['birthday']));
        $user->firstname = $userAttributes['first_name'];
        $user->lastname = $userAttributes['last_name'];
        $user->gender = in_array($userAttributes['gender'], ['male', 'female']) ? strtoupper($userAttributes['gender']) : 'OTHER';
        $user->facebook_id = $userAttributes['id'];
        $user->image = $picture['data']['url'];
        $user->image_hash = hash_file('md5', $user->image);
        $user->status = User::STATUS_ACTIVE;
        
      }
      
      
      if($user && $user->validate()){
        $user->save();
        Yii::$app->user->login($user);
        return [
          'user' => Yii::$app->user->identity
        ];
      }
      else{
        throw new web\HttpException(400, User::errorsToString($user->getErrors()));
      }
    }
    
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
