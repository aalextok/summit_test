<?php
namespace frontend\controllers;

use yii;

class BaseController extends \yii\web\Controller
{
  public $layoutClasses = array(
      "body" => array(),
      "content" => array(),
  );
  
  public $layout = "main";
  
  public function __construct($d, $d2){
    parent::__construct($d, $d2);
  }
  
  public function beforeAction($action) {
    
    if (
        Yii::$app->controller->module->requestedRoute == 'site/index' || 
        Yii::$app->controller->module->requestedRoute == ''
    ) {
        $this->layout = "landing";
    }
    return parent::beforeAction($action);
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
   * Create display name for user based on his data
   * 
   * @return string
   */
  public function getUserDisplayName( )
  {
    if (Yii::$app->user->isGuest) {
      return "Not logged";
    }
    
    $name = Yii::$app->user->identity->username;
    
    $firstname = Yii::$app->user->identity->firstname;
    $lastname = Yii::$app->user->identity->lastname;
    
    if($firstname && $lastname){
      $name = $firstname . " " . $lastname;
    }
    
    return $name;
  }
  
}
