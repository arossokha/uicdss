<?php

/**
 * This is the model class for table "Term".
 *
 * The followings are the available columns in table 'Term':
 * @property integer $termId
 * @property string $names
 * @property integer $termCount
 *
 * The followings are the available model relations:
 * @property Param[] $params
 */
class Term extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Term the static model class
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
		return 'Term';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('termCount,names', 'required'),
			array('termCount', 'numerical', 'integerOnly'=>true),
			array('names', 'isArray'),
			array('names', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('termId, names, termCount', 'safe', 'on'=>'search'),
		);
	}

	public function isArray($attribute,$params = array())
	{
		if(is_array($this->{$attribute})) {
			$this->{$attribute} = array_map('trim',$this->{$attribute});
			if(in_array('', $this->{$attribute})){
				$this->addError($attribute,Yii::t('app','Fill all names!'));
			} else {
				$this->{$attribute} = implode('/',$this->{$attribute});
			}
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'params' => array(self::HAS_MANY, 'Param', 'termId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'termId' => 'Term',
			'names' => 'Names',
			'termCount' => 'Term Count',
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

		$criteria->compare('termId',$this->termId);
		$criteria->compare('names',$this->names,true);
		$criteria->compare('termCount',$this->termCount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getNamesArray()
	{
		return explode('/',$this->names);
	}
}