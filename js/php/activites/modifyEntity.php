<?php

include ('../connect.php');

$subAction=$_GET['operation'];
$field=$_GET['field'];
$bs=$_GET['bs'];
$sql='SELECT '.$field.' FROM z_bs WHERE id_bs="'.$bs.'"';
$rep=$pdo->query($sql);
while ($row=$rep->fetch())
	{
	$value=$row[$field];
	}
switch ($subAction)
	{
	case 'add':
		$value++;
		break;
	case 'rem';
		if ($value!=0)
			{
			$value--;
			}
		break;
	}
$sql='UPDATE z_bs SET '.$field.'="'.$value.'" WHERE id_bs="'.$bs.'"';


$pdo->exec($sql);
session_start();
$_SESSION[$field]=$value;

echo '<font size="5">'.$value.'</font>';

?>