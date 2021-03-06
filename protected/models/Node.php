<?php

/**
 * This is the model class for table "Node".
 *
 * The followings are the available columns in table 'Node':
 * @property integer $nodeId
 * @property integer $dssId
 * @property string $name
 * @property string $description
 * @property string $rulesTable
 * @property integer $outputParamId
 *
 * The followings are the available model relations:
 * @property Param $outputParam
 * @property DSS $dss
 * @property Param[] $params
 */
class Node extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Node the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Node';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dssId, outputParamId, name', 'required'),
			array('dssId, outputParamId', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>250),
			array('rulesTable', 'safe'),
			array('description', 'length', 'max'=>1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('nodeId, dssId, name, description, outputParamId', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'outputParam' => array(self::BELONGS_TO, 'Param', 'outputParamId'),
			'dss' => array(self::BELONGS_TO, 'DSS', 'dssId'),
			'params' => array(self::HAS_MANY, 'Param', 'nodeId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'nodeId' => 'Node',
			'dssId' => 'Dss',
			'name' => 'Name',
			'description' => 'Description',
			'outputParamId' => 'Output Param',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('nodeId',$this->nodeId);
		$criteria->compare('dssId',$this->dssId);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('outputParamId',$this->outputParamId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getParamsList() {
		$out = 'Params : <br />';
		$out .= implode(',',array_map(function($param) {
			return $param->name;
		},$this->params));

		return $out;
	}

	public function getParamIds() {
		$ids = array_map(function($param) {
			return (int) $param->primaryKey;
		},$this->params);
		
		return $ids;
	}

	public function hasRulesTable() {
		return !empty($this->rulesTable) && strlen($this->rulesTable) > 10;
	}

	public function loadRulesTable() {
		if(strlen($this->rulesTable) > 10) {
			$data = @unserialize($this->rulesTable);
			return is_array($data) ? $data : array();
		}

		return array();
	}

	public  function aggregation($fasification)
	{
		$rulesTable = $this->loadRulesTable();
		/**
		 * seach for terms in param wiht zero value
		 */
		$fasDisableList = array();
		$fasificationCalcData = array();
		foreach ($fasification as $fasRow) {
			foreach ($fasRow as $fasItem) {
				$fasificationCalcData[$fasItem['paramName']][$fasItem['term']] = array(
					'value' => $fasItem['value'],
					'start' => $fasItem['start'],
					'end' => $fasItem['end'],
					);
				if(!$fasItem['value']) {
					$fasDisableList[$fasItem['paramName']] = $fasItem;
				}
			}
		}

		$aggregationTable = array_filter($rulesTable,function($item) use ($fasDisableList) {
			foreach ($item as $paramName => $row) {
				if($fasDisableList[$paramName]['term'] == $row['term']) {
					return false;
				}
			}
			return true;
		});

		array_walk($aggregationTable, function (&$item,$key,$fasificationCalcData) {
			$outputParamKey = false;
			$values = array();
			foreach ($item as $paramName => $param) {
				if(Param::TYPE_OUTPUT == $param['type']) {
					$outputParamKey = $paramName;
					continue;
				}
				$item[$paramName]['value'] = $fasificationCalcData[$paramName][$param['term']]['value'];
				$item[$paramName]['start'] = $fasificationCalcData[$paramName][$param['term']]['start'];
				$item[$paramName]['end'] = $fasificationCalcData[$paramName][$param['term']]['end'];
				$values[] = $item[$paramName]['value'];
			}
			$item[$outputParamKey]['value'] = min($values);
			// $item['__data'] = $fasificationCalcData;
		},$fasificationCalcData);

		return $aggregationTable;
	}

	public function activization($aggregation)
	{
		$keys = array_keys($aggregation);
		$firstKey = $keys[0];
		$c = count($aggregation[$firstKey]);
		$keys = array_keys($aggregation[$firstKey]);
		$paramName = $keys[$c-1];
		$min = $aggregation[$firstKey][$paramName]['min'];
		$max = $aggregation[$firstKey][$paramName]['max'];

		$terms = $aggregation[$firstKey][$paramName]['terms'];
		$activizationArray = array_flip($terms);


		$termCount = count($terms);
		$step = ($max - $min) / ($termCount-1);
		$termValue = floatval($min);
		foreach ($activizationArray as $term => $row) {
			if($termValue == $min) {
				$start = $termValue;
				$end = $termValue+$step;
			} elseif($termValue == $max) {
				$start = $termValue-$step;
				$end = $termValue;
			} else {
				$start = $termValue-$step;
				$end = $termValue+$step;
			}
			$termValue += $step;

			$activizationArray[$term] = array(
				'term' => $term,
				'min' => $min,
				'max' => $max,
				'start' => $start,
				'end' => $end,
				'step' => $step,
				'value' => .0,
			);
		}
		foreach ($aggregation as $key => $row) {
			if($activizationArray[$row[$paramName]['term']]['value'] < $row[$paramName]['value']) {
				$activizationArray[$row[$paramName]['term']]['value'] = $row[$paramName]['value'];
			}
		}

		return $activizationArray;
	}

	public function defasification($activization)
	{
		// find point coordinates for polygon
		$num = 0;
		$count = count($activization)-1;
		$poligonList = array();
		foreach ($activization as $key => $row) {
			$a = array($row['start'],0);
			$d = array($row['end'],0);

			if(0 == $num) {
				$b = array($row['start'],$row['value']);
				$x = ($num+1-$row['value'])*$row['step'];
				$c = array($x,$row['value']);
			} elseif($count == $num) {
				$x = ($row['value']+$num-1)*$row['step'];
				$b = array($x,$row['value']);
				$c = array($row['end'],$row['value']);
			} else {
				$x = ($row['value']+$num-1)*$row['step'];
				$b = array($x,$row['value']);
				$x = ($num+1-$row['value'])*$row['step'];
				$c = array($x,$row['value']);
			}
			$poligonList[] = array(
					'a' => $a,
					'b' => $b,
					'c' => $c,
					'd' => $d,
				);
			$num++;
		}

		$xList = '';
		$yList = '';
		foreach ($poligonList as $poligon) {
			var_dump($poligon);
			$pointXList = array_map(function($item) {
				return $item[0];
			},$poligon);
			$pointYList = array_map(function($item) {
				return $item[1];
			},$poligon);
			$xList .= implode(';',$pointXList).';';
			$yList .= implode(';',$pointYList).';';
		}

		var_dump($xList, $yList);
		die();

			CVarDumper::dump($poligonList,5,true);
			die();
			return $defasification;
	}

}