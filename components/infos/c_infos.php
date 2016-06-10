<?php

class CInfos extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

    
public function verifCo()  //fonction de vérification de l'existence de la variable de session iduser
{
if (isset($_COOKIE["iduser"]) && $_COOKIE["iduser"]>'')
	{
	return true;
	}
}

public function newInfo()
{
	$co=$this->verifCo();
	if($co)
	{
		$this->view->formNewInfo();
	}
}
	
public function pushinfo()
{
	$co=$this->verifCo();
	if ($co)
	{
		$this->model->pushInfo();	
		$this->view->autoClose();
	}
}	

public function showInfos()
{
	$co=$this->verifCo();
	if ($co)
	{
		$infos=$this->model->getInfosPushed();
		$this->view->showInfosPushed($infos);
	}
}
}
?>
