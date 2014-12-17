<?php
$this->breadcrumbs=array(
	'СППР'=>array('index'),
	$model->name,

);

?>

<h1>СППР "<?php echo $model->name; ?>"</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'description',
		// 'expert.name',
	),
)); ?>
