<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Create Message'); ?></div>
		<div class="inside">
			<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
			
			<?php echo $this->renderPartial('_form_data', array('model' => $model), true); ?>
			
			<!--Form footer begin-->
			<section class="box_footer">
				<div class="grid-12-12">
					<a href='<?php echo $this->createUrl('index'); ?>' class='right button'><?php echo at('Cancel'); ?></a>
					<input type="submit" class="right button green" name='submit' value="<?php echo at('Send'); ?>" />
				</div>
				<div class="clear"></div>
			</section>
			<!--Form footer end-->
			<?php echo CHtml::endForm(); ?>
		</div>
	</div>
</section>
<div class="clear"></div>