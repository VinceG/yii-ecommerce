<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Error!'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<h2><?php echo $error['message']; ?></h2>
					
					<?php if(YII_DEBUG): ?>
						<pre>
						<?php print_r($error); ?>
						</pre>
					<?php endif; ?>
					
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
</section>
<div class="clear"></div>