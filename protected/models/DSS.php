<?php

/**
 * This is the model class for table "DSS".
 *
 * The followings are the available columns in table 'DSS':
 * @property integer $dssId
 * @property string $name
 * @property string $description
 * @property integer $expertId
 *
 * The followings are the available model relations:
 * @property Node[] $nodes
 */
class DSS extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DSS the static model class
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
		return 'DSS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('expertId, name', 'required'),
			array('expertId', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>250),
			array('description', 'length', 'max'=>1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('dssId, name, description, expertId', 'safe', 'on'=>'search'),
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
			'nodes' => array(self::HAS_MANY, 'Node', 'dssId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'dssId' => 'СППР',
			'name' => 'Назва',
			'description' => 'Опис',
			'expertId' => 'Експерт',
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

		$criteria->compare('dssId',$this->dssId);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('expertId',$this->expertId);

		$criteria->order = 'name ASC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeValidate() {
		if($this->expertId <= 0) {
			$this->expertId = Yii::app()->user->id;
		}

		return parent::beforeValidate();
	}

	public function getClientList() {
		return 'No clients';
	}

	public function beforeDelete() {
		$nodes = Node::model()->findAll('dssId = :id',array(':id' => $this->dssId));
		foreach ($nodes as $node) {
			Param::model()->deleteAll('nodeId = :id',array(':id' => $node->nodeId));
			if($node->outputParamId) {
				Param::model()->delete('paramId = :id',array(':id' => $node->outputParamId));
			}
		}
		Node::model()->deleteAll('dssId = :id',array(':id' => $this->dssId));
		return parent::beforeDelete();
	}
}