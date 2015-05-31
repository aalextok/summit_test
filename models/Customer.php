<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $hash
 * @property string $name
 * @property string $email
 * @property string $fb_id
 * @property string $fb_access_token
 * @property string $device_token
 * @property string $last_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Company $company
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id'], 'required'],
            [['company_id'], 'integer'],
            [['last_active', 'created_at', 'updated_at'], 'safe'],
            [['hash', 'name', 'email', 'fb_id', 'fb_access_token', 'device_id', 'device_token', 'language', 'ip_addr'], 'string', 'max' => 255]
        ];
    }

    public function beforeSave($insert)
    {
        // If no created_at specified set as current timestamp
        if(!$this->created_at){
            $this->created_at = date('Y-m-d H:i:s');
        }
		
		if (!$this->hash) {
			do
			{
				$this->hash = mb_substr(sha1(rand(1, PHP_INT_MAX) . uniqid()), 0, 32);
			}
			while (is_object(self::findByHash($this->hash)));
		}

        $this->updated_at = date('Y-m-d H:i:s');
        
        return true;
    }
	
	public static function findByHash($sHash)
	{
		return self::find()->where('hash=:hash', array('hash' => $sHash))->one();
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'hash' => 'Hash',
            'name' => 'Name',
            'email' => 'Email',
            'fb_id' => 'Fb ID',
            'fb_access_token' => 'Fb Access Token',
			'device_id' => 'Device ID',
            'device_token' => 'Device Token',
			'language' => 'Language',
			'ip_addr' => 'IP Address',
            'last_active' => 'Last Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getCardData() 
    { 
        return $this->hasMany(CardData::className(), ['customer_id' => 'id']);
    } 
}
