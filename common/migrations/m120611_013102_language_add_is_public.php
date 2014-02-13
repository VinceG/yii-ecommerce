<?php

class m120611_013102_language_add_is_public extends CDbMigration
{
	public function up()
	{
		$this->addColumn('language', 'is_public', 'tinyint(1) NOT NULL DEFAULT "0"');
	}

	public function down()
	{
		echo "m120611_013102_language_add_is_public does not support migration down.\n";
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