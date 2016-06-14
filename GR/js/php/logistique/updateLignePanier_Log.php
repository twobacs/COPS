<?php

if(isset($_GET['panier'])){
	include ('../autoload.php');
	$panier = NEW Panier($pdo);
	$rep=$panier->updateLignePanier_Log($_GET['panier'],$_GET['log']);
	echo $rep;
	
}

?>