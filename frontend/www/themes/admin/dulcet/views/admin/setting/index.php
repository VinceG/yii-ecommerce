<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Settings Manager'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Add Setting Group',
						'url' => array('addgroup'),
					    'type'=>'primary',
					)); ?>
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Add Setting',
						'url' => array('addsetting'),
					    'type'=>'primary',
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootGridView', array(
					    'type'=>'striped bordered condensed',
					    'dataProvider'=>$model->search(),
					    'columns'=>array(
					        array('name'=>'title', 'header'=>'Title'),
					        array('name'=>'description', 'header'=>'Description'),
							//array('name'=>'groupkey', 'header'=>'Key'),
							array('name'=>'count', 'header'=>'Settings'),
						 	array(
								'class'=>'bootstrap.widgets.BootButtonColumn',
								'template'=>'{view}{update}{deletegroup}',
							    'buttons'=>array(
									'create' => array(
							            'label'=>'Add Setting',
							            'url'=>'Yii::app()->createUrl("admin/setting/addsetting", array("cid"=>$data->id))',
							        ),
							        'view' => array(
							            'label'=>'View',
							            'url'=>'Yii::app()->createUrl("admin/setting/viewgroup", array("id"=>$data->id))',
							        ),
							        'update' => array(
							            'label'=>'Edit',
										'url'=>'Yii::app()->createUrl("admin/setting/editgroup", array("id"=>$data->id))',
							        ),
									'deletegroup' => array(
							            'label'=>'<i class="icon-trash"></i>',
										'options' => array('class' => 'delete'),
										'url'=>'Yii::app()->createUrl("admin/setting/deletegroup", array("id"=>$data->id))',
							        ),
							    ),
					            'htmlOptions'=>array('style'=>'width: 50px'),
					        ),
					    ),
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Add Setting Group',
						'url' => array('addgroup'),
					    'type'=>'primary',
					)); ?>
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Add Setting',
						'url' => array('addsetting'),
					    'type'=>'primary',
					)); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
</section>
<div class="clear"></div>