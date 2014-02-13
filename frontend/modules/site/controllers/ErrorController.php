<?php

class ErrorController extends SiteBaseController {
	public function actionError() {
		$this->render('error', array('error' => Yii::app()->errorHandler->error));
	}
}