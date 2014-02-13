<?php

class m120513_001448_add_help_topic_table extends CDbMigration
{
	public function up()
	{
		$this->execute('DROP TABLE IF EXISTS `help_topic`');
		$this->execute("
			CREATE TABLE IF NOT EXISTS help_topic
			(
				id int(10) NOT NULL auto_increment,
				name varchar(255) NOT NULL default '',
				alias varchar(250) NOT NULL default '', 
				question varchar(255) NOT NULL default '',
				answer text NULL,
				created_at int(10) NOT NULL default '0',
				author_id int(10) NOT NULL default '0',
				updated_at int(10) NOT NULL default '0',
				updated_author_id int(10) NOT NULL default '0',
				tags varchar(255) NOT NULL default '',
				status tinyint(1) NOT NULL default '0',
				sort_ord float NULL,
				PRIMARY KEY (id),
				KEY name (name),
				UNIQUE (alias)
			) ENGINE = InnoDB;
		");
	}

	public function down()
	{
		echo "m120513_001448_add_help_topic_table does not support migration down.\n";
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