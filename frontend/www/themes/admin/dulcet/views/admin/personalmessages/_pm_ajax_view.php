<section class="grid_11" style='width:98%'>
	<div class='grid_12 full-width'>
		<h1><?php echo CHtml::encode($model->title); ?></h1>	
	</div>
	
	<?php foreach($model->replies as $replyRow): ?>		
		<div class='grid_12 full-width'>
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
	
	<div class='grid_12 full-width'>
		<div class="box">
			<div class="title"><?php echo at('Reply'); ?></div>			
			<div class="inside">
				<div class="in">
					<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
					<div class="grid-12-12">
						<?php Yii::app()->customEditor->getEditor(array('includeAssets' => false, 'name' => 'pm-reply-message', 'value' => '', 'htmlOptions' => array('style' => 'height:250px;')), 'redactor'); ?>
					</div>
					<div class="clear"></div>
				</div>
				<?php echo CHtml::endForm(); ?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	
</section>

<div class="clear"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#pm-reply-message').redactor();
	});
</script>

<?php echo CHtml::hiddenField('topic_id', $model->id); ?>