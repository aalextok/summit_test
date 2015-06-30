<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property string $location
 * @property string $model
 * @property integer $model_id
 * @property integer $user_id
 */
class Image extends \yii\db\ActiveRecord
{
    public $image;
    public $resolutions;
    
    public static $masterEntities = [
        'User',
        'Participation',
        'Visit',
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }
    
    public function formName() {
        //parent::formName();
        return '';
    }
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['name', 'description'];
        
        return $scenarios;
    }
    
    public function fields() {
        $fields = parent::fields();
        $fields[] = 'resolutions';
        
        return $fields;
    }

        public function extraFields() {
        $extraFields = parent::extraFields();
        $extraFields[] = 'master';
        $extraFields[] = 'user';
        
        return $extraFields;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['location'], 'required'],
            [['model'], 'string'],
            [['model_id', 'user_id'], 'integer'],
            [['location'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            
            [['image'], 'safe'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location' => 'Location',
            'model' => 'Model',
            'model_id' => 'Model ID',
            'user_id' => 'User ID',
        ];
    }
    
    public function getMaster(){
        $class = $this->getNamespace().$this->model;
        
        return $this->hasOne($class, [
            'id' => 'model_id'
        ]);
    }
    
    public function getUser(){       
        return $this->hasOne(\common\models\User::className(), [
            'id' => 'user_id'
        ]);
    }
    
    public function getNamespace(){
        return $this->model == 'User' ? 'common\models\\' : 'backend\models\\';
    }
    
    public function masterBelongsTo($user){
        $master = $this->master;
        
        if(($master->model == 'User' && $master->id != $user->id) || ($master->model != 'User' && $master->user_id != $user->id)){
            return false;
        }
        
        return true;
    }
    
    public function afterFind() {
        parent::afterFind();
        
        $this->resolutions = $this->getResolutions();
    }

    public function getResolutions(){
        $resolutions = [];
        
        foreach(Yii::$app->params['imgResolutions'] as $rKey => $resolution){
            $path = pathinfo($this->location, PATHINFO_DIRNAME).'/'.pathinfo($this->location, PATHINFO_FILENAME).'_'.$rKey.'.'.pathinfo($this->location, PATHINFO_EXTENSION);
            
            if(is_file($path)){
                $resolutions[] = $path;
            }
        }
        
        return $resolutions;
    }
    
    public function saveResolutions(){
        $img = Yii::$app->image->load($this->location);
        
        $dir = pathinfo($this->location, PATHINFO_DIRNAME);
        $name = pathinfo($this->location, PATHINFO_FILENAME);
        $ext = pathinfo($this->location, PATHINFO_EXTENSION);
        
        foreach(Yii::$app->params['imgResolutions'] as $rKey => $resolution){
            $img->resize($resolution['width'], $resolution['height'], \yii\image\drivers\Image::PRECISE)
                ->crop($resolution['width'], $resolution['height'])
                ->save("$dir/{$name}_$rKey.$ext");
        }
    }
    
    public function deleteResolutions(){
        $resolutions = $this->getResolutions();
        foreach ($resolutions as $r){
            try {
                unlink($r);

            }catch (yii\base\ErrorException $e){}
        }
    }
}
