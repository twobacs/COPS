<?php

if(isset($_GET['id'])){
	include ('../autoload.php');
	$art=new Articles($pdo);
	$art->desactFourn($_GET['id']);
}
?>