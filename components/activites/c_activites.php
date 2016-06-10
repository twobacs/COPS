<?php

class CActivites extends CBase {

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
	
public function mainMenu()
	{
	$this->view->mainMenu();
	}
	
public function actionByPm()
	{
	$this->model->controleByPm();
	switch($_GET['from'])
		{
		case 'modleft':
			include_once ('./components/users/c_users.php');
			$action=new CUsers($this->appli);
			$action->fromMenuTablette();
			break;
		case 'activite':
			$this->view->mainMenu();
			break;
		}
	}	
	
public function intervention()
	{
	if (!isset($_POST['numFiche']))
		{
		// $this->model->verifCookieActivite();
		$data=$this->model->checkInterEncours();
		$this->view->newIntervention(date('Y-m-d H:i:s'), $data);
		}
	else 
		{
		$this->model->recNumFicheByIdPat();
		}
	}
	
public function patrouille(){
	if((isset($_GET['end']))&&($_GET['end']=="O"))
		{
		$this->model->EndPat();
		include_once ('./components/users/c_users.php');
		$action=new CUsers($this->appli);
		$action->fromMenuTablette();
		}
	else
		{
		if(((!isset($_SESSION['Pat']))) OR ($_SESSION['Pat']==0))
			{
			$this->model->StartPat();
			$data=$this->model->getLieuxPat();
			$this->view->SvPat($data);
			}
		else
			{
			$data=$this->model->getLieuxPat();
			$this->view->SvPat($data);
			}
		}
}	
	
public function GenBS()
	{
	$data=$this->model->getInfosBS();
	$this->view->genBS($data);
	return ($data);
	}
	
public function SvInt()
	{
	
	if((isset($_GET['end']))&&($_GET['end']=="O"))
		{
		$this->model->EndSvInt();
		include_once ('./components/users/c_users.php');
		$action=new CUsers($this->appli);
		$action->fromMenuTablette();
		}
	else
		{
		if(((!isset($_SESSION['SvInt']))) OR ($_SESSION['SvInt']==0))
			{
			$this->model->StartSvInt();
			$this->view->SvInt();
			}
		else
			{
			$this->view->SvInt();
			}
		}
	}
	
public function modifBS(){
	$co=$this->verifCo();
	if($co){
		switch($_GET['part']){
			case 'collabos':
				$data=$this->model->getPerso();
				break;
			case 'matos':
				$data=$this->model->getMatos();
				break;
			case 'VV':
				$data=$this->model->getVV();
				break;
		}
		header("Pragma: no-cache");
		$this->view->formModifBS($_GET['part'], $data, $_GET['idBS']);
		
	}
}
}
?>