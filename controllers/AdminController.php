<?php

namespace app\controllers;

use Yii;
use app\models\Company;
use app\models\CompanyUser;
use app\models\User;
use app\models\Customer;
use app\models\Card;
use app\models\CardContent;
use app\models\CardData;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class AdminController extends Controller
{
    public $layout = 'admin';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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
        ];
    }

    public function actionIndex()
    {
        $companyCount = Company::find()->count();
        $userCount = User::find()->count();
        $customerCount = Customer::find()->count();
        $cardCount = Card::find()->count();
		
		$user = CompanyUser::find()->where('user_id=:user_id', array(':user_id' => Yii::$app->user->identity->id))->one();
		$cards = Card::find()->where('company_id=:company_id AND type_id IN (3,7,8,9)', array(':company_id' => $user->company_id))->orderBy('updated_at DESC')->all();
		//$cards = Card::find()->where('company_id=:company_id AND type_id IN (3,7,8,9)', array(':company_id' => 1))->orderBy('id DESC')->all();
		
		
		$aCards = array();
		$cutCards = array_slice($cards, 0, 2);
		foreach ($cutCards AS $card) {
			$contents = CardContent::find()->where('card_id=:id', array(':id' => $card->id))->all();
			//var_dump($content);
			$oType = $card->getType()->one();
			
			$aCards[$card->id] = array(
				'title' => $card->title,
				'question' => $card->question,
				'type' => $oType->name
			);
			
			switch ($oType->name) {
				case "basic" : 
					foreach ($contents AS $content) {
						$answers = CardData::find()->where('card_content_id=:content_id', array(':content_id' => $content->id))->all();
						$aCards[$card->id]['total'] = count($answers);
						if (!empty($answers)) {
							$aAnswers = array_slice($answers, 0, 4);
							foreach ($aAnswers AS $answer) {
								$customer = Customer::find()->where('id=:id', array(':id' => $answer->customer_id))->one();
								$aCards[$card->id]['content'][$answer->id] = array(
									'title' => $customer->name,
									'data' => $answer->answer
								);
							}
						}
					}
					break;
				case "checkbox" :
					$aCards[$card->id]['total'] = (count($contents) > 4) ? 'options' : 0;
					$total = 0;
					$count = array();
					if (!empty($contents)) {
						foreach ($contents AS $c) {
							$count[$c->id] = count(CardData::find()->where('card_content_id=:content_id', array(':content_id' => $c->id))->all());
							$total = $total + $count[$c->id];
						}
						foreach ($contents AS $content) {
							$aCards[$card->id]['content'][$content->id] = array(
								'title' => ($count > 0 && $total > 0) ? ($count[$content->id]/$total * 100) . '%' : '0%',
								'data' => $content->title
							);
						}
					}
					break;
				case "radio" :
					$aCards[$card->id]['total'] = (count($contents) > 4) ? 'options' : 0;
					$total = 0;
					$count = array();
					if (!empty($contents)) {
						foreach ($contents AS $c) {
							$count[$c->id] = count(CardData::find()->where('card_content_id=:content_id', array(':content_id' => $c->id))->all());
							$total = $total + $count[$c->id];
						}
						foreach ($contents AS $content) {
							$aCards[$card->id]['content'][$content->id] = array(
								'title' => ($count > 0 && $total > 0) ? ($count[$content->id]/$total * 100) . '%' : '0%',
								'data' => $content->title
							);
						}
					}
					break;
				case "image" : 
					foreach ($contents AS $content) {
						$images = CardData::find()->where('card_content_id=:content_id', array(':content_id' => $content->id))->all();
						$aCards[$card->id]['total'] = (count($images) > 12) ? count($images) : 0;
						if (!empty($images)) {
							$aImages = array_slice($images, 0, 10);
							foreach ($aImages AS $image) {
								$customer = Customer::find()->where('id=:id', array(':id' => $image->customer_id))->one();
								$aCards[$card->id]['content'][$image->id] = array(
									'title' => $customer->name,
									'data' => $card->hash . '/' . $image->answer
								);
							}
						}
					}
					break;
				case "range" : 
					foreach ($contents AS $content) {
						$aCards[$card->id]['content'][$content->id] = array(
							'title' => 'Work In Progress',
							'data' => $content->title
						);
					}
					break;
				default :
					$aCards[$card->id]['total'] = count($contents);
					foreach ($contents AS $content) {
						$aCards[$card->id]['content'][$content->id] = array(
							'title' => '',
							'data' => $content->title
						);
					}
			}
			if (!isset($aCards[$card->id]['total'])) {
				$aCards[$card->id]['total'] = 0;
			}
		}
		//var_dump($aCards);
		
        return $this->render('index',[
            'companyCount' => $companyCount,
            'userCount' => $userCount,
            'customerCount' => $customerCount,
            'cardCount' => $cardCount,
			'cards' => $aCards
        ]);
    }

}
