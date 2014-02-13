<?php
/**
 * email template controller Home page
 */
class EmailtemplateController extends AdminController {	
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_emailtemplate_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Email Templates Manager'));
		$this->title[] = at('Email Templates Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		$model = new EmailTemplate('search');
        $this->render('index', array( 'model' => $model ) );
    }

	/**
	 * Add a new template action
	 */
	public function actionCreate()
	{		
		// Check Access
		checkAccessThrowException('op_emailtemplate_add');
		
		$model = new EmailTemplate;
		
		if( isset( $_POST['EmailTemplate'] ) ) {
			$model->attributes = $_POST['EmailTemplate'];
			if( isset( $_POST['submit'] ) ) {
				if( $model->save() ) {
					fok(at('Email Template Created.'));
					alog(at("Created Email Template '{name}'.", array('{name}' => $model->title)));
					$this->redirect(array('emailtemplate/index'));
				}
			} else if( isset( $_POST['preview'] ) )  {
				$model->attributes = $_POST['EmailTemplate'];
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New Email Template'));
		$this->title[] = at('Creating New Email Template');
		
		// Display form
		$this->render('form', array( 'model' => $model ));
	}
	
	/**
	 * Edit Email Template action
	 */
	public function actionUpdate()
	{	
		// Check Access
		checkAccessThrowException('op_emailtemplate_edit');
		
		if( isset($_GET['id']) && ( $model = EmailTemplate::model()->findByPk($_GET['id']) ) ) {		
			if( isset( $_POST['EmailTemplate'] ) ) {
				$model->attributes = $_POST['EmailTemplate'];
				if( isset( $_POST['submit'] ) ) {
					if( $model->save() ) {
						fok(at('Email Template Updated.'));
						alog(at("Updated Email Template '{name}'.", array('{name}' => $model->title)));
						$this->redirect(array('emailtemplate/index'));
					}
				} else if( isset( $_POST['preview'] ) ) {
					$model->attributes = $_POST['EmailTemplate'];
				}
			}
		
			// Add Breadcrumb
			$this->addBreadCrumb(at('Updating Email Template'));
			$this->title[] = at('Updating Email Template');
		
			// Display form
			$this->render('form', array( 'model' => $model ));
		}
		else
		{
			ferror(at('Could not find that ID.'));
			$this->redirect(array('emailtemplate/index'));
		}
	}
	
	/**
	 * view Email Template action
	 */
	public function actionView()
	{
		// Check Access
		checkAccessThrowException('op_emailtemplate_view');
		
		if( isset($_GET['id']) && ( $model = EmailTemplate::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Viewed Email Template '{name}'.", array('{name}' => $model->title)));
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing Email Template'));
			$this->title[] = at('Viewing Email Template "{name}"', array('{name}' => $model->title));

			// Display email
			$this->render('view', array( 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('emailtemplate/index'));
		}
	}
	
	/**
	 * Delete Email Template action
	 */
	public function actionDelete()
	{
		// Check Access
		checkAccessThrowException('op_emailtemplate_delete');
		
		if( isset($_GET['id']) && ( $model = EmailTemplate::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted Email Template '{name}'.", array('{name}' => $model->title)));
					
			$model->delete();
			
			fok(at('Email Template Deleted.'));
			$this->redirect(array('emailtemplate/index'));
		} else {
			$this->redirect(array('emailtemplate/index'));
		}
	}
}