<?php

class m120506_211503_add_us_cities_list extends CDbMigration
{
	public function up()
	{
		$this->execute('DROP TABLE IF EXISTS `us_city`');
		$this->execute("CREATE TABLE us_city (
		  id int(10) NOT NULL auto_increment,	
		  city_zip int(5) unsigned zerofill not null,
		  city_name varchar(50) not null,
		  city_state char(2) not null,
		  city_latitude double not null,
		  city_longitude double not null,
		  city_county varchar(50) not null,
		  primary key (id),
		  unique (city_zip)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		
		$contents = file_get_contents(Yii::getPathOfAlias('common.data') . '/us_cities.sql');
		$contents = trim($contents);
		$inserts = explode(';;;', $contents);
		foreach($inserts as $insert) {
			$this->execute(trim($insert));
		}
		
	}

	public function down()
	{
		echo "m120506_211503_add_us_cities_list does not support migration down.\n";
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