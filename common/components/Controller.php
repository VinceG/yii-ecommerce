<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {
	public $breadcrumbs = array();
	public $menu = array();
	public $title = array();
	
	public function init() {
		$this->title[] = Yii::app()->name;
		parent::init();
	}
	
	/**
	 * Construct the breadcrumbs
	 */
	public function addBreadCrumb($title, $url=null) {
		$controller = $this->id;
		$action = $this->action ? $this->action->id : '';
		if($url) {
			$link = $url;
		} elseif($url === null) {
			$link = array($controller.'/'.$action);
		} elseif($url === false) {
			$link = null;
		}
		$this->breadcrumbs[$title] = $link;
	}
}
