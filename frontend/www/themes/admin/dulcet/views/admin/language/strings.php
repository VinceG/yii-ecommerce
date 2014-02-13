<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Translate Strings'); ?> <?php echo $count; ?></div>

		<div class="inside">
			
			<div class="in">
				
				<?php if(strtolower($this->action->id) != 'translateneeded'): ?>
					<div class="grid-3-12">
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'View Only Requiring Translation',
						'url' => array('TranslateNeeded', 'id' => $id),
					    'type'=>'primary',
					)); ?>
					</div>
					<div class="grid-9-12">
						<?php echo CHtml::beginForm(array('language/view', 'id' => $id), 'get', array('class' => 'formee')); ?>
							<?php echo CHtml::textField('term', getRParam('term'), array('placeholder' => at('Search Messages'), 'size' => 40, 'style' => 'width: 50%;')); ?>
							<?php echo CHtml::submitButton(at('Search')); ?>
						<?php echo CHtml::endForm(); ?>
					</div>
				<?php else: ?>
					<div class="grid-5-12">
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'View All',
						'url' => array('View', 'id' => $id),
					    'type'=>'primary',
					)); ?>
					</div>
				<?php endif; ?>
				
				<div class="clear"></div>	
				<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
				<div class="grid_12">
						<div class="grid-2-12"><?php echo at('ID') ?></div>
						<div class="grid-4-12"><?php echo at('Original String'); ?></div>
						<div class="grid-4-12"><?php echo $sort->link('translation', at('Translation'), array('title' => at('Sort by translation') ) ); ?></div>
						<div class="grid-2-12"><?php echo at('Options'); ?></div>
						<div class="clear"></div>
						
					<?php
					$this->widget('zii.widgets.CListView', array(
					    'dataProvider'=>$dataProvider,
					    'itemView'=>'_string',
					));
					?>
				</div>
				<div class="clear"></div>
			</div>
			<!--Form footer begin-->
			<section class="box_footer">
				<div class="grid-12-12">
					<a href='<?php echo $this->createUrl('index'); ?>' class='right button'><?php echo at('Cancel'); ?></a>
					<input type="submit" class="right button green" name='submit' value="<?php echo at('Save'); ?>" />
				</div>
				<div class="clear"></div>
			</section>
			<!--Form footer end-->
			<?php echo CHtml::endForm(); ?>
		</div>

	</div>
</section>
<div class="clear"></div>