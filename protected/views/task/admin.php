<?php
$this->breadcrumbs=array(
	'Tasks',
);

$this->menu=array(
	//array('label'=>'List Task', 'url'=>array('index')),
	array('label'=>'Create Task', 'url'=>'#', 'linkOptions'=>array('onclick'=>"addTask(); $('#dialogTask').dialog('open'); return false;")),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('task-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Tasks</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'task-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		'description',
		array(
			'name'=>'priority',
			'value'=>'$data->priority_list[$data->priority]',
		),
		array(
			'name'=>'due_date',
			'value'=>'date("M j, Y", strtotime($data->due_date))',
		),
		array(
			'header'=>'Status',
			'name'=>'completed',
			'value'=>'$data->completed==1?"Completed":"Open"',
			'htmlOptions'=>array('style'=>'text-align: center;'),
			'headerHtmlOptions'=>array('style'=>'width: 70px;'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'deleteButtonLabel'=>'Remove task',
			'updateButtonLabel'=>'Update task',
			'updateButtonOptions'=>array(
				'onclick'=>"updateModal($(this).attr('href')); $('#dialogTask').dialog('open'); return false;",
			),
			'buttons'=>array(
				'complete'=>array(
					'label'=>'C',
					'click'=>"function(){toggleComplete()}",
					'options'=>array(
						'completed'=>'$data->completed'
					)
				)
			)
		),
	),
)); ?>

	<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array( // the dialog
		'id'=>'dialogTask',
		'options'=>array(
			'title'=>'Task details',
			'autoOpen'=>false,
			'modal'=>true,
			'width'=>520,
			'height'=>420,
		),
	));?>
	<div class="divForForm"></div>

	<?php $this->endWidget();?>

	<script type="text/javascript">
	function addTask()
	{
		<?php echo CHtml::ajax(array(
				'url'=>array('task/create'),
				'data'=> "js:$(this).serialize()",
				'type'=>'post',
				'dataType'=>'json',
				'success'=>"function(data)
				{
					if (data.status == 'failure')
					{
						$('#dialogTask div.divForForm').html(data.div);
						$('#dialogTask div.divForForm form').submit(addTask);
					}
					else
					{
						$('#dialogTask div.divForForm').html(data.div);
						$('#task-grid').yiiGridView.update('task-grid')
						setTimeout(\"$('#dialogTask').dialog('close') \",1000);
					}

				} ",
				))?>;
		return false; 

	}

	function updateTask()
	{
		<?php echo CHtml::ajax(array(
				'url'=>'js:$(this).attr(\'action\')',
				'data'=> "js:$(this).serialize()",
				'type'=>'post',
				'dataType'=>'json',
				'success'=>"function(data)
				{
					if (data.status == 'failure')
					{
						$('#dialogTask div.divForForm').html(data.div);
						$('#dialogTask div.divForForm form').submit(updateTask);
					}
					else
					{
						$('#dialogTask div.divForForm').html(data.div);
						$('#task-grid').yiiGridView.update('task-grid');
						$('#dialogTask').dialog('close');
					}

				} ",
				))?>;
		return false; 

	}
	
	function updateModal(href) {
		
		<?php echo CHtml::ajax(array(
				'url'=>'js:href',
				'data'=> "js:$(this).serialize()",
				'type'=>'post',
				'dataType'=>'json',
				'success'=>"function(data)
				{
					if (data.status == 'failure')
					{
						$('#dialogTask div.divForForm').html(data.div);
						$('#dialogTask div.divForForm form').submit(updateTask);
					}
					else
					{
						$('#dialogTask div.divForForm').html(data.div);
						$('#task-grid').yiiGridView.update('task-grid');
						$('#dialogTask').dialog('close');
					}

				} ",
				))?>;
		return false; 
		
	}
	
	function toggleComplete(task) {
		$.ajax({
			'url':'<?php echo $this->createUrl('api/task'); ?>'+task,
			'type':'PUT',
			'data': {
				
			}
		});
	}
	</script>