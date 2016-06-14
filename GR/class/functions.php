<?php

function uploadDoc($rep){
	$target_dir = './docroom/uploaded_files/'.$rep.'/';
	$target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
	// return $target_file;
	$uploadOk = 1;
	$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if(($fileType=='pdf')||($fileType=='jpeg')||($fileType=='jpg')||($fileType=='png')||($fileType=='gif')){
		$nomFichier=md5(uniqid(rand(), true)).'.'.$fileType;
		$fichier=$target_dir.$nomFichier;
		$resultat = move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$fichier);
		}
	return $nomFichier;
}

function uploadPic($rep){
	$target_dir = './docroom/uploaded_files/'.$rep.'/';
	$target_file = $target_dir . basename($_FILES['picToUpload']['name']);
	$uploadOk = 1;
	$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if(($fileType=='jpeg')||($fileType=='jpg')||($fileType=='png')||($fileType=='gif')){
		$nomFichier=md5(uniqid(rand(), true)).'.'.$fileType;
		$fichier=$target_dir.$nomFichier;
		$resultat = move_uploaded_file($_FILES['picToUpload']['tmp_name'],$fichier);
		}
	return $nomFichier;
}

?>