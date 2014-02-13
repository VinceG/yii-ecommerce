<?php

class m120526_194449_add_settings_group_columns extends CDbMigration
{
	public function up()
	{
		$this->addColumn('setting', 'group_title', 'varchar(50) NOT NULL DEFAULT ""');
		$this->addColumn('setting', 'group_close', 'tinyint(1) NOT NULL DEFAULT "0"');
	}

	public function down()
	{
		echo "m120526_194449_add_settings_group_columns does not support migration down.\n";
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