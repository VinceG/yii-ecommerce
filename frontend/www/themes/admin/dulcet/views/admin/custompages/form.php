<?php if( isset( $_POST['preview'] ) ): ?>
<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Preview Page'); ?> - <?php echo CHtml::encode($model->title); ?></div>
		<div class='inside'>
			<div class="in">
				<div class="grid_12">
					<?php echo $model->content; ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>		
</section>	
<?php endif; ?>

<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo $model->isNewRecord ? at('Create Page') : at('Update Page'); ?></div>
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
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'metadesc'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextArea($model, 'metadesc', array('style' => 'height:100px;')); ?>
						<?php echo CHtml::error($model, 'metadesc'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'metakeys'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeTextArea($model, 'metakeys', array('style' => 'height:100px;')); ?>
						<?php echo CHtml::error($model, 'metakeys'); ?>
					</div>
					<div class="clear"></div>
					<hr />

					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'visible'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::activeDropDownList($model, 'visible', CHtml::listData(AuthItem::model()->findAll('type=:type', array(':type' => CAuthItem::TYPE_ROLE)), 'name', 'name'), array('data-placeholder' => at('Please select multiple...'), 'multiple' => 'multiple', 'prompt' => '', 'class' => 'chzn-select validate[required]')); ?>
						<?php echo CHtml::error($model, 'visible'); ?>
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
					
					<div class="grid-12-12">
						<?php echo CHtml::activeLabelEx($model, 'content'); ?>
						<?php echo CHtml::error($model, 'content'); ?>
						<br />
						<?php Yii::app()->customEditor->getEditor(array('model' => $model, 'attribute' => 'content')); ?>
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