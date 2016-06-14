<?php

class CPersonnel extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }


public function home(){
	if(isset($_SESSION['idUser'])){
		$niv=$this->model->nivAcces();
		$this->view->accueil($niv);
	}
	else $this->view->unconnected();
}

public function gestUsers(){
	if((isset($_SESSION['idUser']))&&($_SESSION['appli']=='personnel')&&($_SESSION['acces']=='10')){
		if((!isset($_GET['error']))||($_GET['error']=='0')){
			$droits=$this->model->getDroitsLog();
			$users=$this->model->getUsers();
			$this->view->gestDroits($droits,$users);
		}
		else $this->view->errorRec(2,'gestUsers');
	}
	else $this->view->error();
}

}
?>
