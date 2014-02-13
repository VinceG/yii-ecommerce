<?php

class m120429_192312_add_admin_user extends CDbMigration
{
	public function up()
	{
		$this->createTable('admin_user', array(
					'id' => 'pk',
					'userid' => 'int',
					'loggedin_time' => 'int',
					'lastclick_time' => 'int',
					'location' => 'string',
				), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	public function down()
	{
		echo "m120429_192312_add_admin_user does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}