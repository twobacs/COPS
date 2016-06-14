<?php

if(isset($_GET['idArt'])){
	include('../autoload.php');
	$art=new Articles($pdo);
	print_r($art->modifActifArticlesById($_GET['idArt'],'N'));
}

?>