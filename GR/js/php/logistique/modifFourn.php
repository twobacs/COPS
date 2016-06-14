<?php

if(isset($_GET['id'])){
	var_dump($_GET['id']);
	include ('../autoload.php');
	$art=new Articles($pdo);
	$art->modifFourn($_GET);
}
?>