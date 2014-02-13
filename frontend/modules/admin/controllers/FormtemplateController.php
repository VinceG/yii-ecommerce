<?php
/**
 * form template controller Home page
 */
class FormtemplateController extends AdminController {	
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_formtemplate_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Form Templates Manager'));
		$this->title[] = at('Form Templates Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		$model = new FormTemplate('search');
        $this->render('index', array( 'model' => $model ) );
    }

	/**
	 * Add a new template action
	 */
	public function actionCreate()
	{		
		// Check Access
		checkAccessThrowException('op_formtemplate_add');
		
		$model = new FormTemplate;
		
		if( isset( $_POST['FormTemplate'] ) ) {
			$model->attributes = $_POST['FormTemplate'];
			if( isset( $_POST['submit'] ) ) {
				if( $model->save() ) {
					fok(at('Form Template Created.'));
					alog(at("Created Form Template '{name}'.", array('{name}' => $model->title)));
					$this->redirect(array('formtemplate/index'));
				}
			} else if( isset( $_POST['preview'] ) )  {
				$model->attributes = $_POST['FormTemplate'];
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New Form Template'));
		$this->title[] = at('Creating New Form Template');
		
		// Display form
		$this->render('form', array( 'model' => $model ));
	}
	
	/**
	 * Edit Form Template action
	 */
	public function actionUpdate()
	{	
		// Check Access
		checkAccessThrowException('op_formtemplate_edit');
		
		if( isset($_GET['id']) && ( $model = FormTemplate::model()->findByPk($_GET['id']) ) ) {		
			if( isset( $_POST['FormTemplate'] ) ) {
				$model->attributes = $_POST['FormTemplate'];
				if( isset( $_POST['submit'] ) ) {
					if( $model->save() ) {
						fok(at('Form Template Updated.'));
						alog(at("Updated Form Template '{name}'.", array('{name}' => $model->title)));
						$this->redirect(array('formtemplate/index'));
					}
				} else if( isset( $_POST['preview'] ) ) {
					$model->attributes = $_POST['FormTemplate'];
				}
			}
		
			// Add Breadcrumb
			$this->addBreadCrumb(at('Updating Form Template'));
			$this->title[] = at('Updating Form Template');
		
			// Display form
			$this->render('form', array( 'model' => $model ));
		}
		else
		{
			ferror(at('Could not find that ID.'));
			$this->redirect(array('formtemplate/index'));
		}
	}
	
	/**
	 * view Form Template action
	 */
	public function actionView()
	{
		// Check Access
		checkAccessThrowException('op_formtemplate_view');
		
		if( isset($_GET['id']) && ( $model = FormTemplate::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Viewed Form Template '{name}'.", array('{name}' => $model->title)));
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing Form Template'));
			$this->title[] = at('Viewing Form Template "{name}"', array('{name}' => $model->title));

			// Display form
			$this->render('view', array( 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('formtemplate/index'));
		}
	}
	
	/**
	 * Delete Form Template action
	 */
	public function actionDelete()
	{
		// Check Access
		checkAccessThrowException('op_formtemplate_delete');
		
		if( isset($_GET['id']) && ( $model = FormTemplate::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted Form Template '{name}'.", array('{name}' => $model->title)));
					
			$model->delete();
			
			fok(at('Form Template Deleted.'));
			$this->redirect(array('formtemplate/index'));
		} else {
			$this->redirect(array('formtemplate/index'));
		}
	}
}