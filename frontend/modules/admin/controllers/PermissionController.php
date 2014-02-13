<?php

class PermissionController extends AdminController {
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_permission_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Permissions Manager'));
		$this->title[] = at('Permissions');
	}
	
	public function actionIndex() {
		// Get Roles
		$roles = new AuthItem('search');
		$roles->unsetAttributes();
        if(isset($_GET['AuthItem'])) {
			$roles->attributes=$_GET['AuthItem'];
		}
		
		$this->render('index', array('roles' => $roles));
	}
	
	/**
	 * Create permission form
	 */
	public function actionCreate($type=null) {
		// Check Access
		checkAccessThrowException('op_permission_create');
		
		$model = new AuthItem;
		if(isset($_POST['AuthItem'])) {
			$model->setAttributes($_POST['AuthItem']);
			if($model->save()) {
				fok(at('Permission Created!'));
				
				// Log Message
				alog(at("New permission created: '{name}'.", array('{name}' => $model->name)));
				
				$this->redirect(array('index'));
			}
		} else {
			if($type!==null) {
				$model->type = $type;
			}
		}
		// Add Breadcrumb
		$this->addBreadCrumb(at('Create Permission'));
		$this->title[] = at('Create Permission');
		$this->render('form', array('model' => $model));
	}
	
	/**
	 * View permission
	 */
	public function actionView($id) {
		// Check Access
		checkAccessThrowException('op_permission_view');
		
		$model = AuthItem::model()->findByPk($id);
		if($model) {
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing Permission'));
			$this->title[] = at('Viewing Permission');
			
			// Log Message
			alog(at("Viewed permission: '{name}'.", array('{name}' => $model->name)));
			
			$this->render('view', array('model' => $model));
		} else {
			throw new CHttpException(404, at('Sorry, That record was not found.'));
		}
	}
	/**
	 * Update permission
	 */
	public function actionUpdate($id) {
		// Check Access
		checkAccessThrowException('op_permission_update');
		
		$model = AuthItem::model()->findByPk($id);
		if($model) {
			if(isset($_POST['AuthItem'])) {
				$old_name = $model->name;
				$model->setAttributes($_POST['AuthItem']);
				if($model->save()) {
					
					// Update parent name and child name in the auth child table
					AuthItemChild::model()->updateAll(array( 'parent' => $model->name ), 'parent=:name', array(':name'=>$old_name));
					AuthItemChild::model()->updateAll(array( 'child' => $model->name ), 'child=:name', array(':name'=>$old_name));	
					AuthAssignment::model()->updateAll(array( 'bizrule' => $model->bizrule, 'data' => $model->data,  'itemname' => $model->name ), 'itemname=:name', array(':name'=>$old_name));
					User::model()->updateAll(array('role'=>$model->name), 'role=:name', array(':name'=>$old_name));
					
					fok(at('Permission Updated!'));
					
					// Log Message
					alog(at("Updated permission: '{name}'.", array('{name}' => $model->name)));
					
					$this->redirect(array('index'));
				}
			}

			// Add Breadcrumb
			$this->addBreadCrumb(at('Update Permission'));
			$this->title[] = at('Update Permission');
			$this->render('form', array('model' => $model));
		} else {
			throw new CHttpException(404, at('Sorry, That record was not found.'));
		}
	}
	/**
	 * Delete permission
	 */
	public function actionDelete($id) {
		// Check Access
		checkAccessThrowException('op_permission_delete');
		
		$model = AuthItem::model()->findByPk($id);
		if($model) {
			// Remove relationships between children
			$children = Yii::app()->authManager->getItemChildren($id);
			if( count( $children ) ) {
				foreach($children as $child) {
					Yii::app()->authManager->removeItemChild($model->name, $child->name);
				}
			}

			// Delete auth item
			Yii::app()->authManager->removeAuthItem( $model->name );
			
			// Log Message
			alog(at("Deleted permission: '{name}'.", array('{name}' => $model->name)));
			
			fok(at('Permission Deleted!'));
		} else {
			throw new CHttpException(404, at('Sorry, That record was not found.'));
		}
	}
	
	/**
	 * adding auth item child relationships
	 */
	public function actionAddItemChild() {
		// Check Access
		checkAccessThrowException('op_permission_add_item_child');
		
		$model = new AuthItemChild;

		$roles = AuthItem::model()->findAll(array('order'=>'type DESC, name ASC'));
		$_roles = array();
		if( count($roles) ) {
			foreach($roles as $role) {
				$_roles[ AuthItem::model()->types[ $role->type ] ][ $role->name ] = $role->description . ' (' . $role->name . ')';
			}
		}

		// Did we choose a parent already?
		if( isset($_GET['parent']) && $_GET['parent'] != '' ) {
			$model->parent = $_GET['parent'];
		}

		if( isset( $_POST['AuthItemChild'] ) ) {
			if( isset($_POST['AuthItemChild']['child']) && count($_POST['AuthItemChild']['child']) ) {
				// We need to delete all child items selected up until now
				$existsalready = AuthItemChild::model()->findAll('parent=:parent', array(':parent'=>$model->parent));
				if( count($existsalready) ) {
					foreach($existsalready as $existitem) {
						Yii::app()->authManager->removeItemChild( $existitem->parent, $existitem->child );
					}
				}
				
				$added = 0;
				foreach($_POST['AuthItemChild']['child'] as $childItem) {
					$model->child = $childItem;
					if( $model->validate() ) {
						$added++;
					}
				}
				
				// Get model parent
				$authItem = AuthItem::model()->find('name=:name', array(':name' => $model->parent));

				fok(at('{number} Child item(s) Added.', array('{number}'=>$added)));
				
				// Log Message
				alog(at("Added {number} child items for {name}", array('{number}' => $added, '{name}' => $model->parent)));
				
				if($authItem) {
					$this->redirect(array('view', 'id' => $authItem->id, '#' => 'tabs-2'));
				} else {
					$this->redirect(array('index'));
				}
			}
		}

		// Selected values
		$selected = AuthItemChild::model()->findAll('parent=:parent', array(':parent'=>$model->parent));
		$_selected = array();
		if( count($selected) ) {
			foreach($selected as $select) {
				$_selected[] = $select->child;
			}
		}

		$model->child = $_selected;
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Adding Child Permissions'));
		$this->title[] = at('Adding Child Permissions');
		$this->render('child_form', array( 'model' => $model, 'roles' => $_roles ));
	}
}