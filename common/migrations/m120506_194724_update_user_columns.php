<?php

class m120506_194724_update_user_columns extends CDbMigration
{
	public function up()
	{
		$this->dropColumn('user', 'shipping_country');
		$this->dropColumn('user', 'shipping_state');
		$this->dropColumn('user', 'billing_country');
		$this->dropColumn('user', 'billing_state');
		
		$this->addColumn('user', 'shipping_country', "int(10) NULL");
		$this->addColumn('user', 'shipping_state', "int(10) NULL");
		$this->addColumn('user', 'billing_country', "int(10) NULL");
		$this->addColumn('user', 'billing_state', "int(10) NULL");
	}

	public function down()
	{
		echo "m120506_194724_update_user_columns does not support migration down.\n";
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