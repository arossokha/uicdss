<?php

class m120904_170418_user extends CDbMigration
{
	public function up()
	{
		$this->createTable('User', array(
										'userId' => 'pk',
										'name' => 'varchar(20)',
										'lastName' => 'varchar(20)',
										'email' => 'varchar(51)',
										'login' => 'varchar(20)',
										'password' => 'varchar(40)',
										'info' => 'text',
										'role' => "enum('user','expert')",
										'active' => 'int(1) DEFAULT 1',
										'updated_timestamp' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
										'created_timestamp' => 'timestamp',
								   ), 'ENGINE=InnoDB CHARSET=utf8');

		$this->execute("INSERT INTO `User` (`name`, `lastName`,  `email`, `login`, `password`, `role`, `created_timestamp`) 
		VALUES
		('Admin', 'Admin', 'pokemon4ik1@mailinator.com', 'admin', 'e00cf25ad42683b3df678c61f42c6bda', 'expert', NOW()),
		('User', 'User', 'pokemon4ik2@mailinator.com', 'user', 'e00cf25ad42683b3df678c61f42c6bda', 'user', NOW())
		");
	}

	public function down()
	{
		$this->dropTable('User');
	}
}
