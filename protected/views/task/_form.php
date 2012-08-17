<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'task-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>2, 'cols'=>40)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'priority'); ?>
		<?php echo $form->dropDownList($model,'priority',$model->priority_list); ?>
		<?php echo $form->error($model,'priority'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'due_date'); ?>		
		<?php echo $form->dateField($model,'due_date'); ?>
		<?php echo $form->error($model,'due_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'completed'); ?>
		<?php echo $form->checkBox($model,'completed'); ?> <span class="info">Check if completed</span>
		<?php echo $form->error($model,'completed'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->