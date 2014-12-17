<?php
$this->breadcrumbs=array(
    'СППР'=>'/dSS/index',
	'СППР "'.$dssName.'"' =>'/dSS/update/'.$dssId,
	'Створення блоку',
);

?>

<h1>Створення блоку</h1>

<?php
    echo $this->renderPartial('_form', array( 'model'=>$model,
                                                'params' => $params,
                                                'outputParam' => $outputParam,
                                                'dssId' => $dssId,
                                                'paramIds' => $paramIds,
                                                'showSelect' => isset($showSelect) ? $showSelect : false
                                                )); ?>