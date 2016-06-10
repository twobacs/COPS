<?php

if (isset($_SESSION['appli']))
{
$appli = $_SESSION['appli'];

$level = (isset($_SESSION['nivApp']) ? $_SESSION['nivApp'] : '');

switch ($appli)
	{
	//APPLICATION COPS
	case 'cops' :
		$html='<a href=?component=cops&action=listing><img src="'.MEDIA.'/icons/list.png" height="100%" title="Liste info COPS"></a>';
		if (($level=='10') || ($level=='20') || ($level=='30') || ($level=='50'))
			{
			$html.='<a href=?component=cops&action=newInfoCops><img src="'.MEDIA.'/icons/buttonAdd.png" height="100%" title="Ajouter info COPS"></a>';
			$html.='<a href=?component=cops&action=editInfoCops><img src="'.MEDIA.'/icons/edit.png" height="100%" title="Editer info COPS"></a>';
			}
		$html.='</ul>';
		break;
	
	//*******************************************************
	
	//APPLICATION VACANCIER
	case 'vacancier' :
		$html='';
		break;
	
	//*******************************************************
	
	default :
		$html='';
	}
$this->botCentre=$html;
}

?>