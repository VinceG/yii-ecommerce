<?php
/**
 * Login form model class
 */
class AdminLogin extends CFormModel
{
	/**
	 * @var string - password
	 */
	public $password;
	
	/**
	 * @var string - email
	 */
	public $email;

	/**
	 * @var string - captcha
	 */
	public $verifyCode;
	
	/**
	 * @var boolean - remember me
	 */
	public $rememberme = false;
	
	/**
	 * @var object
	 */
	public $identity;
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('email, password', 'required'),
			array('email', 'email'),
			array('email', 'checkEmail'),
			array('password', 'length', 'min' => 3, 'max' => 32),
			array('email', 'length', 'min' => 3, 'max' => 55),
			array('rememberme', 'boolean'),
			array('password', 'authenticate'),
			array('verifyCode', 'captcha'),
		);
	}
	
	/**
	 * Check account existence and permission assigned
	 *
	 */
	public function checkEmail() {
		// We lookup the db for this email and make sure that record exists
		// then make sure the record has the op_acp_access permissions granted
		$user = User::model()->find('email=LOWER(:email)', array(':email' => strtolower($this->email)));
		if(!$user) {
			$this->addError('email', at('Sorry, That email address does not exists.'));
			return false;
		}
		
		// Make sure that user has the op_acp_access permission
		if(!Yii::app()->authManager->checkAccess('admin', $user->id)) {
			$this->addError('email', at('Sorry, That account does not have access to the admin section.'));
			return false;
		}
	}
	
	/**
	 * @return null on success error on failure
	 */
	public function authenticate() {
		$this->identity = new UserIdentity($this->email, $this->password);
		if($this->identity->authenticate()) {
			// Member authenticated
			return true;
		} else {
			$this->addError('password', $this->identity->errorMessage);
		}
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'email' => at('Email'),
			'password' => at('Password'),
			'verifyCode' => at('Security Code'),
		);
	}
	
}