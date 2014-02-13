<?php

class m120610_195522_add_language_columns extends CDbMigration
{
	public function up()
	{
		$this->addColumn('language', 'is_source', 'tinyint(1) NOT NULL DEFAULT "0"');
		$this->insert('language', array('name' => 'English', 'abbr' => 'en', 'is_source' => 1));
	}

	public function down()
	{
		echo "m120610_195522_add_language_columns does not support migration down.\n";
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