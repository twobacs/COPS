<?php
include ('connect.php');
if (isset($_GET['nom']))
	{
	$html='';
	$nom=htmlentities($_GET['nom']);
	$sql = 'SELECT IdRue, NomRue, StraatNaam FROM z_rues WHERE NomRue LIKE "%'.$nom.'%" ORDER BY NomRue ASC';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$html.=$row['NomRue'].' ('.$row['StraatNaam'].') - <a href=?component=rues&action=modifRue&type=OneRue&id='.$row['IdRue'].'>Modifier</a><br />';
		}
	echo $html;
	}


?>