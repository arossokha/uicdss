<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'param-form',
	'action' => '/param/create',
	'enableAjaxValidation'=>false,
)); ?>

	<?php
		if(isset($showSelect) && $showSelect) {
	?>
		<label for="Param_id" >Select existing param</label>
		<?php
			$listData = CHtml::listData(
					Param::model()->findAllBySql('
						Select Param.* from Param 
						INNER JOIN Node ON outputParamId = paramId
						WHERE  ISNULL(Param.nodeId)
						AND Node.dssId = :dssId
						AND paramId <> :oPId
						LIMIT 1000
					',array(
						':dssId' => $dssId,
						':oPId' => ($outputParamId > 0 ? $outputParamId : -1),
						))
					,'paramId','name');

			$dropDown = '<select name="Param[paramId]" class="inputParamSelect" >';
				$dropDown .= '<option>Choose param</option>';
			foreach ($listData as $paramId => $paramName) {
				$dropDown .= '<option value="'.$paramId.'" ';
				if(in_array($paramId,$paramIds)) {
					$dropDown .= ' disabled="disabled" >';
				} else {
					$dropDown .= '>';
				}
				$dropDown .= $paramName.'</option>';
			}
			$dropDown .= '</select>';
			echo $dropDown;

		?>
		<br />
	<?php
		}
	?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('cols'=>55,'rows'=>3)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'inverse'); ?>
		<?php echo $form->checkBox($model,'inverse'); ?>
		<?php echo $form->error($model,'inverse'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'min'); ?>
		<?php echo $form->textField($model,'min'); ?>
		<?php echo $form->error($model,'min'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max'); ?>
		<?php echo $form->textField($model,'max'); ?>
		<?php echo $form->error($model,'max'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'termId'); ?>
		<?php 
			echo $form->dropDownList($model,'termId',CHtml::listData(Term::model()->findAll(array('limit' => 3)),'termId','names'),
			array('empty' => 'Select term')); 
		?>
		<button class="addTerm">Add term</button>
		<?php
		if(isset($_POST['newTerm']) && '1' == $_POST['newTerm']) {
			?>
			<div class="newTermForm">
				<?php echo $form->errorSummary($term); ?>
				<input type="hidden" value="1" name="newTerm" />
				<input class="termNumber" type="number" value="<?php echo $_POST['Term']['termCount'];?>" name="Term[termCount]" min="3" max="7"/>
			<?php for($i = 0; $i < (int) $_POST['Term']['termCount']; $i++ ) { ?>
				<div>
					<input type="text" value="<?php echo $_POST['Term']['names'][$i]; ?>" name="Term[names][]" />
				</div>
			<?php } ?>
				<button class="removeTerm">Remove term</button>
			</div>
			<?php
		} else {
						?>
			<div class="newTermForm" style="display:none;">
				<input type="hidden" value="0" name="newTerm" />
				<input class="termNumber" type="number" value="3" name="Term[termCount]" min="3" max="7"/>
				<div>
					<input type="text" value="L" name="Term[names][]" />
				</div>
				<div>
					<input type="text" value="M" name="Term[names][]" />
				</div>
				<div>
					<input type="text" value="H" name="Term[names][]" />
				</div>
				<button class="removeTerm">Remove term</button>
			</div>
			<?php
		}
		?>
		<?php echo $form->error($model,'termId'); ?>

	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->