<?php

class m150112_192029_update_node extends CDbMigration
{
	public function up()
	{
		$this->addColumn('Node','rulesTable','text');
	}

	public function down()
	{
		$this->dropColumn('Node','rulesTable');
	}

}