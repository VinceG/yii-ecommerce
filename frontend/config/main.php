<?php
$root=dirname(__FILE__).'/../..';
$params = require('params.php');

// We need to set this path alias to be able to use the path of alias
// some of this may not be nescessary now, as now the directory is changed to projects root in the bootstrap script
Yii::setPathOfAlias('root', $root);
Yii::setPathOfAlias('common', $root.'/common');
Yii::setPathOfAlias('www', $root.'/frontend/www');
Yii::setPathOfAlias('themes', $root.'/frontend/www/themes');
Yii::setPathOfAlias('frontend', $root.'/frontend');
Yii::setPathOfAlias('ext', $root.'/frontend/extensions');
Yii::setPathOfAlias('backend', $root.'/backend');
Yii::setPathOfAlias('uploads', $root.'/frontend/www/uploads');
Yii::setPathOfAlias('bootstrap', $root.'/frontend/extensions/bootstrap');

$frontendMainLocal = file_exists('frontend/config/main-local.php') ? require('frontend/config/main-local.php') : array ();
// please notice the order of the merged arrays. It is important, and reflectes an ineritance hierarchy in a sense
return CMap::mergeArray (
	//require_once ('common/config/main.php'), //currently doesn't exist
	array(
	'id'=>'bootstrap.frontend',
	'name'=>'Yii Commerce',
	'basePath'=>'frontend',
	'defaultController'=>'site/index',
	'params'=>$params,
	'preload'=>array('log'),
	'language' =>'en',

	'import'=>array(
		'common.components.*',
		'common.models.*',
		'application.extensions.*',
		'application.widgets.*',
	),

	'modules'=>array(
			'admin' => array(
				'import' => array(
					'admin.components.*',
					'admin.models.*',
				),
				'layout' => 'main',
			),
			'site' => array(
				'import' => array(
					'site.components.*',
					'site.models.*',
				),
				'layout' => 'main',
			),
			'api' => array(),
	    ),

	'components'=>array(
		'formtags' => array(
			'class' => 'common.components.FormTags',
		),
		'bootstrap'=>array(
			'class'=>'application.extensions.bootstrap.components.Bootstrap',
	    ),
		'errorHandler'=>array(
            'errorAction'=>'error/error',
        ),
		'user'=>array(
			'class'=>'WebUser',
			'allowAutoLogin'=>true,
		),
		'settings' => array(
			'class' => 'CustomSettings',
        ),
		'messages' => array(
		    'class' => 'CDbMessageSource',
            'cacheID' => 'cache',
			'cachingDuration' => !YII_DEBUG ? 3600*24 : 0,
			'sourceMessageTable' => 'source_message',
			'translatedMessageTable' => 'message',
			'forceTranslation' => true,
        ),
		'authManager'=>array(
		    'class'=>'application.extensions.authcache.ECachedDbAuthManager',
			'cacheID' => 'cache',
            'connectionID'=>'db',
			'cachingDuration' => !YII_DEBUG ? 3600*24 : 0,
			'itemTable' => 'auth_item',
			'itemChildTable' => 'auth_item_child',
			'assignmentTable' => 'auth_assignment',
		),
		'db'=>array(
			'pdoClass' => 'NestedPDO',
			'connectionString' => $params['db.connectionString'],
			'username' => $params['db.username'],
			'password' => $params['db.password'],
			'schemaCachingDuration' => !YII_DEBUG ? 3600 : 0,
			'charset' => 'utf8',
        ),
		'urlManager' => array(
			'class' => 'CustomUrlManager',
			'urlFormat' => 'path',
			'showScriptName' => false,
			'urlSuffix' => '',
		),
	),), CMap::mergeArray (	require_once (dirname(__FILE__).'/environments/main-'.$params['env.code'].'.php'), 	$frontendMainLocal));
