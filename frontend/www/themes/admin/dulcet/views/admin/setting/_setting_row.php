<?php $row = $this->parseSetting($row); ?>

<div class="grid-1-12">	
	<?php echo CHtml::textField('settingorder['.$row->id.']', $row->sort_ord); ?>
</div>

<?php if($row->type == 'editor'): ?>
	<div class="grid-9-12">
		<b><?php echo CHtml::encode($row->title); ?></b>
		<?php if( $row->value !== null && $row->default_value != $row->value ): ?>
			<span class='errorMessage'><?php echo at(' (Changed)'); ?></span>
		<?php endif; ?>
		<?php if($row->description): ?>
			<br /><span class="subtip"><?php echo CHtml::encode($row->description); ?></span>
		<?php endif; ?>
		<br />
		<?php $this->getSettingForm( $row ); ?>
	</div>
<?php else: ?>	
	<div class="grid-4-12">
		<b><?php echo CHtml::encode($row->title); ?></b>
		<?php if( $row->value !== null && $row->default_value != $row->value ): ?>
			<span class='errorMessage'><?php echo at(' (Changed)'); ?></span>
		<?php endif; ?>
		<?php if($row->description): ?>
			<br /><span class="subtip"><?php echo $row->description; ?></span>
		<?php endif; ?>
	</div>

	<div class="grid-5-12">
		<?php $this->getSettingForm( $row ); ?>
	</div>
<?php endif; ?>	

	
	
<div class="grid-2-12">	
	<table>
		<tr>
		<?php if( $row->value !== null && $row->default_value != $row->value ): ?>
			<td><a href="<?php echo $this->createUrl('setting/revertsetting', array( 'id' => $row->id )); ?>" title="<?php echo at('Revert setting value to the default value.'); ?>" rel='tooltip' data-original-title='<?php echo at('Revert'); ?>'><i class='icon-refresh'></i></a></td>
		<?php endif; ?>
		
		<?php if(YII_DEBUG || !$row->is_protected): ?>
			<td><a href="<?php echo $this->createUrl('setting/editsetting', array( 'id' => $row->id )); ?>" title="<?php echo at('Edit this setting'); ?>" rel='tooltip' data-original-title='<?php echo at('Edit'); ?>'><i class='icon-edit'></i></a></td>
		 	<td><a href="<?php echo $this->createUrl('setting/deletesetting', array( 'id' => $row->id )); ?>" title="<?php echo at('Delete this setting!'); ?>" rel='tooltip' data-original-title='<?php echo at('Delete'); ?>'><i class='icon-trash'></i></a></td>
		<?php endif; ?>
		</tr>
	</table>
</div>

<div class="clear"></div>
<hr />