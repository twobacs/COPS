<?php

include ('../connect.php');
include ('../../../class/quartier.class.php');

if (isset($_GET['agent']))
	{
	$html='';
	$id=$_GET['agent'];
	if ($id>''){
		$agent=new Quartier($pdo);
		$rep=$agent->getInfosAgents($id);
		$antenne=$agent->getInfosQuartiers();
		$html.='<table>';
		while ($row=$rep->fetch()){
			if(isset($row['denomination'])){		
				$html.='<tr><th width=48%>Quartier actuellement associé :</th><td>'.$row['denomination'].'</td></tr>';
				}
			else{
				$html.='<tr><th width=48%>Quartier actuellement associé :</th><td>AUCUN</td></tr>';
				}
			}
		$html.='<tr><th width=48%>Nouveau quartier à associer (remplace le précédent) :</th><td><select name=newQuart Id=newQuart><option value=""></option>';	
		while ($row=$antenne->fetch()){
			$html.='<option value='.$row['id_quartier'].'>'.$row['denomination'].'</option>';
			}
		$html.='</td></tr>';
		$html.='<tr><td colspan="2" class=noborder><input type=button onClick=updateAQQuartier(); value="Enregistrer"></td></tr>';
		$html.='</table>';
		}
	echo $html;
	}

?>