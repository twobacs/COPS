<?php

if (isset($_GET['id'])){
	switch ($_GET['type'])
		{
		case 'C':
			$html='index.php?mode=pop&component=cops&action=moreInfos&idFiche='.$_GET['id'];
			break;
		case 'V':
			$html='index.php?mode=pop&component=vacancier&action=infoVac&idVac='.$_GET['id'];
			break;
		}
	echo $html;
}
?>