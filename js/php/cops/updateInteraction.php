<?php

include ('../connect.php');
$fiche=$_GET['fiche'];
$selected=$_GET['select'];

$sql='UPDATE z_fiche SET interaction="'.$selected.'" WHERE id_fiche="'.$fiche.'"';
$pdo->exec($sql);
echo $sql;

?>