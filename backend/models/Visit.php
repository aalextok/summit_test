<?php

namespace backend\models;

use Yii;

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
}
