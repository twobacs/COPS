<?php

include ('../connect.php');

if (isset($_GET['type'])){
	$type=$_GET['type'];
	$id=$_GET['id'];
	switch ($type){
		case 'rue' :
			$html=showInfosByRue($id,$pdo);
			break;
		case 'agent' :
			$html=showInfosByAgent($id,$pdo);
			break;
		case 'antenne':
			$html=showInfosByAntenne($id,$pdo);
			break;
		}
	echo '<br />'.$html;
	}

function showInfosByRue($id,$pdo){
	/*
	La requete SQL doit faire sortir les informations suivantes, sur base de l'identifiant de la rue :
	id_quartier de z_quartier via z_quartier_rue
	denomination de z_antenne_quartier via id_quartier (ce) de z_quartier
	nom et prénom de users via id_quartier (ce) de z_agent_quartier
	*/
	$html='<ul>';
	$sql='
	SELECT a.id_quartier, a.limiteBas, a.limiteHaut, b.denomination, b.id_antenne, b.gsm, c.denomination AS denQ, e.nom, e.prenom
	FROM z_quartier_rue a
	LEFT JOIN z_quartier b ON a.id_quartier=b.id_quartier
	LEFT JOIN z_antenne_quartier c ON b.id_antenne=c.id_antenne
	LEFT JOIN z_agent_quartier d ON d.id_quartier=b.id_quartier
	LEFT JOIN users e ON d.id_user=e.id_user
	WHERE a.IdRue="'.$id.'"
	ORDER BY c.denomination ASC
	';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch()){
		$html.='<li>Du '.$row['limiteBas'].' au '.$row['limiteHaut'].' : '.$row['nom'].' '.$row['prenom'].' ('.$row['denQ'].', quartier "'.$row['denomination'].'" - GSM : '.$row['gsm'].')</li>';
		}
	$html.='</ul>';	
	return $html;
	}
	
function showInfosByAgent($id,$pdo){
	/*
	La requete SQL doit faire sortir les informations suivantes, sur base de l'identifiant de l'agent :
	id_quartier de z_agent_quartier
	denomination de z_antenne_quartier via id_quartier (ce) de z_quartier
	NomRue de z_rues via id_quartier (ce) de z_quartier_rue
	*/
	$html='';
	$i=0;
	$sql='
	SELECT a.id_quartier, b.denomination, b.gsm, c.denomination AS denQ, d.limiteBas, d.limiteHaut, e.NomRue
	FROM z_agent_quartier a
	LEFT JOIN z_quartier b ON a.id_quartier = b.id_quartier
	LEFT JOIN z_antenne_quartier c ON c.id_antenne=b.id_antenne
	LEFT JOIN z_quartier_rue d ON d.id_quartier=b.id_quartier
	LEFT JOIN z_rues e ON e.IdRue=d.IdRue
	WHERE a.id_user="'.$id.'"
	ORDER BY e.NomRue
	';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch()){
		$antenne=$row['denQ'];
		$quartier=$row['denomination'];
		$gsm=$row['gsm'];
		$lb[$i]=$row['limiteBas'];
		$lh[$i]=$row['limiteHaut'];
		$rue[$i]=$row['NomRue'];
		$i++;
		}
	if (isset($antenne)){	
		$html.='<b>'.$antenne.', quartier "'.$quartier.'"</b> (GSM '.$gsm.')<br /><hr><ul>';
		for ($j=0;$i>$j;$j++){
			$html.='<li>'.$rue[$j].', du '.$lb[$j].' au '.$lh[$j].'</li>';
			}
		$html.='</ul>';
		}
	else {$html.='Aucun quartier d\'attribué';}
	return $html;	
	}
	
function showInfosByAntenne($id,$pdo){
	/*
	La requete SQL doit faire sortir les informations suivantes, sur base de l'identifiant de l'antenne :
	Les quartiers liés et leurs infos respectives
	L'adresse et les numéros de contact (tel et fax)
	Le responsable antenne
	*/
	$sql='SELECT a.denomination, a.telephone, a.fax, a.numero, b.nom, b.prenom, c.NomRue, d.denomination AS denQ
	FROM z_antenne_quartier a
	LEFT JOIN users b ON a.id_resp=b.id_user
	LEFT JOIN z_rues c ON c.IdRue=a.IdRue
	LEFT JOIN z_quartier d ON d.id_antenne=a.id_antenne 
	WHERE a.id_antenne="'.$id.'"
	';
	$html='';
	$i=0;
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch()){
		$nom=$row['nom'];
		$prenom=$row['prenom'];
		$rue=$row['NomRue'];
		$num=$row['numero'];
		$tel=$row['telephone'];
		$fax=$row['fax'];
		$denom=$row['denomination'];
		$quartier[$i]=$row['denQ'];		
		$i++;
		}
	$html.='<b>'.$denom.'</b><hr>';
	$html.=$rue.', '.$num.'<br />';
	$html.='Téléphone : '.$tel.'<br />';
	$html.='Fax : '.$fax.'<br />';
	$html.='Responsable antenne : '.$nom.' '.$prenom.'<br />Quartiers attachés : <br /><ul>';
	for ($j=0;$i>$j;$j++){
		$html.='<li>'.$quartier[$j].'</li>';
		}
	$html.='</ul>';
	return $html;
	}
	


?>