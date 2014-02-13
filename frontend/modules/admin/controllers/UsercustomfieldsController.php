<?php
/**
 * user custom fields controller Home page
 */
class UsercustomfieldsController extends AdminController {	
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_usercustomfields_view');
		// Add Breadcrumb
		$this->addBreadCrumb(at('User Custom Fields'));
		$this->title[] = at('User Custom Fields Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		$model = new UserCustomField('search');
		$model->unsetAttributes();
        if(isset($_GET['UserCustomField'])) {
			$model->attributes=$_GET['UserCustomField'];
		}
        $this->render('index', array( 'model' => $model ) );
    }

	/**
	 * Add a new field action
	 */
	public function actionCreate()
	{		
		// Check Access
		checkAccessThrowException('op_usercustomfields_addposts');
		
		$model = new UserCustomField;
		
		if( isset( $_POST['UserCustomField'] ) ) {
			$model->attributes = $_POST['UserCustomField'];
			if( $model->save() ) {
				fok(at('Field Created.'));
				alog(at("Created Custom Field '{name}'.", array('{name}' => $model->title)));
				$this->redirect(array('usercustomfields/index'));
			}
		}

		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New Field'));
		$this->title[] = at('Creating New Field');
		
		// Display form
		$this->render('form', array( 'model' => $model ));
	}
	
	/**
	 * Edit field action
	 */
	public function actionUpdate()
	{	
		// Check Access
		checkAccessThrowException('op_usercustomfields_editposts');
		
		if( isset($_GET['id']) && ( $model = UserCustomField::model()->findByPk($_GET['id']) ) ) {		
			if( isset( $_POST['UserCustomField'] ) ) {
				$model->attributes = $_POST['UserCustomField'];
				if( $model->save() ) {
					fok(at('Field Updated.'));
					alog(at("Updated Custom Field '{name}'.", array('{name}' => $model->title)));
					$this->redirect(array('usercustomfields/index'));
				}
			}
		
			// Add Breadcrumb
			$this->addBreadCrumb(at('Updating Custom Field'));
			$this->title[] = at('Updating Custom Field');
		
			// Display form
			$this->render('form', array( 'model' => $model ));
		}
		else
		{
			ferror(at('Could not find that ID.'));
			$this->redirect(array('usercustomfields/index'));
		}
	}
	
	/**
	 * view field action
	 */
	public function actionView()
	{
		// Check Access
		checkAccessThrowException('op_usercustomfields_viewposts');
		
		if( isset($_GET['id']) && ( $model = UserCustomField::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Viewed Custom Field '{name}'.", array('{name}' => $model->title)));
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing Custom Field'));
			$this->title[] = at('Viewing Custom Field "{name}"', array('{name}' => $model->title));

			// Display form
			$this->render('view', array( 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('usercustomfields/index'));
		}
	}
	
	/**
	 * Delete field action
	 */
	public function actionDelete()
	{
		// Check Access
		checkAccessThrowException('op_usercustomfields_deleteposts');
		
		if( isset($_GET['id']) && ( $model = UserCustomField::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted Custom Field '{name}'.", array('{name}' => $model->title)));
					
			$model->delete();
			
			fok(at('Field Deleted.'));
			$this->redirect(array('usercustomfields/index'));
		} else {
			$this->redirect(array('usercustomfields/index'));
		}
	}
}