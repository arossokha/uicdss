<?php
$this->breadcrumbs=array(
    'СППР'=>'/dSS/index',
    'СППР "'.$dssName.'"' =>'/dSS/update/'.$dssId,
    'Оновлення блоку "'>$model->name.'"',
);
?>

<h1>Оновлення блоку "<?php echo $model->name; ?>"</h1>

<?php 

echo $this->renderPartial('_form', array( 'model'=>$model,
                                                'params' => $params,
                                                'outputParam' => $outputParam,
                                                'dssId' => $dssId,
                                                'paramIds' => $paramIds,
                                                'showSelect' => isset($showSelect) ? $showSelect : false
                                                )); ?>