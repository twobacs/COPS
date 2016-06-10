<?php
if (isset($_GET['idSv'])){
	include ('../connect.php');
	$sql='SELECT a.id_pers , a.id_user, b.nom AS nomIn, b.prenom AS prenomIn, c.nom AS nomEx, c.prenom AS prenomEx
	FROM z_garde_sv_pers a 
	LEFT JOIN users b ON b.id_user=a.id_user
	LEFT JOIN z_pers_garde c ON c.id_pers=a.id_pers
	WHERE id_svGarde=:idSv';
	$req=$pdo->prepare($sql);
	$req->execute(array('idSv'=>$_GET['idSv']));
	if($req->rowCount()==0){
		$html='<font color="red">Aucun enregistrement.</font><br />';
		}
	else{
		$html='';
		while ($row=$req->fetch()){
			$html.='<table>';
			$html.=(isset($row['nomIn'])) ? '<tr><td width="40%">'.$row['nomIn'].' '.$row['prenomIn'].'</td><td width="30%" class="blue"><input type="button" value="Modifier" onclick="modifPersGarde(\'I\',\''.$row['id_user'].'\',\'modif\',\''.$_GET['idSv'].'\');"></td><td width="30%" class="orange"><input type="button" value="Supprimer" onclick="modifPersGarde(\'I\',\''.$row['id_user'].'\',\'delete\',\''.$_GET['idSv'].'\');"></td></tr>': '';
			$html.=(isset($row['nomEx'])) ? '<tr><td width="40%">'.$row['nomEx'].' '.$row['prenomEx'].'</td><td width="30%" class="blue"><input type="button" value="Modifier" onclick="modifPersGarde(\'E\',\''.$row['id_pers'].'\',\'modif\',\''.$_GET['idSv'].'\');"></td><td width="30%" class="orange"><input type="button" value="Supprimer" onclick="modifPersGarde(\'E\',\''.$row['id_pers'].'\',\'delete\',\''.$_GET['idSv'].'\');"></td></tr>': '';
			$html.='</table>';
			}
		}
	$html.='<br /><input type="button" value="Ajouter un membre" onclick="addPersToSvGarde(\''.$_GET['idSv'].'\',\'1\',\'reset\');">';
	echo $html;	
	}
?>