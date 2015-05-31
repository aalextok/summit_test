<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $watched_user_id
*/

class Watching extends ActiveRecord{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'watching';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'watched_user_id'], 'required'],
            [['user_id', 'watched_user_id'], 'integer'],
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
            'watched_user_id' => 'Watched User ID',
        ];
    }
}
