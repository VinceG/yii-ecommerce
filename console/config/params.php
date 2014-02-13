<?php
/**
 * This file contains console specific application parameters.
 */
// please notice the order of the merged arrays. It is important, and in a sense reflectes the ineritance hirarchy
//todo: maybe use array_merge_recursive below
$consoleParamsLocal = file_exists($root.'/console/config/params-local.php') ? require($root.'/console/config/params-local.php') : array ();
$commonParams = require_once ('common/config/params.php');

return CMap::mergeArray (
	$commonParams,
	CMap::mergeArray (
		array(

		),
		CMap::mergeArray ( require_once (dirname(__FILE__).'/environments/params-'.$commonParams['env.code'].'.php'), $consoleParamsLocal)));