<?php

class m120512_231659_add_last_visited_to_user extends CDbMigration
{
	public function up()
	{
		$this->addColumn('user', 'last_visited', 'int(10) NOT NULL DEFAULT "0"');
	}

	public function down()
	{
		echo "m120512_231659_add_last_visited_to_user does not support migration down.\n";
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