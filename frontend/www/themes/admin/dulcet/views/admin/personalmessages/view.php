<section class="grid_3">
	<div class="box">
		<div class="title"><?php echo at('Management') ?></div>						
		<div class="inside">
			<div class="in">
				
				<h6><?php echo at('Author'); ?></h6>
				<div>
					<div class='pm-participant-div'>
					<a href='http://en.gravatar.com' target='_blank'>
						<?php
						$this->widget('ext.yii-gravatar.YiiGravatar', array(
						    'email'=>$model->author->email,
						    'size'=>40,
						    'emailHashed'=>false,
						    'htmlOptions'=>array(
						        'alt'=>'Gravatar image',
						        'title'=>'Gravatar image',
						    )
						)); ?>
					</a>
					<?php echo $model->author->getUserLink(); ?>
					</div>
				</div>
				
				<h6><?php echo at('Participants'); ?></h6>
				<div id='participants-list'>
					<?php echo $this->renderPartial('_participants', array('model' => $model), true); ?>
				</div>
				
				<h6><?php echo at('Add Participants'); ?></h6>
				<div id='add-participant-form'>
					<?php echo $this->renderPartial('_add_participant', array('model' => $model), true); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="grid_9">
	<div class='grid_12'>
		<h1><?php echo CHtml::encode($model->title); ?></h1>	
	</div>
	
	<?php foreach($model->replies as $replyRow): ?>		
		<div class='grid_12'>
			<div class="box">
				<div class='title'>
					<span class="loatleft">
						<?php echo $replyRow->replyAuthor->getUserLink(); ?>
					</span>
					<span class="floatright"><?php echo timeSince($replyRow->created_at); ?></span>	
					<div class="clear"></div>
				</div>		
				<div class="inside">
					<div class="in">
						<?php if($this->beginCache('pm_reply_id_' . $replyRow->id)) { ?>
							<?php echo stripHtmlTags($replyRow->message); ?>
						<?php $this->endCache(); } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	<?php endforeach; ?>
	
	<div class='grid_12'>
		<div class="box">
			<div class="title"><?php echo at('Reply'); ?></div>			
			<div class="inside">
				<div class="in">
					<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
					<div class="grid-12-12">
						<?php echo CHtml::error($reply, 'message'); ?>
						<?php Yii::app()->customEditor->getEditor(array('model' => $reply, 'attribute' => 'message')); ?>
					</div>
					<div class="clear"></div>
				
				</div>
				<section class="box_footer">
					<div class="grid-12-12">
						<a href='<?php echo $this->createUrl('index'); ?>' class='right button'><?php echo at('Cancel'); ?></a>
						<input type="submit" class="right button green" name='submit' value="<?php echo at('Send'); ?>" />
					</div>
					<div class="clear"></div>
				</section>
				<?php echo CHtml::endForm(); ?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	
</section>

<div class="clear"></div>

<?php echo CHtml::hiddenField('topic-id', $model->id); ?>
<?php cs()->registerScriptFile(themeUrl('js/modules/personal_message.js'), CClientScript::POS_END); ?>