<?php

if(isset($_GET['panier'])){
	include ('../autoload.php');
	$panier = NEW Panier($pdo);
	$rep=$panier->updateLignePanier_Date($_GET['panier'],$_GET['date']);
	echo $rep;
	
}

?>