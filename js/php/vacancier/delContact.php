<?php

include ('../connect.php');

$id = $_GET['id'];

$sql='DELETE FROM z_vac_hab_cont WHERE id_contact="'.$id.'"';
$pdo->exec($sql);

$sql='DELETE FROM z_vac_contact WHERE id_contact="'.$id.'"';
$pdo->exec($sql);

?>