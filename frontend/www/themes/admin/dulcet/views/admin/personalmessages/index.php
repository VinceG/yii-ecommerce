<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Personal Messages'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid-2-12">
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create New Message',
						'url' => array('create'),
					    'type'=>'primary',
					)); ?>
				</div>	
					
				<div class="grid-10-12">
					<?php echo CHtml::beginForm(array('personalmessages/index'), 'get', array('class' => 'formee')); ?>
						<?php echo CHtml::textField('term', getRParam('term'), array('placeholder' => at('Search Personal Messages'), 'size' => 40, 'style' => 'width: 50%;')); ?>
						<?php echo CHtml::submitButton(at('Search')); ?>
					<?php echo CHtml::endForm(); ?>
				</div>
					
				<div class="clear"></div>	
				
				<div class="grid_12">	
					<?php bp('PM Index'); ?>
					<?php $this->widget('bootstrap.widgets.BootGridView', array(
					    'type'=>'striped bordered condensed',
					    'dataProvider'=>$model->search(getRParam('term')),
					    'columns'=>array(
					        array('name'=>'id', 'header'=>'#'),
					        array('name'=>'title', 'header'=>'Title', 'type' => 'raw', 'value' => '$data->getTopicTitle()'),
							array('name'=>'type', 'header'=>'Type', 'value' => '$data->getType()'),
							array('name'=>'repliesCount', 'header'=>'Replies', 'value' => '$data->repliesCount'),
							array('name'=>'participantsCount', 'header'=>'Participants', 'value' => '$data->participantsCount'),
					        array('name'=>'created_at', 'header'=>'Created Date', 'value' => 'timeSince($data->created_at)'),
							array('name'=>'author_id', 'header'=>'Author', 'type' => 'raw', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->getAuthorLink()'),
							array('name'=>'last_reply_created_at', 'header'=>'Last Reply Date', 'value' => 'timeSince($data->last_reply_created_at)'),
							array('name'=>'last_reply_author_id', 'header'=>'Last Reply Author', 'type' => 'raw', 'htmlOptions'=>array('style'=>'width: 100px'), 'value' => '$data->lastReplyAuthor->getUserLink()'),
							array(
								'template' => '{remove}',
					            'class'=>'bootstrap.widgets.BootButtonColumn',
					            'htmlOptions'=>array('style'=>'width: 50px'),
								'buttons'=>array(
										'remove' => array(
												'label' => '<i class="icon-trash"></i>',
		                                        'options'=>array('title'=>at('Delete Topic'), 'class' => 'delete'),                           
		                                        'url' => 'Yii::app()->createUrl("/admin/personalmessages/delete", array("id"=>$data->id))',
												'visible' => 'Yii::app()->user->id == $data->author_id || checkAccess("op_personalmessages_manage_topics")'
		                                ),
		                        ),
					        ),
					    ),
					)); ?>
					<?php ep('PM Index'); ?>
					<?php $this->widget('bootstrap.widgets.BootButton', array(
					    'label'=>'Create New Message',
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