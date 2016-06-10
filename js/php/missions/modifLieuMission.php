<?php

if(isset($_GET['idLieu']))
	{
	include ('../connect.php');
	$sql='SELECT nom_lieu FROM z_lieu_mission WHERE id_lieu="'.$_GET['idLieu'].'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$html='<td><input type="text" name="newLieu'.$_GET['idLieu'].'" id="newLieu'.$_GET['idLieu'].'" value="'.$row['nom_lieu'].'"></td><td><input type="button" value="Enregistrer" onclick="recModifsLieu(\''.$_GET['idLieu'].'\',\''.$_GET['level'].'\');"></td>';
		}
	echo $html;
	}
?>