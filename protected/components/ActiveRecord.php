<?php
Yii::import('application.components.DateFormatter');
/**
 * Use this model for generate other models
 * @author: Artem Rossokha <artiom.rossokha@iqria.com>
 */
class ActiveRecord extends CActiveRecord
{
	public function behaviors()
	{
		return array(
			'AutoTimestampBehavior' => array(
				'class' => 'application.components.behaviors.AutoTimestampBehavior',
			)
		);
	}

	protected static $defaultScopeEnable = true;

	public static function disableDefaultScope()
	{
		self::$defaultScopeEnable = false;
	}

	public static function enableDefaultScope()
	{
		self::$defaultScopeEnable = true;
	}

	/**
	 * Update standart delete action
	 */
	public function delete() {

		$columns = $this->tableSchema->columns;

		if(array_key_exists('active', $columns)) {
			if($this->beforeDelete())
			{
				$this->active = 0;
				$res = $this->save(false);
				$this->afterDelete();
				return $res;
			}
		}

		return parent::delete();
	}

	public function deleteAll($condition='',$params=array())
	{
		$columns = $this->tableSchema->columns;

		if(array_key_exists('active', $columns)) {
		    Yii::trace(get_class($this).'.deleteAll()','system.db.ar.CActiveRecord');
		    $builder=$this->getCommandBuilder();
		    $criteria=$builder->createCriteria($condition,$params);
		    $command=$builder->createUpdateCommand($this->getTableSchema(),$criteria);

		    var_dump("NOT IMPLEMENTED",$command);
		    die();
		    return $command->execute();
		}

		return parent::deleteAll();
	}
}
