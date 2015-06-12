<?php
namespace frontend\controllers;

class BaseController extends \yii\web\Controller
{
  public $layoutClasses = array(
      "body" => array(),
      "content" => array(),
  );
  
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
  
}
