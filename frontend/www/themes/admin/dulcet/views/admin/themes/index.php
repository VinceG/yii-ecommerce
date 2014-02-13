<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Theme Manager'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid-4-12">
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>at('Create Theme'),
						'url' => array('create'),
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
							array('name'=>'dirname'),
							array('name'=>'author'),
							array('name'=>'author_site'),
							array('name'=>'total_files', 'header' => at('Total Files'), 'value' => 'numberFormat($data->filesCount)'),
							array('name'=>'total_source_files', 'header' => at('Total Source Files'), 'value' => 'numberFormat($data->getTotalSourceFiles())'),
							array('name'=>'created_at', 'value' => 'timeSince($data->created_at)'),
							array('name' => 'is_active', 'value' => '$data->is_active ? at("Active") : at("Hidden")'),
							array(
								'template' => '{view}{update}{delete}{sync}',
					            'class'=>'bootstrap.widgets.BootButtonColumn',
					            'htmlOptions'=>array('style'=>'width: 100px'),
								'buttons'=>array(
										'sync' => array(
												'label' => '<i class="icon-refresh"></i>',
		                                        'options'=>array('title'=>at('Sync Theme Files'), 'class' => 'sync'),                           
		                                        'url' => 'Yii::app()->createUrl("/admin/themes/sync", array("id"=>$data->id))',
												'visible' => 'checkAccess("op_theme_sync")'
		                                ),
		                        ),
					        ),
					    ),
					)); ?>
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create Theme',
						'url' => array('create'),
					    'type'=>'primary',
					)); ?>
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>at('Sync All'),
						'url' => array('syncall'),
					    'type'=>'primary',
					)); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
</section>
<div class="clear"></div>