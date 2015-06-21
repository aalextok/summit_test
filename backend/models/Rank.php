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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rank';
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
}
