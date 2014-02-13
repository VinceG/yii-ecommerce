<?php $noParticipants = true; ?>
<?php foreach($model->participants as $participant): ?>
	<?php if($participant->participantAuthor->id == $model->author_id): ?>
		<?php continue; ?>
	<?php endif; ?>
	<?php $noParticipants = false; ?>
	<div class='pm-participant-div'>
	<a href='http://en.gravatar.com' target='_blank'>
		<?php
		$this->widget('ext.yii-gravatar.YiiGravatar', array(
		    'email'=>$participant->participantAuthor->email,
		    'size'=>40,
		    'emailHashed'=>false,
		    'htmlOptions'=>array(
		        'alt'=>'Gravatar image',
		        'title'=>'Gravatar image',
		    )
		)); ?>
	</a>
	<?php echo $participant->participantAuthor->getUserLink(); ?>
	<?php if($model->author_id == Yii::app()->user->id || $participant->user_id == Yii::app()->user->id || checkAccess('op_personalmessages_manage_participants')): ?>
		[ <a href="javascript:" id='participant-<?php echo $participant->user_id; ?>' class='remove-participant-button'> <?php echo at('Remove') ?></a> ]
	<?php endif; ?>	
	</div>
<?php endforeach; ?>

<?php if($noParticipants): ?>
	<strong><?php echo at('No Participants'); ?></strong>
<?php endif; ?>