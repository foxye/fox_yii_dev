<?php
$this->breadcrumbs=array(
	'Comments'=>array('index'),
	$model->comment_ID=>array('view','id'=>$model->comment_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Comment', 'url'=>array('index')),
	array('label'=>'Create Comment', 'url'=>array('create')),
	array('label'=>'View Comment', 'url'=>array('view', 'id'=>$model->comment_ID)),
	array('label'=>'Manage Comment', 'url'=>array('admin')),
);
?>

<h1>Update Comment <?php echo $model->comment_ID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>