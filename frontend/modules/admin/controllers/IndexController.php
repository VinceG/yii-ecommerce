<?php

class IndexController extends AdminController {
	public function actionIndex() {
		// Submitted form
		if(isset($_POST['dashboard_staff_message'])) {
			// Check access
			checkAccessThrowException('op_dashboard_update_staff_message');
			// Update message
			Setting::model()->updateSettingByKey('dashboard_staff_message', $_POST['dashboard_staff_message']);
			// Log Message
			alog(at("Updated Staff Message"));
			// Updated redirect
			fok(at('Message Saved.'));
			$this->redirect(array('index'));
		}
		$logModel = new AdminLog;
		$this->render('index', array('logModel' => $logModel));
	}
}