<?php
$this->breadcrumbs=array(
    'СППР'=>array('index'),
    'Створення',
);

?>

<h1>Створення СППР</h1>

<?php echo $this->renderPartial('_form', array( 'model'=>$model)); ?>