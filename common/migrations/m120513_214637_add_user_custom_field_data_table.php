<?php

class m120513_214637_add_user_custom_field_data_table extends CDbMigration
{
	public function up()
	{
		$this->execute('DROP TABLE IF EXISTS `user_custom_field_data`');
		$this->execute("
			CREATE TABLE IF NOT EXISTS user_custom_field_data
			(
				id int(10) NOT NULL auto_increment,
				user_id int(10) NOT NULL default '0',
				field_id int(10) NOT NULL default '0',
				value TEXT NULL,
				PRIMARY KEY (id),
				KEY user_id (user_id),
				KEY field_id (field_id),
				UNIQUE (user_id, field_id)
			) ENGINE = InnoDB;
		");
	}

	public function down()
	{
		echo "m120513_214637_add_user_custom_field_data_table does not support migration down.\n";
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