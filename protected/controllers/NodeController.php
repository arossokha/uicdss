<?php

class NodeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('view','create','update','delete','rules'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->saveModel(new Node());
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		$this->saveModel($model);
	}

	protected function saveModel($model) {
		$params = array();
		$outputParam = array();
		if($model->isNewRecord) {
			$dssId = Yii::app()->getRequest()->getParam('dssId');
		} else {
			$dssId = $model->dssId;
			$paramIds = $model->getParamIds();
			$outputParam = Yii::app()->db->createCommand("
				select 
				paramId,
				name,
				inverse,
				concat(`min`,'..',`max`) as 'range',
				names as terms
				from Param
				LEFT JOIN Term USING(termId)
				WHERE paramId = :e ")->queryRow(true,array(':e' => $model->outputParamId));
		}
		if(isset($_POST['Node']))
		{
			$model->attributes=$_POST['Node'];
			$model->dssId = $dssId;
			$paramIds = isset($_POST['paramIds']) && is_array($_POST['paramIds']) ? $_POST['paramIds'] : array();
			$paramIds = array_map('intval',$paramIds);
			if(!empty($paramIds)) {
				if($model->save()) {
					Yii::app()->db->createCommand("
					Update Param set nodeId = NULL
					WHERE nodeId = {$model->nodeId} ")->execute();
					$paramIdsList = implode(',',$paramIds);
					Yii::app()->db->createCommand("
					Update Param set nodeId = {$model->nodeId}
					WHERE paramId IN ({$paramIdsList}) ")->execute();
					$model->rulesTable = '';
					$model->save(false);
					$this->redirect('/dSS/update/'.$model->dssId);
				}
			} else {
				$model->validate();
				$model->addError('paramIds',Yii::t('app','Need at least one param'));
			}
			if($model->outputParamId > 0) {
				$outputParam = Yii::app()->db->createCommand("
					select 
					paramId,
					name,
					inverse,
					concat(`min`,'..',`max`) as 'range',
					names as terms
					from Param
					LEFT JOIN Term USING(termId)
					WHERE paramId = :e ")->queryRow(true,array(':e' => $model->outputParamId));
			}
		}

		if(is_array($paramIds) && count($paramIds)) {
			$paramIdsList = implode(',',$paramIds);
			$params = Yii::app()->db->createCommand("select 
					paramId,
					name,
					inverse,
					concat(`min`,'..',`max`) as 'range',
					names as terms
					from Param
					LEFT JOIN Term USING(termId)
				WHERE paramId IN ({$paramIdsList})")->queryAll();
		} else {
			$params = array();
			$paramIds = array();
		}

		$dss = DSS::model()->findByPk($dssId);
		$dssName = $dss->name;
		$this->render($model->isNewRecord ? 'create' : 'update',array(
			'model'=>$model,
			'dssName'=> $dssName,
			'dssId'=> $dssId,
			'params'=> $params,
			'paramIds'=> $paramIds,
			'outputParam'=> $outputParam,
		));
	}

	public function actionRules($nodeId) {
		$rules = array();

		$node = $this->loadModel($nodeId);
		$rulesTable = $node->loadRulesTable();

		$params = $node->params;

		$paramArray = array_map(function($param){
			return array(
					'paramId' => $param->paramId,
					'name' => $param->name,
					'terms' => $param->term->getNamesArray(),
					'termId' => $param->termId,
					'termCount' => $param->term->termCount,
					'min' => $param->min,
					'max' => $param->max,
				);
		},$params);

		foreach ($paramArray as $param) {
			if(empty($rules)) {
				foreach ($param['terms'] as $k => $term) {
					$rules[] = array(
						$param['name'] => array(
								'paramId' => $param['paramId'],
								'name' => $param['name'],
								'term' => $term,
								'terms' => $param['terms'],
								'termId' => $param['termId'],
								'termCount' => $param['termCount'],
								'min' => $param['min'],
								'max' => $param['max'],
								'type' => Param::TYPE_INPUT,
							)
						);
				}
			} else {
				$c = count($rules);
				$rules = Util::duplicateArray($rules,count($param['terms']));
				$start = 0;
				foreach ($param['terms'] as $k => $term) {
					for($i = 0; $i < $c ; $i++) {
						$rules[$i+$start][$param['name']] = array(
								'paramId' => $param['paramId'],
								'name' => $param['name'],
								'term' => $term,
								'terms' => $param['terms'],
								'termId' => $param['termId'],
								'termCount' => $param['termCount'],
								'min' => $param['min'],
								'max' => $param['max'],
								'type' => Param::TYPE_INPUT,
							);
					}
					$start += $c;
				}
			}
		}

		if($node->outputParam) {
			$param = $node->outputParam;
			$termNames = $param->term->getNamesArray();
			$row = array(
				'paramId' => $param->paramId,
				'name' => $param->name,
				'terms' => $termNames,
				'term' => null,
				'termId' => $param->termId,
				'termCount' => $param->term->termCount,
				'min' => $param->min,
				'max' => $param->max,
				'type' => Param::TYPE_OUTPUT,
			);
			$c = count($rules);
			for($i = 0; $i < $c ; $i++) {
				if(isset($_POST['rows'][$i][$param->name]) && in_array($_POST['rows'][$i][$param->name], $termNames)) {
					$row['term'] = $_POST['rows'][$i][$param->name];
				} elseif(!empty($rulesTable)) {
					$row['term'] = $rulesTable[$i][$param->name]['term'];
				}
				$rules[$i][$param->name] = $row;
			}
		}

		if(!empty($_POST['rows']) && is_array($_POST['rows'])) {
			$node->rulesTable = serialize($rules);
			if(!$node->save()){
				throw new CHttpException(500,"Rules table for Node not saved!");
			} else {
				$this->redirect('/dSS/update/'.$node->dssId);
			}
		}

		$this->render('rules',array(
				'rules' => $rules
			));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Node::model()->findByPk($id);
		if($model===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='node-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
