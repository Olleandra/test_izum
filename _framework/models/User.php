<?php

namespace app\models;
use yii\helpers\ArrayHelper;
use app\models\User;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $status
 * @property string $login
 * @property string $name
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $role
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_visit
 *
 * @property Profile[] $profiles
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    const ROLE_ROOT = 'root';
    const ROLE_USER = 'user';

    public $password;
    public $password_repeat;
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // trim
            [['login', 'name', 'email', 'password'], 'trim'],

            // required
            [['login', 'name', 'email', 'role', 'status'], 'required'],

            // login
            ['login', 'string', 'min' => 3],
            ['login', 'string', 'max' => 60],

            // name
            ['name', 'string', 'max' => 100],

            // email
            ['email', 'email'],
            ['email', 'string', 'max' => 60],

            // password
            [['password', 'password_repeat'], 'string', 'min' => 3],
            [['password', 'password_repeat'], 'string', 'max' => 20],
            [['password', 'password_repeat'], 'required', 'on' => 'insert'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],

            // status
            ['status', 'boolean'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            // role
            ['role', 'string', 'max' => 20],
            ['role', 'in', 'range' => [self::ROLE_ROOT, self::ROLE_USER]],
        ];
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'name' => 'Имя пользователя',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
            'role' => 'Роль',
            'status' => 'Статус',
            'last_visit' => 'Последний вход',
            'created_at' => 'Добавлен',
            'updated_at' => 'Изменён',
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
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($login)
    {
        return static::findOne(['login' => $login, 'status' => self::STATUS_ACTIVE]);
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
        return \Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    public static function getRoleName($role=null)
    {
        if ($role !== null) {
            $roles = self::getRoleList();
            return isset($roles[$role]) ? $roles[$role] : null;
        }
        return null;
    }

    public static function getStatusName($status=null)
    {
        if ($status !== null) {
            $statuses = self::getStatusList();
            return isset($statuses[$status]) ? $statuses[$status] : null;
        }
        return null;
    }

    public static function getStatusList()
    {
        return [
                   self::STATUS_ACTIVE  => 'Активный',
                   self::STATUS_DELETED => 'Заблокирован',
               ];
    }

    public static function getRoleList()
    {
        return [
                   self::ROLE_USER => 'Пользователь',
                   self::ROLE_ROOT => 'Администратор',
               ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert)
                $this->generateAuthKey();
            if ($this->password)
                $this->setPassword($this->password);
            return true;
        } else {
            return false;
        }
    }

    public static function handleAfterLogin($event)
    {
        $event->identity->touch('last_visit');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
	
    public static function getUnParentList()
    {
		$parent_all = User::find()->all();
		$parent = ArrayHelper::map($parent_all, 'name', 'name');
        return $parent;
    }
	
    public static function getParentList()
    {
		$parent_all = User::find()->all();
		$parent = ArrayHelper::map($parent_all, 'id', 'name');
        return $parent;
    }
}
