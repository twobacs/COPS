<?php

include ('../connect.php');

if (isset($_GET['quartier'])){
	$i=0;
	$sql='SELECT a.nom, a.prenom FROM users a 
	LEFT JOIN z_agent_quartier b ON a.id_user = b.id_user
	LEFT JOIN z_quartier c ON b.id_quartier=c.id_quartier
	WHERE b.id_quartier='.$_GET['quartier'].'';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch()){
		$agent[$i]=$row['nom'].' '.$row['prenom'];
		$i++;
		}
		
	if ($i==0){
		$html='Aucun agent n\'est actuellement couplé à ce quartier.';
		}
	
	else if ($i==1){
			$html='Information : '.$agent[0].' est actuellement associé(e) à ce quartier.<br />';
			}
	
	else{
		$html.='Plusieurs agents sont déjà associé(e)s à ce quartier : <br />';
		for ($j=0;$j<$i;$j++){
			$html.='- '.$agent[$j].'<br />';
			}
		}
	echo $html;
	}

?>