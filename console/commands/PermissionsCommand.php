<?php
/**
 * PermissionsCommand
 */
class PermissionsCommand extends CConsoleCommand
{
	    public function getHelp()
		{
			return <<<EOD
	USAGE
   		- yiic permissions export [options]
			-- Export Options:
				1. --filename=somefile.xml -> file name to load
		- yiic permissions import [options]
			-- Import Options:
				1. --truncate=true -> truncate the auth_item and auth_item_child tables and set auto_increment to 0
				2. --overwrite=true -> overwrite exsiting auth item data
				3. --filename=somefile.xml -> file name to load
	DESCRIPTION
   		- Exports the current permissions roles, tasks, operations into a xml file that will later can be imported to update the permissions
		- Import permissions from the xml file saved in the console/data/permissions.xml
EOD;
		}
		
		/**
		 * Command index action
		 */
		public function actionIndex() {
			die("\n\n--------------------------------------------------------\nPlease use --help to understand how to use this command.\n--------------------------------------------------------\n\n");
		}
		
		/**
		 * Export all permissions into the xml file
		 */
		public function actionExport($filename='permissions.xml') {
			$fileLocation = Yii::getPathOfAlias('console.data') . '/' . $filename;
			
			// Export setting categories first
			$xml = new ClassXML();
			$xml->newXMLDocument();
			
			// Grab auth items
			$authItems = Yii::app()->db->createCommand('SELECT * FROM `auth_item`')->queryAll();
			$xml->addElement( 'permissions_export' );
			$xml->addElement( 'permissions_auth_items', 'permissions_export' );
			foreach($authItems as $authItem) {
				unset($authItem['id']);
				$xml->addElementAsRecord( 'permissions_auth_items', 'auth_item', $authItem );
			}
			
			// Grab auth item child
			$authItemChilds = Yii::app()->db->createCommand('SELECT * FROM `auth_item_child`')->queryAll();
			$xml->addElement( 'auth_item_childs', 'permissions_export' );
			foreach($authItemChilds as $authItemChild) {
				$xml->addElementAsRecord( 'auth_item_childs', 'auth_item_child_row', $authItemChild );
			}
						
			$contents = $xml->fetchDocument();
			
			file_put_contents($fileLocation, $contents);
			echoCli('Export Done');
		}
		
		/** 
		 * Import permissions into the db
		 */
		public function actionImport($truncate=false, $overwrite=false, $filename='permissions.xml') {
			$fileLocation = Yii::getPathOfAlias('console.data') . '/' . $filename;
			if(!file_exists($fileLocation)) {
				echoCli(sprintf("Sorry, The file '%s' was not found.", $fileLocation), true);
			}
			
			if($truncate) {
				// We first delete all the current settings and categories
				Yii::app()->db->createCommand('TRUNCATE TABLE `auth_item_child`; TRUNCATE TABLE `auth_item`')->execute();

				// Reset the auto increment
				Yii::app()->db->createCommand('ALTER TABLE `auth_item_child` AUTO_INCREMENT=0;ALTER TABLE `auth_item` AUTO_INCREMENT=0;')->execute();
			}
			
			$authItemsOld = Yii::app()->db->createCommand('SELECT * FROM `auth_item`')->queryAll();
			// Auth items indexed by name
			$oldAuthItems = array();
			foreach($authItemsOld as $authItemOld) {
				$oldAuthItems[ $authItemOld['name'] ] = $authItemOld;
			}
			
			$authItemsChildOld = Yii::app()->db->createCommand('SELECT * FROM `auth_item_child`')->queryAll();
			// Auth items indexed by name
			$oldAuthItemChilds = array();
			foreach($authItemsChildOld as $authItemChildOld) {
				$oldAuthItemChilds[ $authItemChildOld['parent'] . '_' . $authItemChildOld['child'] ] = $authItemChildOld;
			}
			
			// Load settings file
			$xml = new ClassXML();
			$xml->loadXML( file_get_contents($fileLocation) );
			
			// Import categories
			echoCli('Adding Auth Items');
			foreach( $xml->fetchElements('auth_item') as $authItem ) {
				$data = $xml->fetchElementsFromRecord($authItem);
				
				if(isset($oldAuthItems[ $data['name'] ])) {
					echoCli(sprintf('Auth Item "%s" Exists', $data['name']));
					// Do we want to overwrite
					if($overwrite) {
						echoCli(sprintf('-- Overwriting Auth Item "%s"', $data['name']));
						// Update
						Yii::app()->db->createCommand()->update('auth_item', $data, 'name=:name', array(':name'=>$data['name']));
					} else {
						echoCli(sprintf('-- Skipping Auth Item "%s"', $data['name']));
					}
				} else {
					echoCli(sprintf('Inserting Auth Item "%s"', $data['name']));
					Yii::app()->db->createCommand()->insert('auth_item', $data);
				}
			}
			
			// Import settings
			echoCli('Adding Auth Item Childs');
			foreach( $xml->fetchElements('auth_item_child_row') as $authItemChild ) {
				$data = $xml->fetchElementsFromRecord($authItemChild);
				$childKey = $data['parent'] . '_' . $data['child'];
				
				if(isset($oldAuthItemChilds[ $childKey ])) {
					echoCli(sprintf('Auth Item Child "%s" Exists', $childKey));
					// Do we want to overwrite
					if($overwrite) {
						echoCli(sprintf('-- Overwriting Auth Item "%s"', $childKey));
						// Update
						Yii::app()->db->createCommand()->update('auth_item_child', $data, 'parent=:parent AND child=:child', array(':parent'=>$data['parent'], ':child'=>$data['child']));
					} else {
						echoCli(sprintf('-- Skipping Auth Item Child "%s"', $childKey));
					}
				} else {
					echoCli(sprintf('Inserting Auth Item Child "%s"', $childKey));
					Yii::app()->db->createCommand()->insert('auth_item_child', $data);
				}
			}
			
			echoCli('Import Done');
		}
}