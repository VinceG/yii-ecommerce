<?php

class m120624_210526_add_theme_file_directory extends CDbMigration
{
	public function up()
	{
		$this->addColumn('theme_file', 'file_directory', 'varchar(55) NOT NULL DEFAULT ""');
	}

	public function down()
	{
		echo "m120624_210526_add_theme_file_directory does not support migration down.\n";
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