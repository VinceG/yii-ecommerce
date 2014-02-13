<?php echo CHtml::dropDownList('add_participant_field', '', $model->getRecipientsListDropDown(), array('data-placeholder' => at('Please at least one recipient'), 'prompt' => '', 'style' => 'width:150px;', 'class' => 'chzn-select-nosearch validate[required]')); ?>
<a href="javascript:" id='add-participant-button' class="button with_icon16_notext">
	<span class="icon16_sprite i_add" aria-describedby="ui-tooltip-3"></span>
</a>