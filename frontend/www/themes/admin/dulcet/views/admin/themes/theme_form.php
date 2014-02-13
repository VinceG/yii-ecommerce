<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo $model->isNewRecord ? at('Create Theme') : at('Update Theme'); ?></div>
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
					<?php if($model->isNewRecord): ?>
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'dirname'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'dirname', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'dirname'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					<?php endif; ?>
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'author'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'author'); ?>
						<?php echo CHtml::error($model, 'author'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'author_site'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'author_site'); ?>
						<?php echo CHtml::error($model, 'author_site'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'is_active'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeCheckbox($model, 'is_active'); ?>
						<?php echo CHtml::error($model, 'is_active'); ?>
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