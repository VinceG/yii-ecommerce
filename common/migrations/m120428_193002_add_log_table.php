<?php

class m120428_193002_add_log_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('admin_log', array(
			'id' => 'pk',
			'user_id' => 'int',
			'note' => 'string',
			'created_at' => 'int',
			'ip_address' => 'string',
			'controller' => 'string',
			'action' => 'string',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	public function down()
	{
		echo "m120428_193002_add_log_table does not support migration down.\n";
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