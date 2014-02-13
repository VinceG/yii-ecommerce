<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo $model->isNewRecord ? at('Create String') : at('Update String'); ?></div>
		<div class="inside">
			<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
			<div class="in">
				<div class="grid_12">
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'category'); ?></div>
					<div class="grid-9-12">
						<?php
						$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						    'model'=>$model,
							'attribute' => 'category',
						    'sourceUrl'=>$this->createUrl('language/GetCategoryNames'),
						    // additional javascript options for the autocomplete plugin
						    'options'=>array(
						        'minLength'=>1
						    ),
						));
						?>
						<br /><span class="subtip"><?php echo at('Start typing in a category name and it will provide suggestion using autocomplete'); ?></span>
						<?php echo CHtml::error($model, 'category'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'message'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextArea($model, 'message', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'message'); ?>
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