<?php echo $text; ?>
<br /><br /><br />
<?php if($message->notificationReply): ?>
<blockquote>
	<?php if($this->beginCache('pm_reply_id_' . $message->notificationReply->id)) { ?>
		<?php echo stripHtmlTags($message->notificationReply->message); ?>
	<?php $this->endCache(); } ?>
</blockquote>
<?php endif; ?>

<?php echo CHtml::hiddenField('topic_id', $message->topic_id); ?>