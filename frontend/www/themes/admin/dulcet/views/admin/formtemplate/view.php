<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo CHtml::encode(Yii::app()->formtags->replaceTags($model->title));?></div>
		<div class='inside'>
			<div class="in">
				<div class="grid_12">
					<?php echo sh(Yii::app()->formtags->replaceTags($model->content)); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>		
</section>