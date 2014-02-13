<?php

class m120603_214911_create_form_template_table extends CDbMigration
{
	public function up()
	{
		$this->execute('DROP TABLE IF EXISTS `form_template`');
		$this->execute("
			CREATE TABLE IF NOT EXISTS form_template
			(
				id int(10) NOT NULL auto_increment,
				title varchar(125) NOT NULL DEFAULT '',
				content TEXT NULL,
				created_at int(10) NOT NULL DEFAULT '0',
				author_id int(10) NOT NULL DEFAULT '0',
				updated_at int(10) NOT NULL DEFAULT '0',
				updated_author_id int(10) NOT NULL DEFAULT '0',
				PRIMARY KEY (id),
				KEY author_id (author_id),
				KEY updated_author_id (updated_author_id)
			) ENGINE = InnoDB;
		");
	}

	public function down()
	{
		echo "m120603_214911_create_form_template_table does not support migration down.\n";
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