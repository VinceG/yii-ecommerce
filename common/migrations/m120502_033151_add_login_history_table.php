<?php

class m120502_033151_add_login_history_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('admin_login_history', array(
			'id' => 'pk',
			'username' => 'string',
			'created_at' => 'int',
			'ip_address' => 'string',
			'password' => 'string',
			'is_ok' => 'int',
			'browser' => 'string',
			'platform' => 'string',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	public function down()
	{
		echo "m120502_033151_add_login_history_table does not support migration down.\n";
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