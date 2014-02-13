<?php
/**
 * Langauges controller Home page
 */
class LanguageController extends AdminController {
	const PAGE_SIZE = 50;
	const PAGE_SIZE_LARGE = 500;
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_language_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Languages Manager'));
		$this->title[] = at('Languages Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		// Check Access
		checkAccessThrowException('op_language_view');
	
		$totalStringsInSource = SourceMessage::model()->count();
		$model = new Language('search');
        $this->render('index', array( 'totalStringsInSource' => $totalStringsInSource, 'model' => $model ));
    }

	/**
	 * Create new string
	 */
	public function actionAddString() {
		// Check Access
		checkAccessThrowException('op_language_add_strings');
		
		$model = new SourceMessage;
		
		if( isset( $_POST['SourceMessage'] ) ) {
			$model->attributes = $_POST['SourceMessage'];
			if( $model->save() ) {
				Language::model()->syncAllLanguages();
				
				fok(at('String Created.'));
				alog(at("Added New String '{name}'", array('{name}' => $model->message)));
				$this->redirect(array('language/index'));
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New String'));
		$this->title[] = at('Creating New String');
		finfo(at('Strings will be added to source table then the languages will be synced to have that newly created string included in each language.'));
		
		// Display form
		$this->render('string_form', array( 'model' => $model ));
	}
	
	/**
	 * Get list of source message category names
	 *
	 */
	public function actionGetCategoryNames($term) {
		$res = array();

		if ($term) {
            $command =Yii::app()->db->createCommand("SELECT DISTINCT category FROM source_message WHERE category LIKE :name ORDER BY category ASC LIMIT 20");
	        $command->bindValue(":name", '%'.$term.'%', PDO::PARAM_STR);
	        $res =$command->queryColumn();
        }

		echoJson($res);
	}

	/**
	 * Add a new language
	 */
	public function actionCreate() {		
		// Check Access
		checkAccessThrowException('op_language_create');
		
		$model = new Language;
		
		if( isset( $_POST['Language'] ) ) {
			$model->attributes = $_POST['Language'];
			if( $model->save() ) {
				// Sync
				$total = $model->SyncLanguageStrings();
				
				fok(at('Language Created.'));
				alog(at("Created Language '{name}'.", array('{name}' => $model->name)));
				$this->redirect(array('language/index'));
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New Language'));
		$this->title[] = at('Creating New Language');
		
		// Display form
		$this->render('language_form', array( 'model' => $model ));
	}
	
	/**
	 * Edit language
	 */
	public function actionUpdate() {	
		// Check Access
		checkAccessThrowException('op_language_edit');
		
		if( isset($_GET['id']) && ( $model = Language::model()->findByPk($_GET['id']) ) ) {		
			if( isset( $_POST['Language'] ) ) {
				$model->attributes = $_POST['Language'];
				if( $model->save() ) {
					// Sync
					$total = $model->SyncLanguageStrings();	
					
					fok(at('Language Updated.'));
					alog(at("Updated Language '{name}'.", array('{name}' => $model->name)));
					$this->redirect(array('language/index'));
				}
			}
		
			// Add Breadcrumb
			$this->addBreadCrumb(at('Updating Language'));
			$this->title[] = at('Updating Language');
		
			// Display form
			$this->render('language_form', array( 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('language/index'));
		}
	}
	
	/**
	 * Delete language action
	 */
	public function actionDelete() {
		// Check Access
		checkAccessThrowException('op_language_delete');
		
		if( isset($_GET['id']) && ( $model = Language::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted Language '{name}'.", array('{name}' => $model->name)));
					
			// Make sure language is not source
			if($model->is_source) {
				ferror(at('You can not delete the source language.'));
				$this->redirect(array('language/index'));
			}
			
			// Make sure its not public
			if($model->is_public) {
				ferror(at('You can not delete a public accessible language'));
				$this->redirect(array('language/index'));
			}		
					
			$model->delete();
			
			fok(at('Language Deleted.'));
			$this->redirect(array('language/index'));
		} else {
			$this->redirect(array('language/index'));
		}
	}
	
	/**
	 * Sync language strings
	 * Copies missing source strings to message table
	 */
	public function actionSync($id) {
		// Check Access
		checkAccessThrowException('op_language_sync_messages');
		if(( $model = Language::model()->findByPk($id) ) ) {	
			$total = $model->SyncLanguageStrings();				
				
			alog(at("Synced Language '{name}'.", array('{name}' => $model->name)));		
			fok(at('Language Synced. Total {n} new stings added.', array('{n}' => $total)));
			$this->redirect(array('language/index'));
		} else {
			$this->redirect(array('language/index'));
		}
	}
	
	/**
	 * Sync language strings
	 * Copies missing source strings to message table
	 */
	public function actionSyncAll() {
		// Check Access
		checkAccessThrowException('op_language_sync_messages');
		Language::model()->syncAllLanguages();
		alog(at("Synced All Languages"));		
		fok(at('Synced All Languages'));
		$this->redirect(array('language/index'));
	}
	
	/**
	 * Export language
	 */
	public function actionExport($id) {
		// Check access
		checkAccessThrowException('op_language_export_language');
		// Check if it exists
		$model = Language::model()->findByPk($id);
		if( !$model ) {
			ferror(at('That language was not found.'));
			$this->redirect(array('index'));
		}
		
		// Export setting categories first
		$xml = new ClassXML();
		$xml->newXMLDocument();
		$xml->addElement( 'language_export' );
		
		// Grab categories
		$xml->addElementAsRecord( 'language_export', 'language_row', array('name' => $model->name, 'abbr' => $model->abbr) );
		
		// Grab Messages
		$messages = Yii::app()->db->createCommand()
					->select('m.*, s.message, s.category')
					->from('message m')
					->join('source_message s', 's.id=m.id')
					->where('language_id=:id', array(':id'=>$id))->queryAll();
		$xml->addElement( 'messages_export', 'language_export' );
		foreach($messages as $message) {
			$data = array('category' => $message['category'], 'orig' => $message['message'], 'translation' => $message['translation']);
			$xml->addElementAsRecord( 'messages_export', 'message_row', $data );
		}
					
		$contents = $xml->fetchDocument();
		$name = sprintf('language_export_%s_%s', $model->abbr, date('y_m_d'));
		
		// Download
		downloadAs($name, trim($contents), 'xml');
	}
	
	/**
	 * Import language
	 */
	public function actionImport() {
		// Check access
		checkAccessThrowException('op_language_import_language');
		
		$file = CUploadedFile::getInstanceByName('file');
		$update = getPostParam('update', 0);
		
		// Did we upload anything?
		if(!$file || !$file->getTempName()) {	
			ferror(at('File was not uploaded properly.'));
			$this->redirect(array('language/index'));
		}
		
		// Make sure it's an xml file
		if($file->getType() != 'text/xml') {
			ferror(at('You must upload an XML file.'));
			$this->redirect(array('language/index'));
		}
		
		// Make file has contents
		if(!$file->getSize()) {
			ferror(at('File Uploaded is empty.'));
			$this->redirect(array('language/index'));
		}
		
		// Grab data from file
		$xml = new ClassXML();
		$xml->loadXML( file_get_contents($file->getTempName()) );
		
		// Check to see if it has language details
		foreach( $xml->fetchElements('language_row') as $lang ) {
			// Grab first language
			$langData = $xml->fetchElementsFromRecord($lang);
			break;
		}
		
		// Make sure we have data
		if(!count($langData)) {
			ferror(at('Could not locate language data.'));
			$this->redirect(array('language/index'));
		}
		
		// See if language data missing the name and short form
		if(!isset($langData['name']) || !isset($langData['abbr'])) {
			ferror(at('Language data missing name or abbreviation.'));
			$this->redirect(array('language/index'));
		}
		
		$langName = $langData['name'];
		$langAbbr = $langData['abbr'];
		$langId = null;
		
		// Check if that language exists
		$langModel = Language::model()->find('abbr=:abbr', array(':abbr' => $langAbbr));
		
		// If we have the model then set the id
		if($langModel) {
			$langId = $langModel->id;
		}
		
		// Grab the strings
		$stringsToImport = array();
		foreach( $xml->fetchElements('message_row') as $string ) {
			// Grab first language
			$stringData = $xml->fetchElementsFromRecord($string);
			$stringsToImport[] = $stringData;
		}
		
		// Make sure we have strings
		if(!count($stringsToImport)) {
			ferror(at('Could not locate any strings to import.'));
			$this->redirect(array('language/index'));
		}
		
		// Do we need to create a new language?
		if(!$langModel) {
			// Create new language
			$newLang = new Language;
			$newLang->name = $langName;
			$newLang->abbr = $langAbbr;
			if(!$newLang->save()) {
				ferror(at('Could not save the new language.'));
				$this->redirect(array('language/index'));
			}
			$langId = $newLang->id;
		}
		
		$imported = 0;
		$updated = 0;
		$skipped = 0;
		
		// Run each string and check if the one exists in the current language if it does and we have the update then update
		// otherwise skip
		foreach($stringsToImport as $r) {
			// Get orig id if exists if not create orig
			$orig = SourceMessage::model()->find('category=:category AND message=:message', array(':category' => $r['category'], ':message' => $r['orig']));
			if($orig) {
				// It exists so we have the original message id
				$origId = $orig->id;
			} else {
				// It does not exists create and get newly created id
				$newSource = new SourceMessage;
				$newSource->category = $r['category'];
				$newSource->message = $r['orig'];
				$newSource->save(false);
				$origId = $newSource->id;
			}
			
			// Now that we have the original id check if we need to update or create
			$exists = Message::model()->find('id=:id AND language_id=:lang', array(':id' => $origId, ':lang' => $langId));
			if($exists) {
				if($update) {
					// Exists and update
					$exists->translation = $r['translation'];
					$exists->update();
					$updated++;
				} else {
					// Exists do not update
					$skipped++;
				}
			} else {
				// Does not exist create
				$newMessage = new Message;
				$newMessage->id = $origId;
				$newMessage->language = $langAbbr;
				$newMessage->language_id = $langId;
				$newMessage->translation = $r['translation'];
				$newMessage->save(false);
				$imported++;
			}
		}
		
		// Log and save flash message
		if($langModel) {
			alog(at("Update Language '{name}'", array('{name}' => $langName)));
			fok(at('Language Updated. {i} Strings Imported, {u} Strings Updated, {s} Strings Skipped.', array('{i}' => $imported, '{u}' => $updated, '{s}' => $skipped)));
		} else {
			alog(at("Imported New Language '{name}'", array('{name}' => $langName)));
			fok(at("New Language Created '{name}'. <b>{i}</b> Strings Imported, <b>{u}</b> Strings Updated, <b>{s}</b> Strings Skipped.", array('{name}' => $langName, '{i}' => $imported, '{u}' => $updated, '{s}' => $skipped)));
		}
		
		$this->redirect(array('language/index'));
	}
    
    /**
	 * Translation required strings
	 */
	public function actionTranslateNeeded($id) {
		// Check Access
		checkAccessThrowException('op_language_translate');
		
		// Check if it exists
		$model = Language::model()->findByPk($id);
		if( !$model ) {
			ferror(at('That language was not found.'));
			$this->redirect(array('index'));
		}
		
		// Did we submit?
		if( isset($_POST['submit']) && $_POST['submit'] ) {
			// Update the strings
			if( isset($_POST['strings']) && count($_POST['strings']) ) {
				foreach( $_POST['strings'] as $stringid => $stringvalue ) {
					// Update each one
					Message::model()->updateAll(array('translation'=>$stringvalue), 'language_id=:lang AND id=:id', array(':id' => $stringid, ':lang'=>$id));
				}
				
				fok(at('Strings Updated.'));
			}
		}
		
		$ids = Language::model()->getStringNotTranslated( $id );
		
		// Grab the language data
		$criteria = new CDbCriteria;
		$criteria->condition = 'language_id=:lang';
		$criteria->params = array(":lang"=>$id);
		
		$criteria->addInCondition('id', $ids);
		
		$count = Message::model()->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		$sort = new CSort('Message');
		$sort->defaultOrder = 'id ASC';
		$sort->applyOrder($criteria);

		$sort->attributes = array(
		        'id'=>'id',
		        'translation'=>'translation',
		);
		
		$strings = Message::model()->findAll($criteria);
		$dataProvider=new CActiveDataProvider('Message', array(
			'criteria' => $criteria,
			'pagination' => $pages
		));

		$this->addBreadCrumb(at('Translate'));
		$this->title[] = at('Translate');
		
		$this->render('strings', array( 'dataProvider' => $dataProvider, 'id' => $id, 'strings'=>$strings, 'count'=>$count, 'pages'=>$pages, 'sort'=>$sort ));
	}

	/**
	 * 
	 */
	public function actionView($id, $term=null) {
		// Check Access
		checkAccessThrowException('op_language_translate');
		
		// Check if it exists
		$model = Language::model()->findByPk($id);
		if( !$model ) {
			ferror(at('That language was not found.'));
			$this->redirect(array('index'));
		}
		
		// Did we submit?
		if( isset($_POST['submit']) && $_POST['submit'] ) {
			// Update the strings
			if( isset($_POST['strings']) && count($_POST['strings']) ) {
				foreach( $_POST['strings'] as $stringid => $stringvalue ) {
					// Update each one
					Message::model()->updateAll(array('translation'=>$stringvalue), 'language_id=:lang AND id=:id', array(':id' => $stringid, ':lang'=>$id));
				}
				
				fok(at('Strings Updated.'));
			}
		}
		
		// Grab the language data
		$criteria = new CDbCriteria;
		$criteria->with = array('source');
		$criteria->condition = 'language_id=:lang';
		$criteria->params = array(":lang"=>$id);
		
		// Did we search for a string
		if($term) {
			$criteria->addSearchCondition('translation', $term);
			//$criteria->addSearchCondition('source.message', $term, true, 'OR');
		}
		
		$count = Message::model()->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = self::PAGE_SIZE;
		
		$pages->applyLimit($criteria);
		
		$sort = new CSort('Message');
		$sort->defaultOrder = 't.id ASC';
		$sort->applyOrder($criteria);

		$sort->attributes = array(
		        'id'=>'id',
		        'translation'=>'translation',
		);
		
		$strings = Message::model()->findAll($criteria);
		
		$dataProvider=new CActiveDataProvider('Message', array(
			'criteria' => $criteria,
			'pagination' => $pages
		));
		
		$this->addBreadCrumb(at('Translate'));
		$this->title[] = at('Translate');
		
		$this->render('strings', array( 'dataProvider' => $dataProvider, 'id' => $id, 'strings'=>$strings, 'count'=>$count, 'pages'=>$pages, 'sort'=>$sort ));
	}
	
	/**
	 * Revert a string to it's original form
	 */
	public function actionRevert() {
		// Check Access
		checkAccessThrowException('op_language_translate');
		
		$id = getRParam('id', 0);
		$string = getRParam('string', 0);
		
		// Check if it exists
		$model = Language::model()->findByPk($id);
		if( !$model ) {
			ferror(at('That language was not found.'));
			$this->redirect(array('index'));
		}
		
		// Grab the string and source
		$source = SourceMessage::model()->findByPk($string);
		$stringdata = Message::model()->find('language_id=:lang AND id=:id', array( ':id' => $string,  ':lang'=>$id));
		
		if( ( !$source || !$stringdata ) ) {
			ferror(at('That language string was not found.'));
			$this->redirect(array('index'));
		}
		
		// Update the stringdata based on the soruce
		Message::model()->updateAll(array('translation'=>$source->message), 'language_id=:lang AND id=:id', array( ':id' => $string,  ':lang'=>$id));
		
		fok(at('String Reverted.'));
		$this->redirect(array('language/view', 'id'=>$id));
	}
}