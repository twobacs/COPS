<?php

if(isset($_GET['basket'])){
	include ('../autoload.php');
	$cart = new Cart($pdo);
	$data=$cart->getInfosByIdCart($_GET['basket']);
	while($row=$data->fetch()){
		$com=$row['avisLog'];
		$dateCom=$row['date_avisLog'];
	}
	$html='<br /><textarea id="comBasket'.$_GET['basket'].'" row="4" cols="50" style="color:black;" onfocusout="updateComment(\''.$_GET['basket'].'\');"';
	if ($com==''){
		$html.='placeholder="Entrez un commentaire ici">';
	}
	else{
		$html.='>'.$com;
	}
	$html.='</textarea>';
	echo $html;
}

?>