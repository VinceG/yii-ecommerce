<?php

class SiteBaseController extends Controller {
	public function init() {
		// Set theme if it's not default
		if(getParam('default_theme')) {
			Yii::app()->theme = getParam('default_theme');
		} else {
			Yii::app()->theme = 'site/default';
		}

		// Are we in maintenance mode
		if(getParam('maintenance_status')) {
			$canAccess = false;
			
			// Can we override?
			if(getParam('maintenance_roles_override') && count(explode(',', getParam('maintenance_roles_override')))) {
				$roles = explode(',', getParam('maintenance_roles_override'));
				foreach($roles as $role) {
					if(checkAccess($role)) {
						$canAccess = true;
						break;
					}
				}
			}
			
			$theme = Yii::app()->theme->name . '.views.layouts.maintenance_mode';
			if(Yii::app()->theme->name != 'site') {
				$theme = 'themes.' . Yii::app()->theme->name  . '.views.site.layouts.maintenance_mode';
			}
			
			// Do we show the message or not
			if(!$canAccess) {
				$this->layout = false;
				$this->render($theme);
				Yii::app()->end();
			}
			
			// We can access but show a message
			$this->title[] = t('Maintenance Mode');
		}
		
		parent::init();
	}
}