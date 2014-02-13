<?php

class m120526_235614_add_setting_order extends CDbMigration
{
	public function up()
	{
		$this->addColumn('setting', 'sort_ord', 'float NOT NULL DEFAULT "0"');
	}

	public function down()
	{
		echo "m120526_235614_add_setting_order does not support migration down.\n";
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