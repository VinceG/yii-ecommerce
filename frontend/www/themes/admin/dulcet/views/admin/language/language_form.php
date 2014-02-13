<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo $model->isNewRecord ? at('Create Language') : at('Update Language'); ?></div>
		<div class="inside">
			<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
			<div class="in">
				<div class="grid_12">
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'name'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'name', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'name'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'abbr'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'abbr', array('length' => 2, 'maxlength' => 2, 'class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'abbr'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'is_public'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeCheckbox($model, 'is_public'); ?>
						<?php echo CHtml::error($model, 'is_public'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					
					
				</div>
				<div class="clear"></div>
			</div>
			
			<!--Form footer begin-->
			<section class="box_footer">
				<div class="grid-12-12">
					<a href='<?php echo $this->createUrl('index'); ?>' class='right button'><?php echo at('Cancel'); ?></a>
					<input type="submit" class="right button green" name='submit' value="<?php echo $model->isNewRecord ? at('Create') : at('Update'); ?>" />
				</div>
				<div class="clear"></div>
			</section>
			<!--Form footer end-->
			<?php echo CHtml::endForm(); ?>
		</div>
	</div>
</section>
<div class="clear"></div>