<?php
/**
 * Us City Model
 */
class USCity extends ActiveRecord
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
		return 'us_city';
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels() {
		return array(
			'city_name' => at('City Name'),
			'city_state' => at('City State'),
			'city_zip' => at('City Zip'),
			'city_latitude' => at('City Latitude'),
			'city_longitude' => at('City Longitude'),
			'city_county' => at('City County'),
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
			array('city_name, city_state, city_zip, city_county, city_latitude, city_longitude', 'required' ),
			array('city_name, city_county', 'length', 'min' => 3, 'max' => 55, 'allowEmpty' => false ),
			array('city_state', 'length', 'min' => 1, 'max' => 2, 'allowEmpty' => false ),
			array('city_zip', 'numerical', 'allowEmpty' => false ),
			array('city_zip', 'unique', 'on' => 'insert' ),
			array('city_name, city_state, city_zip, city_county, city_latitude, city_longitude', 'safe', 'on' => 'search'),
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
		$criteria->compare('city_name',$this->city_name,true);
		$criteria->compare('city_state',$this->city_state,true);
		$criteria->compare('city_zip',$this->city_zip,true);
		$criteria->compare('city_county',$this->city_county,true);
		$criteria->compare('city_latitude',$this->city_latitude,true);
		$criteria->compare('city_longitude',$this->city_longitude,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 100),
		));
	}
}