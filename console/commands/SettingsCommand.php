<?php
/**
 * SettingsCommand manager
 */
class SettingsCommand extends CConsoleCommand
{
	    public function getHelp()
		{
			return <<<EOD
	USAGE
   		- yiic settings export [options]
			-- Export Options:
				1. --filename=somefile.xml -> file name to load
		- yiic settings import [options]
			-- Import Options:
				1. --truncate=true -> truncate the settingcat and setting tables and set auto_increment to 0
				2. --overwrite=true -> overwrite exsiting setting data (everything but the setting value)
				3. --filename=somefile.xml -> file name to load
	DESCRIPTION
   		- Exports the current setting categories and settings into a xml file that will later can be imported to update the settings
		- Import settings from the xml file saved in the console/data/settings.xml
EOD;
		}
		
		/**
		 * Command index action
		 */
		public function actionIndex() {
			die("\n\n--------------------------------------------------------\nPlease use --help to understand how to use this command.\n--------------------------------------------------------\n\n");
		}
		
		/**
		 * Export all setting categories and settings into a xml file
		 */
		public function actionExport($filename='settings.xml') {
			$fileLocation = Yii::getPathOfAlias('console.data') . '/' . $filename;
			
			// Export setting categories first
			$xml = new ClassXML();
			$xml->newXMLDocument();
			
			// Grab categories
			$categories = Yii::app()->db->createCommand('SELECT * FROM `settingcat`')->queryAll();
			$categoriesKeys = array();
			$xml->addElement( 'settings_export' );
			$xml->addElement( 'setting_categories', 'settings_export' );
			foreach($categories as $category) {
				$categoriesKeys[$category['id']] = $category['groupkey'];
				unset($category['id']);
				$xml->addElementAsRecord( 'setting_categories', 'setting_category', $category );
			}
			
			// Grab Settings
			$settings = Yii::app()->db->createCommand('SELECT * FROM `setting`')->queryAll();
			$xml->addElement( 'settings', 'settings_export' );
			foreach($settings as $setting) {
				unset($setting['id']);
				// Switch category
				$setting['category'] = $categoriesKeys[$setting['category']];
				$xml->addElementAsRecord( 'settings', 'setting_row', $setting );
			}
						
			$contents = $xml->fetchDocument();
			
			file_put_contents($fileLocation, $contents);
			echoCli('Export Done');
		}
		
		/** 
		 * Import settings into the db
		 */
		public function actionImport($truncate=false, $overwrite=false, $filename='settings.xml') {
			$fileLocation = Yii::getPathOfAlias('console.data') . '/' . $filename;
			if(!file_exists($fileLocation)) {
				echoCli(sprintf("Sorry, The file '%s' was not found.", $fileLocation), true);
			}
			
			if($truncate) {
				// We first delete all the current settings and categories
				Yii::app()->db->createCommand('TRUNCATE TABLE `settingcat`; TRUNCATE TABLE `setting`')->execute();
				// Reset the auto increment
				Yii::app()->db->createCommand('ALTER TABLE `settingcat` AUTO_INCREMENT=0;ALTER TABLE `setting` AUTO_INCREMENT=0;')->execute();
			}
			
			// Get the current setting cats and settings
			$oldSettingCats = Yii::app()->db->createCommand('SELECT * FROM `settingcat`')->queryAll();
			$oldSettings = Yii::app()->db->createCommand('SELECT * FROM `setting`')->queryAll();
			
			// Setting categories indexed by category key
			$oldSettingCatsKeys = array();
			foreach($oldSettingCats as $oldSettingCat) {
				$oldSettingCatsKeys[ $oldSettingCat['groupkey'] ] = $oldSettingCat;
			}
			
			// Settings indexed by setting key
			$oldSettingsKeys = array();
			foreach($oldSettings as $oldSetting) {
				$oldSettingsKeys[ $oldSetting['settingkey'] ] = $oldSetting;
			}
			
			// Load settings file
			$xml = new ClassXML();
			$xml->loadXML( file_get_contents($fileLocation) );
			
			// Import categories
			echoCli('Adding Setting Categories');
			foreach( $xml->fetchElements('setting_category') as $category ) {
				$data = $xml->fetchElementsFromRecord($category);
				
				if(isset($oldSettingCatsKeys[ $data['groupkey'] ])) {
					echoCli(sprintf('Category "%s" Exists', $data['title']));
					// Do we want to overwrite
					if($overwrite) {
						echoCli(sprintf('-- Overwriting Category "%s"', $data['title']));
						// Update
						Yii::app()->db->createCommand()->update('settingcat', $data, 'groupkey=:key', array(':key'=>$data['groupkey']));
					} else {
						echoCli(sprintf('-- Skipping Category "%s"', $data['title']));
					}
				} else {
					echoCli(sprintf('Inserting Category "%s"', $data['title']));
					Yii::app()->db->createCommand()->insert('settingcat', $data);
				}
			}
			
			// Grab the new categories
			$categories = Yii::app()->db->createCommand('SELECT * FROM `settingcat`')->queryAll();
			$categoriesKeys = array();
			foreach($categories as $category) {
				$categoriesKeys[ strtolower($category['groupkey']) ] = $category['id'];
			}
			
			// Import settings
			echoCli('Adding Settings');
			foreach( $xml->fetchElements('setting_row') as $setting ) {
				$data = $xml->fetchElementsFromRecord($setting);
				
				// Unset value, value never changes
				unset($data['value']);
				
				// Grab new category value
				$data['category'] = $categoriesKeys[ strtolower($data['category']) ];
				
				if(isset($oldSettingsKeys[ $data['settingkey'] ])) {
					echoCli(sprintf('Setting "%s" Exists', $data['title']));
					// Do we want to overwrite
					if($overwrite) {
						echoCli(sprintf('-- Overwriting Setting "%s"', $data['title']));
						// Update
						Yii::app()->db->createCommand()->update('setting', $data, 'settingkey=:key', array(':key'=>$data['settingkey']));
					} else {
						echoCli(sprintf('-- Skipping Setting "%s"', $data['title']));
					}
				} else {
					echoCli(sprintf('Inserting Setting "%s"', $data['title']));
					Yii::app()->db->createCommand()->insert('setting', $data);
				}
			}
			
			echoCli('Import Done');
		}
}