<?php
/**
 * custom pages controller Home page
 */
class CustompagesController extends AdminController {	
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_custompages_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Custom Pages Manager'));
		$this->title[] = at('Custom Pages Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		$model = new CustomPage('search');
        $this->render('index', array( 'model' => $model ) );
    }

	/**
	 * Add a new page action
	 */
	public function actionCreate()
	{		
		// Check Access
		checkAccessThrowException('op_custompages_addpages');
		
		$model = new CustomPage;
		
		if( isset( $_POST['CustomPage'] ) ) {
			$model->attributes = $_POST['CustomPage'];
			if( isset( $_POST['submit'] ) ) {
				if( $model->save() ) {
					fok(at('Page Created.'));
					alog(at("Created Custom Page '{name}'.", array('{name}' => $model->title)));
					$this->redirect(array('custompages/index'));
				}
			} else if( isset( $_POST['preview'] ) )  {
				$model->attributes = $_POST['CustomPage'];
			}
		}
		
		$roles = AuthItem::model()->findAll(array('order'=>'type DESC, name ASC'));
		$_roles = array();
		if( count($roles) ) {
			foreach($roles as $role) {
				$_roles[ AuthItem::model()->types[ $role->type ] ][ $role->name ] = $role->name;
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New Page'));
		$this->title[] = at('Creating New Page');
		
		// Display form
		$this->render('form', array( 'roles' => $_roles, 'model' => $model ));
	}
	
	/**
	 * Edit page action
	 */
	public function actionUpdate()
	{	
		// Check Access
		checkAccessThrowException('op_custompages_editpages');
		
		if( isset($_GET['id']) && ( $model = CustomPage::model()->findByPk($_GET['id']) ) ) {		
			if( isset( $_POST['CustomPage'] ) ) {
				$model->attributes = $_POST['CustomPage'];
				if( isset( $_POST['submit'] ) ) {
					if( $model->save() ) {
						fok(at('Page Updated.'));
						alog(at("Updated Custom Page '{name}'.", array('{name}' => $model->title)));
						$this->redirect(array('custompages/index'));
					}
				} else if( isset( $_POST['preview'] ) ) {
					$model->attributes = $_POST['CustomPage'];
				}
			}
			
			$roles = AuthItem::model()->findAll(array('order'=>'type DESC, name ASC'));
			$_roles = array();
			if( count($roles) ) {
				foreach($roles as $role) {
					$_roles[ AuthItem::model()->types[ $role->type ] ][ $role->name ] = $role->name;
				}
			}
			
			$model->visible = explode(',', $model->visible);
		
			// Add Breadcrumb
			$this->addBreadCrumb(at('Updating Custom Page'));
			$this->title[] = at('Updating Custom Page');
		
			// Display form
			$this->render('form', array( 'roles' => $_roles, 'model' => $model ));
		}
		else
		{
			ferror(at('Could not find that ID.'));
			$this->redirect(array('custompages/index'));
		}
	}
	
	/**
	 * view page action
	 */
	public function actionView()
	{
		// Check Access
		checkAccessThrowException('op_custompages_viewpages');
		
		if( isset($_GET['id']) && ( $model = CustomPage::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Viewed Custom Page '{name}'.", array('{name}' => $model->title)));
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing Custom Page'));
			$this->title[] = at('Viewing Custom Page "{name}"', array('{name}' => $model->title));

			// Display form
			$this->render('view', array( 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('custompages/index'));
		}
	}
	
	/**
	 * Delete page action
	 */
	public function actionDelete()
	{
		// Check Access
		checkAccessThrowException('op_custompages_deletepages');
		
		if( isset($_GET['id']) && ( $model = CustomPage::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted Custom Page '{name}'.", array('{name}' => $model->title)));
					
			$model->delete();
			
			fok(at('Page Deleted.'));
			$this->redirect(array('custompages/index'));
		} else {
			$this->redirect(array('custompages/index'));
		}
	}
}