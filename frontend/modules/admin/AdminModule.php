<?php
/**
 * Admin module class
 */
class AdminModule extends WebModule {
    
	/**
	 * Default admin theme
	 */
	public $theme = 'admin/dulcet';

	/**
     * Module constructor - Builds the initial module data
     *
     * @author vadim
     */
    public function init() {
	
		Yii::app()->theme = $this->theme;
		
		// Set theme url
        Yii::app()->themeManager->setBaseUrl( Yii::app()->theme->baseUrl );
        Yii::app()->themeManager->setBasePath( Yii::app()->theme->basePath );
	
		// Set error handler
		Yii::app()->errorHandler->errorAction = 'admin/error/error';
	
        /* Make sure we run the master module init function */
        parent::init();
    }
}