<?php

if(isset($_GET['newMesure'])){
	include ('../autoload.php');
	$art=new Articles($pdo);
	$art->addUMesure($_GET['newMesure']);
}

?>