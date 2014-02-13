<?php

class ErrorController extends Controller {
	public function actionError() {
		$this->render('error', array('error' => Yii::app()->errorHandler->error));
	}
}