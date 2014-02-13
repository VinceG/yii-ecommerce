<?php
/**
 * Language Model
 */
class Language extends ActiveRecord
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
		return 'language';
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'name' => at('Language Title'),
			'abbr' => at('Language Code'),
			'is_source' => at('Source Language'),
			'is_public' => at('Public Language'),
		);
	}
	
	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created_at',
				'updateAttribute' => null,
				'setUpdateOnCreate' => false,
			),
		);
	}
	
	
	
	public function getLanguages() {
		$rows = Language::model()->public()->findAll();
		$list = array();
		foreach($rows as $row) {
			$list[$row->id] = '(' . $row->abbr . ') ' . $row->name;
		}
		return $list;
	}
	
	public function getLanguagesCodes() {
		$rows = Language::model()->public()->findAll();
		$list = array();
		foreach($rows as $row) {
			$list[$row->abbr] = '(' . $row->abbr . ') ' . $row->name;
		}
		return $list;
	}
	
	/**
	 * Before save operations
	 */
	public function beforeSave() {
		if( $this->isNewRecord ) {
			$this->author_id = Yii::app()->user->id;
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
			array('name, abbr, is_public', 'required' ),
			array('name', 'length', 'min' => 3, 'max' => 55 ),
			array('abbr', 'length', 'min' => 2, 'max' => 2 ),
			array('is_public', 'numerical'),
			array('abbr', 'unique'),
			array('name, abbr, is_public', 'safe', 'on' => 'search'),
		);
	}
	
	public function scopes() {
		return array(
			'byName' => array(
				'order' => 'name ASC'
			),
			'public' => array(
				'condition' => 'is_public=:public',
				'params' => array(':public' => 1),
			),
		);
	}
	
	public function relations()
	{
		return array(
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'strings' => array(self::HAS_MANY, 'Message', 'language_id'),
			'stringsCount' => array(self::STAT, 'Message', 'language_id'),
		);
	}
	
	/**
	 * Get ids of translation that were not translated
	 */
	public function getStringNotTranslated( $id ) {
		$origs = SourceMessage::model()->findAll();
		$translated = array();
		if( count( $origs ) ) {
			foreach( $origs as $orig ) {
				// Grab the translation from the messages table
				$message = Message::model()->find('language_id=:lang AND id=:id', array( ':lang' => $id, ':id' => $orig->id ));
				if( $message ) {
					if( $message->translation == '' || $message->translation == $orig->message ) {
						$translated[] = $message->id;
					}
				}
			}
		}
		return $translated;
	}
	
	/**
	 * Before delete event
	 */
	public function beforeDelete() {
		// Delete all messages first
		Message::model()->deleteAll('language_id=:id', array(':id' => $this->id));
		
		return parent::beforeDelete();
	}
	
	/**
	 * Get number of strings that were already translated
	 */
	public function getStringTranslationDifference( $id ) {
		$origs = SourceMessage::model()->findAll();
		$translated = 0;
		if( count( $origs ) ) {
			foreach( $origs as $orig ) {
				// Grab the translation from the messages table
				$message = Message::model()->find('language_id=:lang AND id=:id', array( ':lang' => $id, ':id' => $orig->id ));
				if( $message ) {
					if( $message->translation != $orig->message ) {
						$translated++;
					}
				}
			}
		}
		return $translated;
	}
	
	/**
	 * Sync source language into language
	 *
	 */
	public function SyncLanguageStrings() {
		// Grab all source strings
		$source = Yii::app()->db->createCommand()->select('*')->from('source_message')->queryAll();
		
		// Check if each string exists in the message table for this language
		$total = 0;
		foreach($source as $src) {
			// Query to see if we have that
			$exists = Yii::app()->db->createCommand()->select('id')->from('message')->where('id=:id AND language_id=:lang', array(':id' => $src['id'], ':lang' => $this->id))->queryScalar();
			if($exists) {
				continue;
			}
			
			$message = array('id' => $src['id'], 'language' => $this->abbr, 'language_id' => $this->id, 'translation' => $src['message']);
			Yii::app()->db->createCommand()->insert('message', $message);
			$total++;
		}
		
		return $total;
	}
	
	/**
	 * Sync all languages
	 *
	 */
	public function syncAllLanguages() {
		$rows = Language::model()->findAll();
		foreach($rows as $row) {
			$row->SyncLanguageStrings();
		}
		
		return true;
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
		$criteria->compare('abbr',$this->abbr,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 100),
		));
	}
}