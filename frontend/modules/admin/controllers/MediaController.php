<?php
/**
 * Media controller Home page
 */
class MediaController extends AdminController {
	/**
	 * @var object rackspace files wrapper class
	 */
	public $rackspace;
	
	/**
	 * init
	 */
	public function init()
	{
		// Check Access
		checkAccessThrowException('op_media_view');
		
		// Make sure uploads directory is set
		if(!getParam('uploads_dir')) {
			throw new CHttpException(500, Yii::t('media', 'Sorry, You must set the uploads directory first.'));
		}
		
		parent::init();
		// Add Breadcrumb
		$this->addBreadCrumb(at('Media Manager'));
		$this->title[] = at('Media Manager');
	}
	
	/**
	 * Custom actions for this controller
	 *
	 */
	public function actions() {
	   return array(
	      'elfinder.' => 'application.widgets.elfinder.FinderWidget',
	    );
	}
	
	/**
	 * Index action
	 * Index will show the el finder widget
	 */
    public function actionIndex() {		
        $this->render('index');
    }
}