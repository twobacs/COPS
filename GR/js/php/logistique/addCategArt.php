<?php

if(isset($_GET['newCateg'])){
	include ('../autoload.php');
	$art=new Articles($pdo);
	$art->addCateg($_GET['newCateg']);
}

?>