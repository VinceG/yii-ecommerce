<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Email Templates'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create Template',
						'url' => array('create'),
					    'type'=>'primary',
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootGridView', array(
					    'type'=>'striped bordered condensed',
					    'dataProvider'=>$model->search(),
					    'columns'=>array(
					        array('name'=>'id', 'header'=>'#'),
					        array('name'=>'title'),
							array('name'=>'email_key'),
					        array('name'=>'created_at', 'value' => 'timeSince($data->created_at)'),
							array('name'=>'author_id', 'type' => 'raw', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->getAuthorLink("author")'),
							array('name'=>'updated_at', 'value' => 'timeSince($data->updated_at)'),
					        array('name'=>'updated_author_id', 'type' => 'raw', 'htmlOptions'=>array('style'=>'width: 150px'), 'value' => '$data->getAuthorLink("last_author")'),
							array(
					            'class'=>'bootstrap.widgets.BootButtonColumn',
					            'htmlOptions'=>array('style'=>'width: 50px'),
					        ),
					    ),
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create Template',
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