<?php

class VUsers extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }

public function nonco()
	{
	$this->appli->ctContent.='Vous ne pouvez accéder à cette partie du site, <a href=\"?component=users&action=logForm\">connectez-vous.</a>';
	}

	public function nonDroit()
	{
	$this->appli->ctContent.='Votre profil ne vous permet pas d\'accéder à cette partie du site.';
	}

public function afficheHtml($data)
	{
	$this->appli->ctContent=$data;
	$this->appli->jScript= '<script type="text/javascript" src="./js/users.js"></script>';
	}

private function datefr($date,$h=0)
	{
    $split = explode(" ",$date);
    $jour = $split[0];
	$heure = ($h==0) ? '' : $split[1];

	$split2 = explode("-",$jour);
	$annee = $split2[0];
    $mois = $split2[1];
    $jour = $split2[2];

	$return = ($h==0) ? $jour."-".$mois."-".$annee : $jour."-".$mois."-".$annee." à ".$heure;

	return $return;
	}

public function affichelogform()
	{
		$html='';
		if((isset($_REQUEST['action'])) AND ($_REQUEST['action']=='deco')) {
			$html.='<span id="msgdeco"><p>Déconnexion ok. <br />Par mesure de sécurité, veuillez à présent fermer votre navigateur.</p></span>';
		}
		else
			{
			$html.='<h2>Connexion</h2>';
			$html.='<p><form id="formIdentification" name="identification" method="POST" action="index.php?component=users&action=login">';
			$html.='<table>';
			
			$html.='<tr><th>Plateforme :</th><td><select name="mode" id="mode" onchange="tablette();" autofocus><option value="2">COPS</option><option value="1">COPS version bureau</option></select></td></tr>';
			$html.='<tr id="trLog"><th style="min-width:250px;">Identifiant :</th><td><input class="txtedit" id="login" name="login" id="login" maxlength="50" type="text"></td></tr>';
			$html.='<tr id="trPass"><th>Mot de passe :</th><td><input class="txtedit" id="password" name="password" maxlength="50" type="password"></td></tr>';
			/* ******************************** */
			
			$html.='<tr id="trCol">';
				/* 21/09/2015 */
				$html.='<th>Nombre de véhicule(s) à engager :</th><td><select name="nbVV" id="nbVV">';
				for ($i=1;$i<11;$i++)
					{
					$html.='<option value='.$i.'>'.$i.'</option>';
					}
				$html.='</select></td>';
				/* ----- */
			$html.='</tr>';
			$html.='<tr id="trVV">';
				/* 21/09/2015 */
					$html.='<th>Collaborateur(s) à engager : </th><td><select name="nbCol" id="nbCol" onchange="collabos();">';
					for ($i=0;$i<11;$i++)
					{
					$html.='<option value="'.$i.'">'.$i.'</option>';
					}
					$html.='</select></td>';		
				/* ----- */
			$html.='</tr>';
			$html.='<tr id="bSubmit"><td colspan="2" class="noborder"><input type="submit" value="Entrer"></td></tr>';
			$html.='</table></form></p>';
			$html.='<p><div id="collabos"></div></p>';
			/* ******************************** */
			$html.='<h2>Note &agrave; l\'attention des GDP</h2>';
			$html.='<center>Veuillez choisir l\'option "COPS version bureau" dans le menu ci-dessus.  Merci.</center>';
			// $html.='<h2>Indisponibilit&eacute; de la plateforme</h2>';
			// $html.='<center>Un red&eacute;marrage des serveurs est pr&eacute;vu ce 15 Juin 2016 à 09 heures afin d\'effectuer des mises à jour.  La plateforme sera indisponible jusqu\'&agrave; 10 heures.  Veuillez excuser la g&ecirc;ne occasionn&eacute;e.</center>';
			$this->appli->deco='<a href="?component=users&action=logform"><img src="/templates/mytpl/images/connexion.png" height="40"></a>';
		}
		$this->afficheHtml($html);
	}

public function afficheResultLogin($data,$verif=0,$users=0,$cars=0,$uzi=0,$indicatifs=0)
	{
	$this->appli->bienvenue='<h4>Bienvenue '.$data['prenom'].'.</h4>';
	switch ($data['login'])
		{
		case 1: //Login OK
			switch ($_SESSION["mode"])
				{
				case 1: //Mode PC
					$this->menuPrincipal($data['idUser']);
					break;

				case 2://mode tablette
					($verif==0) ? $this->menuBS($users,$cars,$uzi,$indicatifs) : $this->menuPrincipal($data['idUser']);
					break;
				}
			break;
		case 2: //Première connexion
			$this->firstConnexion();
			break;
		case 3: //Erreur
			$this->erreurConnexion();
			break;
		case 4: //Compte bloqué
			$this->compteBloque();
			break;
		}
	}

public function menuPrincipal($info='')
	{
	include('/var/www/components/applications/c_applications.php');
	$app=new CApplications($this->appli);
	$app->showApps($info);
	if ($info=='FromModifPwd')
		{
		$enTete='Votre mot de passe a été modifié<br />';
		}
	else
		{
		$enTete='';
		}
	$this->appli->botCentre=$enTete;
	$this->appli->deco='<a href="?component=users&action=logoff"><img src="/templates/mytpl/images/deconnexion.png" height="40"></a>';
	}

public function menuBS($users,$cars,$uzi,$indicatifs)
	{
	$nbCol=$_POST['nbCol'];
	$nbVV=$_POST['nbVV'];
	$i=0;
	$optionsCol='';
	$optionsVV='';

	while ($row=$users->fetch())
		{
		$optionsCol.='<option value="'.$row['id_user'].'">'.$row['nom'].' '.$row['prenom'].'</option>';
		}

	while ($row=$cars->fetch())
		{
		$optionsVV.='<option value="'.$row['immatriculation'].'">'.$row['immatriculation'].'</option>';
		}

	$html='<span class="InfosBS"><h2>Informations bulletin de service</h2>';
	$html.='<p><form method="post" action="?mode=m&component=users&action=MenuTablette">';
	//COLLABORATEUR(S) ENGAGÉ(S) --> SECOND NIVEAU
	if($nbCol>0)
	{
	$html.= ($nbCol==1) ? '<h4>Autre personne engagée : </h4>' : '<h4>Autres personnes engagées : </h4>';	
	$html.='<table>';

	while ($i<$nbCol)
		{
		$html.='<tr><th width=50%>Collaborateur '.($i+1).' :</th><td><select name=colaps'.($i+1).'><option></option>';
		$html.=$optionsCol;
		$html.='</td></tr>';
		$i++;
		}
	$html.='</table>';
	}
	//*******************************//


	//VÉHICULE UTILISÉ + LES REMARQUES --> SECOND NIVEAU
	$i=0;
	if ($nbVV>0)
	{
		$html.='<table>';
		$html.= ($nbVV==1) ? '<h4>Véhicule engagé : </h4>' : '<h4>Véhicules engagés : </h4>';
		while ($i<$nbVV)
			{
			$html.='<tr><th width=50%>Véhicule '.($i+1).' :</th><td><select name=VV'.($i+1).'><option></option>';
			$html.=$optionsVV;
			$html.='</td></tr>';
			$i++;
			}
		$html.='</table>';
	}
	//*******************************//

	$html.='<h4>Matériel</h4>';

	//ARME COLLECTIVE OUI / NON (PICKLIST SUR BDD) --> SECOND NIVEAU
	$html.='<table>';
	$html.='<tr><th width=50%>Arme collective emportée ?</th><td>Oui<input type=radio name="armeC" value="Oui" onclick="selectUzi();"> Non<input type=radio name="armeC" value="Non" onclick="cleanUzi();" checked></td><td id=selectUzi></td></tr>';
	// $html.='</table>';
	//*******************************//

	//Appareil photo
	// $html.='<table>';
	$html.='<tr><th width=50%>Appareil photo emporté ?</th><td>Oui<input type=radio name="AP" value="Oui" onclick="selectAP();"> Non<input type=radio name="AP" value="Non" onclick="cleanAP();" checked></td><td id=selectAP></td></tr>';
	// $html.='</table>';
	//*******************************//

	//ETT OUI / NON + NUMÉRO (PICKLIST SUR BDD) --> SECOND NIVEAU
	// $html.='<table>';
	$html.='<tr><th width=50%>ETT emporté ?</th><td>Oui<input type=radio name="ETT" value="Oui" onclick="selectETT();"> Non<input type=radio name="ETT" value="Non" onclick="cleanETT();" checked></td><td id=selectETT></td></tr>';
	// $html.='</table>';
	//*******************************//

	//GSM OUI / NON + NUMÉRO (PICKLIST SUR BDD) --> SECOND NIVEAU
	// $html.='<table>';
	$html.='<tr><th width=50%>GSM emporté ?</th><td>Oui<input type=radio name="GSM" value="Oui" onclick="selectGSM();"> Non<input type=radio name="GSM" value="Non" onclick="cleanGSM();" checked></td><td id=selectGSM></td></tr>';
	$html.='</table>';
	//*******************************//

	$html.='<h4>Informations d\'identification</h4>';

	//DÉNOMINATION ET INDICATIF VIA $indicatifs
	$html.='<table>';
	$html.='<tr><th width=50%>Indicatif : </th><td><select id=indicatif name=indicatif onchange=showDenom();><option></option>';
	while ($row=$indicatifs->fetch())
		{
		$html.='<option value="'.$row['id_patrouille'].'">'.$row['indicatif'].'</option>';
		}
	$html.='</select></td></tr>';
	$html.='<tr><th width=50%>Dénomination : </th><td id="denom"></td></tr>';
	// $html.='</table>';
	//*******************************//


	// $html.='<table>';
	$html.='<tr><td colspan="2" id="lasuite"></td></tr>';
	$html.='<input type=hidden name=nbCol value='.$_POST['nbCol'].'>';
	$html.='<input type=hidden name=nbVV value='.$_POST['nbVV'].'>';
	$html.='</table>';
	$html.='</form></p></span>';
	$this->afficheHtml($html);
	$this->appli->left='';
	$this->appli->deco='';
	}

public function firstConnexion()
	{
	$html='Première connexion<br />';
	$html.='Votre mot de passe est celui par défaut, veuillez le modifier. <br />';
	$html.='<form method=POST action=?component=users&action=modifPassword><table>';
	$html.='<tr><th>Nouveau mot de passe :</th><td><input type=password name=pwd1></td></tr>';
	$html.='<tr><th>Nouveau mot de passe (confirmation) :</th><td><input type=password name=pwd2></td></tr>';
	$html.='<tr><th colspan="2"><input type=submit value="Enregistrer"></th></tr>';
	$this->afficheHtml($html);
	$this->appli->menuBase='';
	}

public function erreurConnexion()
	{
	$html='<font color="red" size=5px>Erreur de login ou de mot de passe.</font><br />';
	$html.='<a href=?component=users&action=logForm>Retour</a>';
	$this->afficheHtml($html);
	}

public function compteBloque()
	{
	$html='<font color="red" size=5px>Ce compte a été bloqué par mesure de sécurité (trop d\'erreurs)</font><br />';
	$html.='Veuillez contacter votre administrateur';
	$this->afficheHtml($html);
	}

public function mainMenu($level)
	{
	if ($level<30)
		{
		$this->nonDroit();
		}
	else
		{
		$html='<h2>Gestion des utilisateurs</h2>';
		$html.='<h3>Choisissez une action à effectuer</h3>';
		$html.='<ul>';
		$html.='<li><a href=?component=users&action=addUser>Ajouter</a> un utilisateur</li>';
		// $html.='<li><a href=?component=users&action=modifUser?Modifier</a> un utilisateur</li>';
		$html.='<li><a href="#" onclick=moreOptions();>Modifier</a> un utilisateur</li>';
		$html.='<div id=rep></div>';
		$html.='</ul>';
		$this->afficheHtml($html);
		}
	}

public function formAddUser($sexes,$grades,$services)
	{
	$html='<h2>Ajout d\'un utilisateur</h2>';
	$html.='<form name=formAddUser><table>';
	$html.='<tr><th>Nom :</th><td><input type=text id=nom name=nom autofocus></td><th>Prénom :</th><td><input type=text id=prenom name=prenom></td></tr>';
	$html.='<tr><th>Login :</th><td><input type=text id=login name=login></td><th>Matricule :</th><td><input type=text id=matricule name=matricule></td></tr>';
	$html.='<tr><th>Sexe :</th><td><select name=sexe id=sexe>';
	while ($row=$sexes->fetch())
		{
		$html.='<option name="grade" value="'.$row['denomination'].'">'.$row['denomination'].'</option>';
		}
	$html.='<th>Grade :</th><td><select name=grade id=grade>';
	while ($row=$grades->fetch())
		{
		$html.='<option name="grade" value="'.$row['denomination_grade'].'">'.$row['denomination_grade'].'</option>';
		}
	$html.='</select></td></tr>';
	$html.='<tr><th>Adresse mail :</th><td><input type=text id=mail name=mail></td><th>Service :</th><td><select id=service name=service>';
	while ($row=$services->fetch())
		{
		$html.='<option name="service" value="'.$row['id_service'].'">'.$row['denomination_service'].'</option>';
		}
	$html.='</td></tr>';
	$html.='<tr><td colspan="4" class=noborder><input type=button value="Enregistrer" onclick=RecNewUser();></td></tr>';
	$html.='</table></form>';
	$html.='<div id=rep></div>';
	$this->afficheHtml($html);
	}


public function modifOneUser()
	{
	$html='<h2>Modifier un utilisateur</h2>';
	$html.='Veuillez introduire le nom de l\'utilisateur à modifier : <input type=text id=nomUser onkeyup=searchUser(this); autofocus><br />';
	$html.='<div id=rep></div>';
	$this->afficheHtml($html);
	}

public function afficheInfosUser($user,$grades,$services)
	{
	$html='<h2>Modification d\'un utilisateur</h2>';
	$html.='<table>';
	while ($row=$user->fetch())
		{
		$html.='<tr><th>Nom :</th><td><input type=text name=nom id=nom value="'.$row['nom'].'"></td><th>Prénom :</th><td><input type=text name=prenom id=prenom value="'.$row['prenom'].'"></tr>';
		$html.='<tr><th>Login :</th><td><input type=login name=nom id=login value='.$row['login'].'></td><th>Matricule :</th><td>'.$row['matricule'].'</tr>';
		$html.='<tr><th>Grade :</th><td><select name=grade id=grade>';
		while ($rowa=$grades->fetch())
			{
			$html.='<option value="'.$rowa['denomination_grade'].'"';
			if ($rowa['denomination_grade']==$row['denomination_grade'])
				{
				$html.=' selected';
				}
			$html.='>'.$rowa['denomination_grade'].'</option>';
			}
		$html.='</td><th>Mail :</th><td><input type=text name=mail id=mail value='.$row['mail'].'></td></tr>';
		$html.='<tr><th>Service :</th><td><select name=service id=service>';
		while ($rowa=$services->fetch())
			{
			$html.='<option value="'.$rowa['id_service'].'"';
			if ($rowa['id_service']==$row['id_service'])
				{
				$html.=' selected';
				}
			$html.='>'.$rowa['denomination_service'].'</option>';
			}
		$html.='<tr><td colspan="2" class=noborder><input type=button value="Enregistrer les modifications" onclick=ModifUser(\''.$row['id_user'].'\');></td><td colspan="2" class=noborder><input type=button value="Reset mot de passe" onclick=resetMdp(\''.$row['id_user'].'\');></td></tr>';
		}

	$html.='</table>';
	$html.='<div id=rep></div>';
	$this->afficheHtml($html);
	}

public function showAllUsers($data)
	{
	$html='<h2>Modification d\'un utilisateur</h2>';
	$html.='<h3>Veuillez sélectionner l\'utilisateur à modifier</h3>';

	while ($row=$data->fetch())
		{
		$html.=$row['nom'].' '.$row['prenom'].' ('.$row['matricule'].') - <a href=?component=users&action=modifUser&type=OneUser&id='.$row['id_user'].'>Modifier</a><br />';
		}

	$this->afficheHtml($html);
	}

public function showMissionsByIdPat($data)
 	{
	$html='';
	if (sizeof($data)==0)
		{
		$html.='Aucune mission ne vous a été attribuée.';
		}
	else
		{
		$NbMissions=sizeof($data);
		$MCops=array();
		$ACops=array();
		$MVacs='';
		$MOther='';
		$media=MEDIA;

		//AFFICHAGE DES MISSIONS DE TYPE COPS
		for ($i=0;$i<sizeof($data);$i++)
			{
			switch($data[$i]['type'])
				{
				case 'cops':
					$MCops['ttl']=(isset($MCops['ttl'])) ? $MCops['ttl']+1 : 1;
					$MCops[$MCops['ttl']]['section']=$data[$i]['section'];
					$MCops[$MCops['ttl']]['categ']=$data[$i]['categ'];
					$MCops[$MCops['ttl']]['texte']=$data[$i]['texte'];
					$MCops[$MCops['ttl']]['id']=$data[$i]['id'];
					$MCops[$MCops['ttl']]['dh_in']=$data[$i]['dh_in'];
					$MCops[$MCops['ttl']]['dh_out']=$data[$i]['dh_out'];
					$MCops[$MCops['ttl']]['idCateg']=$data[$i]['idCateg'];
					break;
				}
			}
		//$TitreCops='<h2>Missions COPS</h2><h3><a href="?component=cops&action=listing">Toutes les fiches</a></h3>';
		$MCops['html']='';
		if(isset($MCops['ttl']))
			{
			// $TitreCops='<h2>Missions COPS</h2>';
			for($i=1;$i<=$MCops['ttl'];$i++)
				{

				if(!in_array($MCops[$i]['id'],$ACops))
					{
					// $MCops['html'].=($MCops['html']=='') ? '' : '<hr width="90%" align="left">';
					if(($MCops[$i]['idCateg']!=31)&&($MCops[$i]['idCateg']!=32)&&($MCops[$i]['idCateg']!=33)){
						$MCops['html'].=$MCops[$i]['section'].' - '.$MCops[$i]['categ'].' : '.$MCops[$i]['texte'].'.  <a href="index.php?component=cops&action=moreInfos&idFiche='.$MCops[$i]['id'].'">Plus d\'infos</a><br />';
						}
					else if ($MCops[$i]['idCateg']==31){
						$MCops['html'].='<font color="red">'.$MCops[$i]['section'].' - '.$MCops[$i]['categ'].' : '.$MCops[$i]['texte'].'.</font>  <a href=index.php?component=cops&action=moreInfos&idFiche='.$MCops[$i]['id'].'">Plus d\'infos</a><br />';
						}
					else if ($MCops[$i]['idCateg']==32){
						$MCops['html'].='<font color="#0000FF">'.$MCops[$i]['section'].' - '.$MCops[$i]['categ'].' : '.$MCops[$i]['texte'].'.</font>  <a href=index.php?component=cops&action=moreInfos&idFiche='.$MCops[$i]['id'].'">Plus d\'infos</a><br />';
						}
					else if ($MCops[$i]['idCateg']==33){
						$MCops['html'].='<font color="#009900">'.$MCops[$i]['section'].' - '.$MCops[$i]['categ'].' : '.$MCops[$i]['texte'].'.</font>  <a href=index.php?component=cops&action=moreInfos&idFiche='.$MCops[$i]['id'].'">Plus d\'infos</a><br />';
						}
					//SOIT BOUTON SUR PLACE, SOIT TEXTAREA DE COMMENTAIRE
					if ((!isset($_SESSION['MissionCops'][$MCops[$i]['id']]))OR($_SESSION['MissionCops'][$MCops[$i]['id']]!='Started'))
						{
						$MCops['html'].='<a href="index.php?component=missions&action=SPCops&id='.$MCops[$i]['id'].'&type=cops"><img src="../media/icons/sur_place.png" height="50px"></a><br />';
						}
					else if ((isset($_SESSION['MissionCops'][$MCops[$i]['id']])) AND ($_SESSION['MissionCops'][$MCops[$i]['id']]=='Started'))
						{
						//*
						$MCops['html'].='<form method="POST" action="index.php?component=missions&action=comCOPS&id='.$MCops[$i]['id'].'"><table>';
						$MCops['html'].='<tr><td align ="left" class="noborder"><textarea name=text'.$MCops[$i]['id'].' rows="5" cols="75" required autofocus></textarea></td></tr>';
						$MCops['html'].='<tr><td class="noborder"><input type="submit" value="Enregistrer"></td></tr>';
						$MCops['html'].='</table></form>';
						//*/
						//$MCops['html'].='Textarea et bouton fin<br />';
						}
					//---------------------------------------------------
					array_push($ACops, $MCops[$i]['id']);
					// print_r($_SESSION['MissionCops']);
					}
				if ($MCops[$i]['dh_in']!='0000-00-00 00:00:00')
					{
					$MCops['html'].='Mission effectuée le '.$this->datefr($MCops[$i]['dh_in'],1).'<br />';
					}
				}
			// $MCops['html'].='<hr width="90%" align="left">';
			}
		// print_r($MCops);
		// $html.=$TitreCops.$MCops['html'];
		$html.=$MCops['html'];
		// }
	//-------------------------------
		// $html.='<h2>Mes missions Vacanciers</h2>';
		// $html.='<ul>';
		for($i=0;$i<sizeof($data);$i++)
			{
			if ($data[$i]['type']=='vacancier')
				{
				$html.=''.ucfirst($data[$i]['type']).' : '.$data[$i]['rue'].', '.$data[$i]['numero'].' à '.$data[$i]['CP'].' '.$data[$i]['ville'].'.  <a href="index.php?component=vacancier&action=infoVac&idVac='.$data[$i]['id'].'">Fiche complète</a><br />';

				$html.='<a href='.$data[$i]['gMap'].' target=blank_><img src="../media/icons/GMaps.png" height=40 title="Localiser"></a>';
				$html.='   ';
				$html.='<img src="'.$media.'icons/zoom-in.ico" height=40 title="Plus d\'infos"  onclick=moreInfos(\''.$data[$i]['id'].'\',\'1\');>';

				$html.='<img src="'.$media.'icons/RAS.png" height=40 title="Contrôlé et RAS" onclick=RAS(\''.$data[$i]['id'].'\',\''.$_COOKIE['iduser'].'\',\''.$_SESSION['idpat'].'\');>      <img src="'.$media.'icons/attention.png" height=40 title="Contrôlé et incident constaté" onclick=incident(\''.$data[$i]['id'].'\',\''.$_COOKIE['iduser'].'\',\''.$_SESSION['idpat'].'\');>';
				$html.= ($data[$i]['dh_in']!='0000-00-00 00:00:00') ? '<br /><b>Mission effectuée le '.$this->datefr($data[$i]['dh_in'],1).'</b>' : '';
				if ($data[$i]['nbIncident']>0)
					{
					$html.='<br /><b><i>';
					$html.= ($data[$i]['nbIncident']==1) ? '1 incident constaté : ' : $data[$i]['nbIncident'].' incidents constatés : ';
					$html.='<br />';
					// $html.='<br /><b><i>'.$data[$i]['nbIncident'].' incidents constatés : <br />';
					while ($row=$data[$i]['incident']->fetch())
						{
						$html.='Le '.$this->datefr($row['date_heure'],1).' : '.$row['commentaire'].'<br />';
						}
					$html.='</i></b>';
					}

				// $html.='';
				$html.='<div id="'.$data[$i]['id'].'"></div><br />';
				}
			}
		// $html.='</ul>';
		// $html.='<h2>Mes autres missions</h2>';
		// $html.='<ul>';
		for($i=0;$i<sizeof($data);$i++)
			{
			if ($data[$i]['type']=='CS')
				{
				if (($data[$i]['HDeb']=='0000-00-00 00:00:00')&&($data[$i]['HFin']=='0000-00-00 00:00:00'))
					{
					$html.='<b>Contrôle statique.</b> Lieu : '.ucfirst($data[$i]['lieu']).'. <div id=rep'.$i.'><input type="button" value="Début" onclick="chgOtherMission(\'start\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');"></div>';
					}
				else if (($data[$i]['HDeb']!='0000-00-00 00:00:00')&&($data[$i]['HFin']=='0000-00-00 00:00:00'))
					{
					$html.='<b>Contrôle statique.</b> Lieu : '.ucfirst($data[$i]['lieu']).'. <div id=rep'.$i.'><input type="button" value="Fin" onclick="chgOtherMission(\'end\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');"></div>';
					}
				else if (($data[$i]['HDeb']!='0000-00-00 00:00:00')&&($data[$i]['HFin']!='0000-00-00 00:00:00'))
					{
					$html.='<b>Contrôle statique.</b> Lieu : '.ucfirst($data[$i]['lieu']).'. Mission effectuée.';
					}
				}

			if ($data[$i]['type']=='PP')
				{
				if ($data[$i]['HDeb']=='0000-00-00 00:00:00')
					{
					$html.='<b>Patrouille pédestre.</b> Lieu : '.ucfirst($data[$i]['lieu']).'. <div id=rep'.$i.'><input type="button" value="Début" onclick="chgOtherMission(\'start\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');"></div>';
					}
				else if ($data[$i]['HFin']=='0000-00-00 00:00:00')
					{
					$html.='<b>Patrouille pédestre.</b> Lieu : '.ucfirst($data[$i]['lieu']).'. <div id=rep'.$i.'><input type="button" value="Fin" onclick="chgOtherMission(\'end\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');"></div>';
					}
				else if (($data[$i]['HDeb']!='0000-00-00 00:00:00')&&($data[$i]['HFin']!='0000-00-00 00:00:00'))
					{
					$html.='<b>Patrouille pédestre.</b> Lieu : '.ucfirst($data[$i]['lieu']).'. Mission effectuée.';
					}
				}
			if ($data[$i]['type']=='PV')
				{
				if ($data[$i]['HDeb']=='0000-00-00 00:00:00')
					{
					$html.='Patrouille en véhicule.</b> Lieu : '.ucfirst($data[$i]['lieu']).'. <div id=rep'.$i.'><input type="button" value="Début" onclick="chgOtherMission(\'start\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');"></div>';
					}
				else if ($data[$i]['HFin']!='0000-00-00 00:00:00')
					{
					$html.='<b>Patrouille en véhicule.</b> Lieu : '.ucfirst($data[$i]['lieu']).'. <div id=rep'.$i.'><input type="button" value="Fin" onclick="chgOtherMission(\'end\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');"></div>';
					}
				else
					{
					$html.='<b>Patrouille en véhicule.</b> Lieu : '.ucfirst($data[$i]['lieu']).'. Mission effectuée.';
					}
				}
			}
	}




	//-------------------------------
	$this->appli->jScript2= '<script type="text/javascript" src="./js/vacancier.js"></script>';
	$this->afficheHtml($html);
	}


public function showMissionsByIdPatBis($data)
 	{
	
	$html='<div id="missions"><table class="mission">';
	if (sizeof($data)==0)
		{
		$html.='Aucune mission ne vous a été attribuée.';
		}
	else
		{
		$NbMissions=sizeof($data);
		$MCops=array();
		$ACops=array();
		$MVacs='';
		$MOther='';
		$media=MEDIA;

		//AFFICHAGE DES MISSIONS DE TYPE COPS
		for ($i=0;$i<sizeof($data);$i++)
			{
			switch($data[$i]['type'])
				{
				case 'cops':
					$MCops['ttl']=(isset($MCops['ttl'])) ? $MCops['ttl']+1 : 1;
					$MCops[$MCops['ttl']]['section']=$data[$i]['section'];
					$MCops[$MCops['ttl']]['categ']=$data[$i]['categ'];
					$MCops[$MCops['ttl']]['texte']=$data[$i]['texte'];
					$MCops[$MCops['ttl']]['id']=$data[$i]['id'];
					$MCops[$MCops['ttl']]['dh_in']=$data[$i]['dh_in'];
					$MCops[$MCops['ttl']]['dh_out']=$data[$i]['dh_out'];
					$MCops[$MCops['ttl']]['idCateg']=$data[$i]['idCateg'];
					break;
				}
			}
		
		$MCops['html']='';
		if(isset($MCops['ttl']))
			{
			for($i=1;$i<=$MCops['ttl'];$i++)
				{

				if(!in_array($MCops[$i]['id'],$ACops))
					{
						$MCops['html'].='<tr><td onclick="details(\'C\',\''.$MCops[$i]['id'].'\');" class="MCops" colspan="3">'.$MCops[$i]['section'].' - '.$MCops[$i]['categ'].' : '.$MCops[$i]['texte'].'.</td>';

					//SOIT BOUTON SUR PLACE, SOIT TEXTAREA DE COMMENTAIRE
					if ((!isset($_SESSION['MissionCops'][$MCops[$i]['id']]))OR($_SESSION['MissionCops'][$MCops[$i]['id']]!='Started'))
						{
						$MCops['html'].='<td class="RepMis"><a href="index.php?mode=m&component=missions&action=SPCops&id='.$MCops[$i]['id'].'&type=cops" class="bSP">Sur place</a>';						
						}
					else if ((isset($_SESSION['MissionCops'][$MCops[$i]['id']])) AND ($_SESSION['MissionCops'][$MCops[$i]['id']]=='Started'))
						{
						$MCops['html'].='<td><form method="POST" action="index.php?mode=m&component=missions&action=comCOPS&id='.$MCops[$i]['id'].'"><table>';
						$MCops['html'].='<tr><td align ="left" class="noborder"><textarea name=text'.$MCops[$i]['id'].' rows="5" cols="15" required autofocus></textarea></td></tr>';
						$MCops['html'].='<tr><td class="bSubmit"><input type="submit" value="Enregistrer" /></td></tr>';
						$MCops['html'].='</table></form></td></tr>';
						}
					//---------------------------------------------------
					array_push($ACops, $MCops[$i]['id']);
					}
				// if ($MCops[$i]['dh_in']!='0000-00-00 00:00:00')
					// {
					// $MCops['html'].='<br />Effectuée</td></tr>';
					// }
				}
			}
		$html.=$MCops['html'];
		$html.='<tr class="trBlank"></tr>';

	//-------------------------------

		for($i=0;$i<sizeof($data);$i++)
			{
			if ($data[$i]['type']=='CS')
				{
				if (($data[$i]['HDeb']=='0000-00-00 00:00:00')&&($data[$i]['HFin']=='0000-00-00 00:00:00'))
					{
					$html.='<tr><td class="MAutre" colspan="3">Contrôle statique. Lieu : '.ucfirst($data[$i]['lieu']).'.</td><td><div id=rep'.$i.'><a href="#" class="bSP" onclick="chgOtherMission(\'start\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');">Début</a></div></td></tr>';
					}
				else if (($data[$i]['HDeb']!='0000-00-00 00:00:00')&&($data[$i]['HFin']=='0000-00-00 00:00:00'))
					{
					$html.='<tr><td class="MAutre" colspan="3">Contrôle statique. Lieu : '.ucfirst($data[$i]['lieu']).'.</td><td><div id=rep'.$i.'><a href="#" class="bSP" onclick="chgOtherMission(\'end\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');">Fin</a></div></td></tr>';
					}
				else if (($data[$i]['HDeb']!='0000-00-00 00:00:00')&&($data[$i]['HFin']!='0000-00-00 00:00:00'))
					{
					$html.='<tr><td class="MAutre" colspan="3">Contrôle statique. Lieu : '.ucfirst($data[$i]['lieu']).'.</td><td class="RepMis"></td></tr>';
					}
				}

			if ($data[$i]['type']=='PP')
				{
				if ($data[$i]['HDeb']=='0000-00-00 00:00:00')
					{
					$html.='<tr><td class="MAutre" colspan="3">Patrouille pédestre. Lieu : '.ucfirst($data[$i]['lieu']).'.</td><td><div id=rep'.$i.'><a href="#" class="bSP" onclick="chgOtherMission(\'start\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');">Début</a></div></td></tr>';
					}
				else if ($data[$i]['HFin']=='0000-00-00 00:00:00')
					{
					$html.='<tr><td class="MAutre" colspan="3">Patrouille pédestre. Lieu : '.ucfirst($data[$i]['lieu']).'.</td><td><div id=rep'.$i.'><a href="#" class="bSP" onclick="chgOtherMission(\'end\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');">Fin</a></div></td></tr>';
					}
				else if (($data[$i]['HDeb']!='0000-00-00 00:00:00')&&($data[$i]['HFin']!='0000-00-00 00:00:00'))
					{
					$html.='<tr><td class="MAutre" colspan="3">Patrouille pédestre. Lieu : '.ucfirst($data[$i]['lieu']).'.</td><td class="RepMis"></td></tr>';
					}
				}
			if ($data[$i]['type']=='PV')
				{
				if ($data[$i]['HDeb']=='0000-00-00 00:00:00')
					{
					$html.='<tr><td class="MAutre" colspan="3">Patrouille en véhicule. Lieu : '.ucfirst($data[$i]['lieu']).'.</td><td><div id=rep'.$i.'><a href="#" class="bSP" onclick="chgOtherMission(\'start\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');">Début</a></div></td></tr>';
					}
				else if ($data[$i]['HFin']=='0000-00-00 00:00:00')
					{
					$html.='<tr><td class="MAutre" colspan="3">Patrouille en véhicule. Lieu : '.ucfirst($data[$i]['lieu']).'.</td><td><div id=rep'.$i.'><a href="#" class="bSP" onclick="chgOtherMission(\'end\',\''.$i.'\', \''.$data[$i]['id'].'\',\''.$_SESSION['idpat'].'\');">Fin</a></div></td></tr>';
					}
				else
					{
					$html.='<tr><td class="MAutre" colspan="3">Patrouille en véhicule. Lieu : '.ucfirst($data[$i]['lieu']).'.</td><td class="RepMis"></td></tr>';
					}
				}
			}
			
			$html.='<tr class="trBlank"></tr>';
			
		for($i=0;$i<sizeof($data);$i++)
			
			{
			if ($data[$i]['type']=='vacancier')
				{
				$html.='<tr><td  onclick="details(\'V\',\''.$data[$i]['id'].'\');"class="MVac">'.ucfirst($data[$i]['type']).' : '.$data[$i]['rue'].', '.$data[$i]['numero'].' à '.$data[$i]['CP'].' '.$data[$i]['ville'].'.</td>';

				$html.='<td class="MVac"><a href='.$data[$i]['gMap'].' target=blank_><img src="../media/icons/GMaps.png" height=30 title="Localiser"></a></td>';
				// $html.='   ';
				// $html.='<td class="MVac"><img src="'.$media.'icons/zoom-in.ico" height=30 title="Plus d\'infos"  onclick=moreInfos(\''.$data[$i]['id'].'\',\'1\');></td>';

				$html.='<td class="MVac"><img src="'.$media.'icons/RAS.png" height=30 title="Contrôlé et RAS" onclick=RAS(\''.$data[$i]['id'].'\',\''.$_COOKIE['iduser'].'\',\''.$_SESSION['idpat'].'\');><td id="'.$data[$i]['id'].'"><a href="#" class="bSP" title="Contrôlé et incident constaté" onclick=incident(\''.$data[$i]['id'].'\',\''.$_COOKIE['iduser'].'\',\''.$_SESSION['idpat'].'\');>Incident</a></td>';
				//$html.= ($data[$i]['dh_in']!='0000-00-00 00:00:00') ? '<br />Mission effectuée.<br />' : '';
				/*if ($data[$i]['nbIncident']>0)
					{
					while ($row=$data[$i]['incident']->fetch())
						{
						$html.='Déjà constaté  : '.$row['commentaire'].'<br />';
						}
					}*/

				//$html.='<td class="RepMis"><div id="'.$data[$i]['id'].'"></div></td></tr>';
				$html.='</tr>';
				}
			}			
	$html.='</table></div>';		
	}
	//-------------------------------
	$this->appli->jScript2= '<script type="text/javascript" src="./js/vacancier.js"></script>';
	$this->afficheHtml($html);
	}
	
public function accueilTab(){
	$html='';
	
	$html.='<a href="?mode=m&component=users&action=fromMenuTablette" class="boutonMissions">Mes missions</a>';
	//$html.='<a href="#" class="boutonHot" onclick="boutonHot();">Hot !</a>';
		
	$html.='<div class="boutonHot">Hot !<br />';
	$html.='<ul><li><a href="#">Infos hot validées</a></li><li><a href="?mode=pop&component=infos&action=showInfos" target="_blank" >Infos hot proposées</a></li><li><a href="?mode=newInfo&component=infos&action=newInfo" target="_blank">Encoder une info</a></li></ul>';
	$html.='</div>';	

	$html.='<br /><div id="blank"></div><div id="repHot"></div>';
	$html.='<a href="#" class="bouton4">Autre</a>';
	$html.='<a href="#" class="boutonInfos" id="boutonInfos" onclick="slide(\'repInfo\');">Infos</a>';
	$html.='<br /><div id="B4"></div><div id="repInfos"><li class="repInfo"><a href="?mode=m&component=garde&action=mainMenu">Gardes</a></li><li class="repInfo"><a href="?mode=m&component=vacancier&action=listEnCours">Vacanciers</a></li><li class="repInfo"><a href="?mode=m&component=cops&action=listing" target="_blank">COPS</a></li><li class="repInfo"><a href="?mode=pop&component=documentation&action=mainMenu" target="_blank">Documentation</a></li><li class="repInfo"><a href="https://www.polcom.be/CIA2/cia.php?step=0&err=2" target="_blank">I+ Hainaut</a></li></div>';
	$html.='</div>';
	// $html.='</p>';
	$this->afficheHtml($html);
	}
	
public function accueilTab2(){
	$html='<div id="accueilTab">';

	$html.='<div class="bMissions" onclick="location.href=\'?mode=m&component=users&action=fromMenuTablette\';">Mes missions</div>';
	$html.='<div class="bHot" id="bHot" onclick="slide(\'repHot\');">Hot !';
	$html.='<li class="repHot"><a href="?mode=pop&component=cops&action=listHot" target="_blank">Infos hot validées</a></li><li class="repHot"><a href="?mode=pop&component=infos&action=showInfos" target="_blank" >Infos hot proposées</a></li><li class="repHot"><a href="?mode=newInfo&component=infos&action=newInfo" target="_blank">Encoder une info</a></li>';
	$html.='</div>';
	$html.='<div class="blank"></div><div class="repHot"></div>';
	$html.='<div class="bAutre">Autre</div>';
	$html.='<div class="bInfo" id="boutonInfos" onclick="slide(\'repInfo\');">Infos';
	$html.='<li class="rInfo"><a href="?mode=m&component=garde&action=mainMenu">Gardes</a></li><li class="repInfo"><a href="?mode=m&component=vacancier&action=listEnCours">Vacanciers</a></li><li class="repInfo"><a href="?mode=m&component=cops&action=listing" target="_blank">COPS</a></li><li class="repInfo"><a href="?mode=pop&component=documentation&action=mainMenu" target="_blank">Documentation</a></li><li class="repInfo"><a href="https://www.polcom.be/CIA2/cia.php?step=0&err=2" target="_blank">I+ Hainaut</a></li></div>';
	$html.='<div id="B4"></div><div id="repInfos"></div>';
	$html.='</div>';
	$html.='</div>';
	$this->afficheHtml($html);
	}
}
?>

