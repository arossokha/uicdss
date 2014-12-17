<?php

/**
 * This is the model class for table "Node".
 *
 * The followings are the available columns in table 'Node':
 * @property integer $nodeId
 * @property integer $dssId
 * @property string $name
 * @property string $description
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
}