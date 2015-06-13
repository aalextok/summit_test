<?php
namespace frontend\widgets;

use Yii\Helpers\Html;

class SummitAuthChoice extends \yii\authclient\widgets\AuthChoice {
  
  
  
  /**
   * Renders the main content, which includes all external services links.
   */
  protected function renderMainContent()
  {
    
    foreach ($this->getClients() as $externalService) {
      $this->clientLink($externalService, "Log in with facebook", ["class" => "btn btn-block btn-facebook"]);
    }
  }
  
}
