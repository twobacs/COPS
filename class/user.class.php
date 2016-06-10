<?php

class User
{
public $idUser;
public $loginUser;
public $nomUser;
public $prenomUser;
public $mdpUser;
public $typeUser;
public $serviceUser;
public $lateraliteUser;
public $gradeUser;
private $pdo;



public function __construct($dbPdo)
	{
	$this->pdo = $dbPdo;
	}

public function loadViaLogin($login,$password) //CONNEXION DE L'UTILISATEUR SUR BASE DU LOGIN
	{
	$return=array();
	$sql='SELECT * FROM users WHERE login="'.$login.'"';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$mdp=$row['mdp_user'];
		$id=$row['id_user'];
		$error=$row['log_error'];
		$prenom=$row['prenom'];
		}
	$ipClient=$_SERVER["REMOTE_ADDR"];
	$heure=date("H:i");	
	//VERIFICATION DE MOT DE PASSE - DIFFERENT DE "azerty" 
	if(isset($id))
		{
		if($error<3)
			{
			if ((md5($password) == $mdp) && (md5($password)!=md5('azerty'))) 
				{
				//INSERTION DU LOG EN BDD
				setcookie("iduser",$id, (time()+36000));
				$_SESSION['idUser']=$id;
				$sql='INSERT INTO z_logs (id_user,ip_user,date_in,heure_in) VALUES ("'.$id.'","'.$ipClient.'",NOW(),"'.$heure.'")';
				$this->pdo->exec($sql);
				$sql='UPDATE users SET log_error="0" WHERE id_user="'.$id.'"';
				$this->pdo->exec($sql);
				$return['login']=1;
				$return['idUser']=$id;
				$return['prenom']=$prenom;
				// return 1;
				}
			
			//VERIFICATION DE MOT DE PASSE - EGAL A "azerty" 
			else if ((md5($password) == $mdp) && (md5($password)==md5('azerty')))
				{
				// $_COOKIE['iduser']=$id;
				setcookie("iduser",$id, (time()+36000));
				$_SESSION['idUser']=$id;
				// $_COOKIE["iduser"]=$id;
				session_set_cookie_params(36000);
				$return['login']=2;
				// return 2;
				}
				
			else 
				{
				//INSERTION DU LOG ERRONE EN BDD SI LE LOGIN EXISTE - LIMITE A 3, SI ATTEINTE -> COMPTE BLOQUE
				$sql='SELECT log_error FROM users WHERE id_user="'.$id.'"';
				$rep=$this->pdo->query($sql);
				while ($row=$rep->fetch())
					{
					$essai=$row['log_error'];
					}
				$essai++;
				$sql='UPDATE users SET log_error="'.$essai.'" WHERE id_user="'.$id.'"';
				$this->pdo->exec($sql);
				$return['login']=3;
				// return 3;
				}
			}
		else $return['login']=4; //return 4; //COMPTE BLOQUE
		}
	else $return['login']=3;//return 3;
	return $return;
	}
	
public function logoff($id)
	{
	$sql='UPDATE z_logs SET date_out=NOW(), heure_out="'.date("H:i").'" WHERE id_user="'.$id.'" AND date_out="0000-00-00"';
	$this->pdo->exec($sql);
	}
	
public function setNewPassword($pwd)
	{
	$id=$_COOKIE["iduser"];
	$sql='UPDATE users SET mdp_user="'.md5($pwd).'" WHERE id_user="'.$id.'"';
	$this->pdo->exec($sql);
	}
	
public function selectUserById($id)
	{
	$sql='SELECT id_user, login, nom, prenom, matricule, denomination_grade, mail, id_service FROM users WHERE id_user="'.$id.'"';
	$rep=$this->pdo->query($sql);
	return $rep;
	}
	
public function selectAllUsers()
	{
	$sql='SELECT id_user, login, nom, prenom, matricule, denomination_grade, mail, id_service FROM users ORDER BY nom, prenom DESC';
	return $this->pdo->query($sql);
	}

}
?>