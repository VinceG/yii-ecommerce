<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;

	const ERROR_USER_REMOVED = 'ERROR_USER_REMOVED';
	const ERROR_ACCOUNT_EXPIRED = 'ERROR_ACCOUNT_EXPIRED';
	
    public function getId() {
        return $this->_id;
    }

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		/** @var $model User */
		$model = User::model()->find('email=LOWER(:email)', array(':email'=>$this->name));
		if($model===null) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
			$this->errorMessage = t('Sorry, An account was not found with that email address.');
        } else if(!$model->checkPassword($this->password)) {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
			$this->errorMessage = t('Sorry, The password did not match to the one in our records.');
		} else {
            $this->_id=$model->id;
            $this->errorCode=self::ERROR_NONE;
			
			// Set states
			$this->setState('name', $model->name);
			$this->setState('email', $model->email);
			$this->setState('role', $model->role);
        }

        return !$this->errorCode;
	}
}