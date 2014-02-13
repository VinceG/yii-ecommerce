<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<title><?php echo implode(' | ', $this->title); ?></title>
	
	<!-- Global styles -->
	<link rel="stylesheet" type="text/css" href="<?php echo themeUrl('css/reset.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo themeUrl('css/grid.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo themeUrl('css/config.css'); ?>" />

	<?php cs()->registerCoreScript('jquery'); ?>
	<script type="text/javascript" src="<?php echo themeUrl('plugins/validator/js/languages/jquery.validationEngine-en.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo themeUrl('plugins/validator/js/jquery.validationEngine.js'); ?>"></script>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("form").validationEngine('attach', {promptPosition : "bottomRight", autoPositionUpdate : true});
	});
	</script>
</head>
<body>
	<section id="login_form">
		<div class="login_form_head"><?php echo at('Administration') ?></div>	
		<?php if(user()->hasFlash('error')): ?>
			<div class="small_alert error"><?php echo user()->getFlash('error'); ?></div>
		<?php endif; ?>
		<?php echo $content; ?>			
	</section><!-- End of #container -->

</body>
</html>
