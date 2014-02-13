<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo $label; ?></div>
		<div class="inside">
			<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
			<div class="in">
				<div class="grid_12">
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'title'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'title', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'title'); ?>
					</div>
					<div class="clear"></div>
					<hr />


					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'description'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'description', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'description'); ?>
					</div>
					<div class="clear"></div>
					<hr />

					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'groupkey'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'groupkey', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'groupkey'); ?>
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
					<input type="submit" class="right button green" value="<?php echo $model->isNewRecord ? at('Create') : at('Update'); ?>" />
				</div>
				<div class="clear"></div>
			</section>
			<!--Form footer end-->
			<?php echo CHtml::endForm(); ?>
		</div>
	</div>
</section>
<div class="clear"></div>