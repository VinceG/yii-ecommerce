<?php
/**
 * Settings model
 */
class Setting extends ActiveRecord
{
	/**
	 * If we want to disable a setting when showing it
	 */
	public $disabled = false;
	
	/**
	 * Supported setting types
	 */
	public $types = array(
		'text' => 'Text Field',
		'textarea' => 'Text Area',
		'dropdown' => 'Select Box',
		'multi' => 'Multi Select Box',
		//'checkbox' => 'Checkbox',
		'yesno' => 'Yes/No',
		//'radio' => 'Radio Button',
		'editor' => 'HTML Editor',
	);
	
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
		return 'setting';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
		    'group' => array(self::BELONGS_TO, 'SettingCat', 'id'),
		);
	}
	
	/**
	 * Get groups array
	 */
	public function getGroups()
	{
		$groups = SettingCat::model()->byTitle()->findAll();
		$_temp = array();
		if( count($groups) )
		{
			foreach ($groups as $value) 
			{
				$_temp[ $value->id ] = $value->title;
			}
		}
		
		return $_temp;
	}
	
	/**
	 * Get setting types
	 */
	public function getTypes()
	{
		$_temp = array();
		if( count($this->types) )
		{
			foreach ($this->types as $key => $value) 
			{
				$_temp[ $key ] = at($value);
			}
		}
		
		return $_temp;
	}
	
	/**
	 * Update setting by key
	 */
	public function updateSettingByKey($key, $value) {
		$return = Setting::model()->updateAll(array('value' => $value), 'settingkey=:key', array(':key' => $key));
		// Clear cache
		Yii::app()->settings->clearCache();
		return $return;
	}
	
	/**
	 * before save
	 */
	public function beforeSave()
	{
		$this->settingkey = Yii::app()->format->text( str_replace(' ', '', $this->settingkey) );
		
		if( $this->value == '' ) {
			$this->value = null;
		}
		
		if(is_array($this->value) && count($this->value)) {
			$this->value = implode(',', $this->value);
		}
		
		$this->default_value = trim($this->default_value);
		
		return parent::beforeSave();
	}
	
	public function scopes() {
		return array(
			'byOrder' => array(
				'order' => 'sort_ord ASC',
			),
		);
	}
	
	/**
	 * Check to make sure the setting key is not protected
	 */
	public function checkProtectedSettingKey() {
		// make sure setting key is not protected
		if(in_array($this->settingkey, Yii::app()->settings->protectedSettings)) {
			$this->addError('settingkey', at('Sorry, That setting key is protected. Please choose a different one.'));
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
			'title' => at('Setting Title'),
			'description' => at('Setting Description'),
			'category' => at('Setting Group'),
			'type' => at('Setting Type'),
			'default_value' => at('Setting Default Value'),
			'value' => at('Setting Value'),
			'extra' => at('Setting Extra'),
			'php' => at('Setting PHP Code'),
			'settingkey' => at('Setting Unique Key'),
			'is_protected' => at('Setting Protected'),
			'group_title' => at('Setting Group Title'),
			'group_close' => at('Close Opened Group'),
			'sort_ord' => at('Setting Sort Order'),
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
			array('title, type, category, settingkey', 'required' ),
			array('title, group_title', 'length', 'min' => 3, 'max' => 55 ),
			array('category, is_protected, group_close, sort_ord', 'numerical', 'integerOnly' => true ),
			array('type', 'in', 'range' => array_keys($this->types) ),
			array('extra, php, value, description, default_value', 'safe'),
			array('settingkey', 'match', 'allowEmpty'=>false, 'pattern'=>'/[A-Za-z0-9]+$/'),
			array('settingkey', 'unique', 'on'=>'insert'),
			array('settingkey', 'checkProtectedSettingKey'),
		);
	}
}