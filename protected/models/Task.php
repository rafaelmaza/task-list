<?php

/**
 * This is the model class for table "task".
 *
 * The followings are the available columns in table 'task':
 * @property integer $task_id
 * @property string $description
 * @property integer $priority
 * @property string $due_date
 * @property integer $completed
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Task extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Task the static model class
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
		return 'task';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description, user_id', 'required'),
			array('priority, completed, user_id', 'numerical', 'integerOnly'=>true),
			array('due_date', 'safe'),
			array('user_id', 'existing_user'),
			
			//Default due date: D+1
			array('due_date', 'default', 'value'=>date('Y-m-d', time()+86400), 'setOnEmpty'=>true),
			array('priority', 'default', 'value'=>1, 'setOnEmpty'=>true),
			array('completed', 'default', 'value'=>0, 'setOnEmpty'=>true),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('task_id, description, priority, due_date, completed, user_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'task_id' => 'Task',
			'description' => 'Description',
			'priority' => 'Priority',
			'due_date' => 'Due Date',
			'completed' => 'Completed',
			'user_id' => 'User',
		);
	}
	
	public function existing_user($attribute,$params) 
	{
		if(is_null(User::model()->findByPk($this->user_id))) {
			$this->addError($attribute,Yii::t('tasklist', 'The specified user does not exist.'));
			return false;
		}
		
		return true;
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

		$criteria->compare('task_id',$this->task_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('completed',$this->completed);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getPriority_List() {
		return array(
			'1'=>'Low',
			'2'=>'Medium',
			'3'=>'High',
		);
	}
}