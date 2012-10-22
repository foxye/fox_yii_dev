<?php
$this->breadcrumbs=array(
	'Comments'=>array('index'),
	$model->comment_ID,
);

$this->menu=array(
	array('label'=>'List Comment', 'url'=>array('index')),
	array('label'=>'Create Comment', 'url'=>array('create')),
	array('label'=>'Update Comment', 'url'=>array('update', 'id'=>$model->comment_ID)),
	array('label'=>'Delete Comment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->comment_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Comment', 'url'=>array('admin')),
);
?>

<h1>View Comment #<?php echo $model->comment_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'comment_ID',
		'comment_post_ID',
		'comment_author',
		'comment_author_email',
		'comment_author_url',
		'comment_author_IP',
		'comment_date',
		'comment_date_gmt',
		'comment_content',
		'comment_karma',
		'comment_approved',
		'comment_agent',
		'comment_type',
		'comment_parent',
		'user_id',
	),
)); ?>
