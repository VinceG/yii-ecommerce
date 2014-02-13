<?php

class m120506_184433_add_user_extra_columns extends CDbMigration
{
	public function up()
	{
		$columns = array(
			// Basic
			'first_name' => "varchar(125) NULL",
			'last_name' => "varchar(125) NULL",
			'birth_date' => "varchar(125) NULL",
			'company' => "varchar(125) NULL",
			'contact' => "varchar(125) NULL",
			'home_phone' => "varchar(125) NULL",
			'cell_phone' => "varchar(125) NULL",
			'work_phone' => "varchar(125) NULL",
			'fax' => "varchar(125) NULL",
			
			// Shipping
			'shipping_contact' => "varchar(125) NULL",
			'shipping_address1' => "varchar(125) NULL",
			'shipping_address2' => "varchar(125) NULL",
			'shipping_city' => "varchar(125) NULL",
			'shipping_state' => "varchar(125) NULL",
			'shipping_zip' => "varchar(125) NULL",
			'shipping_country' => "varchar(125) NULL",
			
			// Billing
			'billing_contact' => "varchar(125) NULL",
			'billing_address1' => "varchar(125) NULL",
			'billing_address2' => "varchar(125) NULL",
			'billing_city' => "varchar(125) NULL",
			'billing_state' => "varchar(125) NULL",
			'billing_zip' => "varchar(125) NULL",
			'billing_country' => "varchar(125) NULL",
		);
		
		foreach($columns as $name => $def) {
			$this->addColumn('user', $name, $def);
		}
	}

	public function down()
	{
		echo "m120506_184433_add_user_extra_columns does not support migration down.\n";
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