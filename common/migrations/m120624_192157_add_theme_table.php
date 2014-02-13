<?php

class m120624_192157_add_theme_table extends CDbMigration
{
	public function up()
	{
		$this->execute("
			CREATE TABLE theme (
			    id INTEGER PRIMARY KEY auto_increment,
			    name varchar(55) NOT NULL DEFAULT '',
				dirname varchar(55) NOT NULL DEFAULT '',
				author varchar(55) NOT NULL DEFAULT '',
				author_site varchar(55) NOT NULL DEFAULT '',
			    created_at int(10) NOT NULL DEFAULT '0',
				is_active tinyint(1) NOT NULL DEFAULT '0',
				UNIQUE (dirname)
			) ENGINE = InnoDB;
		");
		
		$this->execute("
			CREATE TABLE theme_file (
			    id INTEGER PRIMARY KEY auto_increment,
			    theme_id int(10) NOT NULL default '0',
				file_name varchar(125) NOT NULL DEFAULT '',
				file_ext char(4) NOT NULL DEFAULT '',
				file_location varchar(125) NOT NULL DEFAULT '',
				content text NULL,
				created_at int(10) NOT NULL default '0',
				author_id int(10) NOT NULL default '0',
				updated_at int(10) NOT NULL default '0',
				updated_author_id int(10) NOT NULL default '0'
			) ENGINE = InnoDB;
		");
	}

	public function down()
	{
		echo "m120624_192157_add_theme_table does not support migration down.\n";
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