<?php
/**
 * Theme File Model
 */
class ThemeFile extends ActiveRecord
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
		return 'theme_file';
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'theme_id' => at('Theme'),
			'file_name' => at('File Name'),
			'file_ext' => at('File Extension'),
			'file_location' => at('File Location'),
			'content' => at('Content'),
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
	public function beforeSave() {
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
			array('theme_id, file_name, file_ext, file_location', 'required' ),
			array('file_name, file_location', 'length', 'min' => 3, 'max' => 125 ),
			array('theme_id', 'numerical'),
			array('content', 'safe'),
			array('theme_id, file_name, file_ext, file_location', 'safe', 'on' => 'search'),
		);
	}
	
	public function scopes() {
		return array();
	}
	
	public function relations()
	{
		return array(
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'lastAuthor' => array(self::BELONGS_TO, 'User', 'updated_author_id'),
			'theme' => array(self::BELONGS_TO, 'Theme', 'theme_id'),
		);
	}
	
	/**
	 * Before delete event
	 */
	public function beforeDelete() {
		
		return parent::beforeDelete();
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		
		$criteria->compare('id',$this->id);
		$criteria->compare('theme_id',$this->theme_id);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('file_location',$this->file_location,true);
		$criteria->compare('file_ext',$this->file_ext,true);
		$criteria->compare('is_active',$this->is_active);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 100),
		));
	}
}