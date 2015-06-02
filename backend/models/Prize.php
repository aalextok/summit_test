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
 * @property integer $summits
 * @property integer $meters_above_sea_level
 * @property integer $distance
 * @property integer $competition_id
 */
class Prize extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prize';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
            [['points', 'summits', 'meters_above_sea_level', 'distance', 'competition_id'], 'integer'],
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
            'summits' => 'Summits',
            'meters_above_sea_level' => 'Meters Above Sea Level',
            'distance' => 'Distance',
            'competition_id' => 'Competition ID',
        ];
    }
}
