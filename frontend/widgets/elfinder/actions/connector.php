<?php

$path = Yii::getPathOfAlias('application.widgets.elfinder.php');

require_once( $path . '/elFinderConnector.class.php' );
require_once( $path . '/elFinder.class.php' );
require_once( $path . '/elFinderVolumeDriver.class.php' );
require_once( $path . '/elFinderVolumeLocalFileSystem.class.php' );

// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';

/**
 * elFinder connector action
 *
 */
class connector extends CAction{
    public function run(){
		$opts = array(
			'bind' => array(
				'mkdir' => 'dirCreated',
				'mkfile' => 'mkfileDone',
				'rm' => 'rmDone',
				'rename' => 'renameDone',
				'upload' => 'uploadFileDone',
			),
			'debug' => true,
			'roots' => array(
				array(
					'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
					'path'          => base64_decode(getRParam('elfinder_path')),         // path to files (REQUIRED)
					'URL'           => base64_decode(getRParam('elfinder_url')), // URL to files (REQUIRED)
					'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
				)
			)
		);
		
		// run elFinder
		$connector = new elFinderConnector(new elFinder($opts));
		$connector->run();
    }
}

function dirCreated($cmd, $volumes, $result) {
	// Get result
	if(isset($result['added'])) {
		foreach($result['added'] as $dir) {
			alog(at("Media Manager: New directory created: '{name}'", array('{name}' => $dir['name'])));
		}
	}
}

function mkfileDone($cmd, $volumes, $result) {
	// Get result
	if(isset($result['added'])) {
		foreach($result['added'] as $dir) {
			alog(at("Media Manager: New file created: '{name}'", array('{name}' => $dir['name'])));
		}
	}
}

function rmDone($cmd, $volumes, $result) {
	// Get result
	if(isset($result['added'])) {
		foreach($result['added'] as $dir) {
			alog(at("Media Manager: Removed File '{name}'", array('{name}' => $dir['name'])));
		}
	}
}

function renameDone($cmd, $volumes, $result) {
	// Get result
	if(isset($result['added'])) {
		foreach($result['added'] as $dir) {
			alog(at("Media Manager: Rename Completed: '{name}'", array('{name}' => $dir['name'])));
		}
	}
}

function uploadFileDone($cmd, $volumes, $result) {
	// Get result
	if(isset($result['added'])) {
		foreach($result['added'] as $dir) {
			alog(at("Media Manager: Upload Completed '{name}'", array('{name}' => $dir['name'])));
		}
	}
}


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool
 **/
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0   // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')  // set read+write to false, other (locked+hidden) set to true
		: ($attr == 'read' || $attr == 'write');  // else set read+write to true, locked+hidden to false
}
