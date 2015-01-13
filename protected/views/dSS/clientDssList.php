<?php
$this->breadcrumbs=array(
	'Список СППР',
);
?>

<h1>Список СППР</h1>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'dss-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'name',
		array(
			'class'=>'CButtonColumn',
			'template' => '{view}',
			'viewButtonUrl' => '"/dSS/client/?dssId=".$data->dssId',
		),
	),
)); ?>
