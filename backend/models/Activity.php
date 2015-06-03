<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "activity".
 *
 * @property integer $id
 * @property string $name
 * @property string $verb
 * @property string $description
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['name', 'verb'], 'string', 'max' => 255]
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
            'verb' => 'Verb',
            'description' => 'Description',
        ];
    }
}
