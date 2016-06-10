<?php

if(isset($_GET['idSv'])){
	include ('../connect.php');
	$idSv=$_GET['idSv'];
	$typePers=$_GET['typePers'];
	$nom=$_GET['nom'];
	$prenom=$_GET['prenom'];
	$tel=$_GET['tel'];
	$gsm=$_GET['gsm'];
	$fax=$_GET['fax'];
	$mail=$_GET['mail'];
	$rue=$_GET['rue'];
	$num=$_GET['num'];
	$CP=$_GET['CP'];
	$ville=$_GET['ville'];
	$idPersInt=$_GET['idPersInt'];
	$idPersE=$_GET['idPersE'];
	

	
	if($typePers=='I'){
		$sql='UPDATE users SET nom=:nom, prenom=:prenom, mail=:mail, fixe=:fixe, gsm=:gsm, fax=:fax, CP=:CP, ville=:ville, rue=:rue, numero=:numero WHERE id_user=:idUser';
		$req=$pdo->prepare($sql);
		$req->execute(array(
			'nom'=>$nom,
			'prenom'=>$prenom,
			'mail'=>$mail,
			'fixe'=>$tel,
			'gsm'=>$gsm,
			'fax'=>$fax,
			'CP'=>$CP,
			'ville'=>$ville,
			'rue'=>$rue,
			'numero'=>$num,
			'idUser'=>$idPersInt
			));
			$sql='INSERT INTO z_garde_sv_pers (id_svGarde, id_type_pers_garde, id_user) VALUES (:idSv, :idTypePers, :id_user)';
			$req=$pdo->prepare($sql);
			$req->execute(array('idSv'=>$idSv, 'idTypePers'=>$typePers, 'id_user'=>$idPersInt));
			$html='Données enregistrées';
		}
	
	else if($typePers=='E'){
		$sql='SELECT id_pers FROM z_pers_garde WHERE nom=:nom AND prenom=:prenom';
		$req=$pdo->prepare($sql);
		$req->execute(array('nom'=>$nom, 'prenom'=>$prenom));
		$count=$req->rowCount();
		// echo $count;
		if($count==0){
			$sql='INSERT INTO z_pers_garde (nom, prenom, fixe, gsm, CP, ville, rue, numero, fax, mail) VALUES (:nom, :prenom, :fixe, :gsm, :CP, :ville, :rue, :numero, :fax, :mail)';
			$req=$pdo->prepare($sql);
			$req->execute(array(
			'nom' => $nom,
			'prenom' => $prenom,
			'fixe' => $tel,
			'gsm' => $gsm,
			'CP' => $CP,
			'ville' => $ville,
			'rue' => $rue,
			'numero' => $num,
			'fax' => $fax,
			'mail' => $mail
			));
			
			$sql='SELECT id_pers FROM z_pers_garde WHERE nom=:nom AND prenom=:prenom';
			$req=$pdo->prepare($sql);
			$req->execute(array('nom'=>$nom, 'prenom'=>$prenom));
			while($row=$req->fetch()){
				$idPers=$row['id_pers'];
				}
			}
		else {
			$sql='UPDATE z_pers_garde SET nom=:nom, prenom=:prenom, fixe=:fixe, gsm=:gsm, CP=:CP, ville=:ville, rue=:rue, numero=:numero, fax=:fax, mail=:mail WHERE id_pers=:idPers';
			$req=$pdo->prepare($sql);
			$req->execute(array(
			'nom' => $nom,
			'prenom' => $prenom,
			'fixe' => $tel,
			'gsm' => $gsm,
			'CP' => $CP,
			'ville' => $ville,
			'rue' => $rue,
			'numero' => $num,
			'fax' => $fax,
			'mail' => $mail,			
			'idPers' => $idPersE
			));

			$idPers=$idPersE;
			}
		$sql='INSERT INTO z_garde_sv_pers (id_svGarde, id_type_pers_garde, id_pers) VALUES (:idSv, :idTypePers, :id_pers)';
		$req=$pdo->prepare($sql);
		$req->execute(array('idSv'=>$idSv, 'idTypePers'=>$typePers, 'id_pers'=>$idPers));
		$html='Données enregistrées';	
		}
	
	echo $html;
	}

?>