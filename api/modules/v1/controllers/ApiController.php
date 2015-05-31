<?php

namespace app\api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use app\api\modules\v1\models\Api;
use app\models\Customer;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;
	
	public function _checkAuth($onlyKey = false)
	{
		if (!$onlyKey) {
			if(!isset($_SERVER['HTTP_AUTHORIZATION'])) {
				// Error: Unauthorized
				$this->_sendResponse(401);
			}
			
			$customerHash = str_replace('CUSTOMER ', '', $_SERVER['HTTP_AUTHORIZATION']);
			
			$customer = new Customer();
			$checkCustomer = $customer->find()->where('hash=:hash', array(':hash' => $customerHash))->one();
			
			if (empty($checkCustomer)) {
				// Error: Unauthorized
				$this->_sendResponse(401);
			}
			
			if (!isset($_SERVER['HTTP_X_BOARD_APPID'])) {
				// Error: Unauthorized
				$this->_sendResponse(401);
			}
		}
		
		$apiKey = $_SERVER['HTTP_X_BOARD_APPID'];
		
		$api = new Api();
		
		if (!$onlyKey) {
			$checkKey = $api->find()->where('access_token=:api_key AND company_id=:company', array(':api_key' => $apiKey, ':company' => $checkCustomer->company_id))->one();
		} else {
			$checkKey = $api->find()->where('access_token=:api_key', array(':api_key' => $apiKey))->one();
		}
		
		if (empty($checkKey) || $checkKey->status == 0) {
			// Error: Unauthorized
			$this->_sendResponse(401);
		}
		
		if ($onlyKey) {
			return $checkKey;
		}
	}
	
	public function _sendResponse($status = 200, $body = array(), $content_type = 'application/json')
	{
		$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
		
		header($status_header);
		header('Content-type: ' . $content_type . '; charset=utf-8');
        
        $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
        
		if($status == 200 || $status == 201)
		{
			$aResponse = array(
				'success' => true,
				'error' => ''
			);
			if ($body != '') {
				$aResponse['data'] = $body;
			}
		}
		else
		{
			$message = '';

			switch($status)
			{
				case 401:
					$message = 'You must be authorized to view this page.';
					break;
				case 404:
					$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
					break;
				case 500:
					$message = 'The server encountered an error processing your request.';
					break;
				case 501:
					$message = 'The requested method is not implemented.';
					break;
			}

			$aResponse = array(
				'status' => false,
				'data' => (object) array(),
				'error' => $message
			);
			
		}
		echo json_encode($aResponse);
        die();
        //Yii::app()->end(true, json_encode($aResponse));
	}
	
	public function _getStatusCodeMessage($status)
	{
        $codes = Array(  
            100 => 'Continue',  
            101 => 'Switching Protocols',  
            200 => 'OK',  
            201 => 'Created',  
            202 => 'Accepted',  
            203 => 'Non-Authoritative Information',  
            204 => 'No Content',  
            205 => 'Reset Content',  
            206 => 'Partial Content',  
            300 => 'Multiple Choices',  
            301 => 'Moved Permanently',  
            302 => 'Found',  
            303 => 'See Other',  
            304 => 'Not Modified',  
            305 => 'Use Proxy',  
            306 => '(Unused)',  
            307 => 'Temporary Redirect',  
            400 => 'Bad Request',  
            401 => 'Unauthorized',  
            402 => 'Payment Required',  
            403 => 'Forbidden',  
            404 => 'Not Found',  
            405 => 'Method Not Allowed',  
            406 => 'Not Acceptable',  
            407 => 'Proxy Authentication Required',  
            408 => 'Request Timeout',  
            409 => 'Conflict',  
            410 => 'Gone',  
            411 => 'Length Required',  
            412 => 'Precondition Failed',  
            413 => 'Request Entity Too Large',  
            414 => 'Request-URI Too Long',  
            415 => 'Unsupported Media Type',  
            416 => 'Requested Range Not Satisfiable',  
            417 => 'Expectation Failed',  
            500 => 'Internal Server Error',  
            501 => 'Not Implemented',  
            502 => 'Bad Gateway',  
            503 => 'Service Unavailable',  
            504 => 'Gateway Timeout',  
            505 => 'HTTP Version Not Supported'  
        );  
  
        return (isset($codes[$status])) ? $codes[$status] : '';
	}
}
