<?php
$this->breadcrumbs=array(
	'СППР'=>array('index'),
	'Список',
);
?>

<h1>Список СППР</h1>
<?php echo CHtml::link('Створення СППР','/dSS/create',array('class'=>'create-button')); ?>
<div class="search-form" >
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'dss-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'name',
		array(
			'header' => 'Клiенти',
			'type' => 'raw',
			'value' => '$data->getClientList()',
		),
		array(
			'class'=>'CButtonColumn',
			'template' => '{view} {clone} {update} {delete}',
			'buttons' => array(
				'clone' => array(
						'label' => 'Clone',
						'imageUrl' => '',
						'url' => '"/dSS/clone/".$data->dssId',
						'options' => array(),
						// 'click' => '',
					),
			),
		),
	),
)); ?>
