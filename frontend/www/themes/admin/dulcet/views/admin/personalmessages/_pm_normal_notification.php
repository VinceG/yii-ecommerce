<?php echo $text; ?> [<?php echo CHtml::link(at('View'), 'javascript:void(0);', array('id' => 'reply-message-normal')); ?>]
<?php echo CHtml::hiddenField('topic_id', $message->topic_id); ?>