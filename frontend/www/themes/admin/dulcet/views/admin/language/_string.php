<?php $orig = $data->source; ?>
<div class="grid-2-12"><?php echo $data->id; ?></div>
<div class="grid-4-12">
	<?php echo CHtml::encode($orig->message); ?> <br /><small>(<?php echo $orig->category; ?>)</small>
</div>
<div class="grid-4-12">
	<?php
	$large = array('rows' => 10, 'cols' => 50, 'style' => 'width:100%;height:10em;');
	$small = array('rows' => 10, 'cols' => 50, 'style' => 'width:100%;height:4em;');
	?>
	<?php echo CHtml::textArea("strings[{$data->id}]", $data->translation, strlen($data->translation) > 50 ? $large : $small); ?>
</div>
<div class="grid-2-12">
	<?php if( $data->translation != $orig->message ): ?>
		<a href="<?php echo $this->createUrl('language/revert', array( 'id' => $data->language_id, 'string' => $data->id )); ?>"><?php echo at('Revert'); ?></a>
	<?php endif; ?>
</div>
<div class="clear"></div>
<hr />