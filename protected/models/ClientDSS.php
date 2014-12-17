<?php

/**
 * This is the model class for table "ClientDSS".
 *
 * The followings are the available columns in table 'ClientDSS':
 * @property integer $clientDssId
 * @property integer $dssId
 * @property integer $clientId
 */
class ClientDSS extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ClientDSS the static model class
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
		return 'ClientDSS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dssId, clientId', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('clientDssId, dssId, clientId', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'clientDssId' => 'Client Dss',
			'dssId' => 'Dss',
			'clientId' => 'Client',
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

		$criteria->compare('clientDssId',$this->clientDssId);
		$criteria->compare('dssId',$this->dssId);
		$criteria->compare('clientId',$this->clientId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}