<?php
/**
 * Us State Model
 */
class USState extends ActiveRecord
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
		return 'us_state';
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'name' => at('State Name'),
			'short' => at('State Short Abbreviation'),
			'sort_ord' => at('Sort Order'),
		);
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('name, short, sort_ord', 'required' ),
			array('name', 'length', 'min' => 3, 'max' => 55 ),
			array('short', 'length', 'min' => 1, 'max' => 5 ),
			array('name, short', 'safe', 'on' => 'search'),
		);
	}
	
	public function scopes() {
		return array(
			'byOrder' => array(
				'order' => 'sort_ord ASC'
			),
		);
	}
	
	public function relations()
	{
		return array();
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
		$criteria->compare('short',$this->short,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 100),
		));
	}
}