<?php

class m120422_214228_add_role_to_user_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn('user', 'role', 'string');
	}

	public function down()
	{
		echo "m120422_214228_add_role_to_user_table does not support migration down.\n";
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