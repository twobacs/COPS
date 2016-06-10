<?php

class VGarde extends VBase {

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
	$this->appli->jScript= '<script type="text/javascript" src="./js/gardes.js"></script>';
	}
	
private function datefr($date,$dateOnly=0) 
	{
	
		$split = explode(" ",$date);
		$jour = $split[0];
		if(isset($split[1]))
			{
			$heure = $split[1];
			}
		
		$split2 = explode("-",$jour);	
		$annee = $split2[0];
		$mois = $split2[1];
		$jour = $split2[2];
		if($dateOnly==0){
			return $jour."-".$mois."-".$annee.' à '.$heure;
		}
		else if($dateOnly==1){
			return $annee."-".$mois."-".$jour.'T'.$heure;
		}
	}		

public function manageGardes($types,$message,$gardes){
	$html='<h3>Gestion des service de gardes</h3>'; 
	$html.='La modification pour les gardes externes est en cours de cr&eacute;ation.  Merci de votre patience.<br />';
	switch($message){
		case -1:
			$html.='La valeur introduite existe déjà en base de données.<br />';
			break;
		case 1:
			$html.='La nouvelle valeur a été correctement enregistrée.<br />';
			break;
	}
	$html.='<br /><div id=menuGarde>Type de garde à gérer : <select name="sv_garde" id="sv_garde" onchange="garde_to_step1(\'0\');">';
	$html.='<option value="0"></option>';
	while($row=$types->fetch()){
		$html.='<option value="'.$row['id_typeGarde'].'">'.$row['denomination'].'</option>';
		}
	// $html.='<option value="0">---------------</option>';	
	// $html.='<option value="-1">AJOUTER UN TYPE</option>';	
	// $html.='<option value="-2">MODIFIER / SUPPRIMER UN TYPE</option>';	
	$html.='</select>';
	$html.='</div>';
	$html.='<div id="garde_step1"></div>';
	$html.='<div id="garde_step2"></div>';
	$html.='<div id="garde_step3"></div>';
	$html.='<div id="garde_step4"></div>';
	$html.='<h3>Gestion du calendrier des gardes</h3>';

	$html.='<table>';
	$html.='<tr><th class="sstitre" width="30%">Service</th><th class="sstitre" width="30%">Contact</th><th class="sstitre" width="15%">Du</th><th class="sstitre" width="15%">Au</th><td class="noborder" width="10%"></td></tr>';
	$date=date('Y-m-d G:i:s');
	// echo $date;
	while($row=$gardes->fetch()){
		$html.='<tr><td';
		$html.=($row['denomType']=='Interne') ? ' class="blue">' : ' class="orange">';
		$html.=$row['denomination_svGarde'].'</td>';
		if((is_null($row['idPersExt'])) AND (is_null($row['idPersIn']))){
			$html.='<td>Aucune garde encodée.</td><td></td><td></td><td>';
			}
		else{
				if (is_null($row['idPersExt'])){
				$html.='<td onclick="infosPersGarde(\''.$row['nomIn'].' '.$row['prenomIn'].'\nGSM : '.$row['gsmIn'].'\nFixe : '.$row['fixeIn'].'\nFax : '.$row['faxIn'].'\nMail : '.$row['mailIn'].'\nAdresse : '.$row['rueIn'].', '.$row['numIn'].'\n'.$row['CPIn'].' '.$row['villeIn'].'\');">'.$row['nomIn'].' '.$row['prenomIn'].'</td><td>'.$this->datefr($row['dateHr_debut']).'</td><td>'.$this->datefr($row['dateHr_fin']).'</td><td><a href="#" onclick="delGardeById(\''.$row['id_garde'].'\');">Supprimer</a><br /><a href="?component=garde&action=modifPersGarde&idGarde='.$row['id_garde'].'&selected='.$row['id_user'].'">Modifier</a><br />';
				}
			else if (is_null($row['idPersIn'])){
				$html.='<td onclick="infosPersGarde(\''.$row['nomExt'].' '.$row['prenomExt'].'\nGSM : '.$row['gsmExt'].'\nFixe : '.$row['fixeExt'].'\nFax : '.$row['faxExt'].'\nMail : '.$row['mailExt'].'\nAdresse : '.$row['rueExt'].', '.$row['numExt'].'\n'.$row['CPExt'].' '.$row['villeExt'].'\');">'.$row['nomExt'].' '.$row['prenomExt'].'</td><td>'.$this->datefr($row['dateHr_debut']).'</td><td>'.$this->datefr($row['dateHr_fin']).'</td><td><a href="#" onclick="delGardeById(\''.$row['id_garde'].'\');">Supprimer</a><br /><!--<a href="?component=garde&action=modifPersGarde&idGarde='.$row['id_garde'].'&selected='.$row['id_user'].'">Modifier</a><br />-->';
				}
			}
		$html.='<a href="?component=garde&action=addGarde&idGarde='.$row['id_svGarde'].'">Ajouter</a></td>';	
		$html.='</tr>';
			
		}
	$html.='</table>';
	$this->afficheHtml($html);
	}
	
public function formAddPersToGarde($data){
	$html='<h3>Ajout d\'une garde pour le service ';
	$i=0;
	while ($row=$data->fetch()){
		$service=lcfirst($row['denomination_svGarde']);
		$idSv=$row['id_svGarde'];
		if($row['id_type_pers_garde']=='I'){
			$n='nomIn';
			$p='prenomIn';
			$type='I';
			$idP='id_user';
			}
		else{
			$n='nomEx';
			$p='prenomEx';
			$type='E';
			$idP='id_pers';
			}
		$nom[$i]=$row[$n];
		$prenom[$i]=$row[$p];
		$id[$i]=$row[$idP];
		$i++;
		}
	$html.=$service.'.</h3>';
	$html.='<form name="addPersToGarde" method="POST" action="?component=garde&action=addPersToGarde"><table>';
	$html.='<tr><th>Personne</th><th>Date heure début</th><th>Date heure fin</th><td class="noborder"></td></tr>';
	$html.='<td><select name="persToAdd"><option value="-1"></option>';
	for($j=0;$j<$i;$j++){
		$html.='<option value="'.$id[$j].'">'.$nom[$j].' '.$prenom[$j].'</option>';
		}
	$html.='</td><td><input type="datetime-local" name="dhDebut" required></td><td><input type="hidden" name="typePers" value="'.$type.'"><input type="hidden" name="idSvGarde" value="'.$idSv.'"><input type="datetime-local" name="dhFin" required></td><td><input type="submit" value="Enregistrer"></td></tr>';
	$html.='</table></form>';
	$html.='<a href="?component=garde&action=mainMenu">Retour accueil gardes</a>';
	$this->afficheHtml($html);
	}
	
public function showGardesNow($gardes){
	$html='<h3>Gardes actuelles</h3>';
	$html.='<table>';
	$html.='<tr><th class="sstitre" width="20%">Service</th><th class="sstitre" width="20%">Contact</th><th class="sstitre" width="30%">Du</th><th class="sstitre" width="30%">Au</th></tr>';
	while($row=$gardes->fetch()){
		$html.='<tr><td';
		$html.=($row['denomType']=='Interne') ? ' class="blue">' : ' class="orange">';
		$html.=$row['denomination_svGarde'].'</td>';
		if((is_null($row['idPersExt'])) AND (is_null($row['idPersIn']))){
			$html.='<td>Aucune garde encodée</td><td></td><td></td><td>';
			}
		else{
			if (is_null($row['idPersExt'])){
				$html.='<td onclick="infosPersGarde(\''.$row['nomIn'].' '.$row['prenomIn'].'\nGSM : '.$row['gsmIn'].'\nFixe : '.$row['fixeIn'].'\nFax : '.$row['faxIn'].'\nMail : '.$row['mailIn'].'\nAdresse : '.$row['rueIn'].', '.$row['numIn'].'\n'.$row['CPIn'].' '.$row['villeIn'].'\');">'.$row['nomIn'].' '.$row['prenomIn'].'</td><td>'.$this->datefr($row['dateHr_debut']).'</td><td>'.$this->datefr($row['dateHr_fin']).'</td>';
				}
			else if (is_null($row['idPersIn'])){
				$html.='<td onclick="infosPersGarde(\''.$row['nomExt'].' '.$row['prenomExt'].'\nGSM : '.$row['gsmExt'].'\nFixe : '.$row['fixeExt'].'\nFax : '.$row['faxExt'].'\nMail : '.$row['mailExt'].'\nAdresse : '.$row['rueExt'].', '.$row['numExt'].'\n'.$row['CPExt'].' '.$row['villeExt'].'\');">'.$row['nomExt'].' '.$row['prenomExt'].'</td><td>'.$this->datefr($row['dateHr_debut']).'</td><td>'.$this->datefr($row['dateHr_fin']).'</td>';
				}
			}
		$html.='</tr>';
	}
	$html.='</table>';
	$this->afficheHtml($html);
}

public function modifGarde($garde){
	$pers=$garde['pers'];
	$options='';
	for($i=0;$i<sizeof($garde['pers']);$i++){
		$options.='<option value="'.$pers[$i]['id_user'].'">'.$pers[$i]['nom'].' '.$pers[$i]['prenom'].'</option>';
	}
	$html='<h3>Modification d\'une garde</h3>';
	$html.='<form method="POST" action="?component=garde&action=majGarde&idGarde='.$garde['idGarde'].'"><table>';
	$html.='<tr><th>Service</th><th>Contact</th><th>Du</th><th>Au</th></tr>';
	while($row=$garde['infosGarde']->fetch()){
		$html.='<tr><td>'.$row['denomination_svGarde'].'</td><td><select name="persGarde">'.$options.'</select></td><td><input type="datetime-local" name="dhBas" value="'.$this->datefr($row['dateHr_debut'],1).'"></td><td><input type="datetime-local" name="dhHaut" value="'.$this->datefr($row['dateHr_fin'],1).'"></td></tr>';
		}
	$html.='<tr><td colspan="4"><input type=submit value="Enregistrer"></td></tr></table></form>';
	$this->afficheHtml($html);
}
}
?>