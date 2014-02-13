<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('US Cities'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create City',
						'url' => array('create'),
					    'type'=>'primary',
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootGridView', array(
					    'type'=>'striped bordered condensed',
					    'dataProvider'=>$model->search(),
						'filter' => $model,
					    'columns'=>array(
					        array('name'=>'id'),
					        array('name'=>'city_name'),
					        array('name'=>'city_state'),
							array('name'=>'city_county'),
							array('name'=>'city_zip'),
							array('name'=>'city_latitude'),
							array('name'=>'city_longitude'),
							array(
								'template' => '{update}{delete}',
					            'class'=>'bootstrap.widgets.BootButtonColumn',
					            'htmlOptions'=>array('style'=>'width: 50px'),
					        ),
					    ),
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create City',
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