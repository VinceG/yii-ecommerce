<section class="grid_12">
	<div class="box">
		<div class="title">
			<?php echo at('Admin Logs'); ?>
			<?php if($user): ?>
				- <?php echo at('Viewing logs for {name}', array('{name}' => $user->name)); ?>
			<?php endif; ?>	
		</div>

		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<?php $this->widget('bootstrap.widgets.BootGridView', array(
					    'type'=>'striped bordered condensed',
					    'dataProvider'=>$model->search(getRParam('user')),
						'filter' => $model,
					    'columns'=>array(
					        array('name'=>'id', 'header'=>'#', 'htmlOptions'=>array('style'=>'width: 30px')),
					        array('name'=>'created_at', 'filter' => false, 'htmlOptions'=>array('style'=>'width: 100px'), 'header'=>'Created Date', 'value' => 'dateTime( $data->created_at)'),
					        array('name'=>'note', 'header'=>'Note'),
							array('name'=>'user_id', 'header'=>'User', 'type' => 'raw', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->getUserLink()'),
							array('name'=>'ip_address', 'header'=>'IP', 'htmlOptions'=>array('style'=>'width: 80px')),
							array('name'=>'controller', 'header'=>'Controller', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->controller ? ucfirst($data->controller) : "N/A"'),
							array('name'=>'action', 'header'=>'Action', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->action ? ucfirst($data->action) : "N/A"'),
					    ),
					)); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
</section>
<div class="clear"></div>