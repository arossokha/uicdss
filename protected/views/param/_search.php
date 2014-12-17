<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'paramId'); ?>
		<?php echo $form->textField($model,'paramId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nodeId'); ?>
		<?php echo $form->textField($model,'nodeId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>1000)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'inverse'); ?>
		<?php echo $form->textField($model,'inverse'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'min'); ?>
		<?php echo $form->textField($model,'min'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max'); ?>
		<?php echo $form->textField($model,'max'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'termId'); ?>
		<?php echo $form->textField($model,'termId'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->