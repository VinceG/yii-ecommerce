<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Blog Posts'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create Post',
						'url' => array('create'),
					    'type'=>'primary',
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootGridView', array(
					    'type'=>'striped bordered condensed',
					    'dataProvider'=>$model->search(),
					    'columns'=>array(
					        array('name'=>'id', 'header'=>'#'),
					        array('name'=>'title', 'header'=>'Title'),
					        array('name'=>'alias', 'header'=>'Alias'),
							array('name'=>'status', 'header'=>'Status', 'value' => '$data->status ? "Public" : "Hidden"'),
					        array('name'=>'created_at', 'header'=>'Created Date', 'value' => 'timeSince($data->created_at)'),
							array('name'=>'author_id', 'header'=>'Author', 'type' => 'raw', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->getAuthorLink("author")'),
							array('name'=>'updated_at', 'header'=>'Updated Date', 'value' => 'timeSince($data->updated_at)'),
					        array('name'=>'updated_author_id', 'header'=>'Last Author', 'type' => 'raw', 'htmlOptions'=>array('style'=>'width: 150px'), 'value' => '$data->getAuthorLink("last_author")'),
							array(
					            'class'=>'bootstrap.widgets.BootButtonColumn',
					            'htmlOptions'=>array('style'=>'width: 50px'),
					        ),
					    ),
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create Post',
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