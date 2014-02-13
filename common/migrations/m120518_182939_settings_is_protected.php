<?php

class m120518_182939_settings_is_protected extends CDbMigration
{
	public function up()
	{
		$this->addColumn('setting', 'is_protected', 'tinyint(1) NOT NULL DEFAULT "0"');
	}

	public function down()
	{
		echo "m120518_182939_settings_is_protected does not support migration down.\n";
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