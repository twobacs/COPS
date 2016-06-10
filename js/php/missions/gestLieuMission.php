<?php

if (isset($_GET['lvl']))
	{
	include ('../connect.php');
	$sql='SELECT nom_lieu, id_lieu FROM z_lieu_mission';
	$rep=$pdo->query($sql);
	
	$level=$_GET['level'];
	$lvl=$_GET['lvl'];
	switch ($lvl)
		{
		case "0" :
			$html='<table>';
			$html.='<th colspan="2" class="sstitre">Gestion des lieux de missions</th></tr>';
			$html.='<tr><th>Lieu</th><th>Action</th></tr>';
			while ($row=$rep->fetch())
				{
				$html.='<tr id="lieu'.$row['id_lieu'].'"><td width="50%">'.$row['nom_lieu'].'</td><td><input type="button" value="Supprimer" onclick="delLieuMission(\''.$row['id_lieu'].'\',\''.$level.'\');"> - <input type="button" value="Modifier" onclick="modifLieuMission(\''.$row['id_lieu'].'\',\''.$level.'\');"></td></tr>';
				}
			$html.='<tr><th colspan="2"><input type="button" value="Ajouter un lieu" onclick="addLieuMission(1,\''.$level.'\');"></th></tr>';	
			$html.='</table>';	
			$html.='<div id="newLieu"></div>';		
			break;
		
		case "1" :
			$html='<table>';
			$html.='<tr><th colspan="2" class="sstitre">Ajouter un lieu de mission</th></tr>';
			$html.='<tr><td><input type="text" name="NewLieu" id="NewLieu"></td><td><input type="button" value="Ajouter" onclick="recNewLieu(\''.$level.'\');"></td></tr>';
			$html.='</table>';
			break;
			
		}

	echo $html;
	}

?>