<?php

class CFinances extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }


public function home(){
	if(isset($_SESSION['idUser'])){
		$_SESSION['appli']='gestpers';
		$_SESSION['nivAcces']=$this->model->nivAcces();
		$this->view->accueil();
	}
	else $this->view->unconnected();
}

}
?>
