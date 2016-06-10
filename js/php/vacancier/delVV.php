<?php

include ('../connect.php');
$vv=$_GET['vv'];
$vac=$_GET['vac'];

$sql='DELETE FROM z_vac_hab_vv WHERE id_vac="'.$vac.'" AND id_vv="'.$vv.'"';
$pdo->exec($sql);

$sql='DELETE FROM z_vac_vv WHERE id_vv="'.$vv.'"';
$pdo->exec($sql);

echo $sql;
?>