<?php

class CBs extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

public function verifCo()
	{
	if (isset($_COOKIE['iduser']) && ($_COOKIE['iduser']>""))
		{
		return true;
		}
	else 
		{
		$this->view->nonco();
		return false;
		}
	}

public function mainMenu(){
	$co=$this->verifCo();
	if ($co){
		$level=$this->model->getNivAcces();
		$_SESSION['appli']='bs';
		$_SESSION['nivApp']=$level;
		
		$data=$this->model->getBS();
		$this->view->afficheListBS($data);
	}
}
}
?>