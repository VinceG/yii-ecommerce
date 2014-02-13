<?php
/**
 * States Controller
 */
class StateController extends AdminController {	
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_usstates_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('US States Manager'));
		$this->title[] = at('US States Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		$state = new USState('search');
        $this->render('index', array( 'model' => $state ) );
    }

	/**
	 * Add a new state
	 */
	public function actionCreate()
	{		
		// Check Access
		checkAccessThrowException('op_usstates_addpages');
		
		$model = new USState;
		
		if( isset( $_POST['USState'] ) ) {
			$model->attributes = $_POST['USState'];
			if( $model->save() ) {
				fok(at('State Created.'));
				alog(at("Created State Record '{name}'.", array('{name}' => $model->name)));
				$this->redirect(array('state/index'));
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New State'));
		$this->title[] = at('Creating New State Record');
		
		// Display form
		$this->render('form', array( 'model' => $model ));
	}
	
	/**
	 * Edit state action
	 */
	public function actionUpdate()
	{	
		// Check Access
		checkAccessThrowException('op_usstates_editpages');
		
		if( isset($_GET['id']) && ( $model = USState::model()->findByPk($_GET['id']) ) ) {		
			if( isset( $_POST['USState'] ) ) {
				$model->attributes = $_POST['USState'];
				if( $model->save() ) {
					fok(at('State Updated.'));
					alog(at("Updated State '{name}'.", array('{name}' => $model->name)));
					$this->redirect(array('state/index'));
				}
			}
		
			// Add Breadcrumb
			$this->addBreadCrumb(at('Updating State'));
			$this->title[] = at('Updating State Record');
		
			// Display form
			$this->render('form', array( 'model' => $model ));
		}
		else
		{
			ferror(at('Could not find that ID.'));
			$this->redirect(array('state/index'));
		}
	}
	
	/**
	 * view state action
	 */
	public function actionView()
	{
		// Check Access
		checkAccessThrowException('op_usstates_viewpages');
		
		if( isset($_GET['id']) && ( $model = USState::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Viewed State Record '{name}'.", array('{name}' => $model->name)));
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing State'));
			$this->title[] = at('Viewing State Record "{name}"', array('{name}' => $model->name));

			// Display form
			$this->render('view', array( 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('state/index'));
		}
	}
	
	/**
	 * Delete state action
	 */
	public function actionDelete()
	{
		// Check Access
		checkAccessThrowException('op_usstates_deletepages');
		
		if( isset($_GET['id']) && ( $model = USState::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted State Record '{name}'.", array('{name}' => $model->name)));
					
			$model->delete();
			
			fok(at('State Record Deleted.'));
			$this->redirect(array('state/index'));
		} else {
			$this->redirect(array('state/index'));
		}
	}
}