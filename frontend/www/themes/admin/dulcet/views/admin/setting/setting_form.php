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
						<?php echo CHtml::activeTextField($model, 'description'); ?>
						<?php echo CHtml::error($model, 'description'); ?>
					</div>
					<div class="clear"></div>
					<hr />

					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'settingkey'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'settingkey', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'settingkey'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'category'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeDropDownList($model, 'category', Setting::model()->getGroups(), array( 'prompt' => at('-- Choose Value --'), 'class' => 'chzn-select validate[required]' )); ?>
						<?php echo CHtml::error($model, 'category'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'type'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeDropDownList($model, 'type', Setting::model()->getTypes(), array( 'prompt' => at('-- Choose Value --'), 'class' => 'chzn-select validate[required]' )); ?>
						<?php echo CHtml::error($model, 'type'); ?>
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
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'value'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextArea($model, 'value'); ?>
						<?php echo CHtml::error($model, 'value'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'extra'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextArea($model, 'extra'); ?>
						<br /><span class="subtip"><?php echo at("Enter extra data that will be used to create the dropdown list or multi select list. One option per line key=value.<br />Example:<br />m=Male<br />f=Female<br />u=Unknown") ?></span>
						<?php echo CHtml::error($model, 'extra'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'php'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextArea($model, 'php'); ?>
						<br /><span class="subtip"><?php echo at('Enter PHP code that will be executed when the setting is shown, saved or stored to the database.<br />Info:<br /> <b>$show</b> - When the setting is being displayed.<br /><b>$save</b> - When the setting is edited through the setting form.<br /><b>$store</b> - When the setting value is being stored to the database through the group view page.') ?></span>
						<?php echo CHtml::error($model, 'php'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'is_protected'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeCheckBox($model, 'is_protected'); ?>
						<br /><span class="subtip"><?php echo at('By checking this option you will not be able to delete the setting through the admin panel.') ?></span>
						<?php echo CHtml::error($model, 'is_protected'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'group_title'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'group_title'); ?>
						<br /><span class="subtip"><?php echo at("Enter a title to group this settings and the next ones after it in a group of settings until you close it by checking the close opened group checkbox.") ?></span>
						<?php echo CHtml::error($model, 'group_title'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'group_close'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeCheckBox($model, 'group_close'); ?>
						<br /><span class="subtip"><?php echo at("If you've entered a group title for a setting you may close that opened group by checking this checkbox to close it.") ?></span>
						<?php echo CHtml::error($model, 'group_close'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'sort_ord'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'sort_ord', array('class' => 'validate[custom[number]]')); ?>
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