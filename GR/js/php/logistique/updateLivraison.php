<?php

if(isset($_GET['type'])){
	include('../autoload.php');
	if ($_GET['type']=='arme'){
		switch($_GET['sub']){
			case 'livraison':
				$sql='UPDATE armes SET dateLivraison=:date WHERE num_arme=:id';
				break;
			case 'ctrlArmu':
				$sql='UPDATE armes SET validite_arme=:date WHERE num_arme=:id';
				break;
			}		
		$req=$pdo->prepare($sql);
		$req->execute(array('date'=>$_GET['date'], 'id'=>$_GET['id']));
	}
	if ($_GET['type']=='brassard'){
		$sql='UPDATE brassards SET dateLivraison=:date WHERE num_brassard=:id';
		$req=$pdo->prepare($sql);
		$req->execute(array('date'=>$_GET['date'], 'id'=>$_GET['id']));
	}
	if ($_GET['type']=='radio'){
		$sql='UPDATE radios SET dateLivraison=:date WHERE num_TEI=:id';
		$req=$pdo->prepare($sql);
		$req->execute(array('date'=>$_GET['date'], 'id'=>$_GET['id']));
	}
	if ($_GET['type']=='baton'){
		$sql='UPDATE batons SET dateLivraison=:date WHERE num_baton=:id';
		$req=$pdo->prepare($sql);
		$req->execute(array('date'=>$_GET['date'], 'id'=>$_GET['id']));
	}
	if($_GET['type']=='ETT_V'){
		$sql='UPDATE z_ETT SET date_validite=:date WHERE id_ETT=:id';
		$req=$pdo->prepare($sql);
		$req->execute(array('date'=>$_GET['date'], 'id'=>$_GET['id']));
	}
	if($_GET['type']=='ETT_L'){
		$sql='UPDATE z_ETT SET dateLivraison=:date WHERE id_ETT=:id';
		$req=$pdo->prepare($sql);
		$req->execute(array('date'=>$_GET['date'], 'id'=>$_GET['id']));
	}
	
	echo $sql;
}


?>