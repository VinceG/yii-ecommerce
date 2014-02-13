<?php

class m120422_201427_add_auth_tables extends CDbMigration
{
	public function up()
	{
		$this->execute('DROP TABLE IF EXISTS `auth_item_child`');
		$this->execute('DROP TABLE IF EXISTS `auth_assignment`');
		$this->execute('DROP TABLE IF EXISTS `auth_item`');
		$this->execute("
			create table `auth_item`
			(
			   `id`					  int(10) NOT NULL auto_increment,	
			   `name`                 varchar(64) not null,
			   `type`                 integer not null,
			   `description`          text,
			   `bizrule`              text,
			   `data`                 text,
			   primary key (`id`),
			   KEY name (`name`)
			) engine InnoDB;
		");
		
		$this->execute("
			create table `auth_item_child`
			(
			   `parent`               varchar(64) not null,
			   `child`                varchar(64) not null,
			   primary key (`parent`,`child`),
			   foreign key (`parent`) references `auth_item` (`name`) on delete cascade on update cascade,
			   foreign key (`child`) references `auth_item` (`name`) on delete cascade on update cascade
			) engine InnoDB;
		");
		
		$this->execute("
			create table `auth_assignment`
			(
			   `itemname`             varchar(64) not null,
			   `userid`               varchar(64) not null,
			   `bizrule`              text,
			   `data`                 text,
			   primary key (`itemname`,`userid`),
			   foreign key (`itemname`) references `auth_item` (`name`) on delete cascade on update cascade
			) engine InnoDB;
		");

		/** @var $am CDbAuthManager */
		$am = Yii::app()->authManager;
		
		if(!$am->getAuthItem('admin')) {
			echo "Creating admin role\n";
			$am->createRole('admin', 'Administrator');
			$am->createOperation('op_acp_access', 'Admin Access');
			$am->addItemChild('admin', 'op_acp_access');
		}
	}

	public function down()
	{
		/** @var $am CDbAuthManager */
		$am = Yii::app()->authManager;

		echo "Removing admin role\n";
		$am->removeAuthItem('admin');

		$this->dropTable('auth_assignment');
		$this->dropTable('auth_item_child');
		$this->dropTable('auth_item');
	}
}