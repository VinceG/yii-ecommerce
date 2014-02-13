<?php

class m120610_200816_add_language_source extends CDbMigration
{
	public function up()
	{
		$this->delete('language');
		$this->insert('language', array('id' => 1, 'name' => 'English', 'abbr' => 'en', 'is_source' => 1, 'created_at' => time()));
	}

	public function down()
	{
		echo "m120610_200816_add_language_source does not support migration down.\n";
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