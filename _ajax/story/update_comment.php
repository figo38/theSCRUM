<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';

	$id = $_POST['id'];
	$comment = trim(str_replace('\"', '"', urldecode($_POST['comment'])));
	
	$S = new Story($id);
	$S->updateComment($comment);
?>