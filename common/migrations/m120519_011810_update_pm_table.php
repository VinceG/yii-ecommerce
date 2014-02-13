<?php

class m120519_011810_update_pm_table extends CDbMigration
{
	public function up()
	{
		$this->dropColumn('personal_message_reply', 'is_read');
	}

	public function down()
	{
		echo "m120519_011810_update_pm_table does not support migration down.\n";
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