<?php
/**
 * admin users
 */
class AdminUser extends ActiveRecord
{		
	/**
	 * @return object
	 */
	public static function model($class=__CLASS__)
	{
		return parent::model($class);
	}
	
	/**
	 * @return string Table name
	 */
	public function tableName()
	{
		return 'admin_user';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
		    'user' => array(self::BELONGS_TO, 'User', 'userid'),
		);
	}
	
	public function totalLoggedIn() {
		return AdminUser::model()->count();
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array();
	}
	
	public function getUserLink() {
		return $this->user ? ($this->user->id . " - " . CHtml::link($this->user->name, array('user/view', 'id' => $this->user->id))) : "N/A";
	}
	
	/**
	 * Before save operations
	 */
	public function beforeSave()
	{
		if( $this->isNewRecord ) {
			$this->loggedin_time = time();
			$this->userid = Yii::app()->user->id;
			$this->location = Yii::app()->getController()->id;
		} else {
			$this->lastclick_time = time();
			$this->location = Yii::app()->getController()->id;
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array();
	}
}