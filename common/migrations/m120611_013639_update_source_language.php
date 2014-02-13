<?php

class m120611_013639_update_source_language extends CDbMigration
{
	public function up()
	{
		$this->update('language', array('is_public' => 1), 'is_source=1');
	}

	public function down()
	{
		echo "m120611_013639_update_source_language does not support migration down.\n";
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