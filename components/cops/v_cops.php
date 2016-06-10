<?php

class VCops extends VBase {

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
	$this->appli->jScript= '<script type="text/javascript" src="./js/cops.js"></script>';
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
	
private function dateHrToDateUS($date)
	{
	$split = explode(" ",$date);
	$date = explode("-",$split[0]);
	
	$a=$date[0];
	$mois=$date[1];
	$j=$date[2];
	
	$time = $split[1];
	
	$split2=explode(":",$time);
	$h=$split2[0];
	$m=$split2[1];
	
	return $j.'/'.$mois.'/'.$a.' '.$h.':'.$m;
	}
	
private function getDateFromDateTimeUS($date)
	{
	$split=explode(" ",$date);
	return $split[0];
	}
	
private function getTimeFromDateTimeUS($date)
	{
	$split=explode(" ",$date);
	return $split[1];
	}	
	
public function showMenu($level)
	{
	$html='';
	if (($level=='5') || ($level=='10') || ($level=='20') || ($level=='30') || ($level=='50'))
		{
	/*	$html.='<h2>Accès "Lecteur"</h2>';
		$html.='Normalement aucun accès ne doit être configuré pour ce niveau car cette partie n\'est destinée qu\'à l\'encodage.';
	*/	
		$html.='<a href=?component=cops&action=listing>Liste</a> des infos COPS<br />';
		}
	
	if (($level=='10') || ($level=='20') || ($level=='30') || ($level=='50'))
		{
//		$html.='<h2>Accès "Editeur"</h2>';
		$html.='<a href=?component=cops&action=newInfoCops>Création</a> d\'une info COPS<br />';
		// $html.='<a href=?component=cops&action=listing>Liste</a> des infos COPS<br />';
		$html.='<a href=index.php?component=cops&action=editInfoCops>Editer</a> une info COPS<br />';
		$html.='<a href=index.php?component=cops&action=gstMsgs>Gérer</a> les infos HOT ! proposées<br />';
		}
		
	if (($level=='20') || ($level=='30') || ($level=='50'))
		{
	/*	$html.='<h2>Accès "Gestionnaire"</h2>';
		$html.='Création, suppression et modifications d\'infos COPS';
		
	*/	}
		
	if (($level=='30') || ($level=='50'))
		{
	/*	$html.='<h2>Accès "Administrateur"</h2>';
		$html.='Création, suppression et modifications des infos COPS + gestion des sections et catégories';
	*/	}
		
	$this->afficheHtml($html);
	$this->appli->latestNews='essai';
	}
	
public function newInfoCops($sections,$from='',$list='')
	{
	$html='<h2>Création d\'une nouvelle fiche COPS</h2>';
	if ($from=='step2')
		{
		$html.='Enregistrement nouvelle fiche : Opération réussie !';
		}
	$html.='<form method=post action=?component=cops&amp;action=newInfoCops&amp;step=1><table>';
	$html.='<tr><th width=25%>Section</th><td width=25%><select name=getSection id=get_Section onchange=getCateg();><option value=""></option>';
	while ($row=$sections->fetch())
		{
		$html.='<option value="'.$row['id_section'].'">'.$row['denomination'].'</option>';
		}
	$html.='</td><th width=25%>Catégorie</th><td width=25%><div id=getSection></div></td></tr></table>';
	// $html.='<tr>';
	$html.='<div id=laSuite>';
	$html.='<br />Reprise d\'une info COPS en vue d\'en créer une nouvelle';
	
//****

	$i=0;
	//LISTE DES INFOS COPS EXISTANTES POUR REPRISE EVENTUELLE
	$html.='<h3>Fiches encodées</h3>';
	$html.='<table>';
	$html.='<tr><th width="18%"><a href="index.php?component=cops&action=newInfoCops&tri=DateInD"><img src="'.MEDIA.'/icons/down.png" height="15"></a> Date d\'insertion <a href="index.php?component=cops&action=newInfoCops&tri=DateInU"><img src="'.MEDIA.'/icons/up.png" height="15"></a></th><th width="15%">Section</th><th width="15%">Catégorie</th><th width="15%">Texte</th><th width="15%"><a href="index.php?component=cops&action=newInfoCops&tri=DateDebD"><img src="'.MEDIA.'/icons/down.png" height="15"></a> Début <a href="index.php?component=cops&action=newInfoCops&tri=DateDebU"><img src="'.MEDIA.'/icons/up.png" height="15"></a></th><th width="15%"><a href="index.php?component=cops&action=newInfoCops&tri=DateFinD"><img src="'.MEDIA.'/icons/down.png" height="15"></a> Fin <a href="index.php?component=cops&action=newInfoCops&tri=DateFinU"><img src="'.MEDIA.'/icons/up.png" height="15"></a></th><td class="noborder"></td></tr>';
	while ($row=$list['sql']->fetch())
		{
		$html.='<tr><td>'.$this->datefr($row['DHIn']).'</td><td>'.$row['DenomSection'].'</td><td>'.$row['DenomCateg'].'</td><td>'.$row['TextInfo'].'</td><td>'.$this->datefr($row['DHStart']).'</td><td>'.$this->datefr($row['DHEnd']).'</td><td><a href="index.php?component=cops&action=newFicheById&idFiche='.$row['IDFiche'].'">Reprendre</a></td></tr>';
		$i++;
		}
	$html.='</table>';
	if ($list['page']>1)
		{
		$html.='<a href=?component=cops&action=newInfoCops&page='.($list['page']-1).'><img src="'.MEDIA.'/icons/leftArrow.png" height="15"></a>';
		}
	$html.=' Page : '.$list['page'].' ';
	if ($i>9)
		{
		$html.='<a href=?component=cops&action=newInfoCops&page='.($list['page']+1).'><img src="'.MEDIA.'/icons/rightArrow.png" height="15"></a>';
		}
	$html.='</div>';
//***
	$html.='</form>';
	$this->afficheHtml($html);
	}
	
public function newFicheStep2($data)
	{
	//Construction des options disponibles pour le menu "Type liaison"
	$optionsLiaison='';
	while ($row=$data['liaisons']->fetch())
		{
		$optionsLiaison.='<option value="'.$row['id_liaison'].'">'.$row['denomination'].'</option>';
		}
	//***************************************************************//

	//Construction des options disponibles pour la liste de rues
	$optionsRues='';
	while ($row=$data['rues']->fetch())
		{
		$optionsRues.='<option value="'.$row['IdRue'].'">'.$row['NomRue'].'</option>';
		}
	//*********************************************************//
	
		
	$html='<h2>Création d\'une nouvelle fiche COPS</h2>';

	$html.='<form method=post enctype="multipart/form-data" name=newFicheStep2 action="?component=cops&amp;action=newInfoCops&amp;step=2"><table>';
	for ($i=0;$i<$data['qPers'];$i++)
		{
		$html.= ($i==0) ? '<tr><th colspan="4" class=titre>Liaison 1ère personne</th></tr>' : '<tr><th colspan="4" class=titre>Liaison '.($i+1).'ème personne</th></tr>';
		$html.=$this->formNewPersonne($i,$optionsLiaison);
		}
	for ($i=0;$i<$data['qVV'];$i++)
		{
		$html.= ($i==0) ? '<tr><th colspan="4" class=titre>Liaison 1er véhicule</th></tr>' : '<tr><th colspan="4" class=titre>Liaison '.($i+1).'ème véhicule</th></tr>';
		$html.=$this->formNewVV($i,$optionsLiaison);
		}
	for ($i=0;$i<$data['qLDits'];$i++)
		{
		$html.= ($i==0) ? '<tr><th colspan="4" class=titre>Liaison 1er lieudit</th></tr>' : '<tr><th colspan="4" class=titre>Liaison '.($i+1).'ème lieudit</th></tr>';
		$html.=$this->formNewLDit($i,$optionsLiaison);
		}
	for ($i=0;$i<$data['qCommerces'];$i++)
		{
		$html.= ($i==0) ? '<tr><th colspan="4" class=titre>Liaison 1er commerce</th></tr>' : '<tr><th colspan="4" class=titre>Liaison '.($i+1).'ème commerce</th></tr>';
		$html.=$this->formNewCommerce($i,$optionsLiaison,$optionsRues);
		}
	for ($i=0;$i<$data['qTxt'];$i++)
		{
		$html.= ($i==0) ? '<tr><th colspan="4" class=titre>Liaison 1er texte libre</th></tr>' : '<tr><th colspan="4" class=titre>Liaison '.($i+1).'ème texte libre</th></tr>';
		$html.=$this->formNewTxt($i);		
		}
	for ($i=0;$i<$data['qPics'];$i++)
		{
		$html.= ($i==0) ? '<tr><th colspan="4" class=titre>Liaison 1ère photo</th></tr>' : '<tr><th colspan="4" class=titre>Liaison '.($i+1).'ème photo</th></tr>';
		$html.=$this->formNewPic($i);			
		}
	$html.='<tr><td class=noborder colspan="4"><input type=hidden name=idFiche value="'.$data['idFiche'].'"><input type=hidden name=qPics value='.$data['qPics'].'><input type=hidden name=qPers value='.$data['qPers'].'><input type=hidden name=qTxt value='.$data['qTxt'].'><input type=hidden name=qCommerces value='.$data['qCommerces'].'><input type=hidden name=qLDits value='.$data['qLDits'].'><input type=hidden name=qVV value='.$data['qVV'].'><input type=submit value="Enregistrer"></td></tr></table>';
	$this->afficheHtml($html);
	}
	
public function formNewPersonne($i,$liaisons)
	{
	$html='<tr><th>Nom :</th><td><input type=text name=persnom'.$i.' autofocus style="text-transform:uppercase" required></td><th>Prénom :</th><td><input type=text name=persprenom'.$i.' required></tr>';
	$html.='<tr><th>Date de naissance :</th><td><input type=date name=persnaissance'.$i.'><th colspan="2"><input type="hidden" name="MAX_FILE_SIZE" value="5000000" />Photo : <input type="file" name="imagePers_'.$i.'" id="imagePers_'.$i.'"/></th></tr>';
	$html.='<tr><th>CP + Ville :</th><td><input type=text name=persCP'.$i.' size="6" placeholder="CP"><input type=text name=persville'.$i.' placeholder="Ville"></td><th>Rue + numéro :</th><td><input type=text name=persrue'.$i.' placeholder="Rue, Avenue, Bd, ..."><input type=text name=persnum'.$i.' maxlength="6" size="6" placeholder="Num"></td></tr>';
	$html.='<tr><th>Pays :</th><td><input type=text name=perspays'.$i.' value="Belgique"></td><th>Type liaison :</th><td><select name=persliaison'.$i.' required><option></option>';
	$html.=$liaisons;
	$html.='</select></td></tr>';
	$html.='<tr><th>Descriptif :</th><td colspan="3"><textarea rows="3" cols="50" name=persdescriptif'.$i.'  placeholder="Entrez ici une description"></textarea></td></tr>';
	return $html;
	}
	
public function formNewVV($i,$liaisons)
	{
	$html='<tr><th>Marque :</th><td><input type=text name=VVmarque'.$i.' style="text-transform:uppercase" autofocus></td><th>Modèle</th><td><input type=text name=VVmodele'.$i.'></td></tr>';
	$html.='<tr><th>Immatriculation :</th><td><input type=text name=VVimmatriculation'.$i.'></td><th>Chassis :</th><td><input type=text name=VVchassis'.$i.' style="text-transform:uppercase"></td></tr>';
	$html.='<tr><th>Couleur :</th><td><input type=text name=VVcouleur'.$i.'></td><th>Type liaison :</th><td><select name=VVliaison'.$i.' required><option></option>';
	$html.=$liaisons;
	$html.='</select></td></tr>';
	$html.='<tr><th>Descriptif :</th><td colspan="3"><textarea rows="3" cols="50" name=VVdescriptif'.$i.' placeholder="Entrez ici une description"></textarea></td></tr>';
	return $html;
	}

public function formNewLDit($i,$liaisons)
	{
	$html='<tr><th>Dénomination :</th><td><input type=text name=LDDenom'.$i.' required autofocus></td><th>Type liaison :</th><td><select name=LDliaison'.$i.' required><option></option>';
	$html.=$liaisons;
	$html.='</select></td></tr>';
	return $html;
	}

public function formNewCommerce($i,$liaisons,$rues)
	{
	$html='<tr><th>Nom :</th><td><input type=text name=comnom'.$i.' required autofocus></td><th>Type liaison :</th><td><select name=comliaison'.$i.' required><option></option>';
	$html.=$liaisons;
	$html.='</select></td></tr>';
	$html.='<tr><th>CP + Ville :</th><td><input type=text name=comCP'.$i.' size="6" placeholder="CP" required><input type=text name=comville'.$i.' placeholder="Ville" required></td><th>Rue :</th><td><select name=comrue'.$i.' required><option value=""></option>';
	$html.=$rues;
	$html.='</select><input type=text name=comnum'.$i.' maxlength="6" size="6" placeholder="Num"></td></tr>';
	$html.='<tr><th>Descriptif :</th><td colspan="3"><textarea rows="3" cols="50" name=comdescriptif'.$i.' placeholder="Entrez ici une description"></textarea></td></tr>';
	return $html;
	}
	
public function formNewTxt($i)
	{
	$html='<tr><th colspan="2">Titre :</th><td colspan="2"><input type=text size="40" name=txttitre'.$i.' autofocus required></td></tr>';
	$html.='<tr><th colspan="4">Texte :</th></tr>';
	$html.='<tr><td colspan="4" class="noborder"><textarea rows="5" cols="80" name=txttxt'.$i.' placeholder="Tapez ici votre texte"></textarea></td></tr>';
	return $html;
	}
	
public function formNewPic($i)
	{
	$html='<tr><th colspan="4"><input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>Photo :<input type="file" name="imageSup_'.$i.'" id="imageSup_'.$i.'"/></th></tr>';
	$html.='<tr><th>Commentaire :</th><td colspan="3"><textarea rows="2" cols="50" name=picComment'.$i.' placeholder="Entrez ici un commentaire"></textarea></td></tr>';
	return $html;
	}
	
public function showListInfosCops($data, $from='0') /* 15.09.2015 ajout paramètre $from) */
	{
	$i=0;
	if($from=='hot'){
		$html='<h2>Listing des infos HOT encodées</h2>';
	}
	else{
		$html='<h2>Listing des infos COPS encodées</h2>';
	}
	// $html.=$data['sql'];
	$html.='<table><tr>';
	if ($from!='hot'){
		$html.='<th>Section</th><th>Catégorie</th>';
	}
	$html.='<th>Texte</th><th>Début</th><th>Fin</th><td class="noborder"></td></tr>';
	while ($row=$data['sql']->fetch())
		{
		$html.='<tr';
		$html.=($row['IDCateg']==31) ? ' bgcolor="orange"' : '';
		$html.=($row['IDCateg']==32) ? ' bgcolor="#99CCFF"' : '';
		$html.=($row['IDCateg']==33) ? ' bgcolor="#ADFF85"' : '';
		$html.='>';
		if ($from!='hot'){
			$html.='<td>'.$row['DenomSection'].'</td><td>'.$row['DenomCateg'].'</td>';
		}
		$html.='<td>'.$row['TextInfo'].'</td><td>'.$this->dateFr($row['DHStart'],'1').'</td><td>'.$this->dateFr($row['DHEnd'],'1').'</td><td><a href="index.php?mode=pop&component=cops&amp;action=moreInfos&amp;idFiche='.$row['IDFiche'].'" target="_blank">Plus d\'infos</a></td></tr>';
		$i++;
		}
	$html.='</table><br />';
	if ($from!='hot'){
		if ($data['page']>1)
			{
			$html.='<a href=?component=cops&action=listing&page='.($data['page']-1).'><img src="'.MEDIA.'/icons/leftArrow.png" height="15"></a>';
			}
		$html.=' Page : '.$data['page'].' ';
		if ($i>9)
			{
			$html.='<a href=?component=cops&action=listing&page='.($data['page']+1).'><img src="'.MEDIA.'/icons/rightArrow.png" height="15"></a>';
			}
	}
	$this->afficheHtml($html);
	}
	
public function showInfoFiche($data)
	{
	// $html='<h2>Détails d\'une fiche COPS</h2>';
	$html='<form method=post action="?component=cops&amp;action=blah"><table>';
	while ($row=$data['fiche']->fetch())
		{
		$html.='<tr><th class="titre" colspan="4">'.$row['texteInfo'].'</th></tr>';
		// $html.='<tr><th>Section et catégorie :</th><td>'.$row['DenomSection'].' <img src="../media/icons/rightArrow.png" height="15"> '.$row['DenomCateg'].'</td><th>Interaction ?</th><td>';
		// $html.=($row['interaction']=='O') ? 'Oui' : 'Non';
		// $html.='</td></tr>';
		// $html.='<tr><th>Validité :</th><td colspan="3">Du '.$this->dateFr($row['date_debut'],'1').' au '.$this->dateFr($row['date_fin'],'1').'</td></tr>';
		// $html.='<tr><th>Encodage :</th><td colspan="3">Le '.$this->dateFr($row['dateHr_encodage'],'1').' par '.$row['nom'].' '.$row['prenom'].'.</td></tr>';
		}
	$html.='</table><hr>';

	//PERSONNES
	if (($data['pers']['q'])>0)
		{
		$this->appli->picBio='';
		$html.='<table>';
		$j=1;
		while($row=$data['pers']['sql']->fetch())
			{
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=(($j==1)) ? 'Liaison avec 1ère personne ('.$row['liaison'].')' : 'Liaison avec '.($j).'ème personne ('.$row['liaison'].')';
			$html.='</th></tr>';
			$html.='<tr><th width="25%">Nom et prénom :</th><td>'.$row['nom'].' '.$row['prenom'].'</th><th>Date de naissance :</th><td>'.$this->dateFr($row['date_naissance']).'</td></tr>';
			$html.='<tr><th>Adresse :</th><td colspan="3">'.$row['pays'].', '.$row['CP'].' '.$row['ville'].', '.$row['adresse'].', '.$row['numero'].'</td></tr>';
			$html.='<tr><th>Infos complémentaires :</th><td colspan="3">'.$row['descriptif'].'</td></tr>';
			$html.=($row['photo']!="") ? '<tr><th colspan="4"><a href="'.MEDIA.'images/'.$row['photo'].'" target="_blank">Photo</a></th></tr>' : '';// :</th><img src='.MEDIA.'/images/'.$row['photo'].' width="75%"></td></tr>';
			$this->appli->picBio.=($row['photo']=='') ? '<img src="'.MEDIA.'icons/who-md.png" width="100%">' : '<img src="'.MEDIA.'images/'.$row['photo'].'" width="100%"><br /><hr>';
			
			// echo '<img src="'.MEDIA.'images/'.$row['photo'].'" width="100%">';
			$j++;
			}			
		$html.='</table>';
		}
	//------------------------------------------------

	//VEHICULES
	if (($data['vv']['q'])>0)
		{
		$html.='<table>';
		$j=1;
		while($row=$data['vv']['sql']->fetch())
			{
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=(($j==1)) ? 'Liaison avec 1er véhicule ('.$row['liaison'].')' : 'Liaison avec '.($j).'ème véhicule ('.$row['liaison'].')';
			$html.='</th></tr>';
			$html.='<tr><th>Marque et modèle :</th><td>'.$row['marque'].' '.$row['modele'].'</th><th>Couleur :</th><td>'.$row['couleur'].'</td></tr>';
			$html.='<tr><th>Immatriculation :</th><td><b>'.$row['immatriculation'].'</b></td><th>N° chassis :</th><td>'.$row['chassis'].'</td></tr>';
			$html.='<tr><th>Infos complémentaires :</th><td colspan="3">'.$row['descriptif'].'</td></tr>';
			$j++;
			}			
		$html.='</table>';
		}
	//----------------------------------------------
	
	//LIEUDITS
	if (($data['ld']['q'])>0)
		{
		$html.='<table>';
		$j=1;
		while($row=$data['ld']['sql']->fetch())
			{
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=(($j==1)) ? 'Liaison avec 1er lieudit ('.$row['liaison'].')' : 'Liaison avec '.($j).'ème lieudit ('.$row['liaison'].')';
			$html.='</th></tr>';
			$html.='<tr><th width="25%">Dénomination :</th><td colspan="3">'.$row['description'].'</td></tr>';
			$j++;
			}			
		$html.='</table>';	
		}
	//----------------------------------------------------
	
	//COMMERCES
	if (($data['com']['q'])>0)
		{
		$html.='<table>';
		$j=1;
		while($row=$data['com']['sql']->fetch())
			{
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=(($j==1)) ? 'Liaison avec 1er commerce ('.$row['liaison'].')' : 'Liaison avec '.($j).'ème commerce ('.$row['liaison'].')';
			$html.='</th></tr>';
			$html.='<tr><th width="25%">Dénomination :</th><td>'.$row['nom'].'</td><th>Descriptif :</th><td>'.$row['descriptif'].'</td></tr>';
			$html.='<tr><th>Adresse :</th><td colspan="3">'.$row['CP'].' '.$row['ville'].', '.$row['NomRue'].' n° '.$row['numero'].'</td></tr>';
			$j++;
			}			
		$html.='</table>';	
		}
	//----------------------------------------------------	

	//TEXTES LIBRES
	if (($data['tl']['q'])>0)
		{
		$html.='<table>';
		$j=1;
		while($row=$data['tl']['sql']->fetch())
			{
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=(($j==1)) ? 'Liaison avec 1er texte libre' : 'Liaison avec '.($j).'ème texte libre';
			$html.='</th></tr>';
			$html.='<tr><th colspan="4">'.$row['titre'].'</th></tr>';
			$html.='<tr><td colspan="4">'.$row['texte'].'</td></tr>';
			$j++;
			}			
		$html.='</table>';	
		}
	//----------------------------------------------------	
	
	//PHOTOS
	if (($data['pic']['q'])>0)
		{
		$html.='<table>';
		$j=1;
		while($row=$data['pic']['sql']->fetch())
			{
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=(($j==1)) ? 'Liaison avec 1ère photo' : 'Liaison avec '.($j).'ème photo';
			$html.='</th></tr>';
			$html.='<tr><td colspan="4">'.$row['commentaire'].'</th></tr>';
			$html.='<tr><td colspan="4"><a href="'.MEDIA.'images/'.$row['lien'].'" target="_blank">Photo</a></td></tr>';
			$j++;
			}			
		$html.='</table>';	
		}
	//----------------------------------------------------	

	$html.='</form>';
	$this->afficheHtml($html);
	}

public function editList($data)
	{
	$i=0;
	$html='<h2>Edition des infos COPS</h2>';
	$html.='<h3>Infos encodées</h3>';
	$html.='<table>';
	$html.='<tr><th width="18%"><a href="index.php?component=cops&action=editInfoCops&tri=DateInD"><img src="'.MEDIA.'/icons/down.png" height="15"></a> Date d\'insertion <a href="index.php?component=cops&action=editInfoCops&tri=DateInU"><img src="'.MEDIA.'/icons/up.png" height="15"></a></th><th width="15%">Section</th><th width="15%">Catégorie</th><th width="15%">Texte</th><th width="15%"><a href="index.php?component=cops&action=editInfoCops&tri=DateDebD"><img src="'.MEDIA.'/icons/down.png" height="15"></a> Début <a href="index.php?component=cops&action=editInfoCops&tri=DateDebU"><img src="'.MEDIA.'/icons/up.png" height="15"></a></th><th width="15%"><a href="index.php?component=cops&action=editInfoCops&tri=DateFinD"><img src="'.MEDIA.'/icons/down.png" height="15"></a> Fin <a href="index.php?component=cops&action=editInfoCops&tri=DateFinU"><img src="'.MEDIA.'/icons/up.png" height="15"></a></th><td class="noborder"></td></tr>';
	while ($row=$data['sql']->fetch())
		{
		$html.='<tr><td>'.$this->datefr($row['DHIn']).'</td><td>'.$row['DenomSection'].'</td><td>'.$row['DenomCateg'].'</td><td>'.$row['TextInfo'].'</td><td>'.$this->datefr($row['DHStart']).'</td><td>'.$this->datefr($row['DHEnd']).'</td><td><a href="index.php?component=cops&action=editFiche&idFiche='.$row['IDFiche'].'">Editer</a></td></tr>';
		$i++;
		}
	$html.='</table>';
	if ($data['page']>1)
		{
		$html.='<a href=?component=cops&action=editInfoCops&page='.($data['page']-1).'><img src="'.MEDIA.'/icons/leftArrow.png" height="15"></a>';
		}
	$html.=' Page : '.$data['page'].' ';
	if ($i>9)
		{
		$html.='<a href=?component=cops&action=editInfoCops&page='.($data['page']+1).'><img src="'.MEDIA.'/icons/rightArrow.png" height="15"></a>';
		}	
	$this->afficheHtml($html);
	}
	
public function editFiche($data,$section,$categ,$implication,$rues)
	{
	$street=array();
	$j=0;
	while ($rowz=$rues->fetch())
		{
		$street[$j]['id']=$rowz['IdRue'];
		$street[$j]['nom']=$rowz['NomRue'];
		$j++;
		}
	$street['ttl']=$j;
	
	$opt=array();
	$j=0;
	while($rowy=$implication->fetch())
		{
		$opt[$j]['id']=$rowy['id_liaison'];
		$opt[$j]['denom']=$rowy['denomination'];
		$j++;
		}
	$opt['ttl']=$j;
	
	$optdeno = '';              
	$options=$implication->fetchAll();
        foreach($options as $value)
        {
        $optdeno.='<option value="'.$value['id_liaison'].'">'.$value['denomination'].'</option>';
        }
    $implication->closeCursor();
	
	$html='<h2>Edition d\'une fiche COPS</h2>';
	$html.='<table>';
	$fiche=$data['fiche']->fetchAll();
	foreach($fiche as $row)
		{
		$html.='<tr><th class="titre" colspan="4">Section et catégorie</th></tr>';
		$html.='<tr><th width="25%">Section :</th><td width="25%">';
		//SELECTION SECTION
		$html.='<select name="getSection" id="get_Section" onchange="getCateg();")><option value=""></option>';
		$sections=$section->fetchAll();
		foreach($sections as $rowa)
			{
			$html.='<option value="'.$rowa['id_section'].'" ';
			$html.=($rowa['denomination']==$row['DenomSection']) ? 'selected' : '';
			$html.='>'.$rowa['denomination'].'</option>';
			$section->closeCursor();
			}
		$html.='</select>';
		//SELECTION CATEGORIE
		$html.='</td><th width="25%">Catégorie :</th><td width="25%"><div id=getSection>';
		$html.='<select name=categ id=get_Categ>';
		$categs=$categ->fetchAll();
		foreach($categs as $rowb)
			{
			$html.='<option value="'.$rowb['id_categ'].'" ';
			$html.=($rowb['id_categ']==$row['id_categ']) ? 'selected' : '';
			$html.='>'.$rowb['denomination'].'</option>';
			$categ->closeCursor();
			}
		$html.='</select>';
		$html.='</div></td></tr>';
		$html.='<tr><td class="noborder" colspan="4"><div id="repSecCat"><input type="button" onclick="recSecCateg(\''.$row['id_fiche'].'\');" value="Enregistrer modifications &quot;Section et catégorie&quot;"></div></td></tr>';
		
		//INTERACTION
		$html.='<tr><th class="titre" colspan="4">Interaction et date d\'encodage</th></tr>';
		$html.='<tr><th>Interaction ?</th><td>';
		$html.=($row['interaction']=='O') ? '<input type="radio" id="interactionO" name="interaction" value="O" onchange="recInteraction(\''.$row['id_fiche'].'\');" checked> Oui ' : '<input type="radio" id="interactionO" onchange="recInteraction(\''.$row['id_fiche'].'\');" name="interaction" value="O"> Oui ';
		$html.=($row['interaction']=='N') ? '<input type="radio" id="interactionN" name="interaction" value="N" onchange="recInteraction(\''.$row['id_fiche'].'\');" checked> Non ' : '<input type="radio" id="interactionN" name="interaction" onchange="recInteraction(\''.$row['id_fiche'].'\');" value="N"> Non ';
		//DATE D'ENCODAGE
		$html.='</td><th>Date d\'encodage :</th><td>'.$this->datefr($row['dateHr_encodage']).'</td></tr>';
		//VALIDITE
		$html.='<tr><th class="titre" colspan="4">Validité de la fiche</th></tr>';
		$html.='<tr><th>Début :</th><td><input name="dateDebut" id="dateDebut" type="date" value="'.$this->getDateFromDateTimeUS($row['date_debut']).'" style="width:130px;"><input type="time" name="heureDebut" id="heureDebut" value="'.$this->getTimeFromDateTimeUS($row['date_debut']).'" style="width:73px;"></td><th>Date fin :</th><td><div id="datefin"><input type="date" name="dateFin" id="dateFin" value="'.$this->getDateFromDateTimeUS($row['date_fin']).'" style="width:130px;"><input type="time" name="heureFin" id="heureFin" value="'.$this->getTimeFromDateTimeUS($row['date_fin']).'" style="width:73px;"></div></td></tr>';
		$html.='<tr><td class="noborder" colspan="4"><input type="button" onclick="recValidite(\''.$row['id_fiche'].'\');" value="Enregistrer changements"> ou <input type="button" onclick="endNowFiche(\''.$row['id_fiche'].'\');" value="Clôturer cette fiche maintenant"></td></tr>';
		//LIAISON AVEC PERSONNES
		$html.='<tr><th class="titre" colspan="4" id="personne">Liaison avec personnes</th></tr>';
		if ($data['pers']['q']==0)
			{
			$html.='<tr><td colspan="4">Aucune personne n\'est encodée pour cette fiche</td></tr>';
			}
		else
			{
			$i=0;
			$personne=$data['pers']['sql']->fetchAll();
			foreach($personne as $rep)
				{
				$html.=($i==0) ? '<tr><th class="sstitre" colspan="4">1ère personne</th></tr>' : '<tr><th class="sstitre" colspan="4">'.($i+1).'ème personne</th></tr>';
				$html.='<tr><th>Nom :</th><td><input type="text" name="nom'.$i.'" id="nom'.$i.'" value="'.$rep['nom'].'" style="text-transform:uppercase;"></td><th>Prénom :</th><td><input type="text" name="prenom'.$i.'" id="prenom'.$i.'" value="'.$rep['prenom'].'"></td></tr>';
				$html.='<tr><th>Date de naissance :</th><td><input type="date" name="DN'.$i.'" id="DN'.$i.'" value="'.$rep['date_naissance'].'"></td><th>Pays de résidence :</th><td><input type="text" name="pays'.$i.'" id="pays'.$i.'" value="'.$rep['pays'].'"></td></tr>';
				$html.='<tr><th>Adresse :</th><td colspan="3">CP : <input type="text" name="CP'.$i.'" id="CP'.$i.'" value="'.$rep['CP'].'" style="width:15%;"> Ville : <input type="text" name="ville'.$i.'" id="ville'.$i.'" value="'.$rep['ville'].'" style="width:15%;"> Rue : <input type="text" name="rue'.$i.'" id="rue'.$i.'" value="'.$rep['adresse'].'" style="width:25%;"> Numéro : <input type="text" name="num'.$i.'" id="num'.$i.'" value="'.$rep['numero'].'" style="width:10%;"></td></tr>';
				$html.='<tr><th>Infos complémentaires :</th><td colspan="3"><input type="text" name="desc'.$i.'" id="desc'.$i.'" value="'.$rep['descriptif'].'" style="width:90%;"></td></tr>';
				$html.='<tr><th>Implication :</th><td><select name="implication'.$i.'" id="implication'.$i.'">';
				// $html.=$optdeno; //ICI
				for ($j=0;$j<$opt['ttl'];$j++)
					{
					$html.='<option value="'.$opt[$j]['id'].'"';
					if ($rep['liaison']==$opt[$j]['denom'])
						{
						$html.=' selected';
						}
					$html.='>'.$opt[$j]['denom'].'</option>';
					}
				$html.='</select></td></tr>';
				$html.='</select></td><th>Photo :</th><td>';
				if ((isset($rep['photo']))&&($rep['photo']!=''))
					{
					$html.='<a href="'.MEDIA.'images/'.$rep['photo'].'" target="_blank"><img src="'.MEDIA.'images/'.$rep['photo'].'" style="width:50%;"></img></td></tr>';
					}
				else
					{
					$html.='Photo non disponible';
					}
				$html.='<tr><td colspan="4" class="noborder"><input type="button" value="Enregistrer modifications" onclick="recModifsPersonnes(\''.$i.'\',\''.$rep['id_personne'].'\',\''.$row['id_fiche'].'\');"> ou <input type="button" value="Supprimer cette personne" onclick="delPersonne(\''.$rep['id_personne'].'\',\''.$row['id_fiche'].'\');"</td></tr>';
				$data['pers']['sql']->closeCursor();
				$i++;
				}
			}
		$html.='<tr><td colspan="4" class="noborder"><input type="button" value="Ajouter une personne" onclick="addPersonne(\''.$row['id_fiche'].'\');"></td></tr>';
		$html.='</table>';
		$html.='<div id="newPers"></div>';
		$html.='<table>';
		//VEHICULES
		$html.='<tr><th class="titre" colspan="4" id="vehicules">Liaison avec véhicules</th></tr>';
		if ($data['vv']['q']==0)
			{
			$html.='<tr><td colspan="4">Aucun véhicule d\'encodé pour cette fiche</td></tr>';
			}
		else
			{
			$i=0;
			$vehicule=$data['vv']['sql']->fetchAll();
			foreach($vehicule as $rowc)
				{
				$html.=($i==0) ? '<tr><th class="sstitre" colspan="4">1er véhicule</th></tr>' : '<tr><th class="sstitre" colspan="4">'.($i+1).'ème véhicule</th></tr>';
				$html.='<tr><th width="25%">Marque et modèle :</th><td width="25%"><input type="text" name="marque'.$i.'" id="marque'.$i.'" value="'.$rowc['marque'].'" style="width:50%;text-transform:uppercase;"><input type="text" name="modele'.$i.'" id="modele'.$i.'" value="'.$rowc['modele'].'" style="width:50%;"></td><th width="25%">Couleur :</th><td width="25%"><input type="text" name="couleur'.$i.'" id="couleur'.$i.'" value="'.$rowc['couleur'].'"></td></tr>';
				$html.='<tr><th>Immatriculation :</th><td><input type="text" name="immat'.$i.'" id="immat'.$i.'" value="'.$rowc['immatriculation'].'" style="text-transform:bold;"></td><th>N° chassis :</th><td><input type="text" name="chassis'.$i.'" id="chassis'.$i.'" value="'.$rowc['chassis'].'"></td></tr>';
				$html.='<tr><th>Infos complémentaires :</th><td><input type="text" name="infos'.$i.'" id="infos'.$i.'" value="'.$rowc['descriptif'].'"></td><th>Implication :</th><td><select name="implicationvv'.$i.'" id="implicationvv'.$i.'"><option value="-1"></option>';
				// $html.=$optdeno;
				for ($j=0;$j<$opt['ttl'];$j++)
					{
					$html.='<option value="'.$opt[$j]['id'].'"';
					if ($rowc['liaison']==$opt[$j]['denom'])
						{
						$html.=' selected';
						}
					$html.='>'.$opt[$j]['denom'].'</option>';
					}
				$html.='</select></td></tr>';
				$html.='</select></td></tr>';
				$html.='<tr><td class="noborder" colspan="4"><input type="button" onclick="recModifsVV(\''.$rowc['id_vv'].'\', \''.$row['id_fiche'].'\',\''.$i.'\');" value="Enregistrer modifications"> ou <input type="button" onclick="delVV(\''.$rowc['id_vv'].'\',\''.$row['id_fiche'].'\',\''.$i.'\');" value="Supprimer ce véhicule"></td></tr>';
				$data['vv']['sql']->closeCursor();
				$i++;
				}
			}
		$html.='<tr><td colspan="4" class="noborder"><input type="button" value="Ajouter un véhicule" onclick="addVV(\''.$row['id_fiche'].'\');"></td></tr>';
		$html.='</table>';
		$html.='<div id="newVV"></div>';
		$html.='<table>';
		//LIEUDITS
		$html.='<tr><th class="titre" colspan="2" id="lieudits">Liaison avec lieudits</th></tr>';
		if ($data['ld']['q']==0)
			{
			$html.='<tr><td colspan="2" id="lieudit">Aucun lieudit d\'encodé pour cette fiche</td></tr>';
			}
		else
			{
			$i=0;
			$vehicule=$data['ld']['sql']->fetchAll();
			foreach($vehicule as $rowd)
				{
				$html.=($i==0) ? '<tr><th class="sstitre" colspan="2">1er lieudit</th></tr>' : '<tr><th class="sstitre" colspan="2">'.($i+1).'ème lieudit</th></tr>';
				$html.='<tr><th width="50%">Dénomination :</th><td><input type="text" name="denominationLD'.$i.'" id="denominationLD'.$i.'" value="'.$rowd['description'].'" style="width:90%;"></td></tr>';
				$html.='<tr><th>Implication :</th><td><select name="implicationLD'.$i.'" id="implicationLD'.$i.'" required><option value="-1"></option>';
				// $html.=$optdeno;
				for ($j=0;$j<$opt['ttl'];$j++)
					{
					$html.='<option value="'.$opt[$j]['id'].'"';
					if ($rowd['liaison']==$opt[$j]['denom'])
						{
						$html.=' selected';
						}
					$html.='>'.$opt[$j]['denom'].'</option>';
					}
				$html.='</select></td></tr>';				
				$html.='</select></td></tr>';
				$html.='<tr><td class="noborder" colspan="2"><input type="button" onclick="recModifsLD(\''.$rowd['id_lieudit'].'\',\''.$row['id_fiche'].'\', \''.$i.'\');" value="Enregistrer modifications"> ou <input type="button" onclick="delLD(\''.$rowd['id_lieudit'].'\',\''.$row['id_fiche'].'\',\''.$i.'\');" value="Supprimer ce lieudit"></td></tr>';
				$i++;
				$data['ld']['sql']->closeCursor();
				}
			}
		$html.='<tr><td class="noborder" colspan="2"><input type="button" onclick="addLD(\''.$row['id_fiche'].'\');" value="Ajouter un lieudit"></td></tr>';
		$html.='</table>';
		$html.='<div id="newLD"></div>';
		$html.='<table>';
		//COMMERCES
		$html.='<tr><th class="titre" colspan="4" id="commerce">Liaison avec commerces</th></tr>';
		if($data['com']['q']==0)
			{
			$html.='<tr><td colspan="2">Aucun commerce d\'encodé pour cette fiche</td></tr>';
			}
		else
			{
			$i=0;
			$commerce=$data['com']['sql']->fetchAll();
			foreach($commerce as $rowe)
				{
				$html.=($i==0) ? '<tr><th class="sstitre" colspan="4">1er commerce</th></tr>' : '<tr><th class="sstitre" colspan="4">'.($i+1).'ème commerce</th></tr>';	
				$html.='<tr><th width="25%">Dénomination :</th><td><input type="text" name="denominationCom'.$i.'" id="denominationCom'.$i.'" value="'.$rowe['nom'].'"></td><th>Descriptif :</th><td><input type="text" name="descCom'.$i.'" id="descCom'.$i.'" value="'.$rowe['descriptif'].'"></td></tr>';
				$html.='<tr><th>Adresse :</th><td colspan="3">CP : <input type="text" name="CPCom'.$i.'" id="CPCom'.$i.'" value="'.$rowe['CP'].'" style="width:15%;"> Ville : <input type="text" name="villeCom'.$i.'" id="villeCom'.$i.'" value="'.$rowe['ville'].'" style="width:15%;"> Rue : ';
				$html.='<select name="rueCom'.$i.'" id="rueCom'.$i.'" style="width:25%;">';
				for($j=0;$j<$street['ttl'];$j++)
					{
					$html.='<option value="'.$street[$j]['id'].'"';
					if ($rowe['idRue']==$street[$j]['id'])
						{
						$html.=' selected';
						}
					$html.='>'.$street[$j]['nom'].'</option>';
					}
				$html.='</select> ';
				$html.='Numéro : <input type="text" name="numCom'.$i.'" id="numCom'.$i.'" value="'.$rowe['numero'].'" style="width:10%;"></td></tr>';
				$html.='<tr><th>Implication :</th><td><select name="impCom'.$i.'" id="impCom'.$i.'">';
				for ($j=0;$j<$opt['ttl'];$j++)
					{
					$html.='<option value="'.$opt[$j]['id'].'"';
					if ($rowe['liaison']==$opt[$j]['denom'])
						{
						$html.=' selected';
						}
					$html.='>'.$opt[$j]['denom'].'</option>';
					}
				$html.='</select></td></tr>';
				$html.='<tr><td class="noborder" colspan="4"><input type="button" onclick="recModifsCom(\''.$rowe['id_commerce'].'\',\''.$row['id_fiche'].'\',\''.$i.'\');" value="Enregistrer modifications"> ou <input type="button" onclick="delCom(\''.$rowe['id_commerce'].'\',\''.$row['id_fiche'].'\',\''.$i.'\');" value="Supprimer ce commerce"></td></tr>';

				$i++;
				}
			$data['com']['sql']->closeCursor();
			}
		$html.='<tr><td colspan="4" class="noborder"><input type="button" onclick="addCom(\''.$row['id_fiche'].'\');" value="Ajouter un commerce"></td></tr>';	
		$html.='</table>';
		$html.='<div id="newCom"></div>';
		$html.='<table>';
		//TEXTES LIBRES
		$html.='<tr><th class="titre" colspan="2" id="TL">Liaison avec textes libres</th></tr>';
		if($data['tl']['q']==0)
			{
			$html.='<tr><td colspan="2">Aucun texte libre d\'encodé pour cette fiche</td></tr>';
			}
		else
			{
			$i=0;
			$tl=$data['tl']['sql']->fetchAll();
			foreach($tl as $rowf)
				{
				$html.=($i==0) ? '<tr><th class="sstitre" colspan="4">1er texte libre</th></tr>' : '<tr><th class="sstitre" colspan="4">'.($i+1).'ème texte libre</th></tr>';
				$html.='<tr><th width="25%">Titre :</th><td width="75%"><input type="text" name="titreTxt'.$i.'" id="titreTxt'.$i.'" value="'.$rowf['titre'].'"></td></tr>';
				$html.='<tr><th>Texte :</th><td width="75%"><input type="text" name="TextTxt'.$i.'" id="TextTxt'.$i.'" value="'.$rowf['texte'].'" style="width:90%;"></td></tr>';
				$html.='<tr><td colspan="2" class="noborder"><input type="button" onclick="recModifsTL(\''.$rowf['id_textelibre'].'\',\''.$row['id_fiche'].'\',\''.$i.'\');" value="Enregistrer modifications"> ou <input type="button" onclick="delTL(\''.$rowf['id_textelibre'].'\',\''.$row['id_fiche'].'\',\''.$i.'\');" value="Supprimer ce texte libre"></td></tr>';
				$i++;
				}
			$data['tl']['sql']->closeCursor();
			}
		$html.='<tr><td colspan="4" class="noborder"><input type="button" onclick="addTL(\''.$row['id_fiche'].'\');" value="Ajouter un texte libre"></td></tr>';	
		$html.='</table>';
		$html.='<div id="newTL"></div>';
		$html.='<table>';
		//PHOTOS
		$html.='<tr><th class="titre" colspan="2" id="photos">Liaison avec photos</th></tr>';
		if($data['pic']['q']==0)
			{
			$html.='<tr><td colspan="2">Aucune photo pour cette fiche</th></tr>';
			}
		else
			{
			$i=0;
			$pic=$data['pic']['sql']->fetchAll();
			foreach($pic as $rowg)
				{
				$html.=($i==0) ? '<tr><th class="sstitre" colspan="2">1ère photo</th></tr>' : '<tr><th class="sstitre" colspan="2">'.($i+1).'ème photo</th></tr>';
				$html.='<tr><th>Commentaire :</th><td><input type="text" name="comPic'.$i.'" id="comPic'.$i.'" value="'.$rowg['commentaire'].'" style="width:90%;"></td></tr>';
				$html.='<tr><td colspan="2"><img src="'.MEDIA.'/images/'.$rowg['lien'].'" height="250"></td></tr>';
				$html.='<tr><td colspan="2" class="noborder"><input type="button" value="Enregistrer modifications commentaire" onclick="recModifsPic(\''.$rowg['id_photo'].'\',\''.$row['id_fiche'].'\',\''.$i.'\');"> ou <input type="button" value="Supprimer cette photo" onclick="delPic(\''.$rowg['id_photo'].'\',\''.$row['id_fiche'].'\',\''.$i.'\');"></td></tr>';
				$i++;
				}
			$data['pic']['sql']->closeCursor();	
			}
		$html.='<tr><td class="noborder" colspan="2"><input type="button" value="Ajouter une photo" onclick="addPic(\''.$row['id_fiche'].'\');"></td></tr>';
		$data['fiche']->closeCursor();
		$html.='</table>';
		$html.='<div id="newPic"></div>';
		$html.='<table>';
		}
	$html.='</table>';
	
	$this->afficheHtml($html);
	}
	
public function newInfoFicheById($data,$section,$categ,$implication,$rues)
	{
	$html='<h2>Nouvelle fiche avec relation</h2>';
	$html.='<form method="POST" enctype="multipart/form-data" action="?component=cops&action=newInfoCopsWRel"><table>';
	$fiche=$data['fiche']->fetchAll();
	foreach($fiche as $row)
		{
		$idcategFiche=$row['id_categ'];
		$idSectionFiche=$row['IdSection'];
		$dhDebutFiche=$row['date_debut'];
		$dhFinFiche=$row['date_fin'];
		$interactionFiche=$row['interaction'];
		$texteInfo=$row['texteInfo'];
		}
	$html.='<tr><th class="titre" colspan="4">Informations générales</th></tr>';	
	$html.='<tr><th width="25%">Section</th><td width="25%">';
	$html.='<select name="section" onchange="getCateg()" id="get_Section">';
	while ($row=$section->fetch())
		{
		$html.='<option value="'.$row['id_section'].'" ';
		if ($row['id_section']==$idSectionFiche)
			{
			$html.='selected';
			}
		$html.='>'.$row['denomination'].'</option>';
		}
	$html.='</select>';
	$html.='</td><th width="25%">Catégorie</th><td width="25%" id="getSection"><select name="categorie">';
	while($row=$categ->fetch())
		{
		if($row['id_section']==$idSectionFiche)
			{
			$html.='<option value="'.$row['id_categ'].'" ';
			if ($row['id_categ']==$idcategFiche)
				{
				$html.='selected';
				}
			$html.='>'.$row['denomination'].'</option>';
			}
		}
	$html.='</select></td></tr>';
	$html.='<tr><th>Valide du :</th><td><input type="datetime-local" name="dhDebut" value="'.$this->getDateFromDateTimeUS($dhDebutFiche).'T'.$this->getTimeFromDateTimeUS($dhDebutFiche).'"></td><th>Au :</th><td><input type="datetime-local" name="dhFin" value="'.$this->getDateFromDateTimeUS($dhFinFiche).'T'.$this->getTimeFromDateTimeUS($dhFinFiche).'"></td></tr>';
	$html.='<tr><th>Interaction ?</th><td>';
	$html.='<input type="radio" name="interaction" value="O"';
	$html.=($interactionFiche=="O") ? ' checked>Oui' : '>Oui';
	$html.='<input type="radio" name="interaction" value="N"';
	$html.=($interactionFiche=="N") ? ' checked>Non' : '>Non';
	$html.='</td></tr>';
	$html.='<tr><th>Texte info :</th><td colspan="3"><textarea rows="2" cols="50" name="txtInfo" autofocus required>'.$texteInfo.'</textarea></td></tr>';
	$html.='<tr><th class="titre" colspan="4">Liaison aves personnes</th></tr>';
	//Affichage des personnes de la fiche de référence
	if ($data['pers']['q']!="0")
		{
		$i=1;
		while($row=$data['pers']['sql']->fetch())
			{
			$html.='<table id="tabPers'.$i.'">';
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=($i==1) ? '1ère personne' : $i.'ème personne';
			$html.='</th></tr>';
			$html.='<tr><th>Nom :</th><td><input type="text" name="nomPers_'.$i.'" value="'.$row['nom'].'"  style="text-transform:uppercase" readonly="readonly"></td><th>Prénom :</th><td><input type="text" name="prenom_'.$i.'" value="'.$row['prenom'].'" readonly="readonly"</tr>';
			$html.='<tr><th>Date de naissance :</th><td><input type="date" name="DN_'.$i.'" value="'.$row['date_naissance'].'" readonly="readonly"></td><th>Pays de résidence :</th><td><input type="text" name="PaysRes_'.$i.'" value="'.$row['pays'].'"  readonly="readonly"></td></tr>';
			$html.='<tr><th>Adresse :</th><td colspan="3">CP : <input type="text" name="CPPers_'.$i.'" value="'.$row['CP'].'" style="width:15%;"  readonly="readonly"> Ville : <input type="text" name="villePers_'.$i.'" value="'.$row['ville'].'" style="width:15%;"  readonly="readonly"> Rue : <input type="text" name="RuePers_'.$i.'" value="'.$row['adresse'].'" style="width:25%;"  readonly="readonly"> Numéro : <input type="text" name="numPers_'.$i.'" value="'.$row['numero'].'" style="width:10%;"  readonly="readonly"></td></tr>';
			$html.='<tr><th>Descriptif :</th><td colspan="2"><textarea rows="3" cols="50" name=descrPers_'.$i.' readonly="readonly">'.$row['descriptif'].'</textarea></td><td>Implication : <input type="text" value="'.$row['liaison'].'" readonly="readonly"><input type="hidden" name="implicationPers_'.$i.'" value="'.$row['id_liaison'].'"></td></tr>';
			$html.='<tr><th>Photo :</th><td colspan="3"><img src="../media/images/'.$row['photo'].'" width="200px"><input type="hidden" name="photo_'.$i.'" value="'.$row['photo'].'"></td></tr>';
			$html.='<tr><td colspan="4"><input type="button" value="Supprimer les données de cette personne" onclick="hidePers(\''.$i.'\');"></td></tr></table>';
			$i++;
			}
		}
	else
		{
		$i=1;
		$html.='<table><tr><td colspan="4">Aucune personne n\'est encodée.</td></tr></table>';
		}
	$html.='<table><tr><td colspan="4" class="noborder"><input type="button" onclick="AddPersonneFiche('.$i.');" value="Ajouter une personne"></td></tr></table>';
	$html.='<table id="AddPersonneFiche"><input type="hidden" name="nbPers" value="'.$i.'"></table>';
	//Affichage des véhicules de la fiche de référence
	$html.='<table><tr><th class="titre" colspan="4">Liaison avec véhicules</th></tr>';
	if ($data['vv']['q']!="0")
		{
		$i=1;
		while($row=$data['vv']['sql']->fetch())
			{
			$html.='<table id="tabVV'.$i.'">';
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=($i==1) ? '1er véhicule' : $i.'ème véhicule';
			$html.='</th></tr>';
			$html.='<tr><th>Marque :</th><td><input type="text" name="marqueVV_'.$i.'" value="'.$row['marque'].'" readonly="readonly"></td><th>Modèle :</th><td><input type="text" name="modeleVV_'.$i.'" value="'.$row['modele'].'" readonly="readonly"></td></tr>';
			$html.='<tr><th>Immatriculation :</th><td><input type="text" name="immatVV_'.$i.'" value="'.$row['immatriculation'].'" readonly="readonly"></td><th>N° chassis :</th><td><input type="text" name="chassisVV_'.$i.'" value="'.$row['chassis'].'" readonly="readonly"></td></tr>';
			$html.='<tr><th>Couleur :</th><td><input type="text" name="couleurVV_'.$i.'" value="'.$row['couleur'].'" readonly="readonly"></td><th>Descriptif :</th><td><input type="text" name="descVV_'.$i.'" value="'.$row['descriptif'].'" readonly="readonly"></td></tr>';
			$html.='<tr><th>Implication :</th><td><input type="text"  value="'.$row['liaison'].'" readonly="readonly"></td><td colspan="2"><input type="button" value="Supprimer les données de ce véhicule" onclick="hideVV(\''.$i.'\');"><input type="hidden" name="implicationVV_'.$i.'" value="'.$row['id_liaison'].'"></td></tr></table>';
			$i++;
			}
		}	
	else
		{
		$i=1;
		$html.='<table><tr><td colspan="4">Aucune véhicule n\'est encodé.</td></tr></table>';
		}
	$html.='<table><tr><td colspan="4" class="noborder"><input type="button" onclick="AddVVFiche('.$i.');" value="Ajouter un véhicule"></td></tr></table>';
	$html.='<table id="AddVVFiche"><input type="hidden" name="nbVV" value="'.$i.'"></table>';
	
	//Affichage des lieudits de la fiche de référence
	$html.='<table><tr><th class="titre" colspan="4">Liaison avec lieudits</th></tr>';
	if ($data['ld']['q']!="0")
		{
		$i=1;
		while($row=$data['ld']['sql']->fetch())
			{
			$html.='<table id="tabLD'.$i.'">';
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=($i==1) ? '1er lieudit' : $i.'ème lieudit';
			$html.='</th></tr>';
			$html.='<tr><th>Dénomination :</th><td><input type="text" name="denomLD_'.$i.'" value="'.$row['description'].'" readonly="readonly"></td><th>Implication :</th><td><input type="text" value="'.$row['liaison'].'" readonly="readonly"><input type="hidden"  name="implicationLD_'.$i.'" value="'.$row['id_liaison'].'"></td></tr>';
			$html.='<tr><td colspan="4"><input type="button" onclick="hideLD(\''.$i.'\');" value="Supprimer les données de ce lieudit"></td></tr>';
			$html.='</table>';
			$i++;
			}
		}
	else
		{
		$i=1;
		$html.='<table><tr><td colspan="4">Aucun lieudit n\'est encodé.</td></tr></table>';
		}
	$html.='<table><tr><td colspan="4" class="noborder"><input type="button" onclick="AddLDFiche('.$i.');" value="Ajouter un lieudit"></td></tr></table>';
	$html.='<table id="AddLDFiche"><input type="hidden" name="nbLD" value="'.$i.'"></table>';
	
	//Affichage des commerces de la fiche de référence
	$html.='<table><tr><th class="titre" colspan="4">Liaison avec commerces</th></tr>';
		if ($data['com']['q']!="0")
		{
		$i=1;	
		while ($row=$data['com']['sql']->fetch())
			{
			$html.='<table id="tabCom'.$i.'">';
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=($i==1) ? '1er commerce' : $i.'ème commerce';
			$html.='</th></tr>';
			$html.='<tr><th>Dénomination :</th><td><input type="text" name="denomCom_'.$i.'" value="'.$row['nom'].'" readonly="readonly"></td><th>Descriptif :</th><td><input type="text" name="descCom_'.$i.'" value="'.$row['descriptif'].'" readonly="readonly"></td></tr>';
			$html.='<tr><th>Adresse :</th><td colspan="3"><input type="text" name="CPCom_'.$i.'" value="'.$row['CP'].'" readonly="readonly"> <input type="text" name="comCom_'.$i.'" value="'.$row['ville'].'" readonly="readonly"> <input type="text" value="'.$row['NomRue'].'" readonly="readonly"><input type="hidden" name="idRueCom_'.$i.'" value="'.$row['idRue'].'"> <input type="text" name="numCom_'.$i.'" value="'.$row['numero'].'" readonly="readonly"> </td></tr>';
			$html.='<tr><th>Implication :</th><td><input type="text" value="'.$row['liaison'].'"><input type="hidden" name="implicationCom_'.$i.'" value="'.$row['id_liaison'].'"></td><td colspan="2"><input type="button" onclick="hideCom(\''.$i.'\');" value="Supprimer ce commerce"></td></tr>';
			$html.='</table>';
			$i++;
			}
		}
	else
		{
		$i=1;
		$html.='<table><tr><td colspan="4">Aucun commerce n\'est encodé.</td></tr></table>';
		}
	$html.='<table><tr><td colspan="4" class="noborder"><input type="button" onclick="AddCommerceFiche('.$i.');" value="Ajouter un commerce"></td></tr></table>';
	$html.='<table id="AddCommerceFiche_"><input type="hidden" name="nbCommerce" value="'.$i.'"></table>';	
		
	//Affichage des textes libres de la fiche de référence
	$html.='<table><tr><th class="titre" colspan="4">Liaison avec textes libres</th></tr>';
		if ($data['tl']['q']!="0")
		{
		$i=1;
		while($row=$data['tl']['sql']->fetch())
			{
			$html.='<table id="tabTL'.$i.'">';
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=($i==1) ? '1er texte libre' : $i.'ème texte libre';
			$html.='</th></tr>';
			$html.='<tr><th colspan="4">'.$row['titre'].'<input type="hidden" name="titreTL_'.$i.'" value="'.$row['titre'].'"</th></tr>';
			$html.='<tr><td colspan="4"><textarea rows="4" cols="50" name="texteTL_'.$i.'" readonly="readonly">'.$row['texte'].'</textarea></td></tr>';
			$html.='<tr><td colspan="4"><input type="button" onclick="hideTL(\''.$i.'\');" value="Supprimer ce texte libre"></td></tr>';
			$html.='</table>';
			$i++;
			}
		}
	else
		{
		$i=1;
		$html.='<table><tr><td colspan="4">Aucun texte libre n\'est encodé.</td></tr></table>';
		}
	$html.='<table><tr><td colspan="4" class="noborder"><input type="button" onclick="AddTLFiche(\''.$i.'\');" value="Ajouter un texte libre"></td></tr></table>';
	$html.='<table id="AddTLFiche_"><input type="hidden" name="nbTL" value="'.$i.'"></table>';
		
	//Affichage des photos de la fiche de référence
	$html.='<table><tr><th class="titre" colspan="4">Liaison avec photos</th></tr>';
		if ($data['pic']['q']!="0")
		{
		$i=1;	
		while($row=$data['pic']['sql']->fetch())
			{
			$html.='<table id="tabPic'.$i.'">';
			$html.='<tr><th class="sstitre" colspan="4">';
			$html.=($i==1) ? '1ère photo' : $i.'ème photo';
			$html.='</th></tr>';
			$html.='<tr><th>Commentaire :</th><td colspan="3"><input type="text" name="comPic_'.$i.'" value="'.$row['commentaire'].'" readonly="readonly"></td></tr>';
			$html.='<tr><th>Photo :</th><td colspan="3"><img src="'.MEDIA.'images/'.$row['lien'].'" height="250px"></td></tr>';
			$html.='<tr><td colspan="4"><input type="button" onclick="hidePic(\''.$i.'\');" value="Supprimer cette photo"></td></tr>';
			$html.='</table>';
			$i++;
			}
		}
	else
		{
		$i=1;
		$html.='<table><tr><td colspan="4">Aucune photo n\'est encodée.</td></tr></table>';
		}
	$html.='<table><tr><td colspan="4" class="noborder"><input type="button" onclick="AddPicFiche(\''.$i.'\');" value="Ajouter une photo"></td></tr></table>';
	$html.='<table id="AddPicFiche_"><input type="hidden" name="nbPic" value="'.$i.'"></table>';
		
	//*************
	$html.='<table><tr><td class="noborder" colspan="4"><input type="submit" value="Enregistrer" align="center"></td></tr></table>';
	$html.='</table></form>';
	$this->afficheHtml($html);
	}
	
	public function afficheResultSearch($data,$search){
		$html='<h3>Résultats de votre recherche sur "'.$search.'"</b></h3>';
		if($data['ttl']==0){
			$html.='Aucun résultat ne correspond à votre recherche.';
		}
		$html.='<ul>';
		for ($i=0;$i<$data['ttl'];$i++){
			if ($data[$i]['type']=='vv'){
				$html.='<li>'.$data[$i]['vv']['marque'].' '.$data[$i]['vv']['modele'].' de couleur '.$data[$i]['vv']['couleur'].', immatriculé '.$data[$i]['vv']['immatriculation'].', châssis n° '.$data[$i]['vv']['chassis'].'. Infos complémentaires : '.$data[$i]['vv']['descriptif'].'.<br /><a href="?component=cops&action=moreInfos&idFiche='.$data[$i]['vv']['id_fiche'].'">Fiche complète</a></li>';
			}
			else if ($data[$i]['type']=='pers'){
				$html.='<li>'.$data[$i]['pers']['nom'].' '.$data[$i]['pers']['prenom'].', né(e) le '.$this->datefr($data[$i]['pers']['dn']).'.<br /><img src="'.MEDIA.'images/'.$data[$i]['pers']['photo'].'" height="250px"><br /><a href="?component=cops&action=moreInfos&idFiche='.$data[$i]['pers']['id_fiche'].'">Fiche complète</a></li>';
			}
			
		}
	$html.='</ul>';	
	$this->afficheHtml($html);
	}
	
public function listMsgs($data){ /* 15.09.2015 */
	$html='<h1>Liste des infos HOT ! proposées</h1>';
	$html.='<table>';
	while($row=$data->fetch()){
		$html.='<tr><th width="20%">'.$row['titre'].'</th><td width="40%">'.$row['info'].'</td><td width="20%">('.$row['nom'].' '.$row['prenom'].')</td><td width="20%"><a href="?component=cops&action=delMsgPush&id='.$row['id_info'].'">Supprimer</a></td></tr>';
	}
	$html.='</table>';
	$this->afficheHtml($html);
}
	
}
?>