<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Custom Field Form'); ?></div>
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
						<?php echo CHtml::activeTextField($model, 'description'); ?>
						<?php echo CHtml::error($model, 'description'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'type'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeDropDownList($model, 'type', UserCustomField::model()->getTypes(), array( 'prompt' => at('-- Choose Value --'), 'class' => 'chzn-select validate[required]' )); ?>
						<?php echo CHtml::error($model, 'type'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'status'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeDropDownList($model, 'status', array( 0 => at('Hidden'), 1 => at('Active') ), array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select')); ?>
						<?php echo CHtml::error($model, 'status'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'is_public'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeDropDownList($model, 'is_public', array( 0 => at('No'), 1 => at('Yes') ), array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select')); ?>
						<?php echo CHtml::error($model, 'is_public'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'is_editable'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeDropDownList($model, 'is_editable', array( 0 => at('No'), 1 => at('Yes') ), array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select')); ?>
						<?php echo CHtml::error($model, 'is_editable'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'default_value'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextArea($model, 'default_value'); ?>
						<?php echo CHtml::error($model, 'default_value'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'extra'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextArea($model, 'extra'); ?>
						<br /><span class="subtip"><?php echo at('Used for drop downs and multi select boxes. One item per each line separate with = between the key and value. for example:<br /><br /> key=>value<br />key=>value'); ?></span>
						<?php echo CHtml::error($model, 'extra'); ?>
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