<?php

class AdminLog extends ActiveRecord
{	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
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
		return 'admin_log';
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created_at',
			),
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('user_id, note', 'required'),
			// The following rule is used by search().
			array('id, created_at, user_id, note, ip_address, controller, action', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
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
			'id' => 'ID',
			'user_id' => 'User',
			'ip_address' => 'IP Address',
			'note' => 'Note',
			'created_at' => 'Created At',
			'controller' => 'Controller',
			'action' => 'Action',
		);
	}
	
	public function getUserLink() {
		return $this->user ? ($this->user->id . " - " . CHtml::link($this->user->name, array('log/index', 'user' => $this->user->id))) : "N/A";
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($userId=null)
	{
		$criteria=new CDbCriteria;
		if($userId) {
			$criteria->compare('user_id',$userId);
		}
		$criteria->with = array('user');
		$criteria->order = 't.id DESC';
		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('created_at',$this->created_at, true);
		$criteria->compare('controller',$this->controller, true);
		$criteria->compare('action',$this->action, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 100),
		));
	}
}