<?php
/**
 * Settings application component
 */
class CustomSettings extends CApplicationComponent
{
	/**
	 * Private key for storing the cached keys
	 */
	const CACHE_KEY_PREFIX = 'Yii.Settings.Component.Settings';
	/**
	 * @var array the settings array
	 */
	public $settings = array();
	/**
	 * @var string the cache component ID
	 */
	public $cacheID = 'cache';
	/**
	 * @var int the duration to cache the items
	 */
	public $cachingDuration = 1440;
	/**
	 * @var array - array of protected settings that should not be included in the manager
	 */
	public $protectedSettings = array(
		'db.name',
		'db.connectionString',
		'db.username',
		'db.password',
		'testdb.name',
		'testdb.connectionString',
		'testdb.username',
		'testdb.password',
		'cache.core',
		'cache.content',
		'php.exePath',
		'env.code',
		'urlRules',
	);
	/**
	 * Run init
	 */
	public function init()
	{	
		if($this->cachingDuration>0 && $this->cacheID!==false && ($cache=Yii::app()->getComponent($this->cacheID))!==null)
		{
			$key=self::CACHE_KEY_PREFIX;
			if(($data=$cache->get($key))!==false)
			{
				$this->settings = unserialize($data);
				return;
			}
		}
		
		// Load Settings
		$settings = Setting::model()->findAll();
		
		if( count($settings) ) {
			foreach( $settings as $setting ) {
				$this->settings[ $setting->settingkey ] = $setting->value !== null ? $setting->value : $setting->default_value;
			}
		}

		if(isset($cache))
		{
			$cache->set($key,serialize($this->settings),$this->cachingDuration);
		}

		return $this->settings;
	}
	/**
	 * Get all settings
	 */
	public function getSettings()
	{
		return $this->settings;
	}
	/**
	 * Get setting value by key
	 */
	public function get( $key, $default=null )
	{
		if(!isset($this->settings[$key])) {
			$this->addMissingSetting($key, $default);
		}
		return isset($this->settings[$key]) ? $this->settings[$key] : $default;
	}
	/**
	 * Magic method __get()
	 */
	public function __get( $key )
	{
		return $this->get($key);
	}
	/**
	 * Delete cache if exists
	 */
	public function clearCache()
	{
		if($this->cachingDuration>0 && $this->cacheID!==false && ($cache=Yii::app()->getComponent($this->cacheID))!==null)
		{
			$key=self::CACHE_KEY_PREFIX;
			$cache->delete($key);
		}
	}
	
	/**
	 * Add the missing settings if we found one
	 *
	 */
	protected function addMissingSetting($key, $default=null) {
		// Ignore if key is protected
		if(in_array($key, $this->protectedSettings)) {
			return;
		}
		// First make sure we haven't already added it
		// without looking in the db
		$missingSettings = Yii::app()->cache->get('missingSettings');
		if($missingSettings === false) {
			// Init
			$missingSettings = array();
		}
		
		// Do we have that setting in the array
		if(!in_array($key, $missingSettings)) {
			// We don't so look up the db
			$settingExists = Setting::model()->find('settingkey=:key', array(':key' => $key));
			if(!$settingExists) {
				// We didn't find anything so add it
				// Do we have the missing setting cat?
				$missingCat = SettingCat::model()->find('groupkey=:key', array(':key' => 'missing_settings'));
				if(!$missingCat) {
					$missingCat = new SettingCat;
					$missingCat->title = 'Missing Settings';
					$missingCat->description = 'Settings that were accessed but were not found in the db';
					$missingCat->groupkey = 'missing_settings';
					if(!$missingCat->save()) {
						alog('Could not create the missing setting category.');
					}
				}
				
				// Add the new setting
				$newSetting = new Setting;
				$newSetting->title = ucwords(str_replace('_', ' ', $key));
				$newSetting->settingkey = $key;
				$newSetting->category = $missingCat->id;
				$newSetting->type = 'text';
				$newSetting->default_value = $default ? $default : '0';
				if(!$newSetting->save()) {
					alog(sprintf("Could not create a setting with the key '%s'", $key));
				}
				
			}
			
			$missingSettigns[$key] = $key;
			
			// Save
			Yii::app()->cache->set('missing_settings', $missingSettigns);
		}
		$this->clearCache();
	}	
}