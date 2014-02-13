<?php
/**
 * Source message model
 */
class SourceMessage extends ActiveRecord
{
	/**
	 * @return SourceMessage
	 */
	public static function model($model=__CLASS__)
	{
		return parent::model($model);
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('message, category', 'required' ),
			array('message, category', 'length', 'min' => 1),
			array('category', 'checkExisting'),
			array('category, message', 'safe', 'on' => 'search'),
		);
	}
	
	/**
	 * Check if the category-message combination exists
	 */
	public function checkExisting() {
		$exists = SourceMessage::model()->find('category=LOWER(:category) AND message=LOWER(:message)', array(':category' => strtolower($this->category), ':message' => strtolower($this->message)));
		if($exists) {
			$this->addError('message', at('That message already exists under that category.'));
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
			'category' => at('Message Category'),
			'name' => at('Message'),
		);
	}
	
	/**
	 * @return string Table name
	 */
	public function tableName()
	{
		return 'source_message';
	}
}