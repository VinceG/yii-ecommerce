<?php

class FormTags extends CApplicationComponent {
	protected $user = null;
	public $tagPrefix = '{{';
	public $tagSuffix = '}}';
	public function init() {
		if(!$this->user && Yii::app()->user->id) {
			$this->user = $this->loadUser(Yii::app()->user->id);
		}
	}
	
	/**
	 * Return list of tags for display
	 */
	public function getTagsForDisplay() {
		$tags = $this->getTags();
		$list = array();
		foreach($tags as $group => $values) {
			foreach($values as $key => $value) {
				$list[ucfirst($group)][ $this->tagPrefix. ($group.'.'.$key) .$this->tagSuffix ] = $value;
			}
		}
		
		return $list;
	}
	
	/**
	 * Replace tags
	 */
	public function replaceTags($content, $data=array()) {
		// do we need to set a user now?
		if(isset($data['user']) && $data['user']) {
			$this->setUser($data['user']);
		}
		
		// Get tags
		$tags = $this->getTagsForDisplay();
		$list = array();
		foreach($tags as $group => $values) {
			foreach($values as $key => $value) {
				$list[$key] = $value;
			}
		}
		// Replace Content
		return str_ireplace(array_keys($list), $list, $content);
	}
	
	/**
	 * Get user tags
	 */	
	public function getTags() {
		return array(
			'user' => array(
				'id' => $this->user->id,
				'name' => $this->user->name,
				'email' => $this->user->email,
				'created_at' => dateOnly($this->user->created_at),
				'updated_at' => dateOnly($this->user->updated_at),
				'notes' => $this->user->notes,
				'role' => $this->user->role,
				'first_name' => $this->user->first_name,
				'last_name' => $this->user->last_name,
				'birth_date' => dateOnly($this->user->birth_date),
				'company' => $this->user->company,
				'contact' => $this->user->contact,
				'home_phone' => $this->user->home_phone,
				'cell_phone' => $this->user->cell_phone,
				'work_phone' => $this->user->work_phone,
				'fax' => $this->user->fax,
				'shipping_contact' => $this->user->shipping_contact,
				'shipping_address1' => $this->user->shipping_address1,
				'shipping_address2' => $this->user->shipping_address2,
				'shipping_city' => $this->user->shipping_city,
				'shipping_zip' => $this->user->shipping_zip,
				'shipping_state' => ($this->user->shipping_state && $this->user->shippingState) ? $this->user->shippingState->name : '',
				'shipping_country' => ($this->user->shipping_country && $this->user->shippingCountry) ? $this->user->shippingCountry->name : '',
				'billing_contact' => $this->user->billing_contact,
				'billing_address1' => $this->user->billing_address1,
				'billing_address2' => $this->user->billing_address2,
				'billing_city' => $this->user->billing_city,
				'billing_zip' => $this->user->billing_zip,
				'billing_state' => ($this->user->billing_state && $this->user->billingState) ? $this->user->billingState->name : '',
				'billing_country' => ($this->user->billing_country && $this->user->billingCountry) ? $this->user->billingCountry->name : '',
				'last_visited' => dateOnly($this->user->last_visited),
			),
		);
	}

	/**
	 * Set user object
	 *
	 */
	public function setUser($user) {
		
		// If we got an integer then load the user record
		if($user && !is_object($user)) {
			$user = $this->loadUser($user);
			// Set our user object
			$this->user = $user;
		}
		
		return $this;
	}
	
	/**
	 * Internal load user with relations
	 */
	protected function loadUser($user) {
		preg_match('/[a-zA-Z]+/', $user, $matches);
		if(!count($matches)) {
			$row = User::model()->with(array('shippingCountry', 'shippingState', 'billingCountry', 'billingState'))->findByPk($user);
		} else {
			$row = User::model()->with(array('shippingCountry', 'shippingState', 'billingCountry', 'billingState'))->find('t.name=LOWER(:name)', array(':name' => strtolower($user)));
		}
		if(!$row) {
			$row = User::model()->with(array('shippingCountry', 'shippingState', 'billingCountry', 'billingState'))->findByPk(Yii::app()->user->id);
		}
		return $row;
	}
}