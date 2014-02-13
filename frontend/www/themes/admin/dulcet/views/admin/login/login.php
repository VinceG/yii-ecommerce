<?php echo CHtml::beginForm('', 'post', array('class' => 'formee')); ?>
<div class="login_form_display">
	<div class="login_row">
		<?php echo CHtml::activeTextField($form, 'email', array('class' => 'validate[required,custom[email]]', 'placeholder' => 'Email Address')); ?>
		<?php echo CHtml::error($form, 'email'); ?>
	</div>
	<div class="clear"></div>
	
	<div class="login_row">
		<?php echo CHtml::activePasswordField($form, 'password', array('class' => 'validate[required,custom[passwordLogin]]', 'placeholder' => 'Password')); ?>
		<?php echo CHtml::error($form, 'password'); ?>
	</div>
	<div class="clear"></div>
	
	<div class="login_row">
		<?php $this->widget('CCaptcha', array('imageOptions' => array('class' => 'captcha-image'), 'buttonOptions' => array('class' => 'button-refresh-link'))); ?>
		<?php echo CHtml::activeTextField($form, 'verifyCode', array('class' => 'validate[required]', 'placeholder' => 'Captcha Verification')); ?>
		<?php echo CHtml::error($form, 'verifyCode'); ?>
	</div>
	<div class="clear"></div>
</div>

<!--Form footer begin-->
<section class="login_footer">
	<div class="textcenter"><input type="submit" value="Login" /></div>
	<div class="clear"></div>
</section>
<!--Form footer end-->
	
<?php echo CHtml::endForm(); ?>