<?php

class m141201_163833_sppr_core extends CDbMigration
{
    public function up()
    {

        $this->createTable('DSS', array(
                                'dssId' => 'pk',
                                'name' => 'varchar(250)',
                                'description' => 'varchar(1000)',
                                'expertId' => 'int(11)',
                           ), 'ENGINE=InnoDB CHARSET=utf8');

        $this->createTable('ClientDSS', array(
                            'clientDssId' => 'pk',
                            'dssId' => 'int(11)',
                            'clientId' => 'int(11)',
                       ), 'ENGINE=InnoDB CHARSET=utf8');


        $this->createTable('Node', array(
                                'nodeId' => 'pk',
                                'dssId' => 'int(11)',
                                'name' => 'varchar(250)',
                                'description' => 'varchar(1000)',
                                'outputParamId' => 'int(11) DEFAULT NULL',
                           ), 'ENGINE=InnoDB CHARSET=utf8');

        $this->addForeignKey('Node_DSS_fk','Node','dssId','DSS','dssId');

        $this->createTable('Term', array(
                        'termId' => 'pk',
                        'names' => 'varchar(250)',
                        'termCount' => 'INT(2)',
                   ), 'ENGINE=InnoDB CHARSET=utf8');

        $this->createTable('Param', array(
                                'paramId' => 'pk',
                                'nodeId' => 'int(11) DEFAULT NULL',
                                'name' => 'varchar(250)',
                                'description' => 'varchar(1000)',
                                'inverse' => 'INT(1) DEFAULT 0',
                                'min' => 'float(10,2) DEFAULT 0',
                                'max' => 'float(10,2) DEFAULT 100',
                                'termId' => 'int(11) DEFAULT NULL',
                           ), 'ENGINE=InnoDB CHARSET=utf8');
        
        $this->addForeignKey('Node_OutputParam_fk','Node','outputParamId','Param','paramId');
        $this->addForeignKey('Param_Term_fk','Param','termId','Term','termId');
        $this->addForeignKey('Param_Node_fk','Param','nodeId','Node','nodeId');

    }

    public function down()
    {
    	$this->dropForeignKey('Node_DSS_fk','Node');
		$this->dropForeignKey('Node_OutputParam_fk','Node');
		$this->dropForeignKey('Param_Term_fk','Param');
		$this->dropForeignKey('Param_Node_fk','Param');

        $this->dropTable('Term');
        $this->dropTable('Param');
        $this->dropTable('Node');
        $this->dropTable('ClientDSS');
        $this->dropTable('DSS');
    }

}