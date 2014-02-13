<?php

class ErrorController extends AdminController {
	public function actionError() {
		$this->render('error', array('error' => Yii::app()->errorHandler->error));
	}
}