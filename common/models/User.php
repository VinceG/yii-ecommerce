<?php

class User extends ActiveRecord
{
	public $new_password;
	
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
		return 'user';
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created_at',
				'updateAttribute' => 'updated_at',
				'setUpdateOnCreate' => true,
			),
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('email, name, role', 'required'),
			array('email', 'checkEmail'),
			array('name', 'checkName'),
			array('name', 'match', 'allowEmpty'=>false, 'pattern'=>'/[A-Za-z0-9]+$/'),
			
			array('new_password', 'required', 'on' => 'insert'),
			array('new_password', 'length', 'min'=>6),
			
			array('email, name', 'length', 'max'=>255),
			array('name, notes, last_visited', 'safe'),
			
			array('first_name, last_name, birth_date, birthdate, company, contact, home_phone, cell_phone, work_phone, fax, 
			shipping_contact, shipping_address1, shipping_address2, shipping_city, shipping_state, shipping_zip, shipping_country, 
			billing_contact, billing_address1, billing_address2, billing_city, billing_state, billing_zip, billing_country', 'length', 'max' => 100),

			// The following rule is used by search().
			array('
				id, created_at, updated_at, email, name, last_visited
				first_name, last_name, birth_date, birthdate, company, contact, home_phone, cell_phone, work_phone, fax, 
				shipping_contact, shipping_address1, shipping_address2, shipping_city, shipping_state, shipping_zip, shipping_country, 
				billing_contact, billing_address1, billing_address2, billing_city, billing_state, billing_zip, billing_country
			', 'safe', 'on'=>'search'),
		);
	}
	
	public function checkEmail() {
		if($this->isNewRecord) {
			if(User::model()->exists('email=LOWER(:email)', array(':email' => strtolower($this->email)))) {
				$this->addError('email', at('Sorry, That email is already in use.'));
			}
		} else {
			if(User::model()->exists('email=LOWER(:email) AND id!=:id', array(':id' => $this->id, ':email' => strtolower($this->email)))) {
				$this->addError('email', at('Sorry, That email is already in use.'));
			}
		}
	}
	
	public function checkName() {
		if($this->isNewRecord) {
			if(User::model()->exists('name=LOWER(:name)', array(':name' => strtolower($this->name)))) {
				$this->addError('name', at('Sorry, That name is already in use.'));
			}
		} else {
			if(User::model()->exists('name=LOWER(:name) AND id!=:id', array(':id' => $this->id, ':name' => strtolower($this->name)))) {
				$this->addError('name', at('Sorry, That name is already in use.'));
			}
		}
	}

	public function getUserLink() {
		return CHtml::link($this->name, array('user/view', 'id' => $this->id));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'shippingCountry' => array(self::BELONGS_TO, 'Country', 'shipping_country'),
			'shippingState' => array(self::BELONGS_TO, 'USState', 'shipping_state'),
			'billingCountry' => array(self::BELONGS_TO, 'Country', 'billing_country'),
			'billingState' => array(self::BELONGS_TO, 'USState', 'billing_state'),
			'fieldData' => array(self::HAS_MANY, 'UserCustomFieldData', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => at('ID'),
			'name' => at('Name'),
			'email' => at('Email'),
			'notes' => at('Notes'),
			'created_at' => at('Created At'),
			'updated_at' => at('Updated At'),
			'password_hash' => at('Password Hash'),
			'password_reset_token' => at('Password Reset Token'),
			'last_visited' => at('Last Visited'),
			
			// Basic
			'first_name' => at('First Name'),
			'last_name' => at('Last Name'),
			'birth_date' => at('Birth Date'),
			'company' => at('Company'),
			'contact' => at('Contact'),
			'home_phone' => at('Home Phone'),
			'cell_phone' => at('Cell Phone'),
			'work_phone' => at('Work Phone'),
			'fax' => at('Fax'),
			
			// Shipping
			'shipping_contact' => at('Shipping Contact Name'),
			'shipping_address1' => at('Shipping Address 1'),
			'shipping_address2' => at('Shipping Address 2'),
			'shipping_city' => at('Shipping City'),
			'shipping_state' => at('Shipping State'),
			'shipping_zip' => at('Shipping Zip'),
			'shipping_country' => at('Shipping Country'),
			
			// Billing
			'billing_contact' => at('Billing Contact Name'),
			'billing_address1' => at('Billing Address 1'),
			'billing_address2' => at('Billing Address 2'),
			'billing_city' => at('Billing City'),
			'billing_state' => at('Billing State'),
			'billing_zip' => at('Billing Zip'),
			'billing_country' => at('Billing Country'),
			
		);
	}
	
	public function beforeSave() {
		if($this->birth_date) {
			// See if it has / in it
			// if it does convert to unix
			if(strpos($this->birth_date, '/') !== false) {
				$date = explode('/', $this->birth_date);
				$this->birth_date = mktime(0, 0, 0, $date[0], $date[1], $date[2]);
			}
		}
		
		return parent::beforeSave();
	}
	
	public function getShippingCountryName() {
		return $this->shipping_country && $this->shippingCountry ? $this->shippingCountry->name : null;
	}
	
	public function getShippingStateName() {
		return $this->shipping_state && $this->shippingState ? $this->shippingState->name : null;
	}
	
	public function getBillingCountryName() {
		return $this->billing_country && $this->billingCountry ? $this->billingCountry->name : null;
	}
	
	public function getBillingStateName() {
		return $this->billing_state && $this->billingState ? $this->billingState->name : null;
	}
	
	public function setBirthDate($value) {
		$this->birth_date = $value;
	}
	
	public function getBirthDate() {
		return ($this->birth_date && strpos($this->birth_date, '/') === false) ? date('m/d/Y', $this->birth_date) : $this->birth_date;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('first_name',$this->name,true);
		$criteria->compare('last_name',$this->name,true);
		$criteria->compare('contact',$this->name,true);
		$criteria->compare('company',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 200),
		));
	}
	
	/**
	 * After save event
	 */
	public function afterSave() {
		// Assign role to user
		$auth=Yii::app()->authManager;
        if(!$auth->isAssigned($this->role, $this->id)) {
            if($auth->assign($this->role, $this->id)) {
                Yii::app()->authManager->save();
            }
        }
		
		return parent::afterSave();
	}

	/**
	 * Sets new password
	 * @param string $password
	 */
	public function setPassword($password)
	{
		if(empty($password)) {
			return;
		}
		// 2a is the bcrypt algorithm selector, see http://php.net/crypt
		// 12 is the workload factor
		$this->password_hash = $this->hashPassword($password);
	}
	
	/**
	 * Sets new password
	 * @param string $password
	 */
	public static function hashPassword($password) {
		$salt=substr(str_replace('+','.',base64_encode(sha1(microtime(true),true))),0,22);
		// 2a is the bcrypt algorithm selector, see http://php.net/crypt
		// 12 is the workload factor
		return crypt($password,'$2a$12$'.$salt);
	}

	/**
	 * Checks if password supplied matches user password
	 *
	 * @param string $password
	 * @return bool
	 */
	public function checkPassword($password)
	{
		return $this->password_hash == crypt($password, $this->password_hash);
	}
	
	public function beforeDelete() {
		foreach($this->fieldData as $field) {
			$field->delete();
		}
		return parent::beforeDelete();
	}
	
	public function getFieldsData($userId) {
		$fields = UserCustomField::model()->getFieldsForAdmin();
		$arr = array();
		foreach($fields as $field) {
			$arr[$field->getKey()] = UserCustomFieldData::model()->getFieldValueForDisplay($field, $userId);
		}
		
		return $arr;
	}
	
	public function getFieldsAttributes() {
		$fields = UserCustomField::model()->getFieldsForAdmin();
		$arr = array();
		foreach($fields as $field) {
			$arr[] = array('name' => $field->getKey(), 'label' => $field->getTitle());
		}
		
		return $arr;
	}
	
	/**
	 * 
	 */
	public function afterValidate() {
		if (!$this->hasErrors()) {
			if(!empty($this->new_password))
			{
				$this->setPassword($this->new_password);
			}
		}

		return parent::afterValidate();
	}
}