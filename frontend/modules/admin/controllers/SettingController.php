<?php
/**
 * Settings controller Home page
 */
class SettingController extends AdminController {
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_settings_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Settings Manager'));
		$this->title[] = at('Settings');	
	}
	
	/**
	 * Index action
	 */
    public function actionIndex() {
		$model = new SettingCat('search');
		// Log Message
		alog(at("Accessed Settings Manager."));
        $this->render('index', array( 'model' => $model ));
    }

	/**
	 * Add setting group action
	 */
	public function actionaddgroup()
	{
		// Check Access
		checkAccessThrowException('op_settings_add_settings_groups');
		
		$model = new SettingCat;
		
		if( isset( $_POST['SettingCat'] ) )
		{
			$model->attributes = $_POST['SettingCat'];
			if( $model->save() )
			{
				fok(at('Setting group added.'));
				
				// Log Message
				alog(at("Added New setting group '{name}'", array('{name}' => $model->title)));
				
				$this->redirect(array('setting/index'));
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Adding Setting Group'));
		$this->title[] = at('Adding Setting Group');
		
		// Display form
		$this->render('group_form', array( 'model' => $model, 'label' => at('Adding Setting Group') ));
	}
	
	/**
	 * Edit setting group action
	 */
	public function actioneditgroup()
	{
		// Check Access
		checkAccessThrowException('op_settings_edit_settings_groups');
		
		if( isset($_GET['id']) && ($model = SettingCat::model()->findByPk($_GET['id']) ) )
		{
			if( isset( $_POST['SettingCat'] ) )
			{
				$model->attributes = $_POST['SettingCat'];
				if( $model->save() )
				{
					fok(at('Setting group updated.'));
					// Log Message
					alog(at("Updated setting group '{name}'", array('{name}' => $model->title)));
					
					$this->redirect(array('setting/index'));
				}
			}
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Editing Setting Group'));
			$this->title[] = at('Editing Setting Group');

			// Display form
			$this->render('group_form', array( 'model' => $model, 'label' => at('Editing Setting Group') ));
		}
		else
		{
			ferror(at('Could not find that ID.'));
			$this->redirect(array('setting/index'));
		}
	}
	
	/**
	 * Delete setting group action
	 */
	public function actiondeletegroup()
	{
		// Check Access
		checkAccessThrowException('op_settings_delete_settings_groups');
		
		if( isset($_GET['id']) && ( $model = SettingCat::model()->with(array('settings'))->findByPk($_GET['id']) ) )
		{
			// Do we have any settings in it?
			if( count($model->settings) )
			{
				// Log Message
				alog(at("Tried Deleting Setting Group '{name}'", array('{name}' => $model->title)));

				ferror(at("Can't delete that setting group as it contains active settings."));
				$this->redirect(array('setting/index'));
			}
			
			// Log Message
			alog(at("Deleted Setting Group '{name}'", array('{name}' => $model->title)));
			
			$model->delete();
			
			fok(at('Setting group deleted.'));
			$this->redirect(array('setting/index'));
		}
		else
		{
			$this->redirect(array('setting/index'));
		}
	}
	
	/**
	 * View group settings action
	 */
	public function actionviewgroup()
	{
		// Check Access
		checkAccessThrowException('op_settings_view_settings');
		
		if( isset($_GET['id']) && ( $category = SettingCat::model()->findByPk($_GET['id']) ) )
		{
			
			// Submit?
			if( isset( $_POST['submit'] ) )
			{	
				// Check Access
				checkAccessThrowException('op_settings_update_settings');
				
				if( count( $_POST ) )
				{
					foreach( $_POST as $key => $value )
					{
						if( !preg_match('/setting_\d+/', $key) )
						{
							continue;
						}
						
						// Get the id
						$settingID = str_replace('setting_', '', $key);
						$settingValue = ( is_array( $value ) && count( $value ) ) ? implode(',', $value) : $value;
						
						$setting = Setting::model()->findByPk($settingID);
						
						if($setting) {
							// Store setting and run the php code for storing
							// Evaluate php code
							if ( $setting->php ) {
								$show = 0;
								$save = 0;
								$store = 1;
								eval( $setting->php );
							}
						}
						
						// Update setting
						Setting::model()->updateByPk($settingID, array( 'value' => $settingValue ));
						
						if($setting) {
							// Store setting and run the php code for storing
							// Evaluate php code
							if ( $setting->php ) {
								$show = 0;
								$save = 0;
								$store = 1;
								eval( $setting->php );
							}
						}
						
					}
				}
				
				// Clear cache
				Yii::app()->settings->clearCache();
				
				// Log Message
				alog(at("Updated settings in group '{name}'", array('{name}' => $category->title)));
				
				// Updated redirect
				fok(at('Settings Updated.'));
				$this->redirect(array('setting/viewgroup', 'id'=>$_GET['id']));
			} elseif( isset($_POST['reorder']) ) {
				// Check Access
				checkAccessThrowException('op_settings_update_settings_order');
				
				if( isset($_POST['settingorder']) && count( $_POST['settingorder'] ) ) {
					foreach( $_POST['settingorder'] as $settingId => $settingOrder ) {						
						// Update setting
						Setting::model()->updateByPk($settingId, array( 'sort_ord' => $settingOrder ));						
					}
				}
				
				// Log Message
				alog(at("Updated settings order in group '{name}'", array('{name}' => $category->title)));
				
				// Updated redirect
				fok(at('Settings Order Updated.'));
				$this->redirect(array('setting/viewgroup', 'id'=>$_GET['id']));
			}
			
			// Grab all settings by this group
			$settings = Setting::model()->byOrder()->findAll('category=:category', array( ':category' => $_GET['id'] ));
			
			// Set Title
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing Setting Category'), false);
			$this->title[] = at('Viewing Category "{name}"', array('{name}' => $category->title));

			// Log Message
			alog(at("Viewing Setting Group '{name}'", array('{name}' => $category->title)));
			
			// Render
			$this->render('group_view', array( 'settings' => $settings ));
		}
		else
		{
			ferror(at('Could not find that ID.'));
			$this->redirect(array('setting/index'));
		}
	}
	
	/**
	 * Add setting action
	 */
	public function actionaddsetting()
	{
		// Check Access
		checkAccessThrowException('op_settings_add_settings');
		
		$setting = new Setting;
		$category = null;
		
		if( isset($_GET['cid']) && ( $category = SettingCat::model()->findByPk($_GET['cid']) ) )
		{
			$setting->category = $category->id;
			
			$this->title[] = at('Viewing Category "{name}"', array('{name}' => $category->title));
		}
		
		if( isset( $_POST['Setting'] ) )
		{
			$setting->attributes = $_POST['Setting'];
			// Evaluate php code
			if ( $setting->php ) {
				$show = 0;
				$store = 0;
				$save = 1;
				eval( $setting->php );
			}
			
			if( $setting->save() )
			{
				$message =  at("Added Setting '{name}'", array('{name}' => $setting->title));
				if($category) {
					$message = at("Added Setting '{name}' To category '{cat}'", array('{name}' => $setting->title, '{cat}' => $category->title));
				}
				// Clear cache
				Yii::app()->settings->clearCache();
				
				fok(at('Setting added.'));
								
				// Log Message
				alog($message);
				
				$this->redirect(array('setting/viewgroup', 'id' => $setting->category));
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Adding Setting'));
		$this->title[] = at('Adding Setting');
		
		// Display form
		$this->render('setting_form', array( 'model' => $setting, 'label' => at('Adding Setting') ));
	}
	
	/**
	 * Edit setting action
	 */
	public function actioneditsetting()
	{
		// Check Access
		checkAccessThrowException('op_settings_edit_settings');
		
		if( isset($_GET['id']) && ( $setting = Setting::model()->findByPk($_GET['id']) ) )
		{
			$category = SettingCat::model()->findByPk($setting->category);
			if( $category )
			{
				$setting->category = $category->id;
			
				$this->title[] = at('Viewing Category "{name}"', array('{name}' => $category->title));
			}
			
			// Make sure the setting is not protected as we can't edit protected settings
			if(!YII_DEBUG && $setting->is_protected) {
				// Log Message
				alog(at("Tried Editing Protected Setting '{name}'", array('{name}' => $setting->title)));

				ferror(at("Can't edit that setting as it's a protected setting."));
				$this->redirect(getReferrer('setting/index'));
			}
		
			if( isset( $_POST['Setting'] ) )
			{
				$setting->attributes = $_POST['Setting'];
				// Evaluate php code
				if ( $setting->php ) {
					$show = 0;
					$store = 0;
					$save = 1;
					eval( $setting->php );
				}
				if( $setting->save() )
				{
					// Clear cache
					Yii::app()->settings->clearCache();
					
					fok(at('Setting edited.'));
										
					// Log Message
					alog(at("Updated Setting '{name}'", array('{name}' => $setting->title)));
					
					$this->redirect(array('setting/viewgroup', 'id' => $setting->category));
				}
			}
		
			// Add Breadcrumb
			$this->addBreadCrumb(at('Updating Setting'));
			$this->title[] = at('Updating Setting');
		
			// Display form
			$this->render('setting_form', array( 'model' => $setting, 'label' => at('Editing Setting') ));
		}
		else
		{
			ferror(at('Could not find that ID.'));
			$this->redirect(array('setting/index'));
		}
	}
	
	/**
	 * Delete setting action
	 */
	public function actiondeletesetting()
	{
		// Check Access
		checkAccessThrowException('op_settings_delete_settings');
		
		if( isset($_GET['id']) )
		{
			$model = Setting::model()->findByPk($_GET['id']);
			
			// Make sure the setting is not protected as we can't edit protected settings
			if(!YII_DEBUG && $model->is_protected) {
				// Log Message
				alog(at("Tried Deleting Protected Setting '{name}'", array('{name}' => $model->title)));

				ferror(at("Can't delete that setting as it's a protected setting."));
				$this->redirect(getReferrer('setting/index'));
			}
		
			// Log Message
			alog(at("Deleted Setting '{name}'", array('{name}' => $model->title)));
			
			Setting::model()->deleteByPk($_GET['id']);
			
			// Clear cache
			Yii::app()->settings->clearCache();
			
			fok(at('Setting deleted.'));
			if($model) {
				$this->redirect(array('setting/viewgroup', 'id' => $model->category));
			}
			$this->redirect(array('setting/index'));
		}
		else
		{
			$this->redirect(array('setting/index'));
		}
	}
	/**
	 * Revert setting action
	 */
	public function actionrevertsetting()
	{
		// Check Access
		checkAccessThrowException('op_settings_revert_settings');
		
		if( isset($_GET['id']) ) {
			$setting = Setting::model()->findByPk($_GET['id']);
			
			Setting::model()->updateByPk($_GET['id'], array('value'=>$setting->default_value));
			
			if($setting) {
				$setting->value = $setting->default_value;
				// Store setting and run the php code for storing
				// Evaluate php code
				if ( $setting->php ) {
					$show = 0;
					$save = 0;
					$store = 1;
					eval( $setting->php );
				}
			}
			
			// Log Message
			alog(at("Reverted Setting '{name}'", array('{name}' => $setting->title)));
			
			// Clear cache
			Yii::app()->settings->clearCache();
			
			fok(at('Setting Reverted.'));
			$this->redirect(array('setting/viewgroup', 'id'=>$setting->category));
		} else {
			$this->redirect(array('setting/index'));
		}
	}
	
	/**
	 * Parse each setting
	 */
	public function getSettingForm( $setting )
	{
		$setting = $this->parseSetting($setting);
		
		$name = 'setting_' . $setting->id;
		$value = $setting->value !== null ? $setting->value : $setting->default_value;
		
		switch( $setting->type )
		{
			case 'textarea':
			echo CHtml::textArea( $name, $value, array( 'rows' => 5, 'class' => 'textbox', 'disabled' => $setting->disabled ? 'disabled' : '' ) );
			break;
			
			case 'dropdown':
			echo CHtml::dropDownList( $name, $value, $this->convertExtraToArray( $setting->extra ), array( 'class' => 'chzn-select', 'disabled' => $setting->disabled ? 'disabled' : '' ) );
			break;
			
			case 'multi':
			echo CHtml::listBox( $name, $value ? explode(',', $value) : '', $this->convertExtraToArray( $setting->extra ), array('size' => 20,  'multiple' => 'multiple', 'class' => 'chosen', 'disabled' => $setting->disabled ? 'disabled' : '' ) );
			break;
			
			case 'checkbox':
			echo CHtml::checkbox( $name, $setting->value != '' ? $setting->value : $setting->default_value, array( 'class' => '', 'disabled' => $setting->disabled ? 'disabled' : '' ) );
			break;
			
			case 'yesno':
			echo CHtml::dropDownList( $name, $value, array( '0' => Yii::t('global', 'No'), '1' => Yii::t('global', 'Yes') ), array( 'class' => 'chzn-select', 'disabled' => $setting->disabled ? 'disabled' : '' ) );
			break;
			
			case 'editor':
			Yii::app()->customEditor->getEditor(array('name' => $name, 'value' => $value));
			break;
			
			case 'text':
			default:
			echo CHtml::textField( $name, $value, array( 'class' => 'textbox', 'disabled' => $setting->disabled ? 'disabled' : '' ) );
			break;
		}
	}
	
	/**
	 * Parse each setting
	 */
	public function parseSetting( $setting )
	{
		$name = 'setting_' . $setting->id;
		$value = $setting->value !== null ? $setting->value : $setting->default_value;
		
		// Parse php if we need to
		if ( $setting->php ) {
			$show = 1;
			$store = 0;
			$save = 0;
			eval( $setting->php );
		}
		
		return $setting;
	}
	
	/**
	 * Convert extra data to an array of key=>value pairs
	 */
	protected function convertExtraToArray( $string ) {
		if( !$string )
		{
			return array();
		}
		
		$_temp = array();
		
		if( $string == '#show_roles#' )
		{
			$roles = Yii::app()->authManager->getAuthItems();
			$items = array( CAuthItem::TYPE_ROLE => array(), CAuthItem::TYPE_TASK => array(), CAuthItem::TYPE_OPERATION => array() );
			$itemTitles = array( CAuthItem::TYPE_ROLE => at('Roles'), CAuthItem::TYPE_TASK => at('Tasks'), CAuthItem::TYPE_OPERATION => at('Operations') );
			if( count($roles) ) {
				foreach( $roles as $item ) {
					$_temp[ $itemTitles[$item->type] ][ $item->name ] = $item->name;
				}
			}
		} else if( $string == '#show_timezones#' ) {
			$_temp = getTimeZones();
		} elseif( $string == '#show_themes#' ) {
			$_temp = Theme::model()->getThemesByDirname();
		} else if( $string == '#show_languages#' ) {
			$_temp = Language::model()->getLanguagesCodes();	
		} else if( $string == '#show_cache_options#' ) {
			$cacheOptions = getSupprotedCacheOptions();
			foreach($cacheOptions as $cacheKey => $cacheOption) {
				if($cacheOption['visible']) {
					$_temp[$cacheKey] = $cacheOption['title'];
				}
			}	
		} else {
			$exploded = explode("\n", $string);
			if( count($exploded) ) {
				foreach( $exploded as $explode ) {
					if(!trim($explode)) {
						continue;
					}
					
					// Make sure we have = in the line
					if(strpos($explode, '=') === false) {
						continue;
					}
					
					list($key, $value) = explode('=', trim($explode));
					$_temp[$key] = $value;
				}
			}
		}	
		
		return $_temp;
	}
	
}