<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('dssId')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->dssId), array('view', 'id'=>$data->dssId)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('expertId')); ?>:</b>
	<?php echo CHtml::encode($data->expertId); ?>
	<br />


</div>