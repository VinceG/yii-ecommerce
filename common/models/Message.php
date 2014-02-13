<?php

class Message extends ActiveRecord
{

	/**
	 * @return Message
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
		return 'message';
	}
	
	public function relations()
	{
		return array(
			'source' => array(self::BELONGS_TO, 'SourceMessage', 'id'),
		);
	}
}