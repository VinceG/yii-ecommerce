<section class="grid_11" style='width:98%'>	
	<div class='grid_12 full-width'>
		<div class="box">		
			<div class="inside">
				<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
				<?php echo $this->renderPartial('_form_data', array( 'model' => $model, 'viaAjax' => true ), true); ?>
				<?php echo CHtml::endForm(); ?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	
</section>

<div class="clear"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#pm-reply-message').redactor();
	});
</script>