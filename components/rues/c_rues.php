<?php

class CRues extends CBase {

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

public function mainMenu()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->checkLevel();
		$this->view->mainMenu($level);
		}
	}
	
public function modifRue()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->checkLevel();
		if ($level<30)
			{
			$this->view->nonDroit();
			}
		else
			{
			$type=$_GET['type'];
			switch ($type)
				{
				case 'OneRue':
					if (isset($_GET['id']))
						{
						$rue=$this->model->selectRueById($_GET['id']);
						$this->view->afficheInfosRue($rue);
						}
					else
						{
						$this->view->modifOneRue();
						}
					break;
					
				case 'ListRues':
					$rues=$this->model->selectRues();
					$this->view->showAllRues($rues);
					break;
				}
			}
		}
	}
	
public function addRue()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->checkLevel();
		if ($level<30)
			{
			$this->view->nonDroit();
			}
		else
			{
			if (isset($_POST['newNom']))
				{
				$this->model->insertNewRue();
				}
			else
				{
				$this->view->formAddRue();
				}
			}
		}
	}
}
?>