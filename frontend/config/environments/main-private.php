<?php
/* main-private*/ 

/**
 * This is the configuration used during development.
 * This file should only contain settings that are specific to your
 * development environment. Any settings that would be used for production
 * should be specified in config/main.php.
 */
return array(
	'components'=>array(
		'db' => array(
			'enableProfiling'=>true,
			'enableParamLogging'=>true,
		),
		'messages' => array(
			'onMissingTranslation' => array('MissingMessages', 'load'),
        ),
	),

);
