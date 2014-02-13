<?php
/**
 * help topic controller Home page
 */
class HelptopicsController extends AdminController {	
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_helptopics_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Help Topics Manager'));
		$this->title[] = at('Help Topics Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		$model = new HelpTopic('search');
        $this->render('index', array( 'model' => $model ) );
    }

	/**
	 * Add a new help topic action
	 */
	public function actionCreate()
	{		
		// Check Access
		checkAccessThrowException('op_helptopics_add');
		
		$model = new HelpTopic;
		
		if( isset( $_POST['HelpTopic'] ) ) {
			$model->attributes = $_POST['HelpTopic'];
			if( isset( $_POST['submit'] ) ) {
				if( $model->save() ) {
					fok(at('Help Topic Created.'));
					alog(at("Created Help Topic '{name}'.", array('{name}' => $model->name)));
					$this->redirect(array('helptopics/index'));
				}
			} else if( isset( $_POST['preview'] ) )  {
				$model->attributes = $_POST['HelpTopic'];
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New Help Topic'));
		$this->title[] = at('Creating New Help Topic');
		
		// Display form
		$this->render('form', array( 'model' => $model ));
	}
	
	/**
	 * Edit help topic action
	 */
	public function actionUpdate()
	{	
		// Check Access
		checkAccessThrowException('op_helptopics_edit');
		
		if( isset($_GET['id']) && ( $model = HelpTopic::model()->findByPk($_GET['id']) ) ) {		
			if( isset( $_POST['HelpTopic'] ) ) {
				$model->attributes = $_POST['HelpTopic'];
				if( isset( $_POST['submit'] ) ) {
					if( $model->save() ) {
						fok(at('Help Topic Updated.'));
						alog(at("Updated Help Topic '{name}'.", array('{name}' => $model->name)));
						$this->redirect(array('helptopics/index'));
					}
				} else if( isset( $_POST['preview'] ) ) {
					$model->attributes = $_POST['HelpTopic'];
				}
			}
		
			// Add Breadcrumb
			$this->addBreadCrumb(at('Updating Help Topic'));
			$this->title[] = at('Updating Help Topic');
		
			// Display form
			$this->render('form', array( 'model' => $model ));
		}
		else
		{
			ferror(at('Could not find that ID.'));
			$this->redirect(array('helptopics/index'));
		}
	}
	
	/**
	 * view help topic action
	 */
	public function actionView()
	{
		// Check Access
		checkAccessThrowException('op_helptopics_view');
		
		if( isset($_GET['id']) && ( $model = HelpTopic::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Viewed Help Topic '{name}'.", array('{name}' => $model->name)));
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing Help Topic'));
			$this->title[] = at('Viewing Help Topic "{name}"', array('{name}' => $model->name));

			// Display form
			$this->render('view', array( 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('helptopics/index'));
		}
	}
	
	/**
	 * Delete help topic action
	 */
	public function actionDelete()
	{
		// Check Access
		checkAccessThrowException('op_helptopics_delete');
		
		if( isset($_GET['id']) && ( $model = HelpTopic::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted Help Topic '{name}'.", array('{name}' => $model->name)));
					
			$model->delete();
			
			fok(at('Help Topic Deleted.'));
			$this->redirect(array('helptopics/index'));
		} else {
			$this->redirect(array('helptopics/index'));
		}
	}
}