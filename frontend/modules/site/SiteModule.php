<?php
/**
 * Site module class
 */
class SiteModule extends WebModule {
	/**
     * Module constructor - Builds the initial module data
     *
     * @author vadim
     */
    public function init() {
		// Set error handler
		Yii::app()->errorHandler->errorAction = 'site/error/error';
	
        /* Make sure we run the master module init function */
        parent::init();
    }
}