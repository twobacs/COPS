<?php

if (isset($_SESSION['indic'])){
$html='Bienvenue, votre indicatif est : <b>'.$_SESSION['indic'].'</b><br />';
// $html.='Vos missions du jour sont :';
$this->bienvenue=$html;
}

else if (isset($_COOKIE['iduser'])){
$sql='SELECT prenom FROM users WHERE id_user="'.$_COOKIE['iduser'].'"';
$rep=$this->dbPdo->query($sql);
while($row=$rep->fetch()){
	$prenom=$row['prenom'];
	}
$this->bienvenue='<h4>Bienvenue '.$prenom.'.</h4>';
}

// else{
	// $this->bienvenue='';
// }


?>