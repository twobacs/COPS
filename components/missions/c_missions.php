<?php

class CMissions extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

	
public function verifCo()
	{
	if (isset($_COOKIE['iduser']) && $_COOKIE['iduser']>'')
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
	// **************** ANCIEN SYSTEME **************** //
	// $level=$this->model->getNivAcces();
	// $teams=$this->model->getNextTeams();
	// $missionsByTeam=$this->model->getQMissionByTeam();
	// $_SESSION['appli']='missions';
	// $this->view->showMenu($level,$teams,$missionsByTeam);
	
	// **************** NOUVEAU SYSTEME **************** //
	$_SESSION['appli']='missions';
	$level=$this->model->getNivAcces();
	$this->view->showMenu($level);
	}
	
public function addMission()
	{
	if ($this->model->getNivAcces()>29)
		{
		$pat=$_GET['pat'];
		$level=$this->model->getNivAcces();
		$prest=$_GET['idprest'];
		switch ($prest)
			{
			case 0 :
				switch ($_GET['step'])
					{
					case 1:
						//liste missions déjà attribuées
						$missions=$this->model->getMissionsByIdPat($pat);
						$vacAttrib=$this->model->getVacanciersByIdPat($pat);
						
						//liste missions COPS
						$cops=$this->model->getMissionsCopsActifs($pat);
						
						//liste missions VACANCIERS
						$vacanciers=$this->model->getVacanciersActifs($pat);
						
						//liste missions VACANCIERS
						$otherMissions=$this->model->getOtherMissionsByIdPat($pat);
						
						$this->view->addMission($level,$missions,$prest,$cops,$vacanciers,$pat,$vacAttrib,$otherMissions);
						
						
						break;
					case 2:
						if ($_GET['mission']=='cops')
							{
							$this->model->recMissionCops();
							}
						else if($_GET['mission']=='vacanciers')
							{
							$this->model->recMissionVacanciers();
							}
						else if($_GET['mission']=='autre')
							{
							$this->model->recMissionAutre();
							}
						$missions=$this->model->getMissionsByIdPat($pat);
						$vacAttrib=$this->model->getVacanciersByIdPat($pat);
						$otherMissions=$this->model->getOtherMissionsByIdPat($pat);
						$cops=$this->model->getMissionsCopsActifs($pat);
						$vacanciers=$this->model->getVacanciersActifs($pat);
						$this->view->addMission($level,$missions,$prest,$cops,$vacanciers,$pat,$vacAttrib,$otherMissions);						
						break;
					default:
						echo 'aucune action pour cette étape';
						break;
					}
				break;
			
			case 1 :
				
				break;
			}
		}
	else
		{
		$this->view->nonco();
		}
	
	}
	
public function removeMission()
	{
	$this->model->removeMission();
	$pat=$_GET['pat'];
	$level=$this->model->getNivAcces();
	$prest=$_GET['idprest'];
	$missions=$this->model->getMissionsByIdPat($pat);
	$vacAttrib=$this->model->getVacanciersByIdPat($pat);
	$cops=$this->model->getMissionsCopsActifs($pat);
	$vacanciers=$this->model->getVacanciersActifs($pat);
	$this->view->addMission($level,$missions,$prest,$cops,$vacanciers,$pat,$vacAttrib);	
	}
	
public function SPCops()
	{
	include_once ('./components/users/c_users.php');
	$action=new CUsers($this->appli);
	$this->model->SPCops();
	$action->fromMenuTablette();
	}
	
public function comCOPS()
	{
	include_once ('./components/users/c_users.php');
	$action=new CUsers($this->appli);
	$this->model->comCops();
	$action->fromMenuTablette();
	}
	

}
?>