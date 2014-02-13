<?php

$root=realpath(dirname(__FILE__).'/../..');
Yii::setPathOfAlias('site',$root);

/**
 * Parameters shared by all applications.
 * Please put environment-sensitive parameters in params-env.php
 */

$commonParamsEnv = array();
$commonParamsLocal = array();

if(file_exists($root . '/common/config/params-local.php')) {
	$commonParamsLocal = require($root . '/common/config/params-local.php');
	$commonParamsEnv = require('environments/params-'.$commonParamsLocal['env.code'].'.php');
} elseif(file_exists($root . '/common/config/environments/params-prod.php')) {
	$commonParamsLocal = array();
	$commonParamsEnv = require($root . '/common/config/environments/params-prod.php');
}

return CMap::mergeArray(array(
	'php.exePath' => '/usr/bin/php'
), CMap::mergeArray( $commonParamsEnv, $commonParamsLocal));
