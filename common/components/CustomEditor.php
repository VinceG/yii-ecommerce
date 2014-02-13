<?php
/**
 * Custom Editor Application Component
 */
class CustomEditor extends CApplicationComponent {
	/**
	 * Editor type to display
	 */
	public $type = 'ckeditor';
	public $htmlOptions = array();
	public $editorOptions = array();
	public $includeAssets = true;
	/**
	 * Component Init function
	 */
	public function init() {
		Yii::import('application.widgets.*');
		Yii::import('application.components.*');
	}
	
	/**
	 * Get editor
	 */
	public function getEditor($data, $type=null) {
		$editorType = $type !== null ? $type : $this->type;
		if(!is_array($data) && count($data)) {
			echo t('Missing info in $data array.');
			return;
		}
		
		// Make sure we have model or name
		if(!isset($data['model']) && !isset($data['name'])) {
			echo t('Missing info in $data array. Must seet model or name elements.');
			return;
		}
		
		// If this is redactor then add includeAssets
		if($editorType == 'redactor') {
			$data['includeAssets'] = $this->includeAssets;
		}
		
		// Build editor based on type
		switch($editorType) {
			case 'redactor':
			Yii::app()->widgetFactory->createWidget(new Controller('controller'), 'application.widgets.redactorjs.Redactor', $data)->run();
			break;
			
			case 'tinymce':
			Yii::app()->widgetFactory->createWidget(new Controller('controller'), 'application.widgets.tinymce.ETinyMce', $data)->run();
			break;
			
			case 'ckeditor':
			default:
			Yii::app()->widgetFactory->createWidget(new Controller('controller'), 'application.widgets.ckeditor.CKEditor', $data)->run();
			break;
		}
	}
}