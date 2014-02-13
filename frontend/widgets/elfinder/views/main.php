<?php 

JSFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js');
CSSFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/smoothness/jquery-ui.css');

$publish = publish( Yii::getPathOfAlias('application.widgets.elfinder.assets'));
CSSFile($publish .  '/css/elfinder.full.css');
CSSFile($publish .  '/css/theme.css');

$publish = publish( Yii::getPathOfAlias('application.widgets.elfinder.assets.js') .  '/elfinder.min.js');
JSFile($publish);

$script = "var elf = $('".$this->selector."').elfinder({
					url : '".$this->action."'  // connector URL (REQUIRED)
				}).elfinder('instance');";
JSCode('elfinder', $script, CClientScript::POS_READY);

?>