<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo $model->isNewRecord ? at('Create City') : at('Update City'); ?></div>
		<div class="inside">
			<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
			<div class="in">
				<div class="grid_12">
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'city_name'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'city_name', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'city_name'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'city_state'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeDropDownList($model, 'city_state', CHtml::listData(USState::model()->byOrder()->findAll(), 'short', 'name'), array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select validate[required]')); ?>
						<?php echo CHtml::error($model, 'city_state'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'city_county'); ?></div>
					<div class="grid-9-12">
						<?php
						$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						    'model'=>$model,
							'attribute' => 'city_county',
						    'sourceUrl'=>$this->createUrl('GetCityCountyNames'),
						    // additional javascript options for the autocomplete plugin
						    'options'=>array(
						        'minLength'=>'2',
						    ),
						    'htmlOptions'=>array(
								'class' => 'validate[required]',
						    ),
						));
						?>
						<br /><span class="subtip"><?php echo at('Start typing in a county name and it will provide suggestion using autocomplete'); ?></span>
						<?php echo CHtml::error($model, 'city_county'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'city_zip'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'city_zip', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'city_zip'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'city_latitude'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'city_latitude', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'city_latitude'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'city_longitude'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'city_longitude', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'city_longitude'); ?>
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