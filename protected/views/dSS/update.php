<?php
$this->breadcrumbs=array(
    'СППР'=>array('index'),
	'СППР "'. $model->name.'"' =>array('update','id'=>$model->dssId),
	'Оновлення',
);

?>

<h1>Оновлення СППР "<?php echo $model->name; ?>"</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

<div class="row buttons">
    <?php echo CHtml::button('Add node',array('submit' => '/node/create?dssId='.$model->dssId)); ?>
</div>


<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'node-grid',
    'dataProvider'=>$node->search(),
    'columns'=>array(
        'name',
        array(
            'header' => 'Input Params',
            'type' => 'raw',
            'value' => '$data->getParamsList();',
        ),
        'outputParam.name',
        array(
            'class'=>'CButtonColumn',
            'template' => '{table} {update} {delete}',
            'updateButtonUrl' => '"/node/update/".$data->nodeId',
            'deleteButtonUrl' => '"/node/delete/".$data->nodeId',
            'buttons' => array(
                'table' => array(
                        'label' => 'Rules table',
                        'imageUrl' => '/images/rulesTable.png',
                        'url' => '"/node/rules?nodeId=".$data->nodeId',
                        'options' => array(),
                        // 'click' => '',
                    ),
            ),
        ),
    ),
)); ?>