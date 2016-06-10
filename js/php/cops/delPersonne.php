<?php

include ('../connect.php');

$fiche=$_GET['idFiche'];
$pers=$_GET['idPers'];

$sql='DELETE FROM z_fiche_personne WHERE id_personne="'.$pers.'" AND id_fiche="'.$fiche.'"';
$pdo->exec($sql);

$sql='DELETE FROM z_personne WHERE id_personne="'.$pers.'"';
$pdo->exec($sql);

?>