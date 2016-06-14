<?php

if(isset($_GET['idArt'])){
	include('../autoload.php');
	$art=new Articles($pdo);
	print_r($art->delArticlesById($_GET['idArt']));
	// echo $_GET['idArt'];
}

?>