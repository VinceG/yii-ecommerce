<?php
/**
 * auth item child model
 */
class AuthItemChild extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string Table name
	 */
	public function tableName()
	{
		return 'auth_item_child';
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'parent' => at('Auth Item Parent'),
			'child' => at('Auth Item Child'),
		);
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('parent, child', 'required' ),
			array('parent', 'CheckLoop'),
		);
	}
	/**
	 * Check we are not violating anything
	 */
	public function checkLoop()
	{
		if( $this->parent == $this->child )
		{
			$this->addError('child', at('Cannot add child as an item of itself.'));
		}
		
		try
		{
			if( !Yii::app()->authManager->hasItemChild($this->parent, $this->child) )
			{
				// Create an auth item based on those parameters
				Yii::app()->authManager->addItemChild( $this->parent, $this->child );
			}
		}
		catch (CException $e)
		{
			$this->addError('child', $e->getMessage());
		}
	}
	
}