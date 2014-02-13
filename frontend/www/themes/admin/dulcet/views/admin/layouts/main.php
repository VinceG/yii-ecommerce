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

		<!-- Plugin configuration (styles) -->
		<link rel="stylesheet" href="<?php echo themeUrl('css/plugin_config.css'); ?>" />
		
		<!--[if IE 8]><link rel="stylesheet" href="<?php echo themeUrl('css/ie8.css'); ?>" /><![endif]-->
        
		<?php
		Yii::app()->clientScript->registerMetaTag( 'noindex, nofollow', 'robots' );

		// We add a meta 'language' tag based on the currently viewed language
		Yii::app()->clientScript->registerMetaTag( Yii::app()->language, 'language', 'content-language' );
		?>

		<!-- = Global Scripts [required for template] 
		***************************************************************************************-->
		<?php cs()->registerCoreScript('jquery'); ?>
		<script type="text/javascript" src="<?php echo themeUrl('js/global_plugins_scripts.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('js/not_required/chosen.jquery.js'); ?>"></script>
	
		<script type="text/javascript" src="<?php echo themeUrl('plugins/lightbox/js/lightbox/jquery.lightbox.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('plugins/jqueryui/all/jquery-ui-1.8.16.custom.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('plugins/validator/js/languages/jquery.validationEngine-en.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('plugins/validator/js/jquery.validationEngine.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('plugins/dialogs/jquery-fallr-1.2.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('plugins/spin/jquery-spin.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('plugins/qtip/jquery.qtip.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('plugins/plupload/js/browserplus-min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('plugins/multiselect/js/ui.multiselect.js'); ?>"></script>			
		<script type="text/javascript" src="<?php echo themeUrl('plugins/alerts/javascript/jquery.toastmessage.js'); ?>"></script>	
		<script type="text/javascript" src="<?php echo themeUrl('plugins/prettify/prettify.js'); ?>"></script>

		<script type="text/javascript" src="<?php echo themeUrl('js/modernizr.custom.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('js/jquery.autogrowtextarea.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('js/jquery.autotab-1.1b.js'); ?>"></script>
		
		<!-- From JS Dir [plugin initialization] -->
		<script type="text/javascript" src="<?php echo themeUrl('js/head_scripts.js'); ?>"></script>
		
		<?php
		$jsVariables = array(
			'req' => array(
				'controller' => $this->id,
				'action' => $this->getAction()->id,
			),
			'settings' => array(
				'editor' => getParam('global_editor_type'),
				'pm_ajax_check_messages' => getParam('pm_ajax_check_messages'),
			),
		);
		cs()->registerScript('globalSettings', 'var $application = ' . CJSON::encode($jsVariables) . ';', CClientScript::POS_HEAD);
		?>
		
    </head>
    <body>	
		<section id="layout">
		
			<div class="logo_profile container_12">
				<div class="grid_6 logo_img">
					<img src="<?php echo themeUrl('images/logo.png'); ?>" alt="Logo" />
				</div>
				<div class="grid_6 profile_meta">
					<div class="user_meta">
						<div>
							<a href='http://en.gravatar.com' target='_blank'>
								<?php
								$this->widget('ext.yii-gravatar.YiiGravatar', array(
								    'email'=>Yii::app()->user->getModel()->email,
								    'size'=>80,
								    'emailHashed'=>false,
								    'htmlOptions'=>array(
								        'alt'=>'Gravatar image',
								        'title'=>'Gravatar image',
								    )
								)); ?>
							</a>
						</div>
						<div class="name">
							<?php echo t('Welcome {name}', 'global', array('{name}' => user()->name)); ?><br />
							<a href="<?php echo $this->createUrl('user/view', array('id' => user()->id)); ?>" class="submeta"><?php echo t('Profile'); ?></a>
							<a href="<?php echo $this->createUrl('login/logout'); ?>" class="submeta"><?php echo t('Logout'); ?></a>
						</div>
					</div>
					
					<div class="user_meta user_meta_extended">
						<?php $title = PersonalMessageTopic::model()->getUserNotificationCount(Yii::app()->user->id); ?>
						<?php echo at("You Have {link} New Personal Messages. <br />{create}", array('{create}' => CHtml::link(at('Compose Message'), 'javascript:', array('id' => 'create-message')), '{link}' => CHtml::link($title, array('personalmessages/index'), array('class' => 'submeta')))); ?>
					</div>
				</div>
			
				<div class="clear"></div>
			</div>
	
			<section id="top">
					
				<section id="top_bar">						
					<section id="main_menu">
						<?php
						$this->widget('zii.widgets.CMenu', array(
							'htmlOptions' => array('class' => 'sf-menu'),
						    'items'=>array(
						        array('label' => at('Dashboard'), 'url' => array('/admin')),
						        array('visible' => checkAccess('op_menu_view_managementtab'), 'label' => at('Management'), 'url' => 'javascript:', 'items'=>array(
						            array('visible' => checkAccess('op_menu_view_usersandpermstab'), 'label' => at('Users & Permissions'), 'url' => 'javascript:void', 'items' => array(
										array('visible' => checkAccess('op_menu_view_users'), 'label' => at('Users'), 'url' => array('user/index')),
										array('visible' => checkAccess('op_menu_view_usercustomfields'), 'label' => at('User Custom Fields'), 'url' => array('usercustomfields/index')),
										array('visible' => checkAccess('op_menu_view_permissions'), 'label' => at('Permissions'), 'url' => array('permission/index')),
									)),
								array('visible' => checkAccess('op_menu_view_personalmessages'), 'label' => at('Personal Messages'), 'url' => array('personalmessages/index')),	
						        )),
								array('visible' => checkAccess('op_menu_view_contenttab'), 'label' => at('Content'), 'url' => 'javascript:', 'items'=>array(
						            array('visible' => checkAccess('op_menu_view_sitetab'), 'label' => at('Site'), 'url' => 'javascript:void', 'items' => array(
										array('visible' => checkAccess('op_menu_view_custompages'), 'label' => at('Custom Pages'), 'url' => array('custompages/index')),
										array('visible' => checkAccess('op_menu_view_blog'), 'label' => at('Blog'), 'url' => array('blog/index')),
										array('visible' => checkAccess('op_menu_view_helptopics'), 'label' => at('Help Topics'), 'url' => array('helptopics/index')),
									)),
									array('visible' => checkAccess('op_menu_view_emailandformtemplatestab'), 'label' => at('Emails & Form Templates'), 'url' => 'javascript:void', 'items' => array(
										array('visible' => checkAccess('op_menu_view_formtemplates'), 'label' => at('Form Templates'), 'url' => array('formtemplate/index')),
										array('visible' => checkAccess('op_menu_view_emailtemplates'), 'label' => at('Email Templates'), 'url' => array('emailtemplate/index')),
									)),
									array('visible' => checkAccess('op_menu_view_mediatab'), 'label' => at('Media'), 'url' => 'javascript:void', 'items' => array(
										array('visible' => checkAccess('op_menu_view_mediamanager'), 'label' => at('Media Manager'), 'url' => array('media/index')),
									)),
									array('visible' => checkAccess('op_menu_view_geotab'), 'label' => at('Geographical'), 'url' => 'javascript:void', 'items' => array(
										array('visible' => checkAccess('op_menu_view_countries'), 'label' => at('Countries'), 'url' => array('country/index')),
										array('visible' => checkAccess('op_menu_view_states'), 'label' => at('US States'), 'url' => array('state/index')),
										array('visible' => checkAccess('op_menu_view_cities'), 'label' => at('US Cities'), 'url' => array('city/index')),
									)),
						        )),
								array('visible' => checkAccess('op_menu_view_storetab'), 'label' => at('Store'), 'url' => 'javascript:', 'items'=>array(
						            array('visible' => checkAccess('op_menu_view_productstab'), 'label' => at('Products'), 'url' => 'javascript:void', 'items' => array(
										array('visible' => checkAccess('op_menu_view_productcats'), 'label' => at('Product Categories'), 'url' => array('productcat/index')),
										array('visible' => checkAccess('op_menu_view_products'), 'label' => at('Products'), 'url' => array('products/index')),
									)),
									array('visible' => checkAccess('op_menu_view_orderstab'), 'label' => at('Orders'), 'url' => 'javascript:void', 'items' => array(
										array('visible' => checkAccess('op_menu_view_orders'), 'label' => at('Orders'), 'url' => array('orders/index')),
										array('visible' => checkAccess('op_menu_view_createorder'), 'label' => at('Create New Order'), 'url' => array('orders/create')),
										array('visible' => checkAccess('op_menu_view_manageorderstatus'), 'label' => at('Manage Order Statuses'), 'url' => array('orderstatus/index')),
									)),
									array('visible' => checkAccess('op_menu_view_contactustab'), 'label' => at('Contact Us'), 'url' => 'javascript:void', 'items' => array(
										array('visible' => checkAccess('op_menu_view_contactus'), 'label' => at('Contact Us Messages'), 'url' => array('contactus/index')),
									)),
						        )),
								array('visible' => checkAccess('op_menu_view_reportstab'), 'label' => at('Reports'), 'url' => 'javascript:', 'items'=>array(
									array('visible' => checkAccess('op_menu_view_reportsusers'), 'label' => at('Users'), 'url' => array('reports/users')),
									array('visible' => checkAccess('op_menu_view_reportsorders'), 'label' => at('Orders'), 'url' => array('reports/orders')),
									array('visible' => checkAccess('op_menu_view_reportsproducts'), 'label' => at('Products'), 'url' => array('reports/products')),
									array('visible' => checkAccess('op_menu_view_reportscreate'), 'label' => at('Create Report'), 'url' => array('reports/create')),
						        )),
								array('visible' => checkAccess('op_menu_view_toolstab'), 'label' => at('Tools'), 'url' => 'javascript:', 'items'=>array(
									array('visible' => checkAccess('op_menu_view_toolssettings'), 'label' => at('Settings'), 'url' => array('setting/index')),
									array('visible' => checkAccess('op_menu_view_toolslanguages'), 'label' => at('Languages'), 'url' => array('language/index')),
						            /*array('visible' => checkAccess('op_menu_view_themestab'), 'label' => at('Themes'), 'url' => 'javascript:void', 'items' => array(
										array('visible' => checkAccess('op_menu_view_themesindex'), 'label' => at('Themes Manager'), 'url' => array('themes/index')),
										array('visible' => checkAccess('op_menu_view_themewidgetsindex'), 'label' => at('Theme Widgets Manager'), 'url' => array('themewidgets/loginhistory')),
										array('visible' => checkAccess('op_menu_view_themefunctionsindex'), 'label' => at('Theme Functions Manager'), 'url' => array('themefunctions/loginhistory')),
									)),*/
									array('visible' => checkAccess('op_menu_view_toolslogstab'), 'label' => at('Logs'), 'url' => 'javascript:void', 'items' => array(
										array('visible' => checkAccess('op_menu_view_logsindex'), 'label' => at('Logs'), 'url' => array('log/index')),
										array('visible' => checkAccess('op_menu_view_loginhistory'), 'label' => at('Login History'), 'url' => array('log/loginhistory')),
									)),
						        )),
						    ),
						));
						?>						
					<div class="clear"></div>
					</section><!-- End of #main_menu -->
				</section><!-- End of #top_bar -->
				<div class="clear"></div>
				
			</section><!-- End of #top -->
			
			<section id='navigation'>
				<section id="nav_bar">
					<?php
					$this->widget('zii.widgets.CBreadcrumbs', array('homeLink' => false, 'links'=> $this->breadcrumbs));
					?>
				</section>	
			</section>

			<section id="container" class="container_12">
				<?php if(user()->hasFlash('ok')): ?>
				<div class='grid_12'>
					<div class="alert succes_msg">
						<span class="alert_close"></span><?php echo user()->getFlash('ok'); ?>
					</div>
				</div>
				<?php endif; ?>
				
				<?php if(user()->hasFlash('error')): ?>
				<div class='grid_12'>
					<div class="alert error_msg">
						<span class="alert_close"></span><?php echo user()->getFlash('error'); ?>
					</div>
				</div>
				<?php endif; ?>
				
				<?php if(user()->hasFlash('info')): ?>
				<div class='grid_12'>
					<div class="alert info_msg">
						<span class="alert_close"></span><?php echo user()->getFlash('info'); ?>
					</div>
				</div>
				<?php endif; ?>
				
				<?php if(user()->hasFlash('warning')): ?>
				<div class='grid_12'>
					<div class="alert exclamation_msg">
						<span class="alert_close"></span><?php echo user()->getFlash('warning'); ?>
					</div>
				</div>
				<?php endif; ?>
				
				<?php echo $content; ?>				
			</section><!-- End of #container -->
			

		</section><!-- End of #layout -->
		<div class="clear"></div>

		<section id="footer_bar">
			<div class="copyr">Copyright <?php echo app()->name; ?> &copy; 2012</div>
			<div class="copyr"><?php echo at("Local Time: {time}", array('{time}' => date('m/d/Y H:i'))); ?></div>
		</section>		
		
		<?php $this->beginWidget('bootstrap.widgets.BootModal', array('id'=>'admin-modal-window', 'options' => array('backdrop' => 'static'), 'htmlOptions' => array('class' => 'large hide', 'style' => 'display:none;'))); ?>
		<div class="modal-header" id='modal-header-div'>
		    <a class="close" data-dismiss="modal">&times;</a>
		    <h3></h3>
		</div>
		<div class="modal-body" id='modal-body-div'></div>
		<div class="modal-footer" id='modal-footer-div'></div>
		<?php $this->endWidget(); ?>
		
		<div style='display:none;'>
		<?php Yii::app()->customEditor->getEditor(array('name' => '--', 'value' => '', 'htmlOptions' => array('style' => 'height:1;')), 'redactor'); ?>
		</div>
		
		<!-- Bottom Scripts -->
		<script type="text/javascript" src="<?php echo themeUrl('js/bottom_scripts.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo themeUrl('js/jquery.thumbnailScroller.js'); ?>"></script>		
    </body>
</html>