
<?php

if(isset($_GET['denNewFourn'])){
	include ('../autoload.php');
	$art=new Fournisseurs($pdo);
	$art->addNewFourn($_GET);
}

?>
