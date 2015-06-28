<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "prize".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $points
 * @property integer $competition_id
 */
class Prize extends \yii\db\ActiveRecord
{
    public $model = 'Prize';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prize';
    }
    
    public function fields() {
        $fields = parent::fields();
        $fields[] = 'images';
        
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['points', 'competition_id'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'points' => 'Points',
            'competition_id' => 'Competition ID',
        ];
    }
    
    public function getCompetition(){
        return $this->hasOne(Competition::className(), ['id' => 'competition_id']);
    }
    
    public function getImages(){
        return $this->hasMany(Image::className(), [
            'model' => 'model',
            'model_id' => 'id'
        ]);
    }
}
