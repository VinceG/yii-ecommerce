<?php

$root=dirname(__FILE__).'/../..';
$params = require('params.php');

// We need to set this path alias to be able to define the migrations directory
Yii::setPathOfAlias('root', $root);
Yii::setPathOfAlias('common', $root.'/common');
Yii::setPathOfAlias('console', $root.'/console');
Yii::setPathOfAlias('frontend', $root.'/frontend');
Yii::setPathOfAlias('backend', $root.'/backend');
Yii::setPathOfAlias('uploads', $root.'/frontend/www/uploads');

$consoleMainLocal = file_exists('main-local.php') ? require('main-local.php') : array ();

// please notice the order of the merged arrays. It is important, and reflectes an ineritance hirarchy in a sense
return CMap::mergeArray (
	//require_once ('../../common/config/main.php'), //currently doesn't exist
	array(
	'id'=>'bootstrap.console',
	'name'=>'bootstrap',
	'basePath'=>$root.'/console',
	'params'=>$params,
	'preload'=>array('log'),

	'import'=>array(
		'site.common.extensions.*',
		'site.common.components.*',
		'site.common.models.*',
		'application.components.*',
		'application.models.*',
		'site.frontend.models.*',
	),

	'commandMap'=>array(
		'migrate' => array (
			'class' => 'system.cli.commands.MigrateCommand',
			'migrationPath' => 'site.common.migrations',
		),
     ),

	'components'=>array(
		'db'=>array(
			'pdoClass' => 'NestedPDO',
			'connectionString' => $params['db.connectionString'],
			'username' => $params['db.username'],
			'password' => $params['db.password'],
			'charset' => 'utf8',
			'enableParamLogging' => YII_DEBUG,
			'emulatePrepare'=>true,
			'initSQLs' => array("SET NAMES utf8")
        ),
		'authManager'=>array(
		    'class'=>'AuthManager',
            'connectionID'=>'db',
			'itemTable' => 'auth_item',
			'itemChildTable' => 'auth_item_child',
			'assignmentTable' => 'auth_assignment',
			'defaultRoles'=>array('guest'),
		),
		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
            'baseUrl' => '',
		),
	),), CMap::mergeArray (	require_once (dirname(__FILE__).'/environments/main-'.$params['env.code'].'.php'), $consoleMainLocal));
