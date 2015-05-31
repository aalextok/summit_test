<?php
// Check this namespace:
namespace app\api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "card".
 *
 * @property integer $id
 * @property string $name
 * @property integer $company_id
 * @property string $access_token
 * @property integer $status
 * 
 * @property Company $company
 */
class Api extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_access';
    }
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','company_id','access_token','status'], 'required'],
            [['company_id', 'status'], 'integer'],
            [['name', 'access_token'], 'string', 'max' => 255]
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'			=> 'ID',
			'name'			=> 'Name',
            'company_id'	=> 'Company',
            'access_token'	=> 'Access Token',
			'status'		=> 'Status'
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
