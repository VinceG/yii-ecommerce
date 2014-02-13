<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Users'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create User',
						'url' => array('create'),
					    'type'=>'primary',
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootGridView', array(
					    'type'=>'striped bordered condensed',
					    'dataProvider'=>$model->search(),
						'filter' => $model,
					    'columns'=>array(
					        array('name'=>'id', 'header'=>'#', 'htmlOptions' => array( 'style' => 'width:50px;') ),
					        array('name'=>'name', 'header'=>'Username'),
							array('name'=>'first_name', 'header'=>'First Name'),
							array('name'=>'last_name', 'header'=>'Last Name'),
							array('name'=>'contact', 'header'=>'Contact'),
							array('name'=>'company', 'header'=>'Company'),
					        array('name'=>'email', 'header'=>'Email'),
							array('name'=>'role', 'header'=>'Role', 'filter' => CHtml::listData(AuthItem::model()->findAll('type=:type', array(':type' => CAuthItem::TYPE_ROLE)),'name','name')),
					        array('name'=>'created_at', 'filter' => false, 'header'=>'Created Date', 'value' => 'timeSince($data->created_at, "short", null)'),
							array('name'=>'updated_at', 'filter' => false, 'header'=>'Updated Date', 'value' => 'timeSince($data->updated_at, "short", null)'),
							array('name'=>'last_visited', 'filter' => false, 'header'=>'Last Visited', 'value' => 'timeSince($data->last_visited, "short", null)'),
					        array(
					            'class'=>'bootstrap.widgets.BootButtonColumn',
					            'htmlOptions'=>array('style'=>'width: 50px'),
					        ),
					    ),
					)); ?>
					
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create User',
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