<?php
/**
 * Settings groups model
 */
class SettingCat extends ActiveRecord
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
		return 'settingcat';
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
			'description' => at('Description'),
			'groupkey' => at('Group Unique Key'),
		);
	}
	
	/**
	 * before save
	 */
	public function beforeSave()
	{
		$this->groupkey = Yii::app()->format->text( str_replace(' ', '', $this->groupkey) );
		
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
			array('title, groupkey, description', 'required' ),
			array('groupkey, title', 'match', 'allowEmpty'=>false, 'pattern'=>'/[A-Za-z0-9]+$/'),
			array('groupkey', 'unique', 'on'=>'insert'),
			array('title', 'length', 'min' => 3, 'max' => 55 ),
			array('description', 'length', 'min' => 0, 'max' => 155 ),
		);
	}
	
	public function relations()
	{
		return array(
		    'settings' => array(self::HAS_MANY, 'Setting', 'category'),
			'count' => array(self::STAT, 'Setting', 'category','condition'=>'category'),
		);
	}
	
	/**
	 * Related Scopes
	 */
	public function scopes() {
		return array(
			'byTitle' => array(
				'order' => 'title ASC',
			),
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 200),
			'sort' => array(
				'defaultOrder' => 'title ASC',
			),
		));
	}
}