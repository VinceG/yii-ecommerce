<?php

class LogController extends AdminController {
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_logs_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Logs'));
		$this->title[] = at('Logs');
	}
	/**
	 * User manager index
	 */
	public function actionIndex() {
		$model = new AdminLog('search');
		$model->unsetAttributes();
        if(isset($_GET['AdminLog'])) {
			$model->attributes=$_GET['AdminLog'];
		}
		
		$this->title[] = at('Admin Logs');
		
		$user = null;
		if(getRParam('user')) {
			$user = User::model()->findByPk(getRParam('user'));
			if($user) {
				$this->title[] = at('Viewing logs for {name}', array('{name}' => $user->name));
			}
		}
		$this->render('index', array('model' => $model, 'user' => $user));
	}
	
	/**
	 * User manager index
	 */
	public function actionLoginHistory() {
		// Check Access
		checkAccessThrowException('op_loginhistory_view');
		
		$model = new AdminLoginHistory('search');
		$model->unsetAttributes();
        if(isset($_GET['AdminLoginHistory'])) {
			$model->attributes=$_GET['AdminLoginHistory'];
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Login History'));
		$this->title[] = at('Admin Login History');
		$this->render('login_history', array('model' => $model));
	}
}