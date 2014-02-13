<?php
/**
 * user custom field model
 */
class UserCustomField extends ActiveRecord
{		
	/**
	 * Valid field types
	 */
	public $fieldType = array(
			'text' => 'Text Field',
			'textarea' => 'Text Area',
			'dropdown' => 'Select Box',
			'multi' => 'Multi Select Box',
			'checkbox' => 'Checkbox',
			'yesno' => 'Yes/No',
			//'radio' => 'Radio Button',
			'editor' => 'HTML Editor',
			);
	
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
		return 'user_custom_field';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'last_author' => array(self::BELONGS_TO, 'User', 'updated_author_id'),
			'fieldData' => array(self::HAS_MANY, 'UserCustomFieldData', 'field_id'),
		);
	}
	
	public function getTypes() {
		return $this->fieldType;
	}
	
	public function scopes() {
		return array(
			'isActive' => array(
				'condition' => 'status=:status',
				'params' => array(':status' => 1),
			),
			'isPublic' => array(
				'condition' => 'is_public=:public',
				'params' => array(':public' => 1),
			),
			'isEditable' => array(
				'condition' => 'is_editable=:editable',
				'params' => array(':editable' => 1),
			),
		);
	}
	
	public function getFieldsForAdmin() {
		return UserCustomField::model()->isActive()->findAll();
	}
	
	public function getTitle() {
		return t($this->title, 'usercustomfields');
	}
	
	public function getKey() {
		return  $this->getKeyPrefix() . $this->id;
	}
	
	public function getKeyPrefix() {
		return 'customfield_';
	}
	
	public function getFormField($userId) {
		return $this->buildFormField($this, $userId);
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
			'type' => at('Type'),
			'description' => at('Description'),
			'status' => at('Status'),
			'is_public' => at('Shown On Site'),
			'is_editable' => at('Allow To Edit'),
			'default_value' => at('Default Value'),
			'extra' => at('Extra Data'),
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
	public function beforeSave()
	{
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
			array('title, type', 'required' ),
			array('title', 'length', 'min' => 3, 'max' => 55 ),
			array('description', 'length', 'max' => 55 ),
			array('status, is_public, is_editable', 'numerical' ),
			array('default_value, extra', 'length', 'max' => 500 ),
			array('type', 'in', 'range' => array_keys($this->fieldType)),
		);
	}
	
	/**
	 * Return user link for the author and last updated author
	 *
	 */
	public function getAuthorLink($relation) {
		return $this->$relation ? ($this->$relation->id . " - " . CHtml::link($this->$relation->name, array('user/view', 'id' => $this->$relation->id))) : "--";
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->with = array('author', 'last_author');

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_public',$this->is_public);
		$criteria->compare('is_editable',$this->is_editable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 50),
		));
	}
	
	public function processCustomFields($fields, $userId) {
		if(count($fields)) {
			foreach($fields as $fieldId => $value) {
				$fieldId = str_replace($this->getKeyPrefix(), '', $fieldId);
				// Save fields
				UserCustomFieldData::model()->saveFieldValue($fieldId, $userId, $value);
			}
		}
	}
	
	public function beforeDelete() {
		foreach($this->fieldData as $field) {
			$field->delete();
		}
		return parent::beforeDelete();
	}
	
	/**
	 * Parse each setting
	 */
	public function buildFormField( $field, $userId )
	{
		$name = 'UserCustomField['.$field->getKey().']';
		$value = UserCustomFieldData::model()->getFieldValue($field, $userId);
		
		switch( $field->type )
		{
			case 'textarea':
			echo CHtml::textArea( $name, $value, array( 'rows' => 5, 'class' => 'textbox' ) );
			break;
			
			case 'dropdown':
			echo CHtml::dropDownList( $name, $value, $this->convertExtraToArray( $field->extra ), array( 'class' => 'chosen' ) );
			break;
			
			case 'multi':
			echo CHtml::listBox( $name, $value ? explode(',', $value) : '', $this->convertExtraToArray( $field->extra ), array( 'multiple' => 'multiple', 'class' => 'chosen' ) );
			break;
			
			case 'checkbox':
			echo CHtml::checkbox( $name, $value, array( 'class' => '' ) );
			break;
			
			case 'yesno':
			echo CHtml::dropDownList( $name, $value, array( '0' => Yii::t('global', 'No'), '1' => Yii::t('global', 'Yes') ), array( 'class' => 'chosen' ) );
			break;
			
			case 'editor':
			Yii::app()->customEditor->getEditor(array('name' => $name, 'value' => $value));
			break;
			
			case 'text':
			default:
			echo CHtml::textField( $name, $value, array( 'class' => 'textbox' ) );
			break;
		}
	}
	
	/**
	 * Convert extra data to an array of key=>value pairs
	 */
	public function convertExtraToArray( $string ) {
		if( !$string )
		{
			return array();
		}
		
		$_temp = array();
		
		if( $string == '#show_roles#' )
		{
			$roles = Yii::app()->authManager->getRoles();
			if( count($roles) )
			{
				foreach( $roles as $role )
				{
					$_temp[ $role->name ] = $role->name;
				}
			}
		}
		else
		{
			$exploded = explode("\n", $string);

			if( count($exploded) )
			{
				foreach( $exploded as $explode )
				{
					list($key, $value) = explode('=', $explode);
					$_temp[$key] = $value;
				}
			}
		}	
		
		return $_temp;
	}
}