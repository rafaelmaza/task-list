<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $user_id
 * @property string $username
 * @property string $password
 * @property string $created
 * @property string $last_login
 *
 * The followings are the available model relations:
 * @property Task[] $tasks
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password', 'required'),
			array('username', 'length', 'max'=>14),
			array('password', 'length', 'min'=>6),
			
			array('created', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>true),
			array('last_login', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>true),
			
			array('username', 'unique'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, username, password, created, last_login', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tasks' => array(self::HAS_MANY, 'Task', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'username' => 'Username',
			'password' => 'Password',
			'created' => 'Created',
			'last_login' => 'Last Login',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('last_login',$this->last_login,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeSave() {
		if($this->getScenario() == 'create')
			$this->password=md5($this->password);
		else if($this->getScenario() == 'update')
			unset($this->password);
		return parent::beforeSave();
	}
	
	public function afterSave() {
		if($this->getScenario() == 'create')
			unset($this->password);
		return parent::afterSave();
	}
}