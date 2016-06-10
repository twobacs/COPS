<?php

class VVacancier extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }

public function nonco()
	{
	$this->appli->ctContent.="Vous ne pouvez accéder à cette partie du site.";
	}


public function afficheHtml($data)
	{
	$this->appli->ctContent=$data;
	$this->appli->jScript= '<script type="text/javascript" src="./js/vacancier.js"></script>';
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

public function showMenu($level,$from)
	{
	$html='';
	switch ($from)
		{
		case 'addVac' :
			$html.='<span class="msgconf">Nouvelle demande de surveillance habitation enregistrée</span><br />';
			break;
		case '' :
			$html.='';
			break;
		}
	if (($level=='3') || ($level=='5') || ($level=='10') || ($level=='20') || ($level=='30') || ($level=='50'))
		{
		//Accès "Lecteur"
		$html.='<a href=?component=vacancier&action=listEnCours>Demandes en cours</a><br />';
		}

	if (($level=='10') || ($level=='20') || ($level=='30') || ($level=='50'))
		{
		//Accès "Editeur"
		$html.='<a href=?component=vacancier&action=addHab>Création d\'une nouvelle surveillance habitation</a><br />';
		$html.='<a href=?component=vacancier&action=editVac>Edition d\'une surveillance encodée</a><br />';
		}

	if (($level=='20') || ($level=='30') || ($level=='50'))
		{
		//Accès "Gestionnaire"

		$html.='<a href="index.php?component=vacancier&action=selectCR">Génération courrier de compte-rendu</a><br />';
		}

	if (($level=='30') || ($level=='50'))
		{
		//Accès "Administrateur"
		$html.='<a href="index.php?component=vacancier&action=indicateurs">Accès indicateurs</a><br />';
		}

	$this->afficheHtml($html);
	}

public function formAddHabS1()
	{
	$html='<h2>Ajout d\'une surveillance habitation </h2>';
	$html.='<h3>Etape 1 : Coordonnées demandeur</h3>';
	$html.='<form name="AddVacancier" action="?component=vacancier&action=addHab&etape=1" method=POST><table>';
	$html.='<tr><th>Nom : </th><td><input type=text name=nom id=nom autofocus></td></tr>';
	$html.='<tr><th>Prénom : </th><td><input type=text name=prenom id=prenom ></td></tr>';
	$html.='<tr><th>Date de naissance : </th><td><input type=date id=dn name=dn></td></tr>';
	$html.='<tr id=test><td colspan="2"><input type=button value="Suivant" onclick="vac_checkNewDemandeur();"></td></tr>';
	$html.='</table>';
	$html.='<table id="step1"></table>';
	$html.='</form>';
	$this->afficheHtml($html);
	}


public function formAddHabS2($dem=0,$hab=0,$idDem)
	{
	$html='<h2>Ajout d\'une surveillance habitation </h2>';
	$html.='<h3>Etape 2 : Coordonnées du bien faisant l\'objet de la demande</h3>';
	// $html.=$dem['nom'];
	if ($hab['quantite']==0)
		{
		$html.='Aucun encodage existant';
		$html.='<br /><a href="#" onClick=remploiDonneesHab("no","'.$idDem.'");>Formulaire vierge</a></li></ul>';
		$html.='<div id="step1"></div>';
		}
	else
		{
		$html.=$dem['nom'].' '.$dem['prenom'].' a déjà procédé à une demande de surveillance vacances pour :<br /><ul>';
		for ($i=0;($i<$hab['quantite']);$i++)
			{
			$html.='<li> L\'habitation sise à '.$hab[$i]['CP'].' '.$hab[$i]['ville'].', '.$hab[$i]['adresse'].' '.$hab[$i]['numero'].', ';
			$html.='pour la période du '.$this->datefr($hab[$i]['depart']).' au '.$this->datefr($hab[$i]['retour']);
			$html.=' (demande faite le '.$this->datefr($hab[$i]['demande']).').';
			$html.='<br /><a href="#" onClick=remploiDonneesHab('.$hab[$i]['id_vac'].',"'.$idDem.'");>Réutiliser</a> ces données.<br />';
			}
		$html.='<a href="#" onClick=remploiDonneesHab("no","'.$idDem.'");>Formulaire vierge</a></li></ul>';
		$html.='<div id="step1"></div>';
		}
	$this->afficheHtml($html);
	}

public function listVac($data,$level='0',$from='')
	{
	/*
	Afficher les surveillances en cours et proposer une recherche sur une fourchette de dates ou sur une annnée.
	*/
	$media=MEDIA;
	$html='<h2>Aperçu des demandes en cours';
	$html.=($_SESSION['nivApp']>'4') ? ' - <a href="index.php?component=vacancier&action=search">Rechercher des demandes</a></h2>' : '</h2>';
	//if ($from=='edit'){$html.='Modification apportée.';}
	$html.='<div id="ListVac">';
	$html.='<ul>';
	while ($row=$data->fetch())
		{
		if (($level=='3') && ($row['vac_GDP']=='N'))
			{}
		else
		{
		$html.='<li>'.$row['NomRue'].', '.$row['vac_numero'].' à '.$row['vac_CP'].' '.$row['vac_ville'].'.';
    $html.=($from=='edit') ? '' : '(Quartier : '.$row['denomination'].')';
    $html.='(Du '.$this->datefr($row['vac_dateDepart']).' au '.$this->datefr($row['vac_dateRetour']).')<br />';
		$html.='Demandé par : '.$row['nom_dem'].' '.$row['prenom_dem'].'<br /><br />';


		$html.='<a href='.$row['vac_gmap'].' target=blank_><img src="../media/icons/GMaps.png" height=40 title="Localiser"></a>';
		$html.='   ';
		$html.='<img src="'.$media.'icons/zoom-in.ico" height=40 title="Plus d\'infos"  onclick=moreInfos(\''.$row['id_vac'].'\',\''.$level.'\');>';

		$html.=($from=='') ? '      <img src="'.$media.'icons/RAS.png" height=40 title="Contrôlé et RAS" onclick=RAS(\''.$row['id_vac'].'\',\''.$_COOKIE['iduser'].'\');>      <img src="'.$media.'icons/attention.png" height=40 title="Contrôlé et incident constaté" onclick=incident(\''.$row['id_vac'].'\',\''.$_COOKIE['iduser'].'\');>' : '';//    <a href="#">Incident</a>' : '';

		if (($level=='10') || ($level=='20') || ($level=='30') || ($level=='50') && ($from=='edit'))
			{
			$html.='   ';
			$html.='<a href="?component=vacancier&action=editVac&id='.$row['id_vac'].'"><img src="../media/icons/edit.png" height=40 title="Editer"></a>';
			}
		if (($level=='20') || ($level=='30') || ($level=='50') && ($from=='edit'))
			{
			$html.='   ';
			$html.='<a href="?component=vacancier&action=delVac&id='.$row['id_vac'].'" onclick="return confirm(\'Etes-vous sûr de vouloir supprimer cette surveillance ainsi que toutes les données y relatives ? (opération irréversible)\');"><img src="../media/icons/remove.png" height=40 title="Supprimer"></a>';
			}
		$html.='<div id="'.$row['id_vac'].'"></div>'; //DIV de réception des infos complémentaires
		$html.='</li>';
		$html.='<hr>';
		}
		}
	$html.='</ul>';
	$html.='</div>';
	// if ($level>5)
		// {
		// $this->MenuLeft($level);
		// }
	$this->afficheHtml($html);
	}

public function searchVac()
	{
	$html='<h3>Rechercher</h3>';
	$html.='<a href="#" onclick="updateSearchForm(\'adr\');">Adresse</a><br />';
	$html.='<div id=searchForm></div>';
	}

public function afficheResult($data)
	{
	$i=1;
	$j=0;
	$html='<h3>Résultat de votre recherche</h3>';
	$html.='<form method=post>';

	while ($row=$data->fetch())
		{
		if ($i==1)
			{
			$html.='<b>1er résultat : </b><br />';
			}
		else
			{
			$html.='<b>'.$i.'ème résultat : </b><br />';
			}
		$html.=$row['NomRue'].' '.$row['vac_numero'].', '.$row['vac_CP'].' '.$row['vac_ville'].', du '.$this->datefr($row['vac_dateDepart']).' au '.$this->datefr($row['vac_dateRetour']).', demandé par '.$row['nom_dem'].' '.$row['prenom_dem'].' - <a href="index.php?component=vacancier&action=editVac&id='.$row['id_vac'].'">Editer</a> cette demande.<br />';
		$i++;
		$j++;
		}
	$html.='</form>';

	$html.=($j==0) ? 'Aucun résultat obtenu sur base de cette recherche.' : '';

	$this->afficheHtml($html);
	}

public function MenuLeft($level)
	{
	$html='';
	if ($level>4)
		{
		$html.='<a href="?component=vacancier&action=listHabi&type=all>Voir toutes</a> les demandes.';
		$this->appli->left=$html;
		}
	}

public function editVac($data,$id)
	{
	$html='<h2>Edition d\'une surveillance</h2>';
	//$html.='<input type="search" placeholder="Entrez un mot-clef" name="the_search">';
	//DONNEES DU BIEN A SURVEILLER
	$html.='<form method=post action="?component=vacancier&action=editVac&id='.$id.'&modif=1&part=h"><table>';
	$html.='<tr><th colspan="4" class="titre">Données relatives au bien</th></tr>';
	$html.='<tr><th width="25%">Adresse :</th><td width="25%"><select name=rue>';
	while ($row=$data['rues']->fetch())
		{
		$html.='<option value='.$row['IdRue'];
		$html.=($data['house']['adresse']==$row['IdRue']) ? ' selected>' : '>';
		$html.=$row['NomRue'].'</option>';
		}
	$html.='</select></td><th width="25%">Numéro :<td width="25%"><input type=text name=numHab value="'.$data['house']['numero'].'" size="4"></td></tr>';
	$html.='<th width="25%">CP + Ville</th><td width="25%"><input type=text name="CP" value="'.$data['house']['CP'].'" size="4" ><input type=text name="ville" value="'.$data['house']['ville'].'"</td>';
	$html.='<th width="25%">Date demande :</th><td>'.$this->datefr($data['house']['demande']).'</td></tr>';
	$html.='<tr><th>Nombre de façades :</th><td><input type=text name=nbFacades value="'.$data['house']['nbFacades'].'" size="4"></td><th>Alarme :</th><td><select name=alarme>';
	$html.=($data['house']['alarme']=='O') ? '<option value="O" selected>Oui</option><option value="N">Non</option>' : '<option value="O">Oui</option><option value="N" selected>Non</option>';
	$html.='</select>';
	$html.='</td></tr>';
	$html.='<tr><th>Eclairage extérieur :</th><td><select name=eclairageExt>';
	$html.=($data['house']['eclairageExt']=='O') ? '<option value="O" selected>Oui</option><option value="N">Non</option>' : '<option value="O">Oui</option><option value="N" selected>Non</option>';
	$html.='</td><th>Eclairage intérieur :</th><td><select name=eclairageInt>';
	$html.=($data['house']['eclairageInt']=='O') ? '<option value="O" selected>Oui</option><option value="N">Non</option>' : '<option value="O">Oui</option><option value="N" selected>Non</option>';
	$html.='</td></tr>';
	$html.='<tr><th>Présence d\'un chien :</th><td><select name=chien>';
	$html.=($data['house']['chien']=='O') ? '<option value="O" selected>Oui</option><option value="N">Non</option>' : '<option value="O">Oui</option><option value="N" selected>Non</option>';
	$html.='</td></tr>';
	$html.='<tr><th>Courrier relevé :</th><td><select name="courrier">';
	$html.=($data['house']['courrier']=='O') ? '<option value="O" selected>Oui</option><option value="N">Non</option>' : '<option value="O">Oui</option><option value="N" selected>Non</option>';
	$html.='</select></td><th>Chargé de courrier :</th><td><input type=text name=persCourrier value="'.$data['house']['persCourrier'].'"></td></tr>';


	$html.='<tr><th>Chargé de persiennes ?</th><td><select name=persAuto>';
	$html.=($data['house']['persAuto']=='O') ? '<option value="O" selected>Oui</option><option value="N">Non</option>' : '<option value="O">Oui</option><option value="N" selected>Non</option>';
	$html.='</td>';


	$html.='<th>Chargé de persiennes :</th><td><input type=text name=persPers value="'.$data['house']['persPers'].'"</td></tr>';
	$html.='<tr><th>Remarque :</th><td colspan="3"><textarea name=remarque rows="2" cols="50">'.$data['house']['remarque'].'</textarea></td></tr>';
	$html.='<tr><th>Date départ :</th><td><input type=date name=dateDepart value='.$data['house']['depart'].'></td><th>Date retour :</th><td><input type=date name=dateRetour value='.$data['house']['retour'].'></td></tr>';
	$html.='<tr><th>Visite techno :</th><td><input type=date name=dateTechno value="'.$data['house']['dateTechno'].'"></td><td colspan="2"><input type=submit value="Valider changements habitation"></td></tr>';
	$html.='</table></form>';

	//DONNEES DES VEHICULES
	if ((isset($data['vv']['total'])) && ($data['vv']['total']>0))
		{
		$html.='<form method=post action="index.php?component=vacancier&action=editVac&id='.$id.'&modif=1&part=vv"><table>';
		for ($i=0;$i<$data['vv']['total'];$i++)
			{
			$html.='<tr><th colspan="4" class="titre" id="vv'.$i.'">';
			$html.= ($i==0) ? '1er véhicule' : ($i+1).'ème véhicule' ;
			$html.='</th></tr>';
			$html.='<tr><th width="25%">Marque + modèle :</th><td width="25%"><input type=text name=modele'.$i.' value="'.$data['vv'][$i]['marque'].'"></td><th width="25%">Immatriculation :</th><td width="25%"><input type=text name=immat'.$i.' value="'.$data['vv'][$i]['imm'].'"></td></tr>';
			$html.='<tr><th>Lieu d\'entreposage :</th><td><input type=text name=lieu'.$i.' value="'.$data['vv'][$i]['lieu'].'"></td><td class="noborder" colspan="2"><input type=hidden name=totVV value="'.$data['vv']['total'].'"><input type="hidden" name="idVV'.$i.'" value="'.$data['vv'][$i]['id'].'"><input type=submit value="Valider changements véhicule"><input type="button" value="Supprimer ce véhicule" onclick="delVV(\''.$data['vv'][$i]['id'].'\',\''.$id.'\',\'vv'.$i.'\');"></td></tr>';
			}
		$html.='<tr><td colspan="4"><input type="button" value="Ajouter un véhicule" onclick="addInfo(\'AddVV\',\''.$id.'\');"></td></tr>';
		$html.='</table></form>';
		$html.='<div id="AddVV"></div>';
		}
	else
		{
		$html.='<form><table>';
		$html.='<tr><th colspan="4" class="titre">Véhicules</th></tr>';
		$html.='<tr><td colspan="2" width="50%">Aucun véhicule</td><td colspan="2"><input type="button" onclick="addVV(\''.$id.'\');" value="Ajouter un véhicule"></td></tr>';
		$html.='</table></form>';
		$html.='<div id="AddVV"></div>';
		}

	//DONNEES DES PERSONNES DE CONTACT
	if ((isset($data['contact']['total'])) && ($data['contact']['total']>0))
		{
		$html.='<form method=post action="index.php?component=vacancier&action=editVac&id='.$id.'&modif=1&part=contact"><table>';
		for ($i=1;$i<=$data['contact']['total'];$i++)
			{
			$html.='<tr><th colspan="4" class="titre">';
			$html.= ($i==1) ? '1ère personne' : ($i).'ème personne' ;
			$html.='<tr><th>Nom :</th><td><input type=text name=nom'.$i.' value="'.$data['contact'][$i]['nom'].'"></td><th>Prénom :</th><td><input type=text name=prenom'.$i.' value="'.$data['contact'][$i]['prenom'].'"></td></tr>';
			$html.='<tr><th>Adresse :</th><td><input type=text name=addCont'.$i.' value="'.$data['contact'][$i]['adresse'].'"><input type=text name=numCont'.$i.' size="5" value="'.$data['contact'][$i]['numero'].'"></td><th>CP + ville :</th><td><input type=text name=CPCont'.$i.' size="5" value="'.$data['contact'][$i]['CP'].'"> <input type=text name=villeCont'.$i.' value="'.$data['contact'][$i]['ville'].'"></td></tr>';
			$html.='<tr><th>Numéros d\'appel :</th><td><input type=text name=telCont1'.$i.' value="'.$data['contact'][$i]['tel'].'"></td><td><input type=text name=telCont2'.$i.' value="'.$data['contact'][$i]['tel2'].'"></td><td class=noborder><input type=submit value="Valider changements personne"><input type=hidden name=totCont value="'.$data['contact']['total'].'"><input type=hidden name=idCont'.$i.' value="'.$data['contact'][$i]['idCont'].'"</td></tr>';
			$html.='<tr><td colspan="4" class="noborder"><input type="button" value="Supprimer ces données de contact" onclick="delContact(\''.$data['contact'][$i]['idCont'].'\');"></td></tr>';
			}
		$html.='</table></form>';
		$html.='<table><tr><td colspan="4"><input type="button" value="Ajouter une personne" onclick="addInfo(\'AddPers\',\''.$id.'\');"></td></tr>';
		$html.='</table>';
		$html.='<div id="AddPers"></div>';
		}
	else
		{
		$html.='<form><table>';
		$html.='<tr><th colspan="4" class="titre">Personnes de contact</th></tr>';
		$html.='<tr><td colspan="2" width="50%">Aucun contact</td><td colspan="2"><input type="button" onclick="addInfo(\'AddPers\',\''.$id.'\');" value="Ajouter une personne de contact"></td></tr>';
		$html.='</table></form>';
		$html.='<div id="AddPers"></div>';
		}


	//DONNEES DU DEMANDEUR
	while ($row=$data['demandeur']->fetch())
		{
		$html.='<form method=post action="index.php?component=vacancier&action=editVac&id='.$id.'&modif=1&part=dem"><table>';
		$html.='<tr><th colspan="4" class=titre>Demandeur</th></tr>';
		$html.='<tr><th>Nom et prénom :</th><td><input type=text name=nomDem value="'.$row['nom_dem'].'"> <input type=text name=prenomDem value="'.$row['prenom_dem'].'"></td><th>Date de naissance :</th><td><input type="date" name="dnDem" value="'.$row['dn_dem'].'"></td></tr>';
		$html.='<tr><th>Téléphone :</th><td><input type=tel name=telDem value="'.$row['tel_dem'].'"></td><th>GSM :</th><td><input type=tel name=gsmDem value="'.$row['gsm_dem'].'"></td></tr>';
		$html.='<tr><th>Mail :</th><td><input type="email" name=mailDem value="'.$row['mail_dem'].'"><td class=noborder colspan="2"><input type=submit value="Enregistrer changements demandeur"><input type="hidden" name="idDem" value="'.$row['id_dem'].'"></td></tr>';
		}
	$html.='</table></form>';
	$this->afficheHtml($html);
	}

public function menuIndicateurs()
	{
	$html='<h2>Indicateurs</h2>';
	$html.='<form method="POST" action="?component=vacancier&action=indicateurs&search=1"><table>';
	$html.='<tr><th>Année à prendre en considération :</th><td><input type="text" name="annee" autofocus required></td></tr>';
	$html.='<tr><td colspan="2" class=noborder><input type="submit" value="Afficher indicateurs"></td></tr>';
	$html.='</table></form>';
	$this->afficheHtml($html);
	}

public function showIndic($annee,$data)
	{
	$html='<h2>Indicateurs</h2>';
	$html.='<h3>Année considérée : '.$annee.'</h3>';
	$html.='Nombre de demandes : '.$data['biens'].'<br />';
	$html.='Nombre de demandeurs : '.$data['demandeurs'].'<br />';

	$this->afficheHtml($html);
	}

public function showListCRToDo($data)
	{
	$html='<h2>Création compte-rendu vacancier</h2>';
	$html.='<h4>Veuillez sélectionner le vacancier pour lequel vous désirez créer le compte-rendu</h4>';
	$html.='<table border="1">';
	$html.='<tr><th>Adresse</th><th>Demandeur</th><th>Départ</th><th>Retour</th><td class="noborder"></td></tr>';
	while ($row=$data->fetch()){
		$html.='<tr>';
		$html.='<td>'.$row['NomRue'].', '.$row['vac_numero'].'</td>';
		$html.='<td>'.$row['nom_dem'].' '.$row['prenom_dem'].'</td>';
		$html.='<td>'.$this->datefr($row['vac_dateDepart']).'</td>';
		$html.='<td>'.$this->datefr($row['vac_dateRetour']).'</td>';
		$html.='<td><a href="index.php?component=vacancier&action=verifVac&idVac='.$row['id_vac'].'">V&eacute;rifier</a>';
		if ($row['vac_dateRetour']<date('Y-m-d')){
			$html.=' / <a href="index.php?component=vacancier&action=vacSend&idVac='.$row['id_vac'].'">Envoyé</a>';
			}
		// else{
			// $html.=date('Y-m-d');
			// }		
		$html.='</td>';
		$html.='</tr>';
		}
	$html.='</table>';
	$this->afficheHtml($html);
	}

public function doCR($data)
	{
	$demandeur='';
	$adresse='';
	$surveillances='';

	while ($row=$data['demandeur']->fetch())
		{
		$demandeur = $row['nom_dem'].' '.$row['prenom_dem'];
		}

	$adresse=$data['bien']['numero'].' '.$data['bien']['NomRue'].' <br /> '.$data['bien']['CP'].' '.$data['bien']['ville'].' ';
	$dateDepart=$this->datefr($data['bien']['depart']);
	$dateRetour=$this->datefr($data['bien']['retour']);

	$surveillances='<table><tr><th>Date et heure</th><th>Commentaire</th></tr>';
	while ($row=$data['surveillances']->fetch())
		{
		$surveillances.='<tr><td>'.$this->datefr($row['date_heure'],1).'</td><td>'.$row['commentaire'].'</td></tr>';
		}
	$surveillances.='</table>';

	$this->appli->tplPath='pdfTemplate/';
	$this->tplIndex = 'index.html';

	$this->appli->demandeur=$demandeur;
	$this->appli->adresse=$adresse;
	$this->appli->tableauPassages=$surveillances;
	$this->appli->date_depart=$dateDepart;
	$this->appli->date_retour=$dateRetour;
	}

public function formSearchVacancier() {
	$html='<h2>Effectuer une recherche</h2>';
	$html.='<form name=formSearchVacancier method="POST" action="index.php?component=vacancier&action=search"><table>';
	$html.='<tr><th>Entrez un mot-clé :</th><td><input type="text" id="searchVacancier" onkeyup="searchVac();" autofocus></td></tr>';
	$html.='</table></form>';
	$html.='<div id=rep></div>';
	$this->afficheHtml($html);
	}

public function showVac($data)
	{
	$html='<h2>Coordonnées d\'une surveillance</h2>';
	//$html.='<input type="search" placeholder="Entrez un mot-clef" name="the_search">';
	//DONNEES DU BIEN A SURVEILLER
	// $html.='<form method=post action="?component=vacancier&action=editVac&id='.$id.'&modif=1&part=h">';
	$html.='<table>';
	$html.='<tr><th colspan="4" class="titre">Données relatives au bien</th></tr>';
	$html.='<tr><th width="25%">Adresse :</th><td width="25%">';
	while ($row=$data['rues']->fetch())
		{
		$html.=($data['house']['adresse']==$row['IdRue']) ?  $row['NomRue']: '';
		// $html.=$row['NomRue'].'';
		}
	$html.='</td><th width="25%">Numéro :<td width="25%">'.$data['house']['numero'].'</td></tr>';
	$html.='<th width="25%">CP + Ville</th><td width="25%">'.$data['house']['CP'].' '.$data['house']['ville'].'</td>';
	$html.='<th width="25%">Date demande :</th><td>'.$this->datefr($data['house']['demande']).'</td></tr>';
	$html.='<tr><th>Nombre de façades :</th><td>'.$data['house']['nbFacades'].'</td><th>Alarme :</th><td>';
	$html.=($data['house']['alarme']=='O') ? 'Oui' : 'Non';
	$html.='</td></tr>';
	$html.='<tr><th>Eclairage extérieur :</th><td>';
	$html.=($data['house']['eclairageExt']=='O') ? 'Oui' : 'Non';
	$html.='</td><th>Eclairage intérieur :</th><td>';
	$html.=($data['house']['eclairageInt']=='O') ? 'Oui' : 'Non';
	$html.='</td></tr>';
	$html.='<tr><th>Présence d\'un chien :</th><td>';
	$html.=($data['house']['chien']=='O') ? 'Oui' : 'Non';
	$html.='</td></tr>';
	$html.='<tr><th>Courrier relevé :</th><td>';
	$html.=($data['house']['courrier']=='O') ? 'Oui' : 'Non';
	$html.='</select></td><th>Chargé de courrier :</th><td>'.$data['house']['persCourrier'].'</td></tr>';
	$html.='<tr><th>Chargé de persiennes ?</th><td>';
	$html.=($data['house']['persAuto']=='O') ? 'Oui' : 'Non';
	$html.='</td>';


	$html.='<th>Chargé de persiennes :</th><td>'.$data['house']['persPers'].'</td></tr>';
	$html.='<tr><th>Remarque :</th><td>'.$data['house']['remarque'].'</td></tr>';
	$html.='<tr><th>Date départ :</th><td>'.$this->datefr($data['house']['depart']).'</td><th>Date retour :</th><td>'.$this->datefr($data['house']['retour']).'</td></tr>';
	$html.='<tr><th>Visite techno :</th><td>'.$this->datefr($data['house']['dateTechno']).'</td><td colspan="2"></td></tr>';
	$html.='</table></form>';

	//DONNEES DES VEHICULES
	if ((isset($data['vv']['total'])) && ($data['vv']['total']>0))
		{
		$html.='<form><table>';
		for ($i=0;$i<$data['vv']['total'];$i++)
			{
			$html.='<tr><th colspan="4" class="titre">';
			$html.= ($i==0) ? '1er véhicule' : ($i+1).'ème véhicule' ;
			$html.='</th></tr>';
			$html.='<tr><th width="25%">Marque + modèle :</th><td width="25%">'.$data['vv'][$i]['marque'].'</td><th width="25%">Immatriculation :</th><td width="25%">'.$data['vv'][$i]['imm'].'</td></tr>';
			$html.='<tr><th>Lieu d\'entreposage :</th><td>'.$data['vv'][$i]['lieu'].'</td><td class="noborder" colspan="2"></td></tr>';
			}
		$html.='</table></form>';
		}

	//DONNEES DES PERSONNES DE CONTACT
	if ((isset($data['contact']['total'])) && ($data['contact']['total']>0))
		{
		$html.='<form><table>';
		for ($i=1;$i<=$data['contact']['total'];$i++)
			{
			$html.='<tr><th colspan="4" class="titre">';
			$html.= ($i==1) ? '1ère personne' : ($i).'ème personne' ;
			$html.='<tr><th>Nom :</th><td>'.$data['contact'][$i]['nom'].'</td><th>Prénom :</th><td>'.$data['contact'][$i]['prenom'].'</td></tr>';
			$html.='<tr><th>Adresse :</th><td>'.$data['contact'][$i]['adresse'].', '.$data['contact'][$i]['numero'].'</td><th>CP + ville :</th><td>'.$data['contact'][$i]['CP'].' '.$data['contact'][$i]['ville'].'</td></tr>';
			$html.='<tr><th>Numéros d\'appel :</th><td>'.$data['contact'][$i]['tel'].'</td><td>'.$data['contact'][$i]['tel2'].'</td><td class=noborder></td></tr>';
			$html.='</th></tr>';
			}
		$html.='</table></form>';
		}


	//DONNEES DU DEMANDEUR
	while ($row=$data['demandeur']->fetch())
		{
		$html.='<form><table>';
		$html.='<tr><th colspan="4" class=titre>Demandeur</th></tr>';
		$html.='<tr><th>Nom et prénom :</th><td>'.$row['nom_dem'].' '.$row['prenom_dem'].'</td><th>Date de naissance :</th><td>'.$this->datefr($row['dn_dem']).'</td></tr>';
		$html.='<tr><th>Téléphone :</th><td>'.$row['tel_dem'].'</td><th>GSM :</th><td>'.$row['gsm_dem'].'</td></tr>';
		$html.='<tr><th>Mail :</th><td>'.$row['mail_dem'].'<td class=noborder colspan="2"></td></tr>';
		}
	$html.='</table></form>';
	$this->afficheHtml($html);
	}

public function showPassages($data){
	$html='<h2>Passages réalisés</h2>';
	while ($row=$data['pers']->fetch()){
		$html.='<h4>Demandeur : '.$row['nom_dem'].' '.$row['prenom_dem'].'</h4>';
	}
	$html.='<table>';
	$html.='<tr><th>Date - heure</th><th>Commentaire</th><td class="noborder"></td></tr>';
	$i=0;
	while ($row=$data['sql']->fetch()){
		$html.='<tr><td>'.$this->datefr($row['date_heure'],1).'</td><td>'.$row['commentaire'].'</td><td><a href="index.php?component=vacancier&action=delRowById&idVac='.$data['idVac'].'&time='.$row['date_heure'].'">Supprimer cette ligne</a></tr>';
		$retour=$row['vac_dateRetour'];
		$i++;
	}
	$html.=($i==0) ? '<tr><td colspan="2">Aucun enregistrement</td></tr>' : '';
	$html.='<tr><th colspan="3" class="sstitre" onclick="addRowToCr(\''.$data['idVac'].'\');">Ajouter une ligne</th></tr>';
	$html.='</table>';
	$html.='<div id="rowToAdd"></div>';
	if ((isset($retour)) AND ($retour<date('Y-m-d'))){
	$html.='<a href="index.php?component=vacancier&action=doCR&idVac='.$data['idVac'].'" target="_blank">Générer courrier</a><br /><a href="index.php?component=vacancier&action=vacSend&idVac='.$data['idVac'].'">Signaler comme "Envoyé" et retour sélection vacancier</a><br />';
		}
	$html.='<a href="index.php?component=vacancier&action=selectCR">Retour s&eacute;lection vacancier</a>';
	$this->afficheHtml($html);
}
}
?>
