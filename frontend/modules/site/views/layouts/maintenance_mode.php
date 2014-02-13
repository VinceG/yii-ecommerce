<!DOCTYPE html>
<html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo t('Maintenance Mode'); ?></title>

<!--google web font-->
<link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic' rel='stylesheet' type='text/css'>

<!--style sheets-->
<link rel="stylesheet" media="screen" href="<?php echo getThemeBaseUrl(); ?>/css/maintenance.css"/>

<!--jquery libraries / others are at the bottom-->
<?php cs()->registerCoreScript('jquery'); ?>
</head>
<body>
<!--wrapper starts-->
<div id="wrapper"> 
  <!--content starts-->
  <div id="content">
    <div class="launch"></div>
    
    <!--divider with heading at center-->
    <div class="divider">
      <h4><?php echo t('Maintenance Mode'); ?></h4>
    </div>
    
	<?php echo getParam('maintenance_message'); ?>

  </div>
  <!--content ends--> 
  
  <div class="clear"></div>
</div>
<!--wrapper ends--> 
</body>
</html>