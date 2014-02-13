<?php if( isset( $_POST['preview'] ) ): ?>
<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Preview Help Topic'); ?> - <?php echo CHtml::encode($model->question); ?></div>
		<div class='inside'>
			<div class="in">
				<div class="grid_12">
					<?php echo $model->answer; ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>		
</section>	
<?php endif; ?>

<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo $model->isNewRecord ? at('Create Topic') : at('Update Topic'); ?></div>
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
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'question'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'question', array('class' => 'validate[required]')); ?>
						<?php echo CHtml::error($model, 'question'); ?>
					</div>
					<div class="clear"></div>
					<hr />

					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'alias'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextField($model, 'alias'); ?>
						<br /><span class="subtip"><?php echo at('Leave blank to use the title as the alias'); ?></span>
						<?php echo CHtml::error($model, 'alias'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'tags'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextArea($model, 'tags', array('style' => 'height:100px;')); ?>
						<?php echo CHtml::error($model, 'tags'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'status'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeDropDownList($model, 'status', array( 0 => at('Hidden (Draft)'), 1 => at('Open (Published)') ), array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select validate[required]')); ?>
						<?php echo CHtml::error($model, 'status'); ?>
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
					
					<div class="grid-12-12">
						<?php echo CHtml::activeLabelEx($model, 'answer'); ?>
						<?php echo CHtml::error($model, 'answer'); ?>
						<br />
						<?php Yii::app()->customEditor->getEditor(array('model' => $model, 'attribute' => 'answer')); ?>
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
					<input type="submit" class="right button blue" name='preview' value="<?php echo at('Preview'); ?>" />
				</div>
				<div class="clear"></div>
			</section>
			<!--Form footer end-->
			<?php echo CHtml::endForm(); ?>
		</div>
	</div>
</section>
<div class="clear"></div>