<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Create Permission'); ?></div>
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


					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'description'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'description', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'description'); ?>
					</div>
					<div class="clear"></div>
					<hr />

					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'type'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeDropDownList($model, 'type', $model->types, array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select validate[required]')); ?>
						<?php echo CHtml::error($model, 'type'); ?>
					</div>
					<div class="clear"></div>
					<hr />

					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'bizrule'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextArea($model, 'bizrule', array('class' => 'txt_autogrow')); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'data'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextArea($model, 'data', array('class' => 'txt_autogrow')); ?>
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