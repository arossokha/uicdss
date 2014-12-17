<?php

class ParamController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout=false;

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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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
	public function actionCreate()
	{
		if(Yii::app()->getRequest()->isAjaxRequest) {
			$model=new Param;
			$term=new Term;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
			if(isset($_POST['Param']['paramId']) && $_POST['Param']['paramId'] > 0) {
				$find = Param::model()->findByPk($_POST['Param']['paramId']);
				if($find) {
					$model = $find;
				}
			}

			if($model->isNewRecord) {
				$model->attributes=$_POST['Param'];
			}

			if($_POST['newTerm'] == 1) {
				$term->setAttributes($_POST['Term']);
				$model->validate();
				if($term->validate() && count($model->getErrors()) <= 1) {
					$term->save();
					$model->termId = $term->termId;
				}
			}

			if(!$term->hasErrors() && $model->save()) {
				$param = Yii::app()->db->createCommand("select 
					paramId,
					name,
					inverse,
					concat(`min`,'..',`max`) as 'range',
					names as terms
					from Param
					LEFT JOIN Term USING(termId)
					WHERE paramId = :pId ")->queryRow(true,array(':pId' => $model->paramId));
				$this->successAjaxResponce($param);
			} else {
				if($model->hasErrors('termId') && !$term->hasErrors()) {
					$model->clearErrors('termId');
				}
				$body = $this->renderPartial('create',array(
					'model'=>$model,
					'term'=>$term,
					'showSelect' => isset($_POST['Param']['paramId'])
				),true,false);
				$this->failedAjaxResponce($body);
			}
		} else {
			throw new CHttpException(404,"Invalid Request");
			
		}
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

		if(isset($_POST['Param']))
		{
			$model->attributes=$_POST['Param'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->paramId));
		}

		$this->render('update',array(
			'model'=>$model,
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
		$dataProvider=new CActiveDataProvider('Param');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Param('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Param']))
			$model->attributes=$_GET['Param'];

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
		$model=Param::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='param-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
