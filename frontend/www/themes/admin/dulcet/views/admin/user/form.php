<?php cs()->registerScriptFile(themeUrl('js/modules/city.js'), CClientScript::POS_END); ?>

<section class="grid_12">
	<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
	<h2><?php echo $model->isNewRecord ? at('Create User') : at('Update User'); ?></h2>
	<hr />
	<div class="ui_tabs">
		<ul>
			<li><a class='tabs-header-class' href="#tabs-1"><?php echo at('Basic Information'); ?></a></li>
			<li><a class='tabs-header-class' href="#tabs-2"><?php echo at('Personal Information'); ?></a></li>
			<li><a class='tabs-header-class' href="#tabs-3"><?php echo at('Custom Fields'); ?></a></li>
			<li><a class='tabs-header-class' href="#tabs-4"><?php echo at('Roles & Permissions'); ?></a></li>
			<li><a class='tabs-header-class' href="#tabs-5"><?php echo at('Shipping & Billing Information'); ?></a></li>
		</ul>
		<div id="tabs-1">
			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'name'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::activeTextField($model, 'name', array('class' => 'validate[required]')); ?>
				<?php echo CHtml::error($model, 'name'); ?>
			</div>
			<div class="clear"></div>
			<hr />


			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'email'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::activeTextField($model, 'email', array('class' => 'validate[required,custom[email]]')); ?>
				<?php echo CHtml::error($model, 'email'); ?>
			</div>
			<div class="clear"></div>
			<hr />

			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'role'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::activeDropDownList($model, 'role', CHtml::listData(AuthItem::model()->findAll('type=:type', array(':type' => CAuthItem::TYPE_ROLE)), 'name', 'name'), array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select validate[required]')); ?>
				<?php echo CHtml::error($model, 'role'); ?>
			</div>
			<div class="clear"></div>
			<hr />

			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'new_password'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::activePasswordField($model, 'new_password', array('class' => 'validate[minSize[6]]')); ?>
				<?php echo CHtml::error($model, 'new_password'); ?>
			</div>
			<div class="clear"></div>
			<hr />

			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'notes'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::activeTextArea($model, 'notes', array('class' => 'txt_autogrow')); ?>
			</div>
			<div class="clear"></div>
			<hr />
		</div>
		
		<div id="tabs-2">			
			
			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'first_name'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::activeTextField($model, 'first_name', array('class' => '')); ?>
				<?php echo CHtml::error($model, 'first_name'); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'last_name'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::activeTextField($model, 'last_name', array('class' => '')); ?>
				<?php echo CHtml::error($model, 'last_name'); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'birth_date'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::activeTextField($model, 'birthdate', array('readonly' => 'readonly', 'class' => 'datePickerBirthDate')); ?>
				<?php echo CHtml::error($model, 'birth_date'); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'company'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::activeTextField($model, 'company', array('class' => '')); ?>
				<?php echo CHtml::error($model, 'company'); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'contact'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::activeTextField($model, 'contact', array('class' => '')); ?>
				<?php echo CHtml::error($model, 'contact'); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'home_phone'); ?></div>
			<div class="grid-9-12">
				<?php $this->widget('CMaskedTextField', array('mask'=>'999 999 9999', 'model' => $model, 'attribute'=>'home_phone')); ?>
				<?php echo CHtml::error($model, 'home_phone'); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'cell_phone'); ?></div>
			<div class="grid-9-12">
				<?php $this->widget('CMaskedTextField', array('mask'=>'999 999 9999', 'model' => $model, 'attribute'=>'cell_phone')); ?>
				<?php echo CHtml::error($model, 'cell_phone'); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'work_phone'); ?></div>
			<div class="grid-9-12">
				<?php $this->widget('CMaskedTextField', array('mask'=>'999 999 9999', 'model' => $model, 'attribute'=>'work_phone')); ?>
				<?php echo CHtml::error($model, 'work_phone'); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
			<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'fax'); ?></div>
			<div class="grid-9-12">
				<?php $this->widget('CMaskedTextField', array('mask'=>'999 999 9999', 'model' => $model, 'attribute'=>'fax')); ?>
				<?php echo CHtml::error($model, 'fax'); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
		</div>
		
		<div id="tabs-3">
			<?php $customFields = UserCustomField::model()->getFieldsForAdmin(); ?>
			<?php if(count($customFields)): ?>
				<?php foreach($customFields as $customField): ?>
					<div class="grid-3-12"><?php echo CHtml::label($customField->getTitle(), $customField->getKey()); ?></div>
					<div class="grid-9-12">
						<?php echo $customField->getFormField($model->id); ?>
					</div>
					<div class="clear"></div>
					<hr />
				<?php endforeach; ?>
			<?php else: ?>
				<b><?php echo at('There are no custom fields to display.'); ?></b>
			<?php endif; ?>
		</div>	
		
		<div id="tabs-4">
			
			<div class="grid-3-12"><?php echo CHtml::label(at('Additional Roles'), 'roles'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::listBox('roles', isset($_POST['roles']) ? $_POST['roles'] : isset($items_selected[ CAuthItem::TYPE_ROLE ]) ? $items_selected[ CAuthItem::TYPE_ROLE ] : '', $items[ CAuthItem::TYPE_ROLE ], array( 'size' => 20, 'multiple' => 'multiple' )); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
			<div class="grid-3-12"><?php echo CHtml::label(at('Additional Tasks'), 'tasks'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::listBox('tasks', isset($_POST['tasks']) ? $_POST['tasks'] : isset($items_selected[ CAuthItem::TYPE_TASK ]) ? $items_selected[ CAuthItem::TYPE_TASK ] : '', $items[ CAuthItem::TYPE_TASK ], array( 'size' => 20, 'multiple' => 'multiple')); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
			<div class="grid-3-12"><?php echo CHtml::label(at('Additional Operations'), 'operations'); ?></div>
			<div class="grid-9-12">
				<?php echo CHtml::listBox('operations', isset($_POST['operations']) ? $_POST['operations'] : isset($items_selected[ CAuthItem::TYPE_OPERATION ]) ? $items_selected[ CAuthItem::TYPE_OPERATION ] : '', $items[ CAuthItem::TYPE_OPERATION ], array( 'size' => 20, 'multiple' => 'multiple' )); ?>
			</div>
			<div class="clear"></div>
			<hr />
			
		</div>
		
		<div id="tabs-5">
			
			<div class="grid-6-12">
				<?php echo at('Type in the zipcode and fill in the billing information if the information exists in our databases.'); ?>
				<div class="clear"></div>
				<div class="grid-6-12">
					<?php echo CHtml::textField('zipcode', ''); ?>
				</div>
				<div class="grid-6-12">
					<?php echo CHtml::button(at('Check'), array('id' => 'get-city-info-by-zip')); ?>
					<?php echo CHtml::button(at('Load Into'), array('id' => 'load-city-info-by-zip')); ?>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="grid-6-12">
					<?php echo at('Copy information from billing into shipping and vice versa'); ?>
					<div class="clear"></div>
					<?php echo CHtml::button(at('Billing to Shipping'), array('id' => 'copy-info-from-billing-to-shipping')); ?>
					<?php echo CHtml::button(at('Shipping to Billing'), array('id' => 'copy-info-from-shipping-to-billing')); ?>
			</div>
			
			<div class="clear"></div>
			<hr />
			
			<div class="grid-6-12">
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'billing_contact'); ?></div>
				<div class="grid-8-12">
					<?php echo CHtml::activeTextField($model, 'billing_contact', array('class' => 'billing_info billing_contact')); ?>
					<?php echo CHtml::error($model, 'billing_contact'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'billing_address1'); ?></div>
				<div class="grid-8-12">
					<?php echo CHtml::activeTextField($model, 'billing_address1', array('class' => 'billing_info billing_address1')); ?>
					<?php echo CHtml::error($model, 'billing_address1'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'billing_address2'); ?></div>
				<div class="grid-8-12">
					<?php echo CHtml::activeTextField($model, 'billing_address2', array('class' => 'billing_info billing_address2')); ?>
					<?php echo CHtml::error($model, 'billing_address2'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'billing_city'); ?></div>
				<div class="grid-8-12">
					<?php
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					    'model'=>$model,
						'attribute' => 'billing_city',
					    'sourceUrl'=>$this->createUrl('city/GetCityNames'),
					    // additional javascript options for the autocomplete plugin
					    'options'=>array(
					        'minLength'=>4
					    ),
					    'htmlOptions'=>array(
							'class' => 'billing_info billing_city',
					    ),
					));
					?>
					<br /><span class="subtip"><?php echo at('Start typing in a city name and it will provide suggestion using autocomplete'); ?></span>
					<?php echo CHtml::error($model, 'billing_city'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'billing_zip'); ?></div>
				<div class="grid-8-12">
					<?php echo CHtml::activeTextField($model, 'billing_zip', array('class' => 'billing_info billing_zip')); ?>
					<?php echo CHtml::error($model, 'billing_zip'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'billing_state'); ?></div>
				<div class="grid-8-12">
					<?php echo CHtml::activeDropDownList($model, 'billing_state', CHtml::listData(USState::model()->byOrder()->findAll(), 'id', 'name'), array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select billing_info billing_state')); ?>
					<?php echo CHtml::error($model, 'billing_state'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'billing_country'); ?></div>
				<div class="grid-9-12">
					<?php echo CHtml::activeDropDownList($model, 'billing_country', CHtml::listData(Country::model()->byOrder()->findAll(), 'id', 'name'), array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select billing_info billing_country')); ?>
					<?php echo CHtml::error($model, 'billing_country'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
			</div>
			
			<div class="grid-6-12">
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'shipping_contact'); ?></div>
				<div class="grid-8-12">
					<?php echo CHtml::activeTextField($model, 'shipping_contact', array('class' => 'shipping_info shipping_contact')); ?>
					<?php echo CHtml::error($model, 'shipping_contact'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'shipping_address1'); ?></div>
				<div class="grid-8-12">
					<?php echo CHtml::activeTextField($model, 'shipping_address1', array('class' => 'shipping_info shipping_address1')); ?>
					<?php echo CHtml::error($model, 'shipping_address1'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'shipping_address2'); ?></div>
				<div class="grid-8-12">
					<?php echo CHtml::activeTextField($model, 'shipping_address2', array('class' => 'shipping_info shipping_address2')); ?>
					<?php echo CHtml::error($model, 'shipping_address2'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'shipping_city'); ?></div>
				<div class="grid-8-12">
					<?php
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					    'model'=>$model,
						'attribute' => 'shipping_city',
					    'sourceUrl'=>$this->createUrl('city/GetCityNames'),
					    // additional javascript options for the autocomplete plugin
					    'options'=>array(
					        'minLength'=>4,
					    ),
					    'htmlOptions'=>array(
							'class' => 'shipping_info shipping_city',
					    ),
					));
					?>
					<br /><span class="subtip"><?php echo at('Start typing in a city name and it will provide suggestion using autocomplete'); ?></span>
					<?php echo CHtml::error($model, 'shipping_city'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'shipping_zip'); ?></div>
				<div class="grid-8-12">
					<?php echo CHtml::activeTextField($model, 'shipping_zip', array('class' => 'shipping_info shipping_zip')); ?>
					<?php echo CHtml::error($model, 'shipping_zip'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-4-12"><?php echo CHtml::activeLabelEx($model, 'shipping_state'); ?></div>
				<div class="grid-8-12">
					<?php echo CHtml::activeDropDownList($model, 'shipping_state', CHtml::listData(USState::model()->byOrder()->findAll(), 'id', 'name'), array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select shipping_info shipping_state')); ?>
					<?php echo CHtml::error($model, 'shipping_state'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
				<div class="grid-3-12"><?php echo CHtml::activeLabelEx($model, 'shipping_country'); ?></div>
				<div class="grid-9-12">
					<?php echo CHtml::activeDropDownList($model, 'shipping_country', CHtml::listData(Country::model()->byOrder()->findAll(), 'id', 'name'), array('data-placeholder' => at('Please select one...'), 'prompt' => '', 'class' => 'chzn-select shipping_info shipping_country')); ?>
					<?php echo CHtml::error($model, 'shipping_country'); ?>
				</div>
				<div class="clear"></div>
				<hr />
				
			</div>
			
			<div class="clear"></div>
			
		</div>
		
		<!--Form footer begin-->
		<section class="box_footer">
			<div class="grid-12-12">
				<a href='<?php echo $this->createUrl('index'); ?>' class='right button'><?php echo at('Cancel'); ?></a>
				<input type="submit" class="right button green" value="<?php echo $model->isNewRecord ? at('Create') : at('Update'); ?>" />
			</div>
			<div class="clear"></div>
		</section>
		<!--Form footer end-->
		<?php echo CHtml::endForm(); ?>
	</div>
	
</section>