<?php

class m120519_191050_add_last_reply_to_topic_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn('personal_message_topic', 'last_reply_created_at', 'int(10) NOT NULL DEFAULT "0"');
		$this->addColumn('personal_message_topic', 'last_reply_author_id', 'int(10) NOT NULL DEFAULT "0"');
	}

	public function down()
	{
		echo "m120519_191050_add_last_reply_to_topic_table does not support migration down.\n";
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