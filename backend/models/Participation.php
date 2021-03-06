<?php

namespace backend\models;

use Yii;

use common\models\User;

/**
 * This is the model class for table "participation".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $competition_id
 * @property integer $places
 * @property integer $summits
 * @property integer $meters_above_sea_level
 * @property integer $distance
 * @property integer $points
 * @property integer $minutes
 * @property integer $start_time
 * @property integer $finish_time
 */
class Participation extends \yii\db\ActiveRecord
{
    public $model = 'Participation';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'participation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'competition_id'], 'required'],
            [['user_id', 'competition_id'], 'unique', 'targetAttribute' => ['user_id', 'competition_id']],
            [['user_id', 'competition_id', 'places', 'summits', 'meters_above_sea_level', 'distance', 'points', 'minutes', 'start_time', 'finish_time'], 'integer']
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
            'competition_id' => 'Competition ID',
            'places' => 'Places',
            'summits' => 'Summits',
            'meters_above_sea_level' => 'Meters Above Sea Level',
            'distance' => 'Distance',
            'points' => 'Points',
            'minutes' => 'Minutes',
            'start_time' => 'Start Time',
            'finish_time' => 'Finish Time',
        ];
    }
    
    public function extraFields() {
        $extraFields = parent::extraFields();
        $extraFields[] = 'images';
        
        return $extraFields;
    }
    
    public function getCompetition(){
        return $this->hasOne(Competition::className(), ['id' => 'competition_id']);
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function getImages(){
        return $this->hasMany(Image::className(), [
            'model' => 'model',
            'model_id' => 'id'
        ]);
    }
    
    public static function getActiveParticipations($userId){
        $activeParticipations = [];
        $participations = Participation::find()
                ->where(['user_id' => $userId])
                ->andWhere(['finish_time' => null])
                ->all();
        
        foreach($participations as $p){
            $p->competition;
            if($p->competition->close_time < time()){
                $p->finish_time = time();
                $p->update();
            }
            else{
                $activeParticipations[] = $p;
            }
        }
        
        return $activeParticipations;
    }
}
