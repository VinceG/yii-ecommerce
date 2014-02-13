<?php
/**
 * LanguagesCommand manager
 */
class LanguagesCommand extends CConsoleCommand
{
	    public function getHelp()
		{
			return <<<EOD
	USAGE
   		- yiic languages export [options]
			-- Export Options:
				1. --filename=somefile.xml -> file name to load
		- yiic languages import [options]
			-- Import Options:
				1. --truncate=true -> truncate the source message table and set auto_increment to 0
				2. --overwrite=true -> overwrite exsiting source message data
				3. --filename=somefile.xml -> file name to load
	DESCRIPTION
   		- Exports the current source message into a xml file that will later can be imported to update the messages
		- Import messages from the xml file saved in the console/data/language_messages.xml
EOD;
		}
		
		/**
		 * Command index action
		 */
		public function actionIndex() {
			die("\n\n--------------------------------------------------------\nPlease use --help to understand how to use this command.\n--------------------------------------------------------\n\n");
		}
		
		/**
		 * Export all source messages into a xml file
		 */
		public function actionExport($filename='language_messages.xml') {
			$fileLocation = Yii::getPathOfAlias('console.data') . '/' . $filename;
			
			// Export setting categories first
			$xml = new ClassXML();
			$xml->newXMLDocument();
			
			// Grab categories
			$messages = Yii::app()->db->createCommand('SELECT * FROM `source_message`')->queryAll();
			$messageKeys = array();
			$xml->addElement( 'messages_export' );
			foreach($messages as $message) {
				unset($message['id']);
				$xml->addElementAsRecord( 'messages_export', 'message_row', $message );
			}
						
			$contents = $xml->fetchDocument();
			
			file_put_contents($fileLocation, $contents);
			echoCli('Export Done');
		}
		
		/** 
		 * Import messages into the db
		 */
		public function actionImport($truncate=false, $overwrite=false, $filename='language_messages.xml') {
			$fileLocation = Yii::getPathOfAlias('console.data') . '/' . $filename;
			if(!file_exists($fileLocation)) {
				echoCli(sprintf("Sorry, The file '%s' was not found.", $fileLocation), true);
			}
			
			if($truncate) {
				// We first delete all the current settings and categories
				Yii::app()->db->createCommand('TRUNCATE TABLE `source_message`;')->execute();
				// Reset the auto increment
				Yii::app()->db->createCommand('ALTER TABLE `source_message` AUTO_INCREMENT=0;')->execute();
			}
			
			// Get the current setting cats and settings
			$oldMessages = Yii::app()->db->createCommand('SELECT * FROM `source_message`')->queryAll();
			
			// Setting categories indexed by category key
			$oldMessagesKeys = array();
			foreach($oldMessages as $oldMessage) {
				$oldMessagesKeys[ sha1($oldMessage['category'] . '_' . $oldMessage['message']) ] = $oldMessage;
			}
			
			// Load settings file
			$xml = new ClassXML();
			$xml->loadXML( file_get_contents($fileLocation) );
			
			// Import categories
			echoCli('Adding Source Messages');
			foreach( $xml->fetchElements('message_row') as $message ) {
				$data = $xml->fetchElementsFromRecord($message);
				
				if(isset($oldMessagesKeys[ sha1($data['category'] . '_' . $data['message']) ])) {
					echoCli(sprintf('Message "%s" Exists', $data['message']));
					// Do we want to overwrite
					if($overwrite) {
						echoCli(sprintf('-- Overwriting Message "%s"', $data['category'] . '_' . $data['message']));
						// Update
						Yii::app()->db->createCommand()->update('source_message', $data, 'category=:category AND message=:message', array(':category'=>$data['category'], ':message'=>$data['message']));
					} else {
						echoCli(sprintf('-- Skipping Message "%s"', $data['category'] . '_' . $data['message']));
					}
				} else {
					echoCli(sprintf('Inserting Message "%s"', $data['category'] . '_' . $data['message']));
					Yii::app()->db->createCommand()->insert('source_message', $data);
				}
			}
			
			// Sync all languages
			echoCli('Syncing Languages');
			$langs = Language::model()->findAll();
			foreach($langs as $lang) {
				echoCli('Syncing ' . $lang->name);
				$lang->SyncLanguageStrings();	
			}
						
			echoCli('Import Done');
		}
}