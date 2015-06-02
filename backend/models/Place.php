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
 */
class Place extends \yii\db\ActiveRecord
{
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
            [['description'], 'string'],
            [['meters_above_sea_level', 'distance', 'points'], 'integer'],
            [['latitude', 'longtitude'], 'number'],
            [['code', 'name'], 'string', 'max' => 255]
        ];
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
        ];
    }
}
