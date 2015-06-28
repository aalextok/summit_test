<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rank".
 *
 * @property integer $id
 * @property string $rank
 * @property integer $points
 */
class Rank extends \yii\db\ActiveRecord
{
    public $model = 'Rank';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rank';
    }
    
    public function fields() {
        $fields = parent::fields();
        $fields[] = 'image';
        
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rank', 'points'], 'required'],
            [['points'], 'integer'],
            [['rank'], 'string', 'max' => 255],
            [['rank'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rank' => 'Rank',
            'points' => 'Points',
        ];
    }
    
    public function getImage(){
        return $this->hasOne(Image::className(), [
            'model' => 'model',
            'model_id' => 'id'
        ]);
    }
}
