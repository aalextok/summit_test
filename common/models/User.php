<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Query;
use yii\helpers\Url;

use backend\models\Rank;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $role
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login
 * @property string $device_token
 * @property string $platform
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 10;
    const ROLE_ADMIN = 20;
    
    public static $editableFields = [
        'username',
        'firstname',
        'lastname',
        'gender',
        'phone',
    ];
    
    public $rank_id;
    public $rank_model = 'Rank';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function fields() {
        //$fields = parent::fields();
        //unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);
        
        $fields = [
            'id',
            'username',
            'firstname',
            'lastname',
            'gender',
            'email',
            'phone',
            'points',
            'summits',
            'meters_above_sea_level',
            'rank',
            'rank_image',
            'last_login',
            'image',
            'image_hash',
            'facebook_id'
        ];
        
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN]],
            
            ['email', 'email'],
            [['firstname', 'lastname', 'email'], 'required'],
            [['username', 'email', 'firstname', 'lastname'], 'string', 'min' => 1, 'max' => 255],
            [['email', 'username'], 'unique'],
            ['image', 'file', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'maxSize' => 1024*1024*1024],
            ['gender', 'in', 'range' => ['MALE', 'FEMALE', 'OTHER', '']]
            
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);
        if(isset($user) && $user->auth_key_expires < time()){
            return null;
        }
        else
            return $user;
        
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    public static function findIdentityByEmail($email, $activeOnly = true){
        if($activeOnly)
            return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
        else
            return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        if(empty($username)){
            return null;
        }
        
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }
    
    /**
     * Return currently logged in user ID
     *
     * @return integer|null
     */
    public static function getCurrentUserId()
    {
      if (Yii::$app->user->isGuest) {
        return null;
      }
      
      return Yii::$app->user->identity->id;
    }
    
    /**
     * Login into web using auth token
     *
     * @return boolean
     */
    public static function loginUserByAuthToken( $token )
    {
      $rememberMe = true;
      
      $user = self::findIdentityByAccessToken( $token );
      if($user){
        return Yii::$app->user->login($user, $rememberMe ? 0 : (3600 * 24 * 30) );
      }
      
      return false;
    }
  
    /**
     * Create user's display name based on his data
     * 
     * @param object|integer
     * @return string
     */
    public static function getUserDisplayName( $user )
    {
      if(!is_object($user)){
        $user = self::findIdentity( $user );
        if(!$user){
          return "anonymous";
        }
      }
      
      $name = $user->username;
      
      $firstname = $user->firstname;
      $lastname = $user->lastname;
      
      if($firstname && $lastname){
        $name = $firstname . " " . $lastname;
      }
      
      return $name;
    }
    
    /**
     * Get user's friend count
     *
     * @param integer
     * @return integer
     */
    public static function getUserFriendCount( $userId )
    {
      $cnt = (new yii\db\Query())
        ->from('watching')
        ->where( 'user_id = ' . $userId )
        ->count();
      
      return $cnt;
    }
    
    /**
     * Does given user ($userId) follow other user ($followingUserId)
     * If follows, watching ID is returned. So if greater than 0 
     * is returned user is following other user
     * 
     * user_id - user who is following
     * watched_user_id - user being watched/followed
     * 
     * @param integer $userId
     * @param integer $watchedUserId
     * @return integer
     */
    public static function getUserFollowingId( $userId, $watchedUserId )
    {
      if(!$watchedUserId || !$userId){
        return 0;
      }
      
      $item = (new yii\db\Query())
        ->from('watching')
        ->where( 'user_id = ' . $userId . ' AND watched_user_id = ' . $watchedUserId )
        ->one();
      
      if( isset($item['id']) ){
        return $item['id'];
      }
      
      return 0;
    }
    
    /**
     * Get user's rank title/name
     * TODO: this menthod is just a mock- no real rank yet, yet to be implemented
     *
     * @param object|integer
     * @return string
     */
    public static function getUserRankDisplay( $user )
    {
      
      if(!is_object($user)){
        $user = self::findIdentity( $user );
        if(!$user){
          return "";
        }
      }
      
      //fakse something right now
      if($user->rank == 1){
        return "Beginner";
      }
      
      if($user->rank == 2){
        return "King of the hill";
      }
      
      return "Rookie";
    }
    
    /**
     * Get user avatar profile photo object or uri
     *
     * TODO: finish this after profile photo upload done
     *
     * @param $user object|integer
     * @param $onlyUri object|boolean
     * @return object|string
     */
    public static function getUserPhoto( $user, $onlyUri = false )
    {
    
      if(!is_object($user)){
        $user = self::findIdentity( $user );
        if(!$user){
          return "";
        }
      }
      
      $item = (new yii\db\Query())
        ->from('image')
        ->where( 'model_id = ' . $user->id . ' AND model = "User"' )
        ->one();
      
      if( isset($item['id']) && $onlyUri){
        return Url::base() . '/../../backend/web/' .  $item['location'];
      }
      if( isset($item['id']) && !$onlyUri){
        return $item;
      }
    
      return Url::base() . '/img/default-avatar-man.jpg';
    }
    
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public static function errorsToString($errors){
        $errorsStr = '';
        foreach($errors as $fieldName => $fieldErrors){
            foreach ($fieldErrors as $errorMessage){
                $errorsStr .= $errorMessage.' ';
            }
        }
        
        return trim($errorsStr);
    }
    
    public function upgradeRank($save = false){
        $this->points = isset($this->points) ? $this->points : 0;
        
        $rank = Rank::find()->where(['<=', 'points', $this->points])->orderBy('points DESC')->one();
        
        if(!$rank){
          return;
        }
        
        if($rank->rank != $this->rank){
            $this->rank = $rank->rank;
            if($save){
                $this->save();
            }
        }
    }
    
    public function getRank_image(){
        $rank = Rank::find()->where(['rank' => $this->rank])->one();
        if(!empty($rank)){
            $this->rank_id = $rank->id;

            return $this->hasOne(\backend\models\Image::className(), [
                'model' => 'rank_model',
                'model_id' => 'rank_id'
            ]);
        }
        
        return null;
    }
}
