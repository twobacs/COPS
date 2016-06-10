<?php

include ('../connect.php');
$categ=$_GET['categ'];
$fiche=$_GET['fiche'];
$sql='UPDATE z_fiche SET id_categ='.$categ.' WHERE id_fiche="'.$fiche.'"';
$pdo->exec($sql);
$html='<font color="green">Changement enregistré</font>';
echo $html;
?>