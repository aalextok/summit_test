<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "competition".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property integer $achievements_by_places
 * @property integer $activity_id
 */
class Competition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'competition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'description'], 'required'],
            [['description'], 'string'],
            [['achievements_by_places', 'activity_id'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255]
        ];
    }
    
    public function fields() {
        $fields = parent::fields();
        $fields[] = 'places';
        $fields[] = 'activity';
        
        unset($fields['achievements_by_places']);
        
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
            //'achievements_by_places' => 'Achievements By Places',
            'activity_id' => 'Activity ID',
        ];
    }
    
    public function getPlaces() {
        return $this->hasMany(Place::className(), ['id' => 'place_id'])
                ->viaTable('places_competitions', ['competition_id' => 'id']);
    }
    
    public function getActivity(){
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }
    
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        
        $placesIds = Yii::$app->request->post('places');
        
        if(!empty($placesIds)){
            $oldPlaces = $this->places;
            $newPlaces = Place::find()->where(['id' => $placesIds])->all();
            
            $placesToLink = array_udiff($newPlaces, $oldPlaces, function($pOld, $pNew){
                return $pOld->id - $pNew->id;
            });
            
            foreach ($placesToLink as $place){
                $this->link('places', $place);
            }
            
            $placesToUnlink = array_udiff($oldPlaces, $newPlaces, function($pOld, $pNew){
                return $pOld->id - $pNew->id;
            });
            
            foreach ($placesToUnlink as $place){
                $this->unlink('places', $place);
            }
        }
    }
}
