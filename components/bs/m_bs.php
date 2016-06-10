<?php

class MBs extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}
	
public function getNivAcces()
	{
	$sql='SELECT id_nivAcces FROM z_user_app WHERE id_user="'.$_COOKIE['iduser'].'" AND id_app="9"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$nivAcces=$row['id_nivAcces'];
		}
	return $nivAcces;
	}
	
public function getBS()
	{
	$orderBy=(isset($_GET['orderBy'])) ? $_GET['orderBy']	 : false ;
	$date=(isset($_GET['date'])) ? $_POST['dateBS'] : '';
	$tri=(isset($_GET['tri'])) ? $_GET['tri'] : 0;
	$print=(isset($_GET['print'])) ? $_GET['print'] : 'N';
	$sql='SELECT 
	a.id_bs, a.date_heure_in, a.date_heure_out, a.id_patrouille,
	b.indicatif, b.denomination, b.date_heure_debut, b.date_heure_fin
	FROM z_bs a
	LEFT JOIN z_patrouille b ON b.id_patrouille = a.id_patrouille';
	switch ($print)
		{
		case 'N' :
			$sql.=' WHERE a.printed="N"';
			break;
		
		case 'O':
			$sql.=' WHERE a.printed="O"';
			break;			
		}
	
	$sql.=($date!='') ? ' AND b.date_heure_debut > "'.$date.' 00:00:00" AND b.date_heure_debut < "'.$date.' 23:59:59"' : '';
	$sql.=' AND b.indicatif IS NOT NULL AND b.date_heure_fin<NOW() ';	
	$sql.='ORDER BY ';
	switch($orderBy){
		case 'indicatifUp' :
			$sql.='b.indicatif ASC';
			break;
		case 'indicatifDown' :
			$sql.='b.indicatif DESC';
			break;
		case 'DeUp' :
			$sql.='b.date_heure_debut ASC';
			break;
		case 'DeDown' :
			$sql.='b.date_heure_debut DESC';
			break;
		case 'AUp' :
			$sql.='b.date_heure_fin ASC';
			break;
		case 'ADown' :
			$sql.='b.date_heure_fin DESC';
			break;
		default : 
			$sql.='b.date_heure_debut DESC';
			break;
	}
	return $this->appli->dbPdo->query($sql);
	}
	
}
?>
