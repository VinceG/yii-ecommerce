<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Update Settings'); ?></div>
		<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>

		<div class="inside">
			<div class="in">
					<?php $open = 0; $close = 0; ?>
					<?php if( count($settings) ): ?>

						<?php foreach($settings as $row): ?>
							
							<?php if($row->group_title): ?>
								<?php $open++; ?>
								<!-- Open Group <?php echo $row->group_title; ?> -->
								<div class='grid_12'>
								<div class="box">
									<div class="title"><?php echo $row->group_title; ?></div>
							<?php endif; ?>
							
							<?php echo $this->renderPartial('_setting_row', array('row' => $row), true); ?>
							
							<?php if($row->group_close): ?>
								<?php $close++; ?>
								</div>
								</div>
							<?php endif; ?>
							
						<?php endforeach; ?>
						
						<?php for($i=$close; $i < $open; $i++): ?>
							<!-- Close Group -->
							</div>
							</div>
						<?php endfor; ?>

					<?php else: ?>
						<p><?php echo at('No Settings Found.'); ?></p>
					<?php endif; ?>
					
			</div>
		</div>
		
		<?php if( count($settings) ): ?>
		<!--Form footer begin-->
		<section class="box_footer">
			<div class="grid-12-12">
				<a href='<?php echo $this->createUrl('index'); ?>' class='right button'><?php echo at('Cancel'); ?></a>
				<input type="submit" name='submit' class="right button green" value="<?php echo at('Update'); ?>" />
				<input type="submit" name='reorder' class="right button blue" value="<?php echo at('Reorder'); ?>" />
			</div>
			<div class="clear"></div>
		</section>
		<!--Form footer end-->
		<?php endif; ?>
		<?php echo CHtml::endForm(); ?>
	</div>
</section>
<div class="clear"></div>