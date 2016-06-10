<?php

if (isset($_GET['type']))
	{
	$type=$_GET['type'];
	$html='<form method=post action=?component=vacancier&action=searchVac&type=';
	switch ($type)
		{
		case 'adr' :
			$html.=formSearchAdres();
			break;
			
		case 'dem' :
			$html.=formSearchDemandeur();
			break;
			
		case 'dd' :
			$html.=formSearchPeriode();
			break;
		}
		
	$html.='<input type=submit value="Rechercher">';
	echo $html;
	}
	
	
function formSearchAdres()
	{
	$html='adress>';
	$html.='Nom de rue : <br />';
	$html.='<input type=text name=search><br />';
	$html.='Numéro : <br />';
	$html.='<input type=text name=num><br /><br />';
	return $html;;
	}
	
function formSearchDemandeur()
	{
	$html='demandeur>';
	$html.='Nom demandeur : <br />';
	$html.='<input type=text name=search><br /><br />';	
	return $html;
	}
	
function formSearchPeriode()
	{
	$html='dd>';
	$html.='Date basse : <br />';
	$html.='<input type=date name=dateBasse><br /><br />';	
	$html.='Date haute : <br />';
	$html.='<input type=date name=dateHaute><br /><br />';	
	return $html;
	}

?>