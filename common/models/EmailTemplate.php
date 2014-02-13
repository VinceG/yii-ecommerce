<?php
/**
 * Email Template model
 */
class EmailTemplate extends ActiveRecord
{		
	/**
	 * User id to test the email on
	 */
	public $user = null;
	/**
	 * @return object
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string Table name
	 */
	public function tableName()
	{
		return 'email_template';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'last_author' => array(self::BELONGS_TO, 'User', 'updated_author_id'),
		);
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'title' => at('Title'),
			'email_key' => at('Unique Template Key'),
			'content' => at('Content'),
			'created_at' => at('Date Created'),
			'author_id' => at('Author'),
			'updated_author_id' => at('Updated By'),
			'updated_at' => at('Date Updated'),
			'user' => at('Preview With User'),
		);
	}
	
	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created_at',
				'updateAttribute' => 'updated_at',
				'setUpdateOnCreate' => false,
			),
		);
	}
	
	/**
	 * Before save operations
	 */
	public function beforeSave()
	{
		if( $this->isNewRecord ) {
			$this->author_id = Yii::app()->user->id;
		} else {
			$this->updated_author_id = Yii::app()->user->id;
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
		return array(
			array('title, content, email_key', 'required' ),
			array('email_key', 'unique'),
			array('email_key', 'match', 'pattern' => '/[0-9a-zA-Z\_\-]$/'),
			array('user', 'length', 'max'=>255),
			array('title, email_key', 'length', 'max'=>50),
			array('title, content, created_at, author_id, updated_author_id, updated_at, email_key', 'safe' ),
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->with = array('author', 'last_author');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 50),
		));
	}
}