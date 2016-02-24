<?php
	require_once('../load.php');
	
	$model = new SourceModel();
	$model->name = 'NAMEEE';
	$model->description = 'niii';
	$model->save();
	
	echo $model->id;
	
	/*
	$model = new SourceModel(array('id' => 1));
	$model->load();
	
	echo print_r($model->version, true);
	
	$model->description = 'nope';
	$model->save();
	//*/