<?php

/**
 * Description of WebApplication
 * The customized version of the CWebApplication class
 */
class WebApplication extends CWebApplication 
{
	/**
	 * Init application
	 */
	public function init() {
		// Set default cache first
		Yii::app()->setComponent('cache', Yii::CreateComponent(array('class' => 'CFileCache', 'directoryLevel' => 2)));
		
		// Set cache options
		$this->setCacheOptions();
		
		// Init settings
		Yii::app()->settings->init();
		
		// IP Block
		$this->checkIPBlock();
		
		// Set language
		Yii::app()->language = getParam('default_site_language', 'en');
		
		// Set default time zone
		date_default_timezone_set(getParam('global_time_zone', 'America/Los_Angeles'));
		
		// Build editor component
		Yii::app()->setComponent('customEditor', Yii::CreateComponent(array('class' => 'CustomEditor', 'type' => getParam('global_editor_type', 'ckeditor'))));
		
		// Run parent now
		parent::init();
	}
	
	/**
	 * Check the user IP if we need to ban him
	 */
	protected function checkIPBlock() {
		// array's of banned IP addresses
		$bannedIPS = getParam('ban_ips');
		$userIP = getUserIP();
		$blockedAddresses = explode("\n", $bannedIPS);
		$redirectTo = getParam('ban_ips_redirect_url');

		// Check every ip address
		if($bannedIPS && is_array($blockedAddresses) && count($blockedAddresses)) {
			if(in_array($userIP, $blockedAddresses)) {
			     // this is for exact matches of IP address in array
			     header("Location: " . $redirectTo);
			     exit();
			} else {
			     // this is for wild card matches
			     foreach($blockedAddresses as $ip) {
			          if(preg_match('~'.$ip.'~', $userIP)) {
			               header("Location: " . $redirectTo);
			               exit();
			          }
			     }
			}
		}
	}
	
	/**
	 * Set the cache components based on the settings chosen in the admin
	 *
	 */
	protected function setCacheOptions() {
		// Set content cache
		$caches = array(
			'cache' => array('class' => getParam('global_content_cache', 'CFileCache')),
			'dataCache' => array('class' => getParam('global_data_cache', 'CFileCache')),
		);
		
		foreach($caches as $cacheKey => $cacheData) {
			$failed = false;
			// Based on the cache type we add additional values
			switch($cacheData['class']) {
				case 'CDbCache':
					$caches[$cacheKey]['connectionID'] = 'db';
				break;

				case 'CFileCache':
					$caches[$cacheKey]['directoryLevel'] = 2;
				break;

				case 'CMemCache':
					// Build server array
					$servers = getParam('memcache_cache_servers');
					if($servers) {
						// Explode servers
						$explodeServers = explode("\n", $servers);
						$serversList = array();
						foreach($explodeServers as $serverItem) {
							list($serverAddress, $serverPort, $weight) = explode(':', $serverItem);
							$serversList[] = array('host' => $serverAddress, 'port' => $serverPort, 'weight' => $weight);
						}
						$caches[$cacheKey]['servers'] = $serversList;
					}
				break;
				
				default:
				$failed = true;
				break;
			}
			// Make sure we have the settings
			if(!$failed) {
				Yii::app()->setComponent($cacheKey, Yii::CreateComponent($caches[$cacheKey]));
			}
		}
	}
}