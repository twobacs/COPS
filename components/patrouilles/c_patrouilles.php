<?php

class CPatrouilles extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

    
public function verifCo()  //fonction de vérification de l'existence de la variable de session iduser
	{
	if (isset($_COOKIE['iduser']) && $_COOKIE['iduser']>'')
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
		$_SESSION['appli']='patrouilles';
		$_SESSION['nivApp']=$level;
		}
	}
	
public function newPat()
	{
	$co=$this->verifCo();
	$level=$this->model->checkLevel();
	if (($co) && ($level>29))
		{
		if (isset($_GET['type']))
			{
			switch ($_GET['type'])
				{
				case 'pmob':
					$this->view->formNewPMob();
					break;
				case 'autre':
					$data=$this->model->getFonctionnalites();
					$this->view->formNewAutrePat($data);
					break;
				case 'recu':
					$data=$this->model->getFonctionnalites();
					$this->view->formPatRecu($data);
					break;
				}
			}
		
		else if (isset($_GET['rec']))
			{
			switch ($_GET['from'])
				{
				case 'pmob' :
					$data=$this->model->recNewPMob();
					$this->view->okNewPat($data);
					break;
					
				case 'other' :
					$data=$this->model->recNewAutrePat();
					$this->view->okNewPat($data);
					break;
					
				case 'PMR' :
					$data=$this->model->recNewPMobRecu();
					$this->view->okNewPatRec($data);
					break;
					
				case 'OtherR' :
					$data=$this->model->recNewAutrePatRecu();
					$this->view->okNewPatRec($data);
					break;
				}
			}
		else $this->view->formNewPat();
		}
	}
	
public function viewPat()
	{
	$co=$this->verifCo();
	$level=$this->model->checkLevel();
	if (($co) && ($level>29))
		{
		$data=$this->model->viewPat();
		$this->view->viewPat($data);
		}		
	}
	
public function searchPat()
	{
	$co=$this->verifCo();
	$level=$this->model->checkLevel();
	if (($co) && ($level>29))
		{
		if (isset($_POST['dhb']))
			{
			$data=$this->model->searchPat();
			$dhb=(empty($_POST['dhb'])) ? date('Y-m-d').'T'.'00:00:00' : $_POST['dhb'];
			$dhh=(empty($_POST['dhh'])) ? date('Y-m-d').'T'.'23:59:59' : $_POST['dhh'];
			// $this->view->HistoPat($data, $_POST['dhb'], $_POST['dhh']);
			$this->view->HistoPat($data, $dhb, $dhh);
			}
		else
			{
			$this->view->formSearchPat();
			}
		}
	}
	
}
?>