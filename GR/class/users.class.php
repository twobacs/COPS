<?php

class Users
{
public $idUser;
public $login;
public $nom;
public $prenom;
public $matricule;
public $lateralite;
public $uniformise;
public $sexe;
public $grade;
public $mdp;
public $type;
public $mail;
public $service;
public $points;
public $actif;
public $logError;
public $idEval;
public $fixe;
public $gsm;
public $fax;
public $CP;
public $commune;
public $rue;
public $numero;
public $naissance;
public $rrn;
private $pdo;



public function __construct($dbPdo)
	{
	$this->pdo = $dbPdo;
	}

function login($login, $password){
	$sql='SELECT id_user, nom, prenom, mdp_user, log_error FROM users WHERE login=:login';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'login' => $login
	));
	while($row=$req->fetch()){
		$id=$row['id_user'];
		$passbdd=$row['mdp_user'];
		$nom=$row['nom'];
		$prenom=$row['prenom'];
		$error=$row['log_error'];
	}
	if(isset($id)){
		if($error>='3'){
			return 2;
		}
		
		if(md5($password)==$passbdd){
			if($passbdd==md5('azerty')){
					$_SESSION['idUser']=$id;
					// echo 'pouet';
					return 3;
			}
			else{
				$_SESSION['idUser']=$id;
				$_SESSION['Nom']=$nom;
				$_SESSION['Prenom']=$prenom;
				$sql='UPDATE users SET log_error="0" WHERE id_user=:id';
				$req=$this->pdo->prepare($sql);
				$req->execute(array(
				'id' => $id
				));
				$sql='SELECT niv_acces FROM grh_droits WHERE app="administrateur" AND id_user=:user';
				$req=$this->pdo->prepare($sql);
				$req->execute(array(
					'user' => $id
				));
				while($row=$req->fetch()){
					$adm=$row['niv_acces'];
				}
				if(isset($adm)){
					$_SESSION['admin']=true;
				}
				$sql='INSERT INTO logs_GR (nom, prenom, instaLog) VALUES (:nom, :prenom, NOW())';
				$req=$this->pdo->prepare($sql);
				$req->bindParam(':nom',$nom,PDO::PARAM_STR);
				$req->bindParam(':prenom',$prenom,PDO::PARAM_STR);
				$req->execute();				
				return 1;
			}
		}		
		else{
			$error++;
			$sql='UPDATE users SET log_error=:error WHERE id_user=:idUser';
			$req=$this->pdo->prepare($sql);
			$req->execute(array(
			'error' => $error,
			'idUser' => $id
			));
			return 0;
		}
	}	
	else{
		return 0;
	}

}

function getInfosUserById($id){
	$sql='SELECT 
	a.login, a.nom, a.prenom, a.matricule, a.lateralite, a.uniformise, a.denomination_sexe, a.denomination_grade, a.mail, a.fixe, a.gsm, a.fax, a.CP, a.ville, a.rue, a.numero, a.naissance, a.rrn, a.actif, a.id_service,
	b.denomination_service
	FROM users a
	LEFT JOIN services b ON b.id_service = a.id_service
	WHERE a.id_user=:id';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'id' => $id
	));
	return $req;
}

function updateDonneesUser($id, $tel, $gsm, $fax, $mail){
	$sql='UPDATE users SET fixe=:fixe, gsm=:gsm, fax=:fax, mail=:mail WHERE id_user=:id';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'fixe' => htmlentities($tel),
	'gsm' => htmlentities($gsm),
	'fax' => htmlentities($fax),
	'mail' => htmlentities($mail),
	'id' => $id
	));
}

function getAllUsers($actif=''){
	$sql='SELECT id_user, nom, prenom FROM users';
	$sql.=($actif!='') ? ' WHERE actif="'.$actif.'"' : '';
	$sql.=' ORDER BY nom, prenom';
	return $this->pdo->query($sql);
}

function update($id,$post){
	$lateralite=$post['lateralite'];
	$uniforme=$post['uniforme'];
	$grade=$post['grade'];
	$service=$post['service'];
	$mail=$post['mail'];
	$tel=$post['tel'];
	$gsm=$post['gsm'];
	$fax=$post['fax'];
	$CP=$post['CP'];
	$ville=$post['ville'];
	$rue=$post['rue'];
	$num=$post['num'];
	$login=$post['login'];
	$sql='UPDATE users SET login=:login, lateralite=:lateralite, uniformise=:uniformise, denomination_grade=:grade, id_service=:service, mail=:mail, fixe=:fixe, gsm=:gsm, fax=:fax, CP=:CP, ville=:ville, rue=:rue, numero=:numero WHERE id_user=:id';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'login' => $login,
	'uniformise' => $uniforme,
	'lateralite' => $lateralite,
	'grade' => $grade,
	'service' => $service,
	'mail' => $mail,
	'fixe' => $tel,
	'gsm' => $gsm,
	'fax' => $fax,
	'CP' => $CP,
	'ville' => $ville,
	'rue' => $rue,
	'numero' => $num,
	'id' => $id
	));
}

function addUser($post){
	$nom=$post['nom'];
	$prenom=$post['prenom'];
	$naissance=$post['naissance'];
	$rrn=$post['rrn'];
	$sexe=$post['sexe'];
	$matricule=$post['matricule'];
	$lateralite=$post['lateralite'];
	$uniforme=$post['uniforme'];
	$grade=$post['grade'];
	$service=$post['service'];
	$mail=$post['mail'];
	$tel=$post['tel'];
	$gsm=$post['gsm'];
	$fax=$post['fax'];
	$CP=$post['CP'];
	$ville=$post['ville'];
	$rue=$post['rue'];
	$num=$post['num'];
	$login=$post['login'];
	$sql='INSERT INTO users (login, nom, prenom, matricule, lateralite, uniformise, denomination_sexe, denomination_grade, mdp_user, id_type_user, mail, id_service, actif, log_error, fixe, gsm, fax, CP, ville, rue, numero, naissance, rrn) VALUES (:login, :nom, :prenom, :matricule, :lateralite, :uniformise, :denomination_sexe, :denomination_grade, :mdp_user, :id_type_user, :mail, :id_service, :actif, :log_error, :fixe, :gsm, :fax, :CP, :ville, :rue, :numero, :naissance, :rrn)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'login'=>$login,
	'nom'=>$nom,
	'prenom'=>$prenom,
	'matricule'=>$matricule,
	'lateralite'=>$lateralite,
	'uniformise'=>$uniforme,
	'denomination_sexe'=>$sexe,
	'denomination_grade'=>$grade,
	'mdp_user'=>md5('azerty'),
	'id_type_user'=>"0",
	'mail'=>$mail,
	'id_service'=>$service,
	'actif'=>"O",
	'log_error'=>"0",
	'fixe'=>$tel,
	'gsm'=>$gsm,
	'fax'=>$fax,
	'CP'=>$CP,
	'ville'=>$ville,
	'rue'=>$rue,
	'numero'=>$num,
	'naissance'=>$naissance,
	'rrn'=>$rrn
	));
	$sql='SELECT id_user FROM users WHERE matricule=:mat';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('mat'=>$matricule));
	while($row=$req->fetch()){
		$NEWid=$row['id_user'];
	}
	return $NEWid;
}

public function modifpassword($post,$user){
	$oldPwd=$_POST['oldPwd'];
	$newPwd1=$_POST['newPwd1'];
	$newPwd2=$_POST['newPwd2'];
	$maj=0;
	$min=0;
	$entier=0;
	if((strlen($newPwd1)>7)&&($newPwd1==$newPwd2)){
		for($i=0;$i<strlen($newPwd1);$i++){
			if(is_numeric(substr($newPwd1, $i, $i))){$entier=1;}
			else{
				if(substr($newPwd1, $i, 1)==strtoupper(substr($newPwd1, $i, 1))){$maj=1;}
				if(substr($newPwd1, $i, 1)==strtolower(substr($newPwd1, $i, 1))){$min=1;}
			}			
		}
	}
	else return 1;
	if(($maj!=0)&&($min!=0)&&($entier!=0)){
		$sql='SELECT mdp_user, login FROM users WHERE id_user=:id';
		$req=$this->pdo->prepare($sql);
		$req->execute(array('id'=>$user));
		while ($row=$req->fetch()){
			$pwdbdd=$row['mdp_user'];
			$login=$row['login'];
		}
		if($pwdbdd==md5($oldPwd)){
			$sql='UPDATE users SET mdp_user=:newmdp WHERE id_user=:id';
			$req=$this->pdo->prepare($sql);
			$req->execute(array('newmdp'=>md5($newPwd1), 'id'=>$user));
			$this->login($login,$newPwd1);
			return 0;
		}
		else return 1;
	}
	else return 1;
}

public function getLoginById($id){
	$sql='SELECT login FROM users WHERE id_user=:id';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('id'=>$id));
	while($row=$req->fetch()){
		$login=$row['login'];
	}
	return $login;
}

public function getLogisticiens(){
	$sql='SELECT b.nom, b.prenom, b.id_user 
	FROM grh_droits a 
	LEFT JOIN users b ON b.id_user = a.id_user
	WHERE a.app="logistique" AND a.niv_acces > "7" AND a.id_user <> "1"
	ORDER BY b.nom ASC';
	$req=$this->pdo->prepare($sql);
	$req->execute();
	return $req;
}

}
?>