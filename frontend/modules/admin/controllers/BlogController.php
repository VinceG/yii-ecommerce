<?php
/**
 * blog controller Home page
 */
class BlogController extends AdminController {	
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_blog_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Blog Manager'));
		$this->title[] = at('Blog Posts Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		$model = new BlogPost('search');
        $this->render('index', array( 'model' => $model ) );
    }

	/**
	 * Add a new page action
	 */
	public function actionCreate()
	{		
		// Check Access
		checkAccessThrowException('op_blog_addposts');
		
		$model = new BlogPost;
		
		if( isset( $_POST['BlogPost'] ) ) {
			$model->attributes = $_POST['BlogPost'];
			if( isset( $_POST['submit'] ) ) {
				if( $model->save() ) {
					fok(at('Page Created.'));
					alog(at("Created Blog Post '{name}'.", array('{name}' => $model->title)));
					$this->redirect(array('blog/index'));
				}
			} else if( isset( $_POST['preview'] ) )  {
				$model->attributes = $_POST['BlogPost'];
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
		$this->addBreadCrumb(at('Creating New Post'));
		$this->title[] = at('Creating New Post');
		
		// Display form
		$this->render('form', array( 'roles' => $_roles, 'model' => $model ));
	}
	
	/**
	 * Edit page action
	 */
	public function actionUpdate()
	{	
		// Check Access
		checkAccessThrowException('op_blog_editposts');
		
		if( isset($_GET['id']) && ( $model = BlogPost::model()->findByPk($_GET['id']) ) ) {		
			if( isset( $_POST['BlogPost'] ) ) {
				$model->attributes = $_POST['BlogPost'];
				if( isset( $_POST['submit'] ) ) {
					if( $model->save() ) {
						fok(at('Page Updated.'));
						alog(at("Updated Blog Post '{name}'.", array('{name}' => $model->title)));
						$this->redirect(array('blog/index'));
					}
				} else if( isset( $_POST['preview'] ) ) {
					$model->attributes = $_POST['BlogPost'];
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
			$this->addBreadCrumb(at('Updating Blog Post'));
			$this->title[] = at('Updating Blog Post');
		
			// Display form
			$this->render('form', array( 'roles' => $_roles, 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('blog/index'));
		}
	}
	
	/**
	 * view page action
	 */
	public function actionView()
	{
		// Check Access
		checkAccessThrowException('op_blog_viewposts');
		
		if( isset($_GET['id']) && ( $model = BlogPost::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Viewed Blog Post '{name}'.", array('{name}' => $model->title)));
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing Blog Post'));
			$this->title[] = at('Viewing Blog Post "{name}"', array('{name}' => $model->title));

			// Display form
			$this->render('view', array( 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('blog/index'));
		}
	}
	
	/**
	 * Delete page action
	 */
	public function actionDelete()
	{
		// Check Access
		checkAccessThrowException('op_blog_deleteposts');
		
		if( isset($_GET['id']) && ( $model = BlogPost::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted Blog Post '{name}'.", array('{name}' => $model->title)));
					
			$model->delete();
			
			fok(at('Page Deleted.'));
			$this->redirect(array('blog/index'));
		} else {
			$this->redirect(array('blog/index'));
		}
	}
}