<?php
/**
 * Theme controller Home page
 */
class ThemesController extends AdminController {
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_theme_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Theme Manager'));
		$this->title[] = at('Theme Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		// Check Access
		checkAccessThrowException('op_theme_view');
	
		// make sure we show them that they need to have the themes directory writeable
		if(!is_writeable(Yii::getPathOfAlias('themes'))) {
			ferror(at('Error! You must set write permissions to the themes directory.'));
		}
		
		$model = new Theme('search');
        $this->render('index', array( 'model' => $model ));
    }

	/**
	 * Add a new theme
	 */
	public function actionCreate() {		
		// Check Access
		checkAccessThrowException('op_theme_create');
		
		$model = new Theme;
		
		if( isset( $_POST['Theme'] ) ) {
			$model->attributes = $_POST['Theme'];
			if( $model->save() ) {
				fok(at('Theme Created.'));
				alog(at("Created Theme '{name}'.", array('{name}' => $model->name)));
				$this->redirect(array('themes/index'));
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New Theme'));
		$this->title[] = at('Creating New Theme');
		
		// Display form
		$this->render('theme_form', array( 'model' => $model ));
	}
	
	/**
	 * Edit theme
	 */
	public function actionUpdate() {	
		// Check Access
		checkAccessThrowException('op_theme_edit');
		
		if( isset($_GET['id']) && ( $model = Theme::model()->findByPk($_GET['id']) ) ) {		
			if( isset( $_POST['Theme'] ) ) {
				$model->attributes = $_POST['Theme'];
				if( $model->save() ) {
					fok(at('Theme Updated.'));
					alog(at("Updated Theme '{name}'.", array('{name}' => $model->name)));
					$this->redirect(array('themes/index'));
				}
			}
		
			// Add Breadcrumb
			$this->addBreadCrumb(at('Updating Theme'));
			$this->title[] = at('Updating Theme');
		
			// Display form
			$this->render('theme_form', array( 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('themes/index'));
		}
	}
	
	/**
	 * View theme
	 */
	public function actionView($id) {
		// Check Access
		checkAccessThrowException('op_theme_view');
		$model = Theme::model()->findByPk($id);
		// Make sure it exists
		if(!$model) {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('themes/index'));
		}
		
		// Render theme view
		$this->render('theme_view', array('model' => $model));
	}
	
	/**
	 * Return ajax theme file content
	 */
	public function actionGetAjaxThemeFile($fileId) {
		// Access check
		if(!checkAccess('op_theme_file_edit')) {
			echoJson(array('error' => at('Sorry, You are not allowed to edit theme files.')));
		}
		
		// Check to make sure the theme file exists
		$themeFile = ThemeFile::model()->findByPk($fileId);
		if(!$themeFile) {
			echoJson(array('error' => at('Sorry, We could not located that file.')));
		}
		
		// Return the contents
		$html = $this->renderPartial('_theme_editor', array('file' => $themeFile), true);
		echoJson(array('html' => $html, 'mode' => 'text/php'));
	}
	
	/**
	 * set file content
	 */
	public function actionAjaxSetThemeFileContent() {
		// Init
		$fileId = getPostParam('fileId');
		$content = getPostParam('content');
		
		// Access check
		if(!checkAccess('op_theme_file_edit')) {
			echoJson(array('error' => at('Sorry, You are not allowed to edit theme files.')));
		}
		
		// Check to make sure the theme file exists
		$themeFile = ThemeFile::model()->with(array('theme'))->findByPk($fileId);
		if(!$themeFile) {
			echoJson(array('error' => at('Sorry, We could not located that file.')));
		}
		
		// Update theme content
		$themeFile->content = $content;
		$themeFile->update();
		
		// Log
		alog(at("Updated Theme '{name}', File {file}", array('{name}' => $themeFile->theme->name, '{file}' => $themeFile->file_location)));
		
		// Sync theme to save changes
		$themeFile->theme->syncTheme();
		
		echoJson(array('html' => at('Theme File Saved!')));
	}
	
	/**
	 * Delete theme action
	 */
	public function actionDelete() {
		// Check Access
		checkAccessThrowException('op_theme_delete');
		
		if( isset($_GET['id']) && ( $model = Theme::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted Theme '{name}'.", array('{name}' => $model->name)));
			
			// Make sure its not default
			if($model->dirname == getParam('default_theme')) {
				if(isAjax()) {
					echoError(at('You can not delete the default theme.'));
				} else {
					echoError(at('You can not delete the default theme.'));
				}
			}
			
			// Make sure its not public
			if($model->is_active) {
				if(isAjax()) {
					echoError(at('You can not delete an active theme.'));
				} else {
					echoError(at('You can not delete an active theme.'));
				}
			}		
					
			$model->delete();
			
			fok(at('Theme Deleted.'));
			$this->redirect(array('themes/index'));
		} else {
			$this->redirect(array('themes/index'));
		}
	}
	
	/**
	 * Sync theme files
	 */
	public function actionSyncAll() {
		// Check Access
		checkAccessThrowException('op_theme_sync');
		Theme::model()->syncAllThemes();
		alog(at("Synced All Themes"));		
		fok(at('Synced All Themes'));
		$this->redirect(array('themes/index'));
	}
	
	/**
	 * Sync theme
	 */
	public function actionSync($id) {
		// Check Access
		checkAccessThrowException('op_theme_sync');
		if(( $model = Theme::model()->findByPk($id) ) ) {	
			$total = $model->SyncTheme();				
				
			alog(at("Synced Theme '{name}'.", array('{name}' => $model->name)));		
			fok(at('Theme Synced. Total {n} files synced.', array('{n}' => $total)));
			$this->redirect(array('themes/index'));
		} else {
			$this->redirect(array('themes/index'));
		}
	}
}