<?php

class UserCustomFieldData extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_custom_field_data';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user+id'),
			'field' => array(self::BELONGS_TO, 'UserCustomField', 'field_id'),
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
			array('field_id, user_id, value', 'required' ),
		);
	}
	
	/**
	 * Get field value if it exists
	 *
	 */
	public function getFieldValue($field, $userId) {
		// Check to see if we have that field user combination
		$row = UserCustomFieldData::model()->find('field_id=:field AND user_id=:user', array(':field' => $field->id, ':user' => $userId));
		if($row) {
			// Return the value
			return $row->value;
		} else {
			// Return default
			return $field->default_value;
		}
	}
	
	public function getFieldValueForDisplay($field, $userId) {
		// Check to see if we have that field user combination
		$row = UserCustomFieldData::model()->find('field_id=:field AND user_id=:user', array(':field' => $field->id, ':user' => $userId));
		if($row) {
			// Return the value
			$value = $row->value;
		} else {
			// Return default
			$value = $field->default_value;
		}
		
		if($field->type == 'yesno') {
			$text = $value ? at('Yes') : at('No');
		} elseif($field->type == 'checkbox') {
			$text = $value ? at('Checked') : at('Not Checked');
		} elseif($field->type == 'dropdown') {
			$valueArray = UserCustomField::model()->convertExtraToArray($field->extra);
			$text = (is_array($valueArray) && isset($valueArray[$value])) ? $valueArray[$value] : $value;
		} elseif($field->type == 'multi') {	
			$valueArray = UserCustomField::model()->convertExtraToArray($field->extra);
			$selectedValue = explode(',', $value);
			if(count($selectedValue)) {
				$arr = array();
				foreach($selectedValue as $selectedVal) {
					$arr[] = (is_array($valueArray) && isset($valueArray[$selectedVal])) ? $valueArray[$selectedVal] : $selectedVal;
				}
				$text = implode(', ', $arr);
			} else {
				$text = (is_array($valueArray) && isset($valueArray[$selectedValue])) ? $valueArray[$selectedValue] : $selectedValue;
			}
		} else {
			$text = $value;
		}
		
		return $text;
	}
	
	/**
	 * Store user field value
	 *
	 */
	public function saveFieldValue($fieldId, $userId, $value) {
		// Check to see if we have that field user combination
		$row = UserCustomFieldData::model()->exists('field_id=:field AND user_id=:user', array(':field' => $fieldId, ':user' => $userId));
		if($row) {
			// Update field
			$this->updateFieldData($fieldId, $userId, $value);
		} else {
			// Create new
			$this->createFieldData($fieldId, $userId, $value);
		}
	}
	
	/**
	 * Create new field data record
	 *
	 */
	public function createFieldData($fieldId, $userId, $value) {
		if(is_array($value) && count($value)) {
			$value = implode(',', $value);
		}
		$model = new UserCustomFieldData;
		$model->field_id = $fieldId;
		$model->user_id = $userId;
		$model->value = $value;
		$model->save();
	}
	
	/**
	 * Update field data record
	 * 
	 */
	public function updateFieldData($fieldId, $userId, $value) {
		if(is_array($value) && count($value)) {
			$value = implode(',', $value);
		}
		return UserCustomFieldData::model()->updateAll(array('value' => $value), 'field_id=:field AND user_id=:user', array(':field' => $fieldId, ':user' => $userId));
	}
}