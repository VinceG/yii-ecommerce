<?php
/**
 * Personal Message Participant model
 */
class PersonalMessageParticipant extends ActiveRecord
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
		return 'personal_message_participant';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
			'participantAuthor' => array(self::BELONGS_TO, 'User', 'user_id'),
			'participantTopic' => array(self::BELONGS_TO, 'PersonalMessageTopic', 'topic_id'),
		);
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
			array('topic_id, user_id', 'required' ),
		);
	}
}