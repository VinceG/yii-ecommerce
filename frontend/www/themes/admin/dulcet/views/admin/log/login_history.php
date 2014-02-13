<section class="grid_12">
	<div class="box">
		<div class="title">
			<?php echo at('Admin Login History'); ?>
		</div>

		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<?php $this->widget('bootstrap.widgets.BootGridView', array(
					    'type'=>'striped bordered condensed',
					    'dataProvider'=>$model->search(),
						'filter' => $model,
					    'columns'=>array(
					        array('name'=>'created_at', 'filter' => false, 'htmlOptions'=>array('style'=>'width: 100px'), 'header'=>'Created Date', 'value' => 'timeSince($data->created_at)'),
							array('name'=>'username', 'header'=>'Username', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->username'),
							array('name'=>'password', 'header'=>'Password', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->password'),
							array('name'=>'ip_address', 'header'=>'IP', 'htmlOptions'=>array('style'=>'width: 80px')),
							array('name'=>'browser', 'header'=>'Browser', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->browser ? ucfirst($data->browser) : "N/A"'),
							array('name'=>'platform', 'header'=>'Platform', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->platform ? ucfirst($data->platform) : "N/A"'),
							array('name'=>'is_ok', 'header'=>'Logged In?', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->is_ok ? "Yes" : "No"'),
					    ),
					)); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
</section>
<div class="clear"></div>