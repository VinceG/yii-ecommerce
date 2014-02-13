<div class="in">
	<div class="grid_12">
		
		<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'title'); ?></div>
		<div class="grid-9-12">
			<?php echo CHtml::activeTextField($model, 'title', array('class' => 'pm-create-message-title validate[required]')); ?>
			<?php echo CHtml::error($model, 'title'); ?>
		</div>
		<div class="clear"></div>
		<hr />
		
		<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'type'); ?></div>
		<div class="grid-9-12">
			<?php echo CHtml::activeDropDownList($model, 'type', $model->messageTypes, array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select validate[required] pm-create-message-type ')); ?>
			<?php echo CHtml::error($model, 'type'); ?>
		</div>
		<div class="clear"></div>
		<hr />
		
		<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'to'); ?></div>
		<div class="grid-9-12">
			<?php echo CHtml::activeListBox($model, 'to', $model->getRecipientsList(), array('data-placeholder' => at('Please at least one recipient'), 'prompt' => '', 'multiple' => 'multiple', 'class' => 'chzn-select validate[required] pm-create-message-to')); ?>
			<?php if(getParam('personal_message_max_participants')): ?>
				<br /><span class="subtip"><?php echo at("Maximum participants: {n}", array('{n}' => getParam('personal_message_max_participants'))); ?></span>
			<?php endif; ?>
			<?php echo CHtml::error($model, 'to'); ?>
		</div>
		<div class="clear"></div>
		<hr />
		
		<div class="grid-12-12">
			<?php echo CHtml::activeLabelEx($model, 'message'); ?>
			<?php echo CHtml::error($model, 'message'); ?>
			<br />
			<?php if(isset($viaAjax) && $viaAjax): ?>
					<?php Yii::app()->customEditor->getEditor(array('includeAssets' => false, 'name' => 'pm-reply-message', 'value' => '', 'htmlOptions' => array('style' => 'height:100px;')), 'redactor'); ?>
			<?php else: ?>
				<?php Yii::app()->customEditor->getEditor(array('model' => $model, 'attribute' => 'message')); ?>
			<?php endif; ?>		
		</div>
		<div class="clear"></div>
		<hr />
		
	</div>
	<div class="clear"></div>
</div>

<?php if(isset($viaAjax) && $viaAjax): ?>
<script type="text/javascript">
$(document).ready(function() {
	UpdateChosen();
	// Set width for the dropdowns
	$('#PersonalMessageTopic_type_chzn, #PersonalMessageTopic_to_chzn').css({width: '100%'});
	$('#PersonalMessageTopic_type_chzn, #PersonalMessageTopic_to_chzn').find('.chzn-drop').css({width: '100%'});
	$('#PersonalMessageTopic_to_chzn').find('.search-field').css({width: '100%'});
	$('#PersonalMessageTopic_to_chzn').find('.search-field').find('.default').css({width: '100%'});
	
	// Set width for the editor
	
});
</script>
<?php endif; ?>