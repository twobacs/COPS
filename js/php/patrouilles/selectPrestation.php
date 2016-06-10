<?php

include ('../connect.php');

if (isset($_GET['fonction']))
	{
	$html='<select name="prest" id="3" required><option value=""></option>';
	$fonction=$_GET['fonction'];
	$sql='SELECT id_prestation, denomination FROM z_prestations WHERE id_fonctionnalite="'.$fonction.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$html.='<option value="'.$row['id_prestation'].'">'.$row['denomination'].'</option>';
		}
	$html.='</select>';
	echo $html;
	}
?>