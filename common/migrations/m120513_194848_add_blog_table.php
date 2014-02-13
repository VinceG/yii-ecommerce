<?php

class m120513_194848_add_blog_table extends CDbMigration
{
	public function up()
	{
		$this->execute('DROP TABLE IF EXISTS `blog_post`');
		$this->execute("
			CREATE TABLE IF NOT EXISTS blog_post
			(
				id int(10) NOT NULL auto_increment,
				title varchar(255) NOT NULL default '',
				alias varchar(250) NOT NULL default '', 
				content text NULL,
				created_at int(10) NOT NULL default '0',
				author_id int(10) NOT NULL default '0',
				updated_at int(10) NOT NULL default '0',
				updated_author_id int(10) NOT NULL default '0',
				tags varchar(255) NOT NULL default '',
				metadesc varchar(255) NOT NULL default '',
				metakeys varchar(255) NOT NULL default '',
				visible text NULL,
				status tinyint(1) NOT NULL default '0',
				PRIMARY KEY (id),
				KEY title (title),
				UNIQUE (alias)
			) ENGINE = InnoDB;
		");
	}

	public function down()
	{
		echo "m120513_194848_add_blog_table does not support migration down.\n";
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