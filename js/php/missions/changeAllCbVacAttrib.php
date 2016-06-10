<?php

if(isset($_GET['mission']))
	{
	include ('../connect.php');
	include ('/var/www/class/patrouilles.class.php');
	$pat=new Patrouille($pdo);
	
	$status=$_GET['status'];
	$mission=$_GET['mission'];
	$debut=$_GET['debut'];
	$fin=$_GET['fin'];
	
	$equipes=$pat->getPatInTime($debut,$fin);
	
	for($i=0;$i<$equipes['ttl'];$i++)
		{
		$sql='SELECT COUNT(*) FROM z_pat_missions WHERE id_patrouille="'.$equipes[$i]['id'].'" AND id_fiche="'.$mission.'"';
		$rep=$pdo->query($sql);
		while ($row=$rep->fetch())
			{
			$count=$row['COUNT(*)'];
			}
		
		if (($status=='true')&&($count==0))
			{
			$sql='INSERT INTO z_pat_missions (id_patrouille, type_mission, id_fiche) VALUES ("'.$equipes[$i]['id'].'", "vacanciers", "'.$mission.'")';
			$pdo->exec($sql);
			}
		else if (($status=='false')&&($count==1))
			{
			$sql='DELETE FROM z_pat_missions WHERE id_patrouille="'.$equipes[$i]['id'].'" AND id_fiche="'.$mission.'"';
			$pdo->exec($sql);
			}
		}
	}

?>