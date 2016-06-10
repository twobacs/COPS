<?php

if (isset($_GET['type']))
	{
	include ('../connect.php');
	include_once ('../../../class/patrouilles.class.php');
	$type=$_GET['type'];
	$html='<form method=POST action="?component=patrouilles&action=newPat';
	switch ($type)
		{
		case '0':
			$html.='"><table><th width="100%">Veuillez sélectionner un type de patrouille</th>';
			break;
		
		case 'PM':
			$html.='&rec=rec&from=PMR"><table><tr><th width="50%">Dénomination :</th><td><input type=text id="0" name="denPat" autofocus required></td></tr>';
			$html.='<tr><th>Indicatif :</th><td><input type=text id="1" name="indicPat" required></td></tr>';
			$html.='<tr><th>Date début :</th><td><input type="date" id="2" name="dateDebut" required></td></tr>';
			$html.='<tr><th>Date fin :</th><td><input type="date" id="3" name="dateFin" required></td></tr>';
			$html.='<tr><th>Horaire :</th><td>De <input type="time" id="4" name="hDebut" required> à <input type="time" id="5" name="hFin"></td></tr>';
			$html.='<tr><th>Récurrence :</th><td>Tous les <select name="recurrence" required>';
			for($i=1;$i<8;$i++)
				{
				$html.='<option value="'.$i.'">'.$i.'</option>';
				}
			$html.='</select> jour(s)</td></tr>';			
			$html.='<tr><td colspan="2" class=noborder><input type=submit value="Enregistrer"><input type=reset></td></tr>';
			break;
			
		case 'Other':
			$patrouille = new Patrouille($pdo);
			$data = $patrouille->getFonctionnalites();
			$html.='&rec=rec&from=OtherR"><table><tr><th width="50%">Dénomination :</th><td><input type=text id="0" name="denPat" autofocus required></td></tr>';
			$html.='<tr><th>Indicatif :</th><td><input type=text id="1" name="indicPat" required></td></tr>';
			$html.='<tr><th>Fonctionnalité :</th><td>';
			$html.='<select id ="2" name="fonctionnalite" onchange="upPresta();" required><option value=""></option>';
			while ($row=$data->fetch())
				{
				$html.='<option value="'.$row['id_fonctionnalite'].'">'.$row['denomination'].'</option>';
				}
			$html.='</select></td></tr>';
			$html.='<tr><th>Prestation :</th><td><div id="presta"><select name="prest" id="3" required><option value=""></option></select></div></td></tr>';
			$html.='<tr><th>Date début :</th><td><input type="date" id="4" name="dateDebut" required></td></tr>';
			$html.='<tr><th>Date fin :</th><td><input type="date" id="5" name="dateFin" required></td></tr>';
			$html.='<tr><th>Horaire :</th><td>De <input type="time" id="6" name="hDebut" required> à <input type="time" id="7" name="hFin"></td></tr>';
			$html.='<tr><th>Récurrence :</th><td>Tous les <select name="recurrence" required>';
			for($i=1;$i<8;$i++)
				{
				$html.='<option value="'.$i.'">'.$i.'</option>';
				}
			$html.='</select> jour(s)</td></tr>';
			$html.='<tr><td colspan="2" class=noborder><input type=submit value="Enregistrer"><input type=reset></td></tr>';
			break;			
		}
	$html.='</table></form>';
	echo $html;
	}

?>