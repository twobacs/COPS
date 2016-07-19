<?php

class VApplications extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }
	
public function nonCo()
		{
		$this->appli->ctContent='Vous n\'êtes pas connecté ou votre session a expiré.  Veuillez vous (re)connecter.';
		}
		
public function afficheHtml($html)
	{
	$this->appli->ctContent=$html;
	}
		
public function showApps($data)
	{
	// $html='<meta http-equiv="refresh" content="1">';
	
	$html='<nav>';
	for($i=0;$i<count($data);$i++)
		{
		$idApp=$data[$i]['idApp'];
		$idNivAcces=$data[$i]["idNivAcces"];
		$descNivAcces=$data[$i]["descNivAcces"];
		// $html.=$idApp.' '.$idNivAcces.' '.$descNivAcces.'<br />';
		switch ($idApp)
			{
			case 0:
				$this->appli->ctContent='Vous n\'êtes pas connecté ou votre session a expiré.  Veuillez vous (re)connecter.';
				break;
			case 1: //Gestion des droits
				if ($idNivAcces>29)
					{
					$html.='<a href="?component=applications&action=gestDroits">Droits</a>';
					//<img src="/templates/mytpl/images/gestion_droits.png" height="27%"></a><br />'';
					}
				break;
			case 2: //VACANCIER
				if (($idNivAcces>4) || ($idNivAcces==3))
					{
					$html.='<a href="?component=vacancier&action=mainMenu">Vacanciers</a>';
					// $html.='<img id="img-vacanciers" src="/templates/mytpl/images/vacanciers.png" width="20%"></a><br />';
					}
				break;
			case 3: //Gestion des utilisateurs
				if ($idNivAcces>29)
					{
					$html.='<a href="?component=users&action=mainMenu">Utilisateurs</a>';
					// $html.='<img id="img-users" src="/templates/mytpl/images/users.png" width="20%"></a><br />';
					}
				break;
				
			case 4: //Gestion des rues
				if ($idNivAcces>29)
					{
					$html.='<a href="?component=rues&action=mainMenu">Rues</a>';
					// $html.='<img id="img-rues" src="/templates/mytpl/images/rues.png" width="20%"></a><br />';
					}
				break;	

			case 5: //Gestion des agents de quartier
				if ($idNivAcces>29)
					{
					$html.='<a href="?component=quartier&action=mainMenu">Quartiers</a>';
					// $html.='<img id="img-quartiers" src="/templates/mytpl/images/quartiers.png" width="20%"></a><br />';
					}
				break;	

			case 6: //Gestion des patrouilles
				if ($idNivAcces>29)
					{
					$html.='<a href="?component=patrouilles&action=mainMenu">Patrouilles</a>';
					// $html.='<img id="img-patrouilles" src="/templates/mytpl/images/patrouilles.png" width="20%"></a><br />';
					}
				break;	
					
			case 7: //Gestion fiche COPS
				if ($idNivAcces>3)
					{
					$html.='<a href="?component=cops&action=mainMenu">Fiches COPS</a>';
					$html.='<a href="?component=documentation&action=gestDoc">Gestion documentation</a>';
					// $html.='<img id="img-fcops" src="/templates/mytpl/images/fcops.png" width="20%"></a><br />';
					}
				break;
				
			case 8: //Attribution de missions
				if ($idNivAcces>29)
					{
					$html.='<a href="?component=missions&action=mainMenu">Missions</a>';
					// $html.='<img id="img-missions" src="/templates/mytpl/images/bmissions.png" width="20%"></a><br />';
					}
				break;
				
			case 9: //Gestion des BS
				if ($idNivAcces>19)
					{
					$html.='<a href="?component=bs&action=mainMenu">Gestion BS</a>';
					// $html.='<img id="img-bs" src="/templates/mytpl/images/bbs.png" width="20%"></a><br />';
					}
				break;
				
			case 10: //Gestion des Gardes
				if ($idNivAcces>19)
					{
					$html.='<a href="?component=garde&action=mainMenu">Gardes</a>';
					// $html.='<img id="img-bs" src="/templates/mytpl/images/bbs.png" width="20%"></a><br />';
					}
				break;
			case 11: //Chiffres		
					if ($idNivAcces>19)
					{
					$html.='<a href="?component=chiffres&action=mainMenu">Chiffres</a>';
					// $html.='<img id="img-bs" src="/templates/mytpl/images/bbs.png" width="20%"></a><br />';
					}
				break;
			}
		}
	$html.='</nav>';	
	// $this->afficheHtml($html);
	$this->appli->menuNav=$html;
	}
	
public function showMenuGestDroits($data)
	{
	$html='<h1>Gestion des droits</h1>';
	$html.='<h2>Choisissez l\'application pour laquelle vous voulez gérer les droits d\'accès</h2>';
	for($i=0;$i<count($data);$i++)
		{
		$idApp=$data[$i]['idApp'];
		switch ($idApp)
			{
			case 1: //Gestion des droits
				$html.='<a href=?component=applications&action=gestDroits&appli='.$idApp.'>Gestion des droits</a><br />';
				break;			
			
			case 2: //VACANCIER
				$html.='<a href=?component=applications&action=gestDroits&appli='.$idApp.'>Vacancier</a><br />';
				break;
				
			case 3: //Gestion des utilisateurs
				$html.='<a href=?component=applications&action=gestDroits&appli='.$idApp.'>Gestion des utilisateurs</a><br />';
				break;		

			case 4: //Gestion des rues
				$html.='<a href=?component=applications&action=gestDroits&appli='.$idApp.'>Gestion des rues</a><br />';
				break;
				
			case 5: //Gestion des agents de quartier
				$html.='<a href=?component=applications&action=gestDroits&appli='.$idApp.'>Gestion des agents de quartier</a><br />';
				break;	

			case 6: //Gestion des patrouilles
				$html.='<a href=?component=applications&action=gestDroits&appli='.$idApp.'>Gestion des patrouilles</a><br />';
				break;
				
			case 7: //Gestion des patrouilles
				$html.='<a href=?component=applications&action=gestDroits&appli='.$idApp.'>Fiche COPS</a><br />';
				break;
			
			case 8: //Attribution de missions
				$html.='<a href=?component=applications&action=gestDroits&appli='.$idApp.'>Attribution de missions</a><br />';
				break;

			case 9: //Attribution de missions
				$html.='<a href=?component=applications&action=gestDroits&appli='.$idApp.'>Gestion des BS</a><br />';
				break;		

			case 10: //Gestion des gardes
					$html.='<a href=?component=applications&action=gestDroits&appli='.$idApp.'>Gestion des gardes</a><br />';
					break;
			
			case 11: //Chiffres
					$html.='<a href=?component=applications&action=gestDroits&appli='.$idApp.'>Chiffres</a><br />';
					break;
			}
		}
	$this->appli->ctContent=$html;
	}
	
public function MenuAddUser($users,$niveau,$appli)
	{
	$html='<h2>Ajout d\'un utilisateur</h2>';
	$html.='<form name=MenuAddUser method=POST action=?component=applications&action=recordNewUser><table><tr><th>Utilisateur à ajouter</th><th>Droits sur l\'application</th></tr>';
	$html.='<tr><td><select name="idUser">';
	while ($row=$users->fetch())
		{
		$html.='<option value="'.$row['id_user'].'">'.$row['nom'].' '.$row['prenom'].'</option>';  //VALUE A VERIFIER
		}
	$html.='</select></td>';
	$html.='<td><select name="idNivAcces">';
	while ($row=$niveau->fetch())
		{
		$html.='<option value="'.$row['id_nivAcces'].'">'.$row['denom_nivAcces'].'</option>';
		}	
	$html.='</select></td></tr>';
	$html.='<tr><td colspan="2"><input type=hidden name="idApp" value="'.$appli.'"><input type=submit value="Enregistrer"></td></tr>';
	$html.='</table></form>';
	$this->appli->ctContent=$html;
	return $html;
	}
	
public function insertNewUserOk($appli,$users,$niveau)
	{
	$html=$this->MenuAddUser($users,$niveau,$appli);
	$html.='<br><font color="green">Enregistrement effectué.</font><br />';
	$html.='<a href=?component=applications&action=gestDroits&appli='.$appli.'>Retour</a> gestion des droits.';
	$this->appli->ctContent=$html;
	}
}
?>
