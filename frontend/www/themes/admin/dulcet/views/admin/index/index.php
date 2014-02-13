<section class="grid_12">
	<div class="ui_tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo at('Staff Messages'); ?></a></li>
			<li><a href="#tabs-2"><?php echo at('Admin Logged In ({total})', array('{total}' => AdminUser::model()->totalLoggedIn())); ?></a></li>
			<li><a href="#tabs-3"><?php echo at('Admin Login History'); ?></a></li>
			<li><a href="#tabs-4"><?php echo at('Admin Latest Logs'); ?></a></li>
		</ul>
		<div id="tabs-1">
			<div class="inside">
					<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
					<div class="in">
						<?php bp('staff message'); ?>
						<?php Yii::app()->customEditor->getEditor(array('name' => 'dashboard_staff_message', 'value' => getParam('dashboard_staff_message'))); ?>
						<?php ep('staff message'); ?>
					</div>

				<!--Form footer begin-->
				<section class="box_footer">
					<div class="grid-12-12">
						<input type="submit" name='submit' class="right button green" value="<?php echo at('Update'); ?>" />
					</div>
					<div class="clear"></div>
				</section>
				<!--Form footer end-->
				<?php echo CHtml::endForm(); ?>
			</div>
		</div>
		
		<div id="tabs-2">
			<?php $this->widget('bootstrap.widgets.BootGridView', array(
			    'type'=>'striped bordered condensed',
			    'dataProvider'=>new CActiveDataProvider('AdminUser', 
								array(
		                       	   'criteria' => array(
														'with' => array('user'),
														'order' => 'lastclick_time DESC'
														),
			                       'pagination' => false,
			                       'sort' => false,
		                       )),
			    'columns'=>array(
			    	array('name'=>'user_id', 'header'=>'User', 'type' => 'raw', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->getUserLink()'),
					array('name'=>'loggedin_time', 'htmlOptions'=>array('style'=>'width: 100px'), 'header'=>'Logged In', 'value' => 'timeSince($data->loggedin_time)'),
					array('name'=>'lastclick_time', 'htmlOptions'=>array('style'=>'width: 100px'), 'header'=>'Last Click', 'value' => 'timeSince($data->lastclick_time)'),
					array('name'=>'location', 'header'=>'Location', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->location ? ucfirst($data->location) : "N/A"'),
				),
			)); ?>
		</div>
		<div id="tabs-3">
			<?php $this->widget('bootstrap.widgets.BootGridView', array(
			    'type'=>'striped bordered condensed',
			    'dataProvider'=>new CActiveDataProvider('AdminLoginHistory', 
								array(
		                       	   'criteria' => array(
														'limit' => getParam('admin_dashboard_latest_login_history', 10),
														'order' => 't.id DESC'
														),
			                       'pagination' => false,
			                       'sort' => false,
		                       )),
			    'columns'=>array(
			        array('name'=>'created_at', 'htmlOptions'=>array('style'=>'width: 100px'), 'header'=>'Created Date', 'value' => 'timeSince($data->created_at)'),
					array('name'=>'username', 'header'=>'Username', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->username'),
					array('name'=>'password', 'header'=>'Password', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->password'),
					array('name'=>'ip_address', 'header'=>'IP', 'htmlOptions'=>array('style'=>'width: 80px')),
					array('name'=>'browser', 'header'=>'Browser', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->browser ? ucfirst($data->browser) : "N/A"'),
					array('name'=>'platform', 'header'=>'Platform', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->platform ? ucfirst($data->platform) : "N/A"'),
					array('name'=>'is_ok', 'header'=>'Logged In?', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->is_ok ? "Yes" : "No"'),
			    ),
			)); ?>
		</div>
		<div id="tabs-4">
			<?php $this->widget('bootstrap.widgets.BootGridView', array(
			    'type'=>'striped bordered condensed',
			    'dataProvider'=>new CActiveDataProvider('AdminLog', 
								array(
		                       	   'criteria' => array(
														'with' => array('user'),
														'limit' => getParam('admin_dashboard_latest_logs', 10),
														'order' => 't.id DESC'
														),
			                       'pagination' => false,
			                       'sort' => false,
		                       )),
			    'columns'=>array(
			        array('name'=>'created_at', 'filter' => false, 'htmlOptions'=>array('style'=>'width: 150px'), 'header'=>'Created Date', 'value' => 'timeSince($data->created_at)'),
			        array('name'=>'note', 'header'=>'Note'),
					array('name'=>'user_id', 'header'=>'User', 'type' => 'raw', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->getUserLink()'),
					array('name'=>'ip_address', 'header'=>'IP', 'htmlOptions'=>array('style'=>'width: 80px')),
					array('name'=>'controller', 'header'=>'Controller', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->controller ? ucfirst($data->controller) : "N/A"'),
					array('name'=>'action', 'header'=>'Action', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->action ? ucfirst($data->action) : "N/A"'),
			    ),
			)); ?>
		</div>
	</div>
	
</section>
