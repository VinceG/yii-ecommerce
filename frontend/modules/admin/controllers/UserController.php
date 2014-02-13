<?php

class UserController extends AdminController {
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_users_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Users'));
		$this->title[] = at('Users');
	}
	
	/**
	 * Get list of user names
	 *
	 */
	public function actionGetUserNames($term) {
		$res = array();
		$list = array();
		
		if ($term) {
            $command =Yii::app()->db->createCommand("SELECT id, name FROM user WHERE name LIKE :name ORDER BY name ASC LIMIT 20");
	        $command->bindValue(":name", '%'.$term.'%', PDO::PARAM_STR);
	        $res =$command->queryAll();
        }

		if(count($res)) {
			foreach($res as $row) {
				$list[] = array('label'=>$row['id'], 'value'=>$row['name']);
			}
		}

		echoJson($list);
	}
	
	/**
	 * User manager index
	 */
	public function actionIndex() {
		$model = new User('search');
		$model->unsetAttributes();
        if(isset($_GET['User'])) {
			$model->attributes=$_GET['User'];
		}
		
		$this->render('index', array('model' => $model));
	}
	
	/**
	 * Create user form
	 */
	public function actionCreate() {
		// Check Access
		checkAccessThrowException('op_users_create');
		$model = new User;
		if(isset($_POST['User'])) {
			$model->setAttributes($_POST['User']);
			if($model->save()) {
				
				if(isset($_POST['UserCustomField'])) {
					UserCustomField::model()->processCustomFields($_POST['UserCustomField'], $model->id);
				}
				
				// Loop through the roles and assign them
				$types = array( 'roles', 'tasks', 'operations' );
				$lastID = Yii::app()->db->lastInsertID;
				foreach($types as $type) {
					if( isset($_POST[ $type ]) && count( $_POST[ $type ] ) ) {
						foreach( $_POST[ $type ] as $others ) {						
							// assign if not assigned yet
							if( !Yii::app()->authManager->isAssigned( $others, $lastID ) ) {
								$authItem = Yii::app()->authManager->getAuthItem( $others );
								Yii::app()->authManager->assign( $others, $lastID, $authItem->bizrule, $authItem->data );
							}
						}
					}
				}
				
				fok(at('User Created!'));
				
				// Log Message
				alog(at("Created new user: '{name}'.", array('{name}' => $model->name)));
				
				$this->redirect(array('index'));
			}
		}
		
		$temp = Yii::app()->authManager->getAuthItems();
		$items = array( CAuthItem::TYPE_ROLE => array(), CAuthItem::TYPE_TASK => array(), CAuthItem::TYPE_OPERATION => array() );
		if( count($temp) ) {
			foreach( $temp as $item ) {
				$items[ $item->type ][ $item->name ] = $item->name;
			}
		}
		
		$items_selected = array();
		$items_selected['roles'] = isset($_POST['roles']) ? $_POST['roles'] : '';
		$items_selected['tasks'] = isset($_POST['tasks']) ? $_POST['tasks'] : '';
		$items_selected['operations'] = isset($_POST['operations']) ? $_POST['operations'] : '';
		
		$this->title[] = at('Create User');
		// Add Breadcrumb
		$this->addBreadCrumb(at('Create User'));
		$this->render('form', array('model' => $model, 'items_selected' => $items_selected, 'items' => $items));
	}
	/**
	 * View user
	 */
	public function actionView($id) {
		// Check Access
		checkAccessThrowException('op_users_view');
		
		$model = User::model()->findByPk($id);
		if($model) {
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing User'));
			$this->title[] = at('Viewing User');
			
			// Log Message
			alog(at("Viewed user profile: '{name}'.", array('{name}' => $model->name)));
			
			$this->render('view', array('model' => $model));
		} else {
			throw new CHttpException(404, at('Sorry, That record was not found.'));
		}
	}
	/**
	 * Update user
	 */
	public function actionUpdate($id) {
		// Check Access
		checkAccessThrowException('op_users_update');
		
		$model = User::model()->findByPk($id);
		if($model) {
			if(isset($_POST['User'])) {
				$model->setAttributes($_POST['User']);
				if($model->save()) {
					if(isset($_POST['UserCustomField'])) {
						UserCustomField::model()->processCustomFields($_POST['UserCustomField'], $model->id);
					}
					// Loop through the roles and assign them
					$types = array( 'roles', 'tasks', 'operations' );
					$lastID = $model->id;
					$allitems = Yii::app()->authManager->getAuthItems(null, $lastID);
					
					if( count($allitems) ) {
						foreach( $allitems as $allitem ) {
							Yii::app()->authManager->revoke( $allitem->name, $lastID );
						}
					}
					
					foreach($types as $type) {
						if( isset($_POST[ $type ]) && count( $_POST[ $type ] ) ) {
							foreach( $_POST[ $type ] as $others ) {						
								// assign if not assigned yet
								if( !Yii::app()->authManager->isAssigned( $others, $lastID ) ) {
									$authItem = Yii::app()->authManager->getAuthItem( $others );
									Yii::app()->authManager->assign( $others, $lastID, $authItem->bizrule, $authItem->data );
								}
							}
						}
					}
					
					fok(at('User Updated!'));
					
					// Log Message
					alog(at("Updated user: '{name}'.", array('{name}' => $model->name)));
					
					$this->redirect(array('index'));
				}
			}
			
			$temp = Yii::app()->authManager->getAuthItems();
			$items = array( CAuthItem::TYPE_ROLE => array(), CAuthItem::TYPE_TASK => array(), CAuthItem::TYPE_OPERATION => array() );
			if( count($temp) ) {
				foreach( $temp as $item ) {
					$items[ $item->type ][ $item->name ] = $item->name;
				}
			}
			
			// Selected
			$temp_selected = Yii::app()->authManager->getAuthItems(null, $model->id);
			$items_selected = array();
			if( count($temp) ) {
				foreach( $temp_selected as $item_selected ) {
					$items_selected[ $item_selected->type ][ $item_selected->name ] = $item_selected->name;
				}
			}

			// Add Breadcrumb
			$this->addBreadCrumb(at('Update User'));
			$this->title[] = at('Update User');
			$this->render('form', array('model' => $model, 'items_selected' => $items_selected, 'items' => $items));
		} else {
			throw new CHttpException(404, at('Sorry, That record was not found.'));
		}
	}
	/**
	 * Delete user
	 */
	public function actionDelete($id) {
		// Check Access
		checkAccessThrowException('op_users_delete');
		
		$model = User::model()->findByPk($id);
		if($model) {
			// Log Message
			alog(at("Deleted user: '{name}'.", array('{name}' => $model->name)));
			
			$model->delete();
			fok(at('User Deleted!'));
		} else {
			throw new CHttpException(404, at('Sorry, That record was not found.'));
		}
	}
}