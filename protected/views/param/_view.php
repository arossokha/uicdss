<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('paramId')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->paramId), array('view', 'id'=>$data->paramId)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nodeId')); ?>:</b>
	<?php echo CHtml::encode($data->nodeId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inverse')); ?>:</b>
	<?php echo CHtml::encode($data->inverse); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('min')); ?>:</b>
	<?php echo CHtml::encode($data->min); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max')); ?>:</b>
	<?php echo CHtml::encode($data->max); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('termId')); ?>:</b>
	<?php echo CHtml::encode($data->termId); ?>
	<br />

	*/ ?>

</div>