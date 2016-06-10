<?php

include ('../connect.php');
include ('../../../class/cops.class.php');

if (isset($_GET['sec']))
	{
	$section=$_GET['sec'];
	$cop = new Cops($pdo);
	$categs=$cop->getCategBySection($section);
	$html='<select name=categ id=get_Categ onchange=getLaSuite();><option value=""></option>';
	while ($row=$categs->fetch())
		{
		$html.='<option value="'.$row['id_categ'].'">'.$row['denomination'].'</option>';
		}
	$html.='</select>';
	echo $html;
	}

?>