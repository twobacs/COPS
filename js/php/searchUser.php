<?php
include ('connect.php');
if (isset($_GET['nom']))
	{
	$html='';
	$nom=htmlentities($_GET['nom']);
	$sql = 'SELECT id_user, nom, prenom, matricule FROM users WHERE nom LIKE "'.$nom.'%" ORDER BY nom, prenom ASC';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$html.=$row['nom'].' '.$row['prenom'].' ('.$row['matricule'].') - <a href=?component=users&action=modifUser&type=OneUser&id='.$row['id_user'].'>Modifier</a><br />';
		}
	echo $html;
	}

?>