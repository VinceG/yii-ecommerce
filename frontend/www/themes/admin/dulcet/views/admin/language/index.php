<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Languages Manager'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid-4-12">
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>at('Create Language'),
						'url' => array('create'),
					    'type'=>'primary',
					)); ?>
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>at('Add String'),
						'url' => array('addstring'),
					    'type'=>'primary',
					)); ?>
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>at('Sync All'),
						'url' => array('syncall'),
					    'type'=>'primary',
					)); ?>
				</div>
					
				<div class="clear"></div>	
				
				<div class="grid_12">
					<?php $this->widget('bootstrap.widgets.BootGridView', array(
					    'type'=>'striped bordered condensed',
					    'dataProvider'=>$model->search(),
					    'columns'=>array(
					        array('name'=>'id', 'header'=>'#'),
					        array('name'=>'name'),
							array('name'=>'abbr'),
							array('name'=>'strings', 'header' => at('Total Strings'), 'value' => 'numberFormat($data->stringsCount)'),
							array('name'=>'source_strings', 'header' => at('Total Source Strings'), 'value' => 'numberFormat(SourceMessage::model()->count())'),
					        array('name'=>'translated_strings', 'header' => at('Total Translated'), 'value' => 'numberFormat($data->getStringTranslationDifference($data->id))'),
							array('name'=>'created_at', 'value' => 'timeSince($data->created_at)'),
							array('name' => 'is_public', 'value' => '$data->is_public ? at("Public") : at("Private")'),
							array('name' => 'is_source', 'value' => '$data->is_source ? at("Source") : at("Copy")'),
							array(
								'template' => '{view}{update}{delete}{sync}{export}',
					            'class'=>'bootstrap.widgets.BootButtonColumn',
					            'htmlOptions'=>array('style'=>'width: 100px'),
								'buttons'=>array(
										'sync' => array(
												'label' => '<i class="icon-refresh"></i>',
		                                        'options'=>array('title'=>at('Sync Messages'), 'class' => 'sync'),                           
		                                        'url' => 'Yii::app()->createUrl("/admin/language/sync", array("id"=>$data->id))',
												'visible' => 'checkAccess("op_language_sync_messages")'
		                                ),
										'export' => array(
												'label' => '<i class="icon-download"></i>',
		                                        'options'=>array('title'=>at('Export Language'), 'class' => 'export'),                           
		                                        'url' => 'Yii::app()->createUrl("/admin/language/export", array("id"=>$data->id))',
												'visible' => 'checkAccess("op_language_export_language")'
		                                ),
		                        ),
					        ),
					    ),
					)); ?>
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create Language',
						'url' => array('create'),
					    'type'=>'primary',
					)); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
</section>
<div class="clear"></div>
<?php if(checkAccess('op_language_import_language')): ?>
<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Import Language'); ?></div>
		<div class="inside">
			<?php echo CHtml::beginForm(array('language/import'), 'post', array('class' => 'formee', 'enctype' => 'multipart/form-data')); ?>
			<div class="in">
				<div class="grid_12">
					
					<div class="grid-3-12"><?php echo CHtml::label(at('Select File'), 'file'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::fileField('file', '', array('class' => 'validate[required]')); ?>
					</div>
					<div class="clear"></div>
					<hr />
					
					<div class="grid-3-12"><?php echo CHtml::label(at('Update Strings if language exists'), 'update'); ?></div>
					<div class="grid-9-12">
						<?php echo CHtml::checkBox('update', false); ?>
						<br /><span class="subtip"><?php echo at('If you are uploading a language that already exists then check this box to update the translation strings otherwise an error will be shown that the language exists already.'); ?></span>
					</div>
					<div class="clear"></div>
					<hr />
					
				</div>
				<div class="clear"></div>
			</div>
			
			<!--Form footer begin-->
			<section class="box_footer">
				<div class="grid-12-12">
					<input type="submit" class="right button green" name='submit' value="<?php echo at('Import'); ?>" />
				</div>
				<div class="clear"></div>
			</section>
			<!--Form footer end-->
			<?php echo CHtml::endForm(); ?>
		</div>
	</div>
</section>
<div class="clear"></div>
<?php endif; ?>