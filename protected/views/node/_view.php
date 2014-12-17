<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('nodeId')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->nodeId), array('view', 'id'=>$data->nodeId)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dssId')); ?>:</b>
	<?php echo CHtml::encode($data->dssId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('outputParamId')); ?>:</b>
	<?php echo CHtml::encode($data->outputParamId); ?>
	<br />


</div>