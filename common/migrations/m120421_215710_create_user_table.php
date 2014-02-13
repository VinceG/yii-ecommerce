<?php

class m120421_215710_create_user_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('user', array(
			'id' => 'pk',
			'name' => 'string',
			'email' => 'string',
			'created_at' => 'int',
			'updated_at' => 'int',
			'password_hash' => 'char(60)',
			'password_reset_token' => 'string',
			'notes' => 'text',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	public function down()
	{
		echo "m120421_215710_create_user_table does not support migration down.\n";
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