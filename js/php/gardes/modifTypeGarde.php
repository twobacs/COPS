<?php
if(isset($_GET['id'])){
	include ('../connect.php');
	$id=$_GET['id'];
	$sql='SELECT denomination FROM z_type_garde WHERE id_typeGarde=:idType';
	$req=$pdo->prepare($sql);
	$req->execute(array('idType'=>$id));
	if ($_GET['action']=='1'){
		while($row=$req->fetch())
			{
			$html='<td><input type="text" id="newDenom_'.$id.'"value="'.$row['denomination'].'" autofocus></td><td><input type="button" value="Mettre à jour" onclick="majTypeGarde(\''.$id.'\')"></td>';	
			}
		}
	else if ($_GET['action']=='-1'){
			while($row=$req->fetch()){
				$denomination=$row['denomination'];
			}
			$sql='DELETE FROM z_type_garde WHERE id_typeGarde=:idType';
			$req=$pdo->prepare($sql);
			$req->execute(array('idType'=>$id));
			$html='<td class="red">'.$denomination.'</td><td class="red">Type supprimé</td>';
		}
	echo $html;
}


?>