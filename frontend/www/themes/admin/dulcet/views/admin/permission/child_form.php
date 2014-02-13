<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Manage Child Items'); ?></div>
		<div class="inside">
			<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
			<div class="in">
				<div class="grid_12">

					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'parent'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeDropDownList($model, 'parent', $roles, array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select validate[required]')); ?>
						<?php echo CHtml::error($model, 'parent'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'type'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeListBox($model, 'child', $roles, array('multiple' => 'multiple', 'size' => 20, 'style' => 'width:500px;height:200px;')); ?>
						<?php echo CHtml::error($model, 'child'); ?>
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
					<input type="submit" class="right button green" value="<?php echo at('Save'); ?>" />
				</div>
				<div class="clear"></div>
			</section>
			<!--Form footer end-->
			<?php echo CHtml::endForm(); ?>
		</div>
	</div>
</section>
<div class="clear"></div>