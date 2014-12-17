<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'node-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>10,'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<div id="create-input-param-dialog">
	    <?php echo CHtml::link(Yii::t('app','Create param'),'javascript:void(0);',array(
	        'onclick'=>'$("#createParamDialog").dialog("open"); return false;',
	        // 'update'=>'#paramDialog'
	        ),array('id'=>'showParamDialog'));?>
	</div>
	<label for="params_input">Input params</label>
	<div class="grid-view">
		<table id="params_input" class="items">
			<thead>
				<tr>
					<th>Назва</th>
					<th>Iнверсiя</th>
					<th>Дiапазон</th>
					<th>Терми</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(count($params)) {
				foreach ($params as $param) {
					?>
						<tr>
							<td> 
							<?php
								echo $param['name'];
								echo CHtml::hiddenField('paramIds[]',$param['paramId']);
							?> 
							</td>
							<td><?=$param['inverse']?></td>
							<td><?=$param['range']?></td>
							<td><?=$param['terms']?></td>
							<td> Update / <a href="javascript:void(0);" class="removeParamRow" >Delete</a> </td>
						</tr>
					<?php
				}
			} else {
				?>
					<tr>
						<td colspan="5">Параметри вiтсутнi</td>
					</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	</div>

	<div id="create-output-param-dialog">
    <?php echo CHtml::link(Yii::t('app','Create param'),'javascript:void(0);',array(
        'onclick'=>'$("#createOutputParamDialog").dialog("open"); return false;',
        // 'update'=>'#paramDialog'
        ),array('id'=>'showParamOutputDialog'));
    ?>
	</div>
	<label for="params_input">Output param</label>
	<div class="grid-view">
		<table id="params_output" class="items">
			<thead>
				<tr>
					<th>Назва</th>
					<th>Iнверсiя</th>
					<th>Дiапазон</th>
					<th>Терми</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php

			if(!empty($outputParam)) {
				echo "<tr>";
				echo "<td>{$outputParam['name']}
				".$form->hiddenField($model,'outputParamId')."
				</td>";
				echo "<td>{$outputParam['inverse']}</td>";
				echo "<td>{$outputParam['range']}</td>";
				echo "<td>{$outputParam['terms']}</td>";
				echo "<td> Update / <a href=\"javascript:void(0);\" class=\"removeParamRow\" >Delete</a> </td>";
				echo "</tr>";
			} else {
				?>
					<tr>
						<td colspan="5">Параметри вiтсутнi</td>
					</tr>
				<?php
			}

			?>
			</tbody>
		</table>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php 
	$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
        'id'=>'createParamDialog',
        'options'=>array(
                'title'=>Yii::t('app','Create input param'),
                'autoOpen'=>false,
                'modal'=>'true',
                'width'=>'auto',
                'height'=>'auto',
                'buttons' => array(
                    array('text'=>'Create input param','click'=> 'js:function() {
                    	addParam("#params_input",createParamForm,"paramIds[]");
                    }'),
                    array('text'=>'Cancel','click'=> 'js:function() {
						createParamForm.reset();
                    	$(createParamDialog).dialog("close");
                    }'),
                ),
            ),
        )
    );
 ?>
	<h2>Create input param</h2>
	<?php echo $this->renderPartial('application.views.param._form', 
	array(
		'model' => new Param(),'showSelect' => true,
		'outputParamId' => $model->outputParamId,
		'dssId'  => isset($dssId) && $dssId > 0 ? $dssId : $model->dssId
	),
	true,false); ?>
<?php 
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<?php 
	$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
        'id'=>'createOutputParamDialog',
        'options'=>array(
                'title'=>Yii::t('app','Create output param'),
                'autoOpen'=>false,
                'modal'=>'true',
                'width'=>'auto',
                'height'=>'auto',
                'buttons' => array(
                    array('text'=>'Create param','click'=> 'js:function() {
                    	addParam("#params_output",createOutputParamForm,"Node[outputParamId]");
                    }'),
                    array('text'=>'Cancel','click'=> 'js:function() {
						createOutputParamForm.reset();
                    	$(createOutputParamDialog).dialog("close");
                    }'),
                ),
            ),
        )
    );
 ?>
	<h2>Create output param</h2>
	<?php echo $this->renderPartial('application.views.param._form', array('model' => new Param()),true,false); ?>
<?php 
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<script>
	function addParamToTable(tableSelector,data,paramName) {
		var table = $(tableSelector);
		if('data' in data) {
			var row = '<tr>';
			row += '<td>'+data.data.name+'<input type="hidden" name="'+paramName+'" value="'+data.data.paramId+'"/></td>';
			row += '<td>'+data.data.inverse+'</td>';
			row += '<td>'+data.data.range+'</td>';
			row += '<td>'+data.data.terms+'</td>';
			row += '<td> Update / <a href="javascript:void(0);" class="removeParamRow" >Delete</a> </td>';
			row += '</tr>';
			if(1 == $(table).find('tbody tr td').length){
				$(table).find('tbody tr').remove();
			}
			$(table).find('tbody').append(row);
			return true;
		}

		alert('Incorrect data!');
	}

	function addParam(tableSelector,form,paramName) {
		$.post($(form).attr('action'),$(form).serialize(),function(data) {
			console.dir(data);
			if('status' in data) {
				if('OK' == data.status) {
						form.reset();
						addParamToTable(tableSelector,data,paramName);
						$(createParamDialog).dialog("close");
						$(createOutputParamDialog).dialog("close");
				} else {
					if('data' in data) {
						$(form).html($(data.data).find('form').html());
					} else {
						alert('Server error!');
					}
				}
			} else {
				alert('Incorrect server response!');
			}
		},'json');
	}

	var createParamForm = $("#createParamDialog").find("form").on('submit',function(e) {
		e.preventDefault();
		return false;
	});
	var createOutputParamForm = $("#createOutputParamDialog").find("form").on('submit',function(e) {
		e.preventDefault();
		return false;
	});
	createParamForm = createParamForm[0];
	createOutputParamForm = createOutputParamForm[0];
	var createParamDialog = $("#createParamDialog").dialog('close');
	var createOutputParamDialog = $("#createOutputParamDialog").dialog('close');

	$('.removeParamRow').live('click',function(e) {
		$(this).parent().parent().remove();
	});

	$('.termNumber').live('change',function(e) {
		var count = parseInt($(this).val());
		if(count >= 3 && count <= 7) {
			$(this).parent().children('div').remove();
			var r = '<div> <input type="text" value="" name="Term[names][]" /> </div>';
			$(this).after(Array(count + 1).join(r));
		}
		return true;
	});
	$('.addTerm').live('click',function(e) {
		var termForm = $(this).parents('form').find('.newTermForm');
		$(termForm).show();
		$(termForm).find('input[name="newTerm"]').val(1);
	});

	$('.removeTerm').live('click',function(e) {
		// clean fields
		alert('clean fields');
		var termForm = $(this).parents('form').find('.newTermForm');
		$(termForm).hide();
		$(termForm).find('input[name="newTerm"]').val(0);
	});
</script>