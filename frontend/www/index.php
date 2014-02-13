<?php

if(isset($_SERVER['YII_DEBUG'])) {
	define('YII_DEBUG', $_SERVER['YII_DEBUG']);
}
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// On dev display all errors
if(YII_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', true);
}

chdir (dirname(__FILE__).'/../..');

$root=dirname(__FILE__).'/..';
$common=$root.'/../common';

$localParamsFile = $common . '/config/params-local.php';
if(!file_exists($localParamsFile)) {
	die(sprintf("Please create the %s file.", $localParamsFile));
}

$config='frontend/config/main.php';
require_once('common/components/Yii.php');
require_once('common/components/WebApplication.php');
require_once('common/lib/global.php');

Yii::createApplication('WebApplication',$config)->run();