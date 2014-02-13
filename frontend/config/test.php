<?php
// This file has not been reviewed deeply for the bootstrap. It's taken almost as is from crm
return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'import'=>array(
		),

		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			'db'=>array(
				'pdoClass' => 'NestedPDO',
				'connectionString' => $params['testdb.connectionString'],
				'username' => $params['testdb.username'],
				'password' => $params['testdb.password'],
			),
			'session'=>array(
				'class'=>'CDbHttpSession',
				'connectionID'=>'maindb',
				'sessionTableName'=>'user_session',
				'autoCreateSessionTable'=>false,
				'timeout' => 86400, // it's 24 hours
			),
		),
	)
);
