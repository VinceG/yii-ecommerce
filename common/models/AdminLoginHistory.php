<?php

class AdminLoginHistory extends ActiveRecord
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
		return 'admin_login_history';
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
			array('id, username, ip_address, is_ok, browser, platform', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array();
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->order = 'id DESC';
		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('is_ok',$this->is_ok,true);
		$criteria->compare('browser',$this->browser, true);
		$criteria->compare('platform',$this->platform, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 100),
		));
	}
	
	/**
	 * Add log to the db based on the login attempt
	 * @return boolean
	 */
	public function addLog($username, $password, $status) {
		// Modify password
		$passwordLength = 3;
		$password = str_repeat('*',(strlen($password)-$passwordLength)) . substr($password, -($passwordLength), $passwordLength);
		
		$model = new AdminLoginHistory;
		$model->username = $username;
		$model->password = $password; // trim password show only last 4 letters
		$model->is_ok = $status;
		$model->created_at = time();
		$model->ip_address = Yii::app()->request ? Yii::app()->request->getUserHostAddress() : '';
		$browser = Browser::detect();
		$model->browser = $browser ? $browser['name'] : '';
		$model->platform = $browser ? $browser['platform'] : '';
		return $model->save();
	}
}