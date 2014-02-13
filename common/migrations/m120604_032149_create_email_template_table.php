<?php

class m120604_032149_create_email_template_table extends CDbMigration
{
	public function up()
	{
		$this->execute('DROP TABLE IF EXISTS `email_template`');
		$this->execute("
			CREATE TABLE IF NOT EXISTS email_template
			(
				id int(10) NOT NULL auto_increment,
				title varchar(125) NOT NULL DEFAULT '',
				email_key varchar(125) NOT NULL DEFAULT '',
				content TEXT NULL,
				created_at int(10) NOT NULL DEFAULT '0',
				author_id int(10) NOT NULL DEFAULT '0',
				updated_at int(10) NOT NULL DEFAULT '0',
				updated_author_id int(10) NOT NULL DEFAULT '0',
				PRIMARY KEY (id),
				KEY author_id (author_id),
				KEY updated_author_id (updated_author_id),
				UNIQUE (email_key)
			) ENGINE = InnoDB;
		");
	}

	public function down()
	{
		echo "m120604_032149_create_email_template_table does not support migration down.\n";
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