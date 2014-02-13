<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Media Manager'); ?></div>
		<div class='inside'>
			<div class="in">
				<div class="grid_12">
					<div id="elfinder"></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>		
</section>

<?php $this->widget('application.widgets.elfinder.FinderWidget', array('path' => getUploadsPath(), 'url' => getUploadsUrl(), 'action' => $this->createUrl('/admin/media/elfinder.connector'))); ?>