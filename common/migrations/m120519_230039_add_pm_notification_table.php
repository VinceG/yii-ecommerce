<?php

class m120519_230039_add_pm_notification_table extends CDbMigration
{
	public function up()
	{
		$this->execute('DROP TABLE IF EXISTS `personal_message_notification`');
		$this->execute("
			CREATE TABLE IF NOT EXISTS personal_message_notification
			(
				id int(10) NOT NULL auto_increment,
				topic_id int(10) NOT NULL default '0',
				user_id int(10) NOT NULL default '0',
				created_at int(10) NOT NULL default '0',
				byuserid int(10) NOT NULL default '0',
				was_shown tinyint(10) NOT NULL default '0',
				PRIMARY KEY (id),
				KEY user_id (user_id),
				KEY topic_id (topic_id)
			) ENGINE = InnoDB;
		");
	}

	public function down()
	{
		echo "m120519_230039_add_pm_notification_table does not support migration down.\n";
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