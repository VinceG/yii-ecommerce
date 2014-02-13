<?php
/**
 * Personal Message Reply model
 */
class PersonalMessageReply extends ActiveRecord
{		
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
		return 'personal_message_reply';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
			'replyAuthor' => array(self::BELONGS_TO, 'User', 'user_id'),
			'replyTopic' => array(self::BELONGS_TO, 'PersonalMessageTopic', 'topic_id'),
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
			'message' => at('message'),
		);
	}
	
	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created_at',
				'updateAttribute' => false,
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
			$this->user_id = Yii::app()->user->id;
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * After save event
	 */
	public function afterSave() {
		// Update Topic
		PersonalMessageTopic::model()->updateByPk($this->topic_id, array('last_reply_created_at' => $this->created_at, 'last_reply_author_id' => $this->user_id));
		
		// Send notifications
		PersonalMessageTopic::model()->sendNotifications($this->topic_id, $this->id);
		
		return parent::afterSave();
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('message', 'required' ),
			array('message', 'length', 'min' => 3 ),
		);
	}
}