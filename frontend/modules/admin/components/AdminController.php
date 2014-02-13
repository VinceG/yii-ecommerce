<?php

class AdminController extends Controller {
	public function init() {
		
		// Login required
		if(Yii::app()->getController()->id != 'login') {
			$returnUrl = Yii::app()->request->getUrl();
			if(strpos($returnUrl, '/admin') === false) {
				$returnUrl = array('/admin');
			}
			Yii::app()->user->setReturnUrl($returnUrl);
		}
	 
		// Make sure we have access
		if( !Yii::app()->user->id || !checkAccess('admin') ) {
			// Do we need to login
			if(!Yii::app()->user->id && Yii::app()->getController()->id != 'login') {
				$this->redirect(array('/admin/login'));
			}

			// Make sure we are not in login page
			if(Yii::app()->getController()->id != 'login') {
				throw new CHttpException(at('Sorry, You are not allowed to enter this section.') );
			}
		}
		
		// Make sure we have a valid admin user record
		if(Yii::app()->getController()->id != 'login' && Yii::app()->user->id && !AdminUser::model()->exists('userid=:id', array(':id' => Yii::app()->user->id))) {
			Yii::app()->user->logout();
			ferror(at('Your session expired. Please login.'));
			$this->redirect(array('/admin/login'));
		}

		// Check if we haven't clicked more then X amount of time
		$maxIdleTime = 60 * 60 * getParam('admin_logged_in_time', 5); // 5 hour default
		
		// Were we using an old session
		if( (Yii::app()->getController()->id != 'login' && time() - $maxIdleTime > Yii::app()->session['admin_clicked']) ) {
			// Loguser out and redirect to login
			AdminUser::model()->deleteAll('userid=:id', array(':id' => Yii::app()->user->id));
			Yii::app()->user->logout();
			ferror(at('Your session expired. Please login.'));
			$this->redirect(array('/admin/login'));
		}

		// Delete old records
		AdminUser::model()->deleteAll('lastclick_time < :time', array(':time' => time() - $maxIdleTime));

		// Update only if this is not an ajax request
		if(!request()->isAjaxRequest) {
			// Update record info
			Yii::app()->session['admin_clicked'] = time();
			AdminUser::model()->updateAll(array('lastclick_time' => time(), 'location' => Yii::app()->getController()->id), 'userid=:id', array(':id' => Yii::app()->user->id));
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Dashboard'), array('index/index'));
		
		parent::init();
	}
}