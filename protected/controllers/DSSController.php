<?php

class DSSController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','client'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','clone'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
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
	public function actionClone($id)
	{
		$id = (int) $id;
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DSS']))
		{
			$model->primaryKey = null;
			$model->setIsNewRecord(true);
			$model->attributes=$_POST['DSS'];
			$transaction=Yii::app()->db->beginTransaction();
            try
            {
                if ($model->save()) {
                    $nodes = Node::model()->findAllByAttributes(
                    	array('dssId' => $id), array('with' => array('params', 'outputParam')));
                    foreach($nodes as $node)
                    {
                        $params = $node->params();
                        $outputParam = $node->outputParam();
                        $node->primaryKey = null;
                        $node->setIsNewRecord(true);
                        $node->dssId = $model->primaryKey;
                        if ($node->save(true)) {
                            foreach($params as $param)
                            {
                                $param->primaryKey = null;
                                $param->setIsNewRecord(true);
                                $param->nodeId = $node->primaryKey;
                                if (!$param->save(false)) {
                                    throw new CDbException("Param not created");
                                }
                            }
                            $outputParam->primaryKey = null;
                            $outputParam->setIsNewRecord(true);
                            $outputParam->nodeId = $node->primaryKey;
                            if (!$param->save(false)) {
                                throw new CDbException("Output Param not created");
                            }
                        } else {
                            throw new CDbException("Node not created");
                        }
                    }
                    $transaction->commit();
                    $this->redirect(array('index'));
                    // $this->redirect(array('update', 'id' => $model->dssId));
                }
                $transaction->rollback();
            }
            catch (Exception $e)
            {
                $transaction->rollback();
            }
        } else {
			$model->name .= ' (clone)';
		}

		$this->render('clone',array(
			'model'=>$model,
		));
	}

	/**
	 * Clone a old model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new DSS;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DSS']))
		{
			$model->attributes=$_POST['DSS'];
			if($model->save()) {
				$this->redirect(array('update','id'=>$model->dssId));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DSS']))
		{
			$model->attributes=$_POST['DSS'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->dssId));
		}


		$node=new Node('search');
		$node->unsetAttributes();
		$node->dssId = $model->primaryKey;
		if(isset($_GET['Node'])) {
			$node->attributes=$_GET['Node'];
		}

		$this->render('update',array(
			'model'=>$model,
			'node'=>$node,
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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new DSS('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DSS'])){
			$model->attributes=$_GET['DSS'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=DSS::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='dss-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionClient($dssId = null) {

		if($dssId > 0) {
			$results = array();
			if(isset($_POST['nodeId']) && is_array($_POST['nodeId']) && count($_POST['nodeId'])) {
				$nodes = Node::model()->findAllByPk($_POST['nodeId']);
				$activization = array();
				$skipedNodes = array();
				foreach ($nodes as $nodeNum => $node) {
					$paramIdList = array_map(function($item) {
						return array_map('intval',$item);
					},$_POST['paramId']);

					$params = Param::model()->findAllByPk($paramIdList[$nodeNum]);
					$fasification = array();
					$paramsArray = array();
					foreach ($params as $paramNum => $param) {
						$currentParamValue = $_POST['paramValue'][$nodeNum][$paramNum];
						$fasification[] = $param->fasification($currentParamValue);
						$paramsArray[] = $param->getATtributes();
					}

					if(!$node->hasRulesTable()) {
						$skipedNodes[$node->primaryKey] = array(
							'nodeId' => $node->primaryKey,
							'name' => $node->name,
							'errors' => array('rulesTable' => array('No rules table')),
						);
						continue;
					}
					$aggregation = $node->aggregation($fasification);

					$results[$node->primaryKey]['node'] = array(
							'nodeId' => $node->primaryKey,
							'dssId' => $node->dssId,
							'name' => $node->name,
							'description' => $node->description,
							'outputParamId' => $node->outputParamId,
						);
					$results[$node->primaryKey]['params'] = $paramsArray;
					$results[$node->primaryKey]['fasification'] = $fasification;
					$results[$node->primaryKey]['aggregation'] = $aggregation;
					$results[$node->primaryKey]['activization'] = $node->activization($aggregation);
				}
			}

			$outputParamIds = array_map(function($param) {
				return $param->primaryKey;
			},Param::model()->findAllBySql('
						Select Param.* from Param 
						INNER JOIN Node ON outputParamId = paramId
						WHERE Node.dssId = :dssId
						LIMIT 1000
					',array(
						':dssId' => $dssId
						)));
			$dss = $this->loadModel($dssId);
			$this->render('clientDss',array(
				'dss' => $dss,
				'outputParamIds' => $outputParamIds ,
				'results' => $results,
				'skipedNodes' => $skipedNodes,
				));
		} else {
			$model=new DSS('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['DSS'])){
				$model->attributes=$_GET['DSS'];
			}

			$this->render('clientDssList',array(
				'model'=>$model,
			));
		}
	}
}
