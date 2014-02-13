<section class="grid_12">

	<h2><?php echo at("Viewing User '{name}'", array('{name}' => $model->name)) ?></h2>
	<hr />
	<div class="ui_tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo at('Basic Information'); ?></a></li>
			<li><a href="#tabs-2"><?php echo at('Personal Information'); ?></a></li>
			<li><a href="#tabs-3"><?php echo at('Custom Fields'); ?></a></li>
			<li><a href="#tabs-4"><?php echo at('Shipping & Billing Information'); ?></a></li>
		</ul>
		<div id="tabs-1">
			<?php $this->widget('bootstrap.widgets.BootDetailView', array(
			    'data'=>$model->attributes,
			    'attributes'=>array(
			        array('name'=>'name', 'label'=>'Name'),
			        array('name'=>'email', 'label'=>'Email'),
					array('name'=>'role', 'label'=>'Role'),
			        array('name'=>'notes', 'label'=>'Notes'),
					array('name'=>'created_at', 'header'=>'Created Date', 'value' => timeSince($model->created_at)),
					array('name'=>'updated_at', 'header'=>'Updated Date', 'value' => timeSince($model->updated_at)),
			    ),
			)); ?>
		</div>
		
		<div id="tabs-2">
			<?php $this->widget('bootstrap.widgets.BootDetailView', array(
			    'data'=>$model->attributes,
			    'attributes'=>array(
			        array('name'=>'first_name'),
			        array('name'=>'last_name'),
					array('name'=>'birth_date'),
			        array('name'=>'company'),
					array('name'=>'contact'),
					array('name'=>'home_phone'),
					array('name'=>'cell_phone'),
					array('name'=>'work_phone'),
					array('name'=>'fax'),
			    ),
			)); ?>
		</div>
		
		<div id="tabs-3">
			<?php $this->widget('bootstrap.widgets.BootDetailView', array(
			    'data'=>User::model()->getFieldsData($model->id),
				'attributes'=>User::model()->getFieldsAttributes($model->id),
			)); ?>
		</div>
		
		<div id="tabs-4">
			<?php $this->widget('bootstrap.widgets.BootDetailView', array(
			    'data'=>$model->attributes,
			    'attributes'=>array(
					array('name'=>'billing_contact'),
			        array('name'=>'billing_address1'),
					array('name'=>'billing_address2'),
			        array('name'=>'billing_city'),
					array('name'=>'billing_state', 'value' => $model->getBillingStateName()),
					array('name'=>'billing_zip'),
					array('name'=>'billing_country', 'value' => $model->getBillingCountryName()),

			        array('name'=>'shipping_contact'),
			        array('name'=>'shipping_address1'),
					array('name'=>'shipping_address2'),
			        array('name'=>'shipping_city'),
					array('name'=>'shipping_state', 'value' => $model->getShippingStateName()),
					array('name'=>'shipping_zip'),
					array('name'=>'shipping_country', 'value' => $model->getShippingCountryName()),
					
			    ),
			)); ?>
		</div>
	</div>
</section>