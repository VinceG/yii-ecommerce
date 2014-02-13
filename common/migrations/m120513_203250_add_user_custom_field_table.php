<?php

class m120513_203250_add_user_custom_field_table extends CDbMigration
{
	public function up()
	{
		$this->execute('DROP TABLE IF EXISTS `user_custom_field`');
		$this->execute("
			CREATE TABLE IF NOT EXISTS user_custom_field
			(
				id int(10) NOT NULL auto_increment,
				title varchar(255) NOT NULL default '',
				description varchar(255) NOT NULL default '',
				created_at int(10) NOT NULL default '0',
				author_id int(10) NOT NULL default '0',
				updated_at int(10) NOT NULL default '0',
				updated_author_id int(10) NOT NULL default '0',
				status tinyint(1) NOT NULL default '0',
				is_public tinyint(1) NOT NULL default '0',
				is_editable tinyint(1) NOT NULL default '0',
				type char(30) NOT NULL DEFAULT 'text',
				default_value TEXT NULL,
				extra TEXT NULL,
				PRIMARY KEY (id),
				KEY title (title)
			) ENGINE = InnoDB;
		");
	}

	public function down()
	{
		echo "m120513_203250_add_user_custom_field_table does not support migration down.\n";
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