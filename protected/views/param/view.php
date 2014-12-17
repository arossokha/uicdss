<?php
$this->breadcrumbs=array(
	'Params'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Param', 'url'=>array('index')),
	array('label'=>'Create Param', 'url'=>array('create')),
	array('label'=>'Update Param', 'url'=>array('update', 'id'=>$model->paramId)),
	array('label'=>'Delete Param', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->paramId),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Param', 'url'=>array('admin')),
);
?>

<h1>View Param #<?php echo $model->paramId; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'paramId',
		'nodeId',
		'name',
		'description',
		'inverse',
		'min',
		'max',
		'termId',
	),
)); ?>
