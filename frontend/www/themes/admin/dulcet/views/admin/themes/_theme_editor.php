<div class="grid-12-12"><?php echo CHtml::activeTextArea($file, 'content', array('class' => 'theme-file-editor-area', 'style' => 'width:100%;min-height: 500px;')); ?></div>
<div class="clear"></div>

<section class="box_footer">
	<div class="grid-12-12">
		<input type="button" class="right button blue" id='cancel_template_edit' name='cancel' value="<?php echo at('Cancel'); ?>" />
		<input type="button" class="right button green" id='save_template_edit' name='save' value="<?php echo at('Save Template'); ?>" />
	</div>
	<div class="clear"></div>
</section>