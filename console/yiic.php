<?php
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

defined('YII_DEBUG') or define('YII_DEBUG', (@$argv == 'index')? false : true);

date_default_timezone_set('UTC'); 

$root=dirname(__FILE__);
require_once('common/components/Yii.php');
$config='console/config/main.php'; 
require_once('common/lib/global.php');


if(isset($config))
{
	$app=Yii::createConsoleApplication($config);
	$app->commandRunner->addCommands(YII_PATH.'/cli/commands');
	$env=@getenv('YII_CONSOLE_COMMANDS');
	if(!empty($env))
		$app->commandRunner->addCommands($env);
}
else
	$app=Yii::createConsoleApplication(array('basePath'=>dirname(__FILE__).'/cli'));

$app->run();
/* Below - the old version of this file*/
/*
defined('YII_DEBUG') or define('YII_DEBUG',true);

$root=dirname(__FILE__);
$config=$root.'/config/main.php';

require_once($root.'/../common/lib/global.php');
require_once($root.'/../common/lib/yii-1.1.8/yiic.php');
*/