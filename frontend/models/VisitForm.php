<?php

namespace frontend\models;

use backend\models\Visit;

use Yii;
use yii\base\Model;
use backend\models\Place;
use common\models\User;

/**
 * CheckinForm is the model behind the "checkin" form.
 */
class VisitForm extends Model
{
    public $code;
    public $foundPlace;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // code is required
            [['code'], 'required'],
            ['code', 'validateCode']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
        ];
    }

    /**
     * TODO: Currenlty competition not saved
     *
     * @return boolean
     */
    public function saveVisit()
    {
      $userId = User::getCurrentUserId();
      $visit = new Visit();
      
      if($this->foundPlace){
        $visit->user_id = $userId;
        $visit->place_id = $this->foundPlace->id;
        //$visit->competition_id = 0;
        $visit->visit_time = time();
        $visit->acivity_id = 1;//TODO: where this comes from?
      }
      
      return $visit->save();
    }
    
    /**
     * check if the code insterted relly exists (place or competition)
     * and user can currently visit that place (is logged etc)
     * 
     * TODO: is not finished yet
     * 
     * @param array $attribute
     * @param array $params
     * @return boolean
     */
    public function validateCode( $attribute, $params )
    {
      $code = $this->$attribute;
      
      if (Yii::$app->user->isGuest) {
        return $this->addError($attribute, 'You must be logged in to add visit.');;
      }
      
      $place = Place::find()
        ->where(['code' => $code])
        ->one();
      
      if($place && $place->code == $code){
        $this->foundPlace = $place;
        return;
      }
      
      $this->addError($attribute, 'Code entered seems to be not match any goals');
    }
}
