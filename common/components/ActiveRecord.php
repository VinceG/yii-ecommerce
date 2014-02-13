<?php

/**
 * ActiveRecord is the base active record class from which all active record model classes should extend.
 *
 * Houses the code needed across all child AR instances
 */
class ActiveRecord extends CActiveRecord {
	/**
	 * Return user link for the author and last updated author
	 *
	 */
	public function getAuthorLink($relation) {
		return $this->$relation ? ($this->$relation->id . " - " . CHtml::link($this->$relation->name, array('user/view', 'id' => $this->$relation->id))) : "--";
	}
}
