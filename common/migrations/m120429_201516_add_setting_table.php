<?php

class m120429_201516_add_setting_table extends CDbMigration
{
	public function up()
	{
		$this->execute('DROP TABLE IF EXISTS `settingcat`');
		$this->execute('DROP TABLE IF EXISTS `setting`');
		$this->execute("
			CREATE TABLE IF NOT EXISTS settingcat
			(
				id int(10) NOT NULL auto_increment,
				title varchar(125) NOT NULL default '',
				description varchar(255) NULL,
				groupkey varchar(125) NOT NULL default '',
				PRIMARY KEY (id),
				KEY title (title),
				UNIQUE (groupkey)
			) ENGINE = InnoDB;

			CREATE TABLE IF NOT EXISTS setting
			(
				id int(10) NOT NULL auto_increment,
				title varchar(125) NOT NULL default '',
				description text,
				category int(10) NOT NULL default '0',
				type char(30) NOT NULL default 'text',
				settingkey varchar(125) NOT NULL default '',
				default_value text,
				value text null,
				extra text,
				php text,
				PRIMARY KEY (id),
				KEY title (title),
				UNIQUE (settingkey)
			) ENGINE = InnoDB;
		");
		
	}

	public function down()
	{
		echo "m120429_201516_add_setting_table does not support migration down.\n";
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