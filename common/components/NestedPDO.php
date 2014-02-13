<?php
/**
 * Emaulates nested transactions support via savepoints
 *
 * @see http://www.yiiframework.com/wiki/38/how-to-use-nested-db-transactions-mysql-5-pgsql
 * @see http://www.kennynet.co.uk/2008/12/02/php-pdo-nested-transactions/
 */
class NestedPDO extends PDO
{
	/**
	 * @var array Database drivers that support SAVEPOINTs.
	 */
	protected static $savepointTransactions=array("pgsql","mysql");

	/**
	 * @var int The current transaction level
	 */
	protected $transLevel=0;

	protected function isNestable()
	{
		return in_array(
			$this->getAttribute(PDO::ATTR_DRIVER_NAME),
			self::$savepointTransactions
		);
	}

	public function beginTransaction()
	{
		if($this->transLevel==0 || !$this->isNestable())
		{
			parent::beginTransaction();
		}
		else
		{
			$this->exec("SAVEPOINT LEVEL{$this->transLevel}");
		}

		$this->transLevel++;
	}

	public function commit()
	{
		$this->transLevel--;

		if($this->transLevel==0 || !$this->isNestable())
		{
			parent::commit();
		}
		else
		{
			$this->exec("RELEASE SAVEPOINT LEVEL{$this->transLevel}");
		}
	}

	public function rollBack()
	{
		$this->transLevel--;

		if($this->transLevel==0 || !$this->isNestable())
		{
			parent::rollBack();
		}
		else
		{
			$this->exec("ROLLBACK TO SAVEPOINT LEVEL{$this->transLevel}");
		}
	}
}