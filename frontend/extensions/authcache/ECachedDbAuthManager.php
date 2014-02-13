<?php
/**
 * ECachecDbAuthManager
 * v0.2
 */
class ECachedDbAuthManager extends CDbAuthManager
{
	/**
	 * The ID for the cache to use
	 */
	public $cacheID;
	/**
	 * Duration for the cache
	 * default 3600*24*30=2592000 (30 days)
	 */ 
	public $cachingDuration=2592000;

	/**
	 * Performs access check for the specified user.
	 * Checks and sees if there are a cached value first.
	 * This method is internally called by {@link checkAccess}.
	 * @param string $itemName the name of the operation that need access check
	 * @param mixed $userId the user ID. This should can be either an integer and a string representing
	 * the unique identifier of a user. See {@link IWebUser::getId}.
	 * @param array $params name-value pairs that would be passed to biz rules associated
	 * with the tasks and roles assigned to the user.
	 * @param array $assignments the assignments to the specified user
	 * @return boolean whether the operations can be performed by the user.
	 * @throws CExeption if the application component could not be loaded.
	 */
	public function checkAccess($itemName,$userId,$params=array())
	{
		if(Yii::app()->getComponent($this->cacheID)!==null)
		{
			$cachedValue=Yii::app()->getComponent($this->cacheID)->get($this->cacheID.'_'.$itemName.'_'.$userId);
			if(count($params)==0 && $cachedValue!==false)
			{
				$returnValue=($cachedValue===1);
			}
			else
			{
				$returnValue=parent::checkAccess($itemName,$userId,$params);
				Yii::app()->getComponent($this->cacheID)->set($this->cacheID.'_'.$itemName.'_'.$userId,intval($returnValue),$this->cachingDuration);
			}
			return $returnValue;
		}
		else
		{
			throw new CException('Application component '.$this->cacheID.' could not be loaded.');
		}
	}

	/**
	 * Removes cache before assigning a new auth item
	 * @param string $itemName the item name
	 * @param mixed $userId the user ID (see {@link IWebUser::getId})
	 * @param string $bizRule the business rule to be executed when {@link checkAccess} is called
	 * for this particular authorization item.
	 * @param mixed $data additional data associated with this assignment
	 * @return CAuthAssignment the authorization assignment information.
	 * @throws CException if the item does not exist or if the item has already been assigned to the user
	 * @throws CExeption if the application component could not be loaded.
	 */
	public function assign($itemName,$userId,$bizRule=null,$data=null)
	{
		if(Yii::app()->getComponent($this->cacheID)!==null)
		{
			Yii::app()->getComponent($this->cacheID)->delete($this->cacheID.'_'.$itemName.'_'.$userId);
			return parent::assign($itemName,$userId,$bizRule,$data);
		}
		else
		{
			throw new CException('Application component '.$this->cacheID.' could not be loaded.');
		}
	}

	/**
	 * Removes cache before revoking auth item assignement
	 * @param string $itemName the item name
	 * @param mixed $userId the user ID (see {@link IWebUser::getId})
	 * @return boolean whether removal is successful
	 * @throws CExeption if the application component could not be loaded.
	 */
	public function revoke($itemName,$userId)
	{
		if(Yii::app()->getComponent($this->cacheID)!==null)
		{
			Yii::app()->getComponent($this->cacheID)->delete($this->cacheID.'_'.$itemName.'_'.$userId);
			return parent::revoke($itemName,$userId);
		}
		else
		{
			throw new CException('Application component '.$this->cacheID.' could not be loaded.');
		}
	}
}