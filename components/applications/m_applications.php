<?php

class MApplications extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}
	
public function selectAppByUserId($id)  //RETOURNE LES APPLIS POUR LESQUELLES L'UTILISATEUR A ACCES
	{
	if (isset($_COOKIE["iduser"])){
		$user=$_COOKIE["iduser"];		
	}
	else{
		$user=$id;
	}
	if ((isset($_COOKIE["iduser"]))OR ($user!=0))	
		{
		$sql='
		SELECT * FROM z_user_app a 
		LEFT JOIN z_niv_acces b ON a.id_nivAcces=b.id_nivAcces 
		LEFT JOIN z_applis c ON a.id_app=c.id_app 
		WHERE a.id_user="'.$user.'"';
		// print_r ($_COOKIE);
		$rep=$this->appli->dbPdo->query($sql);
		if (isset($rep))
			{
			$i=0;
			while($row=$rep->fetch())
				{
				$appli[$i]["idApp"]=$row['id_app'];
				$appli[$i]["idNivAcces"]=$row['id_nivAcces'];
				$appli[$i]["descNivAcces"]=$row['desc_nivAcces'];
				$i++;
				}
			}
		if (isset($appli))
			{
			return $appli;
			} //A TESTER
		else return 0;
		}
	else return 0;
	}

public function selectAppliGestDroits()  //RETOURNE LES APPLIS POUR LESQUELLES L'UTILISATEUR A DES DROITS DE GESTION UTILISATEURS (niv 30 et +)
	{
	$sql='SELECT * FROM z_user_app a 
	LEFT JOIN z_applis b ON a.id_app=b.id_app 
	WHERE a.id_user="'.$_COOKIE['iduser'].'" AND a.id_nivAcces>29';
	// echo $sql;
	$rep=$this->appli->dbPdo->query($sql);
	$i=0;
	while($row=$rep->fetch())
		{
		$appli[$i]["idApp"]=$row['id_app'];
		$i++;
		}
	if (isset($appli))
		{
		return $appli;
		}
	else return 0;	
	}
	
public function GestDroits($idApp)
	{
	$html='<h2>Gestion des droits sur le module ';
	$sql='SELECT description_app FROM z_applis WHERE id_app='.$idApp;
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$html.=$row['description_app'];
		}
	$html.='</h2>';
	$html.='<table><tr><th>Nom</th><th>Prénom</th><th>Niveau actuel</th><th>Nouveau niveau</th></tr>';  //TABLEAU D'ADMINISTRATION DES DROITS SUR BASE DE L'ID DE L'APPLICATION
	$sql='SELECT * FROM z_user_app a 
	LEFT JOIN z_niv_acces b ON a.id_nivAcces=b.id_nivAcces
	LEFT JOIN users c ON a.id_user=c.id_user 
	LEFT JOIN z_applis d ON a.id_app=d.id_app 
	WHERE a.id_app="'.$idApp.'" ORDER BY c.nom, c.prenom ASC';
	$rep=$this->appli->dbPdo->query($sql);
	$i=0;
	while ($row=$rep->fetch())
		{
		$html.='<tr><td>'.$row['nom'].'</td><td>'.$row['prenom'].'</td><td>'.$row['denom_nivAcces'].'</td>';
		$html.='<td>';
		$html.='<select name=NivAcces_'.$row['id_user'].' id=NivAcces_'.$row['id_user'].'><option value=""></option>'; //A VOIR
			$sqla='SELECT * FROM z_niv_acces WHERE id_nivAcces<50';
			$repa=$this->dbPdo->query($sqla);
			while ($rowa=$repa->fetch())
				{
				$html.='<option value="'.$rowa['id_nivAcces'].'">'.$rowa['denom_nivAcces'].'</option>';
				}
		$html.='</select>';		
		$html.='<img src="/templates/mytpl/images/ok.png" height=50% title="Valider changement" onclick="changeDroits('.$row['id_user'].','.$idApp.');">';
		$html.='</td>';
		$html.='</tr>';
		}
	$html.='</table><br />';
	$html.='<a href=?component=applications&action=MenuAddUser&appli='.$idApp.' title="Ajouter un utilisateur"><img src="/templates/mytpl/images/AddUser.ico" height=10%></a>';	
	return $html;
	}
	
public function inputTypeSelectNivAcces()
	{
	$html='<select name=NivAcces id=NivAcces><option value=""></option>';
	$sql='SELECT * FROM z_niv_acces WHERE id_nivAcces<50';
	$rep=$this->dbPdo->query($sql);
	return $rep;
	}
	
public function MenuAddUser($idApp)
	{
	$sql='SELECT a.id_user, a.nom, a.prenom FROM users a 
	LEFT JOIN z_user_app b 
	ON b.id_user = a.id_user 
	WHERE a.id_user NOT IN (SELECT id_user FROM z_user_app WHERE id_app="'.$idApp.'") 
	GROUP BY a.id_user
	ORDER BY a.nom, a.prenom ASC';
	$rep=$this->appli->dbPdo->query($sql);
	return $rep;
	}
	
public function recordNewUser()
	{
	$user=$_POST['idUser'];
	$niveau=$_POST['idNivAcces'];
	$appli=$_POST['idApp'];
	$sql='INSERT INTO z_user_app (id_app,id_user,id_nivAcces) VALUES ("'.$appli.'","'.$user.'","'.$niveau.'")';
	$this->appli->dbPdo->exec($sql);
	return $appli;
	}
	
}
?>
