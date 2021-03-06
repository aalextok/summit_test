<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "place".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property integer $meters_above_sea_level
 * @property integer $distance
 * @property integer $points
 * @property double $latitude
 * @property double $longtitude
 * @property string $address
 * @property string $location
 * @property array $activities
 */
class Place extends \yii\db\ActiveRecord
{
    public $activity_ids;
    public $img;
    
    public $model = 'Place';
    
    public static $countableFields = [
        'points',
        'meters_above_sea_level',
        'distance'
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'address'], 'string'],
            [['meters_above_sea_level', 'distance', 'points'], 'integer'],
            [['latitude', 'longtitude'], 'number'],
            [['code', 'name', 'location'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['activity_ids'], 'safe']
        ];
    }
    
    public function fields() {
        $fields = parent::fields();
        $fields[] = 'activities';
        $fields[] = 'images';
        
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
            'meters_above_sea_level' => 'Meters Above Sea Level',
            'distance' => 'Distance',
            'points' => 'Points',
            'latitude' => 'Latitude',
            'longtitude' => 'Longtitude',
            'address' => 'Full address',
            'activities' => 'Activities',
        ];
    }
    
    public function getActivities() {
        return $this->hasMany(Activity::className(), ['id' => 'activity_id'])
                ->viaTable('places_activities', ['place_id' => 'id']);
    }
    
    public function getImages(){
        return $this->hasMany(Image::className(), [
            'model' => 'model',
            'model_id' => 'id'
        ]);
    }
    
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        
        if(!empty(Yii::$app->request->post('Place')['activity_ids'])){
            $activityIds = array_values(Yii::$app->request->post('Place')['activity_ids']);
            
            $oldActivities = $this->activities;
            $newActivities = Activity::find()->where(['id' => $activityIds])->all();
            
            $activitiesToLink = array_udiff($newActivities, $oldActivities, function($aOld, $aNew){
                return $aOld->id - $aNew->id;
            });
            
            foreach ($activitiesToLink as $activity){
                $this->link('activities', $activity);
            }
            
            $activitiesToUnlink = array_udiff($oldActivities, $newActivities, function($aOld, $aNew){
                return $aOld->id - $aNew->id;
            });
            
            foreach ($activitiesToUnlink as $activity){
                $this->unlink('activities', $activity);
            }
        }
    }
    
    public function getActivitiesNames(){
        $names = '';
        foreach($this->activities as $activity){
            $names .= $activity->name.', ';
        }
        return rtrim($names, ', ');
    }
}
