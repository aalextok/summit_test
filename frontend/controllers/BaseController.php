<?php
namespace frontend\controllers;

use yii;
use yii\helpers\Url;
use common\models\User;

class BaseController extends \yii\web\Controller
{
  public $layoutClasses = array(
      "body" => array(),
      "content" => array(),
  );
  
  public $layout = "main";
  public $pageTitle = "";
  
  public function __construct($d, $d2){
    parent::__construct($d, $d2);
  }
  
  public function beforeAction($action) {
    
    if (
        Yii::$app->controller->module->requestedRoute == 'site/index' || 
        Yii::$app->controller->module->requestedRoute == 'site/signup' || 
        Yii::$app->controller->module->requestedRoute == ''
    ) {
        $this->layout = "landing";
    }
    return parent::beforeAction($action);
  }
  
  public function getPageTitle(){
    if(!$this->pageTitle){
      return Yii::$app->params['appName'];
    }
    return $this->pageTitle;
  }
  
  public function setPageTitle( $title ){
    $this->pageTitle = Yii::$app->params['appName'] . " - ". $title ;
  }
  
  /**
   * Allow adding addional style classes
   * to layout body and content wrappers
   * 
   * @param string $type
   * @param string $class
   * @return void
   */
  public function addLayoutClass( $type, $class )
  {
    $this->layoutClasses[$type][$class] = $class;
  }
  
  /**
   * Create addional classes string for layout
   * 
   * @param string $type
   * @param string $defaultClasses
   * @return string
   */
  public function getClasses( $type, $defaultClasses )
  {
    $classes = "";
    foreach($this->layoutClasses[$type] as $k => $class) {
      $classes .= " " . $class;
    }
    
    if(!$classes) {
      return $defaultClasses;
    }
    
    return $classes;
  }
  
  /**
   * Create current user's display name for user based on his data
   * 
   * @return string
   */
  public function getCurrentUserDisplayName( )
  {
    if (Yii::$app->user->isGuest) {
      return "Not logged";
    }
    
    return User::getUserDisplayName( Yii::$app->user->identity );
  }
  
  /**
   * This method is used in layout view when using Menu::widget to print out menu. 
   * Some active states were not dedected so these are checked here
   * 
   * for example - "Url::to(['profile/index', 'id' => $userId])" was not
   * getting active class trough original logic. 
   * 
   * TODO: Probably problem with my url's and this hack should be removed
   * 
   * @param string $context
   * @return boolean
   */
  public function stoMenuItemActive( $context )
  {
    $route = Yii::$app->controller->module->requestedRoute;
    
    if( $context == 'my-profile' ){
      
      if (Yii::$app->user->isGuest) {
        return false;
      }
      
      $userId = User::getCurrentUserId();
      
      if( $route == 'profile/edit' ){
        return true;
      }
      if( $route == 'profile/index' ){
        $profileId = Yii::$app->request->get('id');
        if($profileId == $userId){
          return true;
        }
      }
    }
    
    if( $context == 'my-settings' ){
      if( $route == 'profile/settings' ){
        return true;
      }
    }
    
    if( $context == 'about' ){
      if( $route == 'site/about' ){
        return true;
      }
    }
    
    return false;
  }
  
  /**
   * Return facebook app ID
   * 
   * @return string
   */
  public function getFacebookAppId( )
  {
    if( isset( Yii::$app->components['authClientCollection']['clients']['facebook']['clientId'] ) ) {
      return Yii::$app->components['authClientCollection']['clients']['facebook']['clientId'];
    }
    
    return "";
  }
  
  /**
   * Return API base uri
   * 
   * TODO: make this better, trough url manager/config?
   * 
   * @return string
   */
  public function getApiBaseUri( )
  {
    return Url::base() . "/../../backend/web";
    //return "http://demo.bind.ee/summittosea/backend/web";
  }
  
}
