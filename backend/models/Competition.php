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
            'achievements_by_places' => 'Achievements By Places',
            'activity_id' => 'Activity ID',
        ];
    }
}
