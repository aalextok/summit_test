<?php

namespace backend\models;

use Yii;

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
}
