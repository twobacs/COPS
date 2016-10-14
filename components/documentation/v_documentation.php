<?php

class VDocumentation extends VBase {

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
	$this->appli->jScript= '<script type="text/javascript" src="./js/documentation.js"></script>';
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

		return $jour."-".$mois."-".$annee.' à '.$heure;
	}		

public function mainMenu(){
	$html='<h3>Documentation</h3>';
	$html.='<ul>';
	$html.='<li><a href="?mode=m&component=garde&action=mainMenu">Liste gardes</a></li>';
	$html.='<li><a href="/docroom/pdf/GIT_201409.pdf" target="_blank">GIT édition septembre 2014</a></li>';
	$html.='<li><a href="/docroom/bio/suivitabacs.pdf" target="_blank">Suivi tabacs</a></li>';
	//$html.='<li><a href="/docroom/bio/bio.pdf" target="_blank">Fiche BIO</a></li>';	
	$html.='<li><a href="/docroom/pdf/le_refuge/.pdf" target="_blank">PIP intervention - Police - Site "Le Refuge"</a></li><ul>';
	for($i=1;$i<9;$i++){
		$html.='<li><a href="/docroom/pdf/le_refuge/fiche_'.$i.'.pdf" target="_blank">Fiche '.$i.' : ';
		switch($i){
			case 1:
				$html.='contacts';
				break;
			case 2:
				$html.='configuration des lieux + plan(s)';
				break;
			case 3:
				$html.='vid&eacute;osurveillance';
				break;
			case 4:
				$html.='acc&egrave;s et contr&ocirc;le d\'acc&egrave;s';
				break;
			case 5:
				$html.='inscriptions des demandeurs d\'asile';
				break;
			case 6:
				$html.='gestion des interventions sur site';
				break;
			case 7:
				$html.='Compl&eacute;tude ISLP';
				break;
			case 8:
				$html.='Contr&ocirc;le des r&eacute;fugi&eacute;s sur VP';
				break;
		}
		$html.='</a></li>';
	}
	$html.='</ul></ul>';
	$this->afficheHtml($html);
	}
        
public function showFilesOld($files) {
        $i=0;
        $j=0;
        $k=0;
        $HtmlBio='<h4>R&eacute;pertoire BIO</h4>';
        $HtmlRefuge='<h4>R&eacute;pertoire Le Refuge</h4>';
        $HtmlAutre='<h4>R&eacute;pertoire G&eacute;n&eacute;ral</h4>';
        foreach ($files as $dir){
            foreach($dir as $doc){
                $exploded=explode('/',$doc);
                
                if($exploded[1]=='bio'){
                    $nomDoc=end($exploded);
                    $HtmlBio.='<a href="'.$doc.'" target="_blank">'.$nomDoc.'</a><br />';
                   // echo $bio[$i].'-'.$i;
                    $i++;
                }
                elseif ($exploded[1]=='pdf') {
                    if($exploded[2]=='le_refuge'){
                        $nomDoc=end($exploded);
                        $HtmlRefuge.='<a href="'.$doc.'" target="_blank">'.$nomDoc.'</a><br />';
                    // echo $bio[$i].'-'.$i;
                        $j++;
                    }
                    else{
                        $nomDoc=end($exploded);
                        $HtmlAutre.='<a href="'.$doc.'" target="_blank">'.$nomDoc.'</a><br />';
                    // echo $bio[$i].'-'.$i;
                        $k++;
                    }
            }
            }
        }
            $html='<h3>Documentation sur le serveur</h3>';
            if($i>0){
                 $html.=$HtmlBio;
                }
            if($j>0){
                $html.=$HtmlRefuge;
            }
            if($k>0){
                $html.=$HtmlAutre;
            }    
    $this->afficheHtml($html);
}

public function showFiles($files){
    $html='<h3>Documentation sur serveur</h3>';
    $html.='<table border="1"><tr><th>Nom document</th><th colspan="3">Statut</th></tr>';
    foreach ($files as $key => $row){
        $html.='<tr><td>'.$row['nomDoc'].'</td><td>';
        $html.=($row['actif']=='O') ? 'Oui</td><td>D&eacute;sactiver</td>' : 'Non</td><td>R&eacute;activer</td>';
        $html.='</td><td>Supprimer</td></tr>';    
        
    }
    $html.='</table>';
    $html.='<button onclick="menuAddFile();">Ajouter un document</button>';
    $html.='<div id="menuAddFile" name="menuAddFile"></div>';
    $this->afficheHtml($html);
}

public function menuAddFile($categ){
    $html='<h3>Ajouter un document sur le serveur</h3>';
    $options='<SELECT name="categFile" id="categFile" required><option selected disabled></option>';
    foreach ($categ as $key => $row){
        $options.='<option value="'.$row['id_container'].'">'.$row['nomContainer'].'</option>';
    }
    $options.='</option>';
    $html.=$options;
    $this->afficheHtml($html);
}
}
?>