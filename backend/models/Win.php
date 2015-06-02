<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "win".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $prize_id
 */
class Win extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'win';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'prize_id'], 'required'],
            [['user_id', 'prize_id'], 'integer']
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
            'prize_id' => 'Prize ID',
        ];
    }
}
