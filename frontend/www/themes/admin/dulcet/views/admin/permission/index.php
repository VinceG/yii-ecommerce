<section class="grid_12">
	<h2><?php echo at('Permissions Manager'); ?></h2>
	<hr />
	<div class="ui_tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo at('Roles'); ?></a></li>
			<li><a href="#tabs-2"><?php echo at('Tasks'); ?></a></li>
			<li><a href="#tabs-3"><?php echo at('Operations'); ?></a></li>
		</ul>
		<div id="tabs-1">
			<?php $this->widget('bootstrap.widgets.BootButton', array(
			    'label'=>'Create Role',
				'url' => array('create', 'type' => CAuthItem::TYPE_ROLE),
			    'type'=>'primary',
			)); ?>
			
			<?php $this->widget('bootstrap.widgets.BootGridView', array(
			    'type'=>'striped bordered condensed',
			    'dataProvider'=>$roles->search(CAuthItem::TYPE_ROLE),
				'filter' => $roles,
			    'columns'=>array(
			        array('name'=>'name', 'header'=>'Name'),
			        array('name'=>'description', 'header'=>'Description'),
					array('header' => 'Childs', 'value' => '$data->getChildsCount()'),
			        array(
			            'class'=>'bootstrap.widgets.BootButtonColumn',
			            'htmlOptions'=>array('style'=>'width: 50px'),
			        ),
			    ),
			)); ?>
			
			<?php $this->widget('bootstrap.widgets.BootButton', array(
			    'label'=>'Create Role',
				'url' => array('create', 'type' => CAuthItem::TYPE_ROLE),
			    'type'=>'primary',
			)); ?>
		</div>
		<div id="tabs-2">
			<?php $this->widget('bootstrap.widgets.BootButton', array(
			    'label'=>'Create Task',
				'url' => array('create', 'type' => CAuthItem::TYPE_TASK),
			    'type'=>'primary',
			)); ?>
			
			<?php $this->widget('bootstrap.widgets.BootGridView', array(
			    'type'=>'striped bordered condensed',
			    'dataProvider'=>$roles->search(CAuthItem::TYPE_TASK),
				'filter' => $roles,
			    'columns'=>array(
			        array('name'=>'name', 'header'=>'Name'),
			        array('name'=>'description', 'header'=>'Description'),
					array('header' => 'Childs', 'value' => '$data->getChildsCount()'),
			        array(
			            'class'=>'bootstrap.widgets.BootButtonColumn',
			            'htmlOptions'=>array('style'=>'width: 50px'),
			        ),
			    ),
			)); ?>
			
			<?php $this->widget('bootstrap.widgets.BootButton', array(
			    'label'=>'Create Task',
				'url' => array('create', 'type' => CAuthItem::TYPE_TASK),
			    'type'=>'primary',
			)); ?>
		</div>
		<div id="tabs-3">
			<?php $this->widget('bootstrap.widgets.BootButton', array(
			    'label'=>'Create Operation',
				'url' => array('create', 'type' => CAuthItem::TYPE_OPERATION),
			    'type'=>'primary',
			)); ?>
			
			<?php $this->widget('bootstrap.widgets.BootGridView', array(
			    'type'=>'striped bordered condensed',
			    'dataProvider'=>$roles->search(CAuthItem::TYPE_OPERATION),
				'filter' => $roles,
			    'columns'=>array(
			        array('name'=>'name', 'header'=>'Name'),
			        array('name'=>'description', 'header'=>'Description'),
			        array('header' => 'Childs', 'value' => '$data->getChildsCount()'),
			        array(
			            'class'=>'bootstrap.widgets.BootButtonColumn',
			            'htmlOptions'=>array('style'=>'width: 50px'),
			        ),
			    ),
			)); ?>
			
			<?php $this->widget('bootstrap.widgets.BootButton', array(
			    'label'=>'Create Operation',
				'url' => array('create', 'type' => CAuthItem::TYPE_OPERATION),
			    'type'=>'primary',
			)); ?>
		</div>
	</div>
</section>