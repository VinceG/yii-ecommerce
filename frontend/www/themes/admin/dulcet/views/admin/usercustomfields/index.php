<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('User Custom Fields'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create Field',
						'url' => array('create'),
					    'type'=>'primary',
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootGridView', array(
					    'type'=>'striped bordered condensed',
					    'dataProvider'=>$model->search(),
						'filter' => $model,
					    'columns'=>array(
					        array('name'=>'id', 'header'=>'#', 'htmlOptions' => array( 'style' => 'width:50px;')),
					        array('name'=>'title', 'header'=>'Title'),
					        array('name'=>'type', 'filter' => UserCustomField::model()->fieldType, 'header'=>'Type'),
							array('name'=>'status', 'filter' => array(0 => at('Public'), 1 => at('Hidden')), 'header'=>'Status', 'value' => '$data->status ? at("Public") : at("Hidden")'),
							array('name'=>'is_public', 'filter' => array(0 => at('Yes'), 1 => at('No')), 'header'=>'On Site', 'value' => '$data->is_public ? at("Yes") : at("No")'),
							array('name'=>'is_editable', 'filter' => array(0 => at('Yes'), 1 => at('No')), 'header'=>'Editable', 'value' => '$data->is_editable ? at("Yes") : at("No")'),
					        array('name'=>'created_at', 'filter' => false, 'header'=>'Created Date', 'value' => 'timeSince($data->created_at)'),
							array('name'=>'author_id', 'filter' => false, 'header'=>'Author', 'type' => 'raw', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->getAuthorLink("author")'),
							array('name'=>'updated_at', 'filter' => false, 'header'=>'Updated Date', 'value' => 'timeSince($data->updated_at)'),
					        array('name'=>'updated_author_id', 'filter' => false, 'header'=>'Last Author', 'type' => 'raw', 'htmlOptions'=>array('style'=>'width: 150px'), 'value' => '$data->getAuthorLink("last_author")'),
							array(
					            'class'=>'bootstrap.widgets.BootButtonColumn',
					            'htmlOptions'=>array('style'=>'width: 50px'),
								'template' => '{update}{delete}',
					        ),
					    ),
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create Field',
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