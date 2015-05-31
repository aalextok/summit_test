<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_user".
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $user_id
 * @property string $permissions
 * @property integer $is_deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 * @property Company $company
 */
class CompanyUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'user_id'], 'required'],
            [['company_id', 'user_id', 'is_deleted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['permissions'], 'string', 'max' => 255]
        ];
    }

    public function beforeSave($insert)
    {
        // If no created_at specified set as current timestamp
        if(!$this->created_at){
            $this->created_at = date('Y-m-d H:i:s');
        }

        $this->updated_at = date('Y-m-d H:i:s');
        
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'user_id' => 'User ID',
            'permissions' => 'Permissions',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
