<?php

class m120602_204354_add_pm_notification_reply_id extends CDbMigration
{
	public function up()
	{
		$this->addColumn('personal_message_notification', 'reply_id', "int(10) NOT NULL default '0'");
	}

	public function down()
	{
		echo "m120602_204354_add_pm_notification_reply_id does not support migration down.\n";
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