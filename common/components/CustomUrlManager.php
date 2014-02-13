<?php
/**
 * Custom rules manager class
 *
 * Override to load the routes from the DB rather then a file
 *
 */
class CustomUrlManager extends CUrlManager {
    /**
     * Build the rules from the DB
     */
    protected function processRules() {
			
		$this->rules = array(
			//-----------------------ADMIN--------------
			"/admin" => 'admin/index/index',
			"/admin/<_c:([a-zA-z0-9-]+)>" => 'admin/<_c>/index',
	        "/admin/<_c:([a-zA-z0-9-]+)>/<_a:([a-zA-z0-9-\.]+)>/*" => 'admin/<_c>/<_a>',
			//-----------------------Site--------------
			"/" => 'site/index/index', 
			"/<_c:([a-zA-z0-9-]+)>" => 'site/<_c>/index',
	        "/<_c:([a-zA-z0-9-]+)>/<_a:([a-zA-z0-9-]+)>/*" => 'site/<_c>/<_a>',
		);

        // Run parent
        parent::processRules();

    }

	public function clearCache() {
		
	}
}
