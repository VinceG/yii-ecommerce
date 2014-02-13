<section class="grid_12">

	<h2><?php echo at("Viewing Permission '{name}'", array('{name}' => $model->name)) ?></h2>
	<hr />
	
	<div class="ui_tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo at('Basic Information'); ?></a></li>
			<li><a href="#tabs-2"><?php echo at('Childs'); ?></a></li>
		</ul>
		<div id="tabs-1">
			<?php $this->widget('bootstrap.widgets.BootDetailView', array(
			    'data'=>$model->attributes,
			    'attributes'=>array(
			        array('name'=>'name', 'label'=>'Name'),
			        array('name'=>'description', 'label'=>'Description'),
			        array('name'=>'type', 'label'=>'Type', 'value' => isset($model->types[$model->type]) ? $model->types[$model->type] : 'N/A'),
					array('name'=>'bizrule', 'label'=>'BizRule'),
					array('name'=>'data', 'label'=>'Data'),
			    ),
			)); ?>
			
			<?php $this->widget('bootstrap.widgets.BootButton', array(
			    'label'=>'Manage Child Items',
				'url' => array('addItemChild', 'parent' => $model->name),
			    'type'=>'primary',
			)); ?>
		</div>
		<div id="tabs-2">
			<?php $childs = $model->getChilds(); ?>
			<?php if(count($childs)): ?>
				<ol>
				<?php foreach($childs as $child): ?>
					<li><?php echo $child->child; ?></li>
				<?php endforeach; ?>
				</ol>
			<?php else: ?>
				<h3><?php echo at('There is no data to display.'); ?></h3>		
			<?php endif; ?>
			
			<?php $this->widget('bootstrap.widgets.BootButton', array(
			    'label'=>'Manage Child Items',
				'url' => array('addItemChild', 'parent' => $model->name),
			    'type'=>'primary',
			)); ?>
		</div>
	</div>
</section>