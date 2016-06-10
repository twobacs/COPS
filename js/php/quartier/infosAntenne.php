<?php

include ('../connect.php');

if(isset($_GET['quartier'])){
	$quartier=$_GET['quartier'];
	$sql='SELECT a.denomination FROM z_antenne_quartier a
	LEFT JOIN z_quartier b ON a.id_antenne = b.id_antenne
	WHERE b.id_quartier = "'.$quartier.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch()){
		$antenne=$row['denomination'];
		}
		
	if ($antenne!=''){
		$html='INFORMATION : Ce quartier est actuellement associé à : '.$antenne;
		}
		
	else{
		$html='INFORMATION : Ce quartier n\'est actuellement associé à aucune antenne.';
		}
	echo $html;
	}

?>