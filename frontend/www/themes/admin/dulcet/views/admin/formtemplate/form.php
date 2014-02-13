<?php if( isset( $_POST['preview'] ) ): ?>
<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Preview Template'); ?> - <?php echo CHtml::encode(Yii::app()->formtags->replaceTags($model->title, array('user' => $model->user))); ?></div>
		<div class='inside'>
			<div class="in">
				<div class="grid_12">
					<?php echo sh(Yii::app()->formtags->replaceTags($model->content)); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>		
</section>	
<?php endif; ?>

<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo $model->isNewRecord ? at('Create Template') : at('Update Template'); ?></div>
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
					
					<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'user'); ?></div>
					<div class="grid-9-12">
						<?php
						$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						    'model'=>$model,
							'attribute' => 'user',
						    'sourceUrl'=>$this->createUrl('user/GetUserNames'),
						    // additional javascript options for the autocomplete plugin
						    'options'=>array(
						        'minLength'=>3
						    ),
						));
						?>
						<br /><span class="subtip"><?php echo at('Start typing in a user name you would like to preview the template with. By default it will use your information'); ?></span>
						<?php echo CHtml::error($model, 'user'); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-12-12">
						<?php echo CHtml::activeLabelEx($model, 'content'); ?>
						<?php echo CHtml::error($model, 'content'); ?>
						<br />
						<?php Yii::app()->customEditor->getEditor(array('model' => $model, 'attribute' => 'content', 'editorOptions' => array('css' => 'docstyle.css', 'autoresize' => true, 'fixed' => true), 'htmlOptions' => array('style' => 'height: 800px;'))); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-12-12">
						<h4><?php echo at('Available Replaceable Tags'); ?></h4><br />
						<?php $tags = Yii::app()->formtags->setUser($model->user)->getTagsForDisplay(); ?>
						<?php $columns = 4; ?>
						<table width='100%' class='form-tags'>
						<?php foreach($tags as $group => $values): ?>
							<tr>
								<th colspan='8'>
									<h5><?php echo $group; ?></h5>
								</th>	
							</tr>
							<?php $count = 0; ?>
							<?php foreach($values as $key => $value): ?>
								<?php if($count % $columns == 0): ?>
									</tr><tr>
								<?php endif; ?>
									<td><?php echo $key; ?></td>
									<td><?php echo $value; ?></td>	
								<?php $count++; ?>
							<?php endforeach; ?>	
						<?php endforeach; ?>	
						</table>
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