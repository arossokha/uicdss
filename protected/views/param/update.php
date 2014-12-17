<?php
$this->breadcrumbs=array(
	'Params'=>array('index'),
	$model->name=>array('view','id'=>$model->paramId),
	'Update',
);

$this->menu=array(
	array('label'=>'List Param', 'url'=>array('index')),
	array('label'=>'Create Param', 'url'=>array('create')),
	array('label'=>'View Param', 'url'=>array('view', 'id'=>$model->paramId)),
	array('label'=>'Manage Param', 'url'=>array('admin')),
);
?>

<h1>Update Param <?php echo $model->paramId; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>