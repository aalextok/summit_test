<?php

namespace backend\models;

use Yii;

use \common\models\User;

/**
 * This is the model class for table "visit".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $place_id
 * @property integer $competition_id
 * @property integer $visit_time
 * @property integer $activity_id
 */
class Visit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'place_id', 'visit_time'], 'required'],
            [['user_id', 'place_id', 'competition_id', 'visit_time', 'activity_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'place_id' => 'Place ID',
            'competition_id' => 'Competition ID',
            'visit_time' => 'Visit Time',
            'activity_id' => 'Activity ID',
        ];
    }
    
    public function getPlace(){
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }
    
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        
        if($insert){
            // recalculate user stat by place
            $place = Place::find()->where(['id' => $this->place_id])->one();
            $user = User::find()->where(['id' => $this->user_id])->one();
            
            foreach(Place::$countableFields as $field){
                if(isset($place->{$field})){
                    $user->{$field} += $place->{$field};
                }
                
                // 'summits' field
                if($field == 'meters_above_sea_level' && isset($place->{$field})){
                    $user->summits++;
                }
            }
            
            $user->upgradeRank();
            $user->save();
            
            
            if(isset($this->participation_id)){

                $participation = Participation::find()->where(['id' => $this->participation_id])->one();
                
                // recalculate participation stats. by place incl. places
                foreach(Place::$countableFields as $field){
                    if(isset($place->{$field})){
                       $participation->{$field} += $place->{$field};
                    }
                    
                    // 'summits' field
                    if($field == 'meters_above_sea_level' && isset($place->{$field})){
                        $participation->summits++;
                    }
                }
                
                
                //is participation with places 
                $competition = $participation->competition;
                if(!empty($competition->places)){
                    $participation->places++;
                    
                    // Check if all places within competition are visited:                    
                    // - get all places for current participation, 
                    $visits = Visit::find()->where(['participation_id' => $participation->id])->all();
                    $places = [];
                    foreach($visits as $v){
                        $places[] = $v->place;
                    }
                    
                    $unvisitedPlaces = array_udiff($competition->places, $places, function($p1, $p2){
                        return $p1->id - $p2->id;
                    });
                    
                    if(count($unvisitedPlaces) === 0){
                        $participation->finish_time = time();
                        $participation->minutes = (int)(($participation->finish_time - $participation->start_time) / 60);
                    }
                }
                else{ // or with countable values
                    $participation->places++;
                    
                    $countableFields = [
                        'meters_above_sea_level', 
                        'distance', 
                        'points', 
                        'summits'
                    ];
                    $finished = true;
                    foreach($countableFields as $field){
                        if(!empty($competition->{$field})){
                            if(empty($participation->{$field}) || $participation->{$field} < $competition->{$field}){
                                $finished = false;
                                break;
                            }
                        }
                    }
                    
                    if($finished){
                        $participation->finish_time = time();
                        $participation->minutes = (int)(($participation->finish_time - $participation->start_time) / 60);
                        
                        $prize = $competition->prize;
                        if(!empty($prize)){
                            $win = new Win();
                            $win->prize_id = $prize->id;
                            $win->user_id = $user->id;
                            
                            $win->save();
                        }
                    }
                }              
                $participation->save();
            }  
        }
    }
}
