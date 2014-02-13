<?php

class m120610_202357_update_language_message extends CDbMigration
{
	public function up()
	{
		
		$this->execute("DROP TABLE IF EXISTS message;");
		$this->execute("DROP TABLE IF EXISTS source_message;");

		$this->execute("
			CREATE TABLE source_message
			(
			    id INTEGER PRIMARY KEY auto_increment,
			    category VARCHAR(32),
			    message TEXT
			) ENGINE = InnoDB;
		");
		
		$this->execute("
			CREATE TABLE message
			(
			    id INTEGER auto_increment,
			    language VARCHAR(16),
				language_id int(10) NOT NULL DEFAULT '0',
			    translation TEXT,
			    PRIMARY KEY (id, language),
			    CONSTRAINT fk_message_source_message FOREIGN KEY (id)
			         REFERENCES source_message (id) ON DELETE CASCADE ON UPDATE RESTRICT
			) ENGINE = InnoDB;
		");
	}

	public function down()
	{
		echo "m120610_202357_update_language_message does not support migration down.\n";
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