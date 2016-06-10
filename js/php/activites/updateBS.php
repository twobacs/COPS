<?php

if(isset($_GET['idbs'])){
	include ('../connect.php');
	$num=$_GET['objet'];
	$type=$_GET['type'];
	$bs=$_GET['idbs'];
	
	switch($type){
		case 'arme':
			$sql='SELECT COUNT(*) FROM z_bs_armeCollec WHERE id_bs="'.$bs.'"';
			$rep=$pdo->query($sql);
			while ($row=$rep->fetch()){
				$count=$row['COUNT(*)'];
			}
			if($num!="0"){
				if ($count>0){
					$sql='UPDATE z_bs_armeCollec SET id_arme="'.$num.'" WHERE id_bs="'.$bs.'" ';
				}
				else{
					$sql='INSERT INTO z_bs_armeCollec (id_arme, id_bs) VALUES ("'.$num.'", "'.$bs.'")';
				}
			}
			else{
				if ($count>0){
					$sql='DELETE FROM z_bs_armeCollec WHERE id_bs="'.$bs.'"';
				}
				
			}
			$pdo->exec($sql);
			break;
		case 'photo':
			$sql='SELECT app_photo FROM z_bs WHERE id_bs="'.$bs.'"';
			$rep=$pdo->query($sql);
			while($row=$rep->fetch()){
				$app=$row['app_photo'];
				}
			if($num=="0"){
				$insert="Aucun";
			}
			else $insert='App.'.$num;
			$sql='UPDATE z_bs SET app_photo="'.$insert.'" WHERE id_bs="'.$bs.'"';
			$pdo->exec($sql);
			break;
		case 'ett':
			$sql='SELECT COUNT(*) FROM z_bs_ETT WHERE id_bs="'.$bs.'"';
			$rep=$pdo->query($sql);
			while ($row=$rep->fetch()){
				$count=$row['COUNT(*)'];
			}
			if($num!="0"){
				if ($count>0){
					$sql='UPDATE z_bs_ETT SET id_ETT="'.$num.'" WHERE id_bs="'.$bs.'" ';
				}
				else{
					$sql='INSERT INTO z_bs_ETT (id_ETT, id_bs) VALUES ("'.$num.'", "'.$bs.'")';
				}
			}
			else{
				if ($count>0){
					$sql='DELETE FROM z_bs_ETT WHERE id_bs="'.$bs.'"';
				}
				
			}
			$pdo->exec($sql);			
			break;
		case 'gsm':
			$sql='SELECT COUNT(*) FROM z_bs_GSM WHERE id_bs="'.$bs.'"';
			$rep=$pdo->query($sql);
			while ($row=$rep->fetch()){
				$count=$row['COUNT(*)'];
			}
			if($num!="0"){
				if ($count>0){
					$sql='UPDATE z_bs_GSM SET id_GSM="'.$num.'" WHERE id_bs="'.$bs.'" ';
				}
				else{
					$sql='INSERT INTO z_bs_GSM (id_GSM, id_bs) VALUES ("'.$num.'", "'.$bs.'")';
				}
			}
			else{
				if ($count>0){
					$sql='DELETE FROM z_bs_GSM WHERE id_bs="'.$bs.'"';
				}
				
			}
			$pdo->exec($sql);					
			break;
	}
}


?>