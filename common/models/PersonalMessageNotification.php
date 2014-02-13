<?php
/**
 * Personal Message Notification model
 */
class PersonalMessageNotification extends ActiveRecord
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
		return 'personal_message_notification';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
			'notificationUser' => array(self::BELONGS_TO, 'User', 'user_id'),
			'notificationAuthor' => array(self::BELONGS_TO, 'User', 'byuserid'),
			'notificationTopic' => array(self::BELONGS_TO, 'PersonalMessageTopic', 'topic_id'),
			'notificationReply' => array(self::BELONGS_TO, 'PersonalMessageReply', 'reply_id'),
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
			$this->byuserid = Yii::app()->user->id;
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * Return latest message to be notified about
	 */
	public function getLastMessage() {
		$criteria = new CDbCriteria;
		$criteria->addCondition('t.user_id=:user AND was_shown=:shown');
		$criteria->params = array(':user' => Yii::app()->user->id, ':shown' => 0);
		$criteria->with = array('notificationAuthor', 'notificationTopic', 'notificationReply');
		$criteria->together = true;
		$criteria->order = 'notificationTopic.type DESC, notificationTopic.last_reply_created_at DESC';
		return PersonalMessageNotification::model()->find($criteria);
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
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('topic_id, user_id, byuserid', 'required' ),
		);
	}
}