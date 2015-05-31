<?php

namespace app\api\modules\v1\controllers;

use app\api\modules\v1\controllers\ApiController as ApiController;
use app\api\modules\v1\models\Api;
use app\models\Customer;

class CustomerController extends ApiController
{
	public function actionGet()
	{
		$this->_checkAuth();
		
		$model = new Customer();
		$customerHash = str_replace('CUSTOMER ', '', $_SERVER['HTTP_AUTHORIZATION']);
		$modelQuery = $model->find()->where('hash=:hash', array(':hash' => $customerHash))->one();
		
		if (!empty($modelQuery)) {
			$return = array(
				'status' => 1,
				'cards_unanswered' => 2,
				'name' => $modelQuery->name,
				'email'	=> $modelQuery->email
			);
			$this->_sendResponse(200, $return);
		} else {
			$this->_sendResponse(500);
		}
	}
	
	public function actionRegister()
	{
		/*
		 * !!! MIGRATION FAIL UUENDADA "DEVICE_ID" VÄLJAGA !!!
		 */
		$apiCheck = $this->_checkAuth(true);
		
		$name = (!empty($_POST['name'])) ? $_POST['name'] : '';
		$email = (!empty($_POST['email'])) ? $_POST['email'] : '';
		$fb_id = (!empty($_POST['fb_id'])) ? $_POST['fb_id'] : '';
		$fb_access_token = (!empty($_POST['fb_access_token'])) ? $_POST['fb_access_token'] : '';
		$device_id = (!empty($_POST['device_id'])) ? $_POST['device_id'] : '';
		$device_token = (!empty($_POST['device_token'])) ? $_POST['device_token'] : '';
		$language = (!empty($_POST['language'])) ? $_POST['language'] : '';
		$ip_addr = (!empty($_POST['ip_addr'])) ? $_POST['ip_addr'] : '';
		
		$getCustomer = new Customer();
		$apiAccess = new Api();
		
		// Mis saab, kui company_id ei matchi kasutajale määratud company_id-ga?
		if (!empty($fb_id) && !empty($fb_access_token)) {
			$findCustomer = $getCustomer->find()
									->where(
										'fb_id=:fb_id AND fb_access_token=:fb_access_token',
										array(':fb_id' => $fb_id, ':fb_access_token' => $fb_access_token)
									)->one();
			if ($findCustomer) {
				$customer = $findCustomer;
			} else {
				$customer = new Customer();
				$customer->fb_id = $fb_id;
				$customer->fb_access_token = $fb_access_token;
			}
			$customer->company_id = (empty($customer->company_id)|| $customer->company_id != $apiCheck->company_id) ? $apiCheck->company_id : $customer->company_id;
			// Siia mingi funktsioon, mis võtab facebookis nime ja emaili, kui on?
			$customer->name = 'fb_name';
			$customer->email = 'fb_email';
			$customer->device_id = (!empty($device_id) || empty($customer->device_id)) ? $device_id : $customer->device_id;
			$customer->device_token = (!empty($device_token) || empty($customer->device_token)) ? $device_token : $customer->device_token;
			$customer->language = (!empty($language) || empty($customer->language)) ? $language : $customer->language;
			$customer->ip_addr = (!empty($ip_addr) || empty($customer->ip_addr)) ? $ip_addr : $customer->ip_addr;
			if ($findCustomer) {
				$insertResult = $customer->save();
			} else {
				$insertResult = $customer->insert();
			}
		} else if (!empty($device_id)) {
			$findCustomer = $getCustomer->find()->where('device_id=:device_id', array(':device_id' => $device_id))->one();
			if ($findCustomer) {
				$customer = $findCustomer;
			} else {
				$customer = new Customer();
				$customer->device_id = $device_id;
			}
			$customer->company_id = (empty($customer->company_id)|| $customer->company_id != $apiCheck->company_id) ? $apiCheck->company_id : $customer->company_id;
			$customer->name = $name;
			$customer->email = $email;
			$customer->fb_id = (!empty($fb_id) || empty($customer->fb_id)) ? $fb_id : $customer->fb_id;
			$customer->fb_access_token = (!empty($fb_access_token) || empty($customer->fb_access_token)) ? $fb_access_token : $customer->fb_access_token;
			$customer->device_token = (!empty($device_token) || empty($customer->device_token)) ? $device_token : $customer->device_token;
			$customer->language = (!empty($language) || empty($customer->language)) ? $language : $customer->language;
			$customer->ip_addr = (!empty($ip_addr) || empty($customer->ip_addr)) ? $ip_addr : $customer->ip_addr;
			if ($findCustomer) {
				$insertResult = $customer->save();
			} else {
				$insertResult = $customer->insert();
			}
		} else {
			$customer = new Customer();
			$customer->company_id = $apiCheck->company_id;
			$customer->name = $name;
			$customer->email = $email;
			$customer->fb_id = $fb_id;
			$customer->fb_access_token = $fb_access_token;
			$customer->device_id = $device_id;
			$customer->device_token = $device_token;
			$customer->language = $language;
			$customer->ip_addr = $ip_addr;
			$insertResult = $customer->insert();
		}
		
		/*
		api key järgi company seos.
		
		Kui device id ja device token tabelis olemas, siis annab lihtsalt hashi vastu, muul juhul sisestab.
		esimene kontroll - fb id ja fb token, Kui fb id ja token olemas, siis fb-ga tehtud.
		teine kontroll - device id
		
		$customer->company_id = $apiCheck->company_id;
		//$customer->hash	- beforeSave
		$customer->name - facebookist? 
		$customer->email - facebookist?
		$customer->fb_id = 
		$customer->fb_access_token = 
		$customer->device_id = 
		$customer->device_token = 
		$customer->language = 
		$customer->ip_addr = 
		//$customer->created_at - beforeSave
		---
		$model->company_id	= (!empty($_POST['company_id'])) ? $_POST['company_id'] : '';
		$model->hash		= mb_substr(sha1(rand(1, PHP_INT_MAX) . uniqid()), 0, 10);
		$model->device_token= (!empty($_POST['device_token'])) ? $_POST['device_token'] : '';
		//$model->language	= (!empty($_POST['language'])) ? $_POST['language'] : '';
		//$model->ip_addr		= (!empty($_POST['ip_addr'])) ? $_POST['ip_addr'] : '';
		$model->fb_id		= (!empty($_POST['fb_id'])) ? $_POST['fb_id'] : '';
		$model->fb_access_token=(!empty($_POST['fb_access_token'])) ? $_POST['fb_access_token'] : '';
		$model->name		= (!empty($_POST['name'])) ? $_POST['name'] : '';
		$model->email		= (!empty($_POST['email'])) ? $_POST['email'] : '';
		
		$insertResult = $customer->insert();
		// vb vaja insertResulti kasutada hoopis hashi saamiseks? */
		if ($insertResult) {
			$return = array(
				'customer_hash' => $customer->hash,
				'status'		=> 1,
				'cards_unanswered'	=> 2
			);
			$this->_sendResponse(201, $return);
		} else {
			$this->_sendResponse(500);
		}
	}
	
	public function actionDevice()
	{
		$this->_checkAuth();
		
		if (!empty($_POST['device_token'])) {
			// do insert $return
			$customerHash = str_replace('CUSTOMER ', '', $_SERVER['HTTP_AUTHORIZATION']);
			$model = new Customer();
			$modelQuery = $model->find()->where('hash=:hash', array(':hash' => $customerHash))->one();
			$modelQuery->device_token = $_POST['device_token'];
			$queryReturn = $modelQuery->save();
			
			if ($queryReturn) {
				$this->_sendResponse(201, array('success' => 1));
			} else {
				$this->_sendResponse(500);
			}
		} else {
			$this->_sendResponse(500);
		}
	}
}
