<?php

class m120518_224241_add_pm_table extends CDbMigration
{
	public function up()
	{
		$this->execute('DROP TABLE IF EXISTS `personal_message_topic`');
		$this->execute("
			CREATE TABLE IF NOT EXISTS personal_message_topic
			(
				id int(10) NOT NULL auto_increment,
				title varchar(255) NOT NULL default '',
				created_at int(10) NOT NULL default '0',
				author_id int(10) NOT NULL default '0',
				type int(10) NOT NULL DEFAULT '0',
				first_post int(10) NOT NULL DEFAULT '0',
				PRIMARY KEY (id),
				KEY title (title),
				KEY first_post (first_post),
				KEY author_id (author_id)
			) ENGINE = InnoDB;
		");
		
		$this->execute('DROP TABLE IF EXISTS `personal_message_reply`');
		$this->execute("
			CREATE TABLE IF NOT EXISTS personal_message_reply
			(
				id int(10) NOT NULL auto_increment,
				topic_id int(10) NOT NULL default '0',
				user_id int(10) NOT NULL default '0',
				created_at int(10) NOT NULL default '0',
				message TEXT NULL,
				is_read tinyint(1) NOT NULL DEFAULT '0',
				PRIMARY KEY (id)
			) ENGINE = InnoDB;
		");
		
		$this->execute('DROP TABLE IF EXISTS `personal_message_participant`');
		$this->execute("
			CREATE TABLE IF NOT EXISTS personal_message_participant
			(
				id int(10) NOT NULL auto_increment,
				topic_id int(10) NOT NULL default '0',
				user_id int(10) NOT NULL default '0',
				PRIMARY KEY (id),
				KEY user_id (user_id),
				KEY topic_id (topic_id),
				UNIQUE (user_id, topic_id)
			) ENGINE = InnoDB;
		");
		
	}

	public function down()
	{
		echo "m120518_224241_add_pm_table does not support migration down.\n";
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