<?php
if ((isset($_REQUEST['mode'])) && ($_REQUEST['mode']=='m'))
{
	$this->cssFile='<link rel="stylesheet" href="templates/mytpl/css/CSStablette.css">';
}

else if ((isset($_REQUEST['mode'])) && ($_REQUEST['mode']=='pop'))
{
	$this->cssFile='<link rel="stylesheet" href="templates/mytpl/css/pop.css">';
	$this->tplIndex = 'pop.html';
}

else if ((isset($_REQUEST['mode'])) && ($_REQUEST['mode']=='newInfo'))
{
	$this->cssFile='<link rel="stylesheet" href="templates/mytpl/css/newInfo.css">';
	$this->tplIndex = 'newInfo.html';
}

else
{
	$this->cssFile='<link rel="stylesheet" href="templates/mytpl/css/style.css">';
}


?>