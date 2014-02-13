<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo $model->isNewRecord ? at('Create State') : at('Update State'); ?></div>
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


					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'short'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'short', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'short'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'sort_ord'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'sort_ord', array('class' => 'validate[required,custom[number]]')); ?>
						<?php echo CHtml::error($model, 'sort_ord'); ?>
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