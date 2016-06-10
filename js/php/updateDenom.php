<?php



$html='';
if (isset($_GET['id']))
	{
	include ('connect.php');
	$id=$_GET['id'];
	$sql='SELECT denomination FROM z_patrouille WHERE id_patrouille="'.$id.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$html.='<input type="hidden" name="denomination" value="'.$row['denomination'].'">'.$row['denomination'].'<input type="hidden" name="idPat" value="'.$id.'">';
		}
	}

echo $html;

?>