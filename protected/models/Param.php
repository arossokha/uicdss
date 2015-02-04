<?php

/**
 * This is the model class for table "Param".
 *
 * The followings are the available columns in table 'Param':
 * @property integer $paramId
 * @property integer $nodeId
 * @property string $name
 * @property string $description
 * @property integer $inverse
 * @property double $min
 * @property double $max
 * @property integer $termId
 *
 * The followings are the available model relations:
 * @property Node[] $nodes
 * @property Node $node
 * @property Term $term
 */
class Param extends ActiveRecord
{
	const TYPE_OUTPUT = 'output';
	const TYPE_INPUT = 'input';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Param the static model class
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
		return 'Param';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, min, max, termId', 'required'),
			array('nodeId, inverse, termId', 'numerical', 'integerOnly'=>true),
			array('min, max', 'numerical'),
			array('name', 'length', 'max'=>250),
			array('description', 'length', 'max'=>1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('paramId, nodeId, name, description, inverse, min, max, termId', 'safe', 'on'=>'search'),
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
			'nodes' => array(self::HAS_MANY, 'Node', 'outputParamId'),
			'node' => array(self::BELONGS_TO, 'Node', 'nodeId'),
			'term' => array(self::BELONGS_TO, 'Term', 'termId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'paramId' => 'Param',
			'nodeId' => 'Node',
			'name' => 'Name',
			'description' => 'Description',
			'inverse' => 'Inverse',
			'min' => 'Min',
			'max' => 'Max',
			'termId' => 'Term',
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

		$criteria->compare('paramId',$this->paramId);
		$criteria->compare('nodeId',$this->nodeId);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('inverse',$this->inverse);
		$criteria->compare('min',$this->min);
		$criteria->compare('max',$this->max);
		$criteria->compare('termId',$this->termId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function fasification($paramValue)
	{
		$termNames = $this->term->getNamesArray();
		$termsCount = $this->term->termCount;
		$min = floatval($this->min);
		$max = floatval($this->max);

		$step = ($max-$min) / ($termsCount - 1) ;

		$termTable = array();
		$termValue = $min;
		foreach ($termNames as $termNum => $termName) {
			$value = 0;

			if($termValue == $min) {
				$b = 1;
				$a = -($b/($termValue+$step));
				$value = $a*$paramValue+$b;
				$start = $termValue;
				$end = $termValue+$step;
			} elseif($termValue == $max) {
				$b = -1*($termNum-1);
				$a = (-1*$b)/($termValue-$step);
				$value = $a*$paramValue+$b;

				$start = $termValue-$step;
				$end = $termValue;
			} else {
				$b = -1*($termNum-1);
				if(1 == $termNum) {
					$a = 1/$termValue;
				} else {
					$a = 1/($termValue-$step);
				}
				$y1 = $a*$paramValue+$b;

				$b = $termNum+1;
				$a = -($b/($termValue+$step));
				$y2 = $a*$paramValue+$b;

				if($y1 < 0 || $y1 > 1) {
					$y1 = 0;
				}
				if($y2 < 0 || $y2 > 1) {
					$y2 = 0;
				}

				$value = max($y1,$y2);
				$start = $termValue-$step;
				$end = $termValue+$step;
			}

			if($value < 0 || $value > 1) {
				$value = 0;
			}

			$termTable[$termNum] = array(
					'termId' => $this->termId,
					'term' => $termName,
					'termValue' => $termValue,
					'value' => $value,
					'start' => $start,
					'end' => $end,
					'paramId' => $this->primaryKey,
					'paramName' => $this->name,
				);

			$termValue += $step;
		}
		return $termTable;
	}
}