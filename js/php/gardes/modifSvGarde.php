<?php

if(isset($_GET['idSv'])){
	include ('../connect.php');
	$idSv=$_GET['idSv'];
	$action=$_GET['action'];
	$sql='SELECT denomination_svGarde FROM z_sv_garde WHERE id_svGarde=:idSv';
	$req=$pdo->prepare($sql);
	$req->execute(array('idSv'=>$idSv));
	while ($row=$req->fetch()){
		$denomSv=$row['denomination_svGarde'];
		}
	
	if ($action=='delete'){
		$sql='DELETE FROM z_sv_garde WHERE id_svGarde=:idSv';
		$req=$pdo->prepare($sql);
		$req->execute(array('idSv'=>$idSv));
		
		$html='<td class="red" colspan="2">'.$denomSv.'</td><td class="red">Service supprimé</td></tr>';
	}
	echo $html;
}

?>