<?php

include ('connect.php');

$nb=$_GET['nb'];
// $html='<table>';
// $i=0;
// $options='';

// $sql='SELECT nom, prenom, id_user FROM users ORDER by nom, prenom ASC';
// $rep=$pdo->query($sql);
	// while ($row=$rep->fetch())
		// {
		// $options.='<option value="'.$row['id_user'].'">'.$row['nom'].' '.$row['prenom'].'</option>';
		// }

// while ($i<$nb)
	// {
	// $html.='<tr><th width=50>Collaborateur '.($i+1).' :</th><td><select name=colaps'.$i.'><option></option>';
	// $html.=$options;
	// $html.='</td></tr>';
	// $i++;
	// }
$html='<input type=hidden name="nbCol" value="'.$nb.'"></table>';
echo $html;



?>