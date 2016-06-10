<?php

$section=$_GET['sec'];
$categ=$_GET['categ'];
$now=date("Y-m-d\TG:i");

$html='<table>';
$html.='<tr><th>Titre :</th><td colspan="3"><textarea rows="2" cols="50" name=txtInfo autofocus placeholder="300 caractères maximum" required></textarea></td></tr>';
$html.='<tr><th width=25%>Lien avec des personnes :</th><td width=25%><select name=qPers>';
for ($i=0;$i<10;$i++)
	{
	$html.='<option value="'.$i.'">'.$i.'</option>';
	}
$html.='</td><th width=25%>Lien avec des véhicules :</th><td width=25%><select name=qVV>';
for ($i=0;$i<10;$i++)
	{
	$html.='<option value="'.$i.'">'.$i.'</option>';
	}
$html.='</td></tr>';

$html.='<tr><th width=25%>Lien avec des lieudits :</th><td width=25%><select name=qLDits>';
for ($i=0;$i<10;$i++)
	{
	$html.='<option value="'.$i.'">'.$i.'</option>';
	}
$html.='</td><th width=25%>Lien avec des commerces :</th><td width=25%><select name=qCommerces>';
for ($i=0;$i<10;$i++)
	{
	$html.='<option value="'.$i.'">'.$i.'</option>';
	}
$html.='</td></tr>';

$html.='<tr><th width=25%>Textes complémentaires :</th><td width=25%><select name=qTxt>';
for ($i=0;$i<10;$i++)
	{
	$html.='<option value="'.$i.'">'.$i.'</option>';
	}
$html.='</td><th width=25%>Lien avec des photos :</th><td width=25%><select name=qPics>';
for ($i=0;$i<10;$i++)
	{
	$html.='<option value="'.$i.'">'.$i.'</option>';
	}
$html.='</td></tr>';
$html.='<tr><th>Valable du :</th><td><input type="datetime-local" name="HrBasse" value="'.$now.'" required></td><th>au :</th><td><input type="datetime-local" name="HrHaute" min="'.$now.'"></td></tr>';

$html.='<tr><th>Interaction souhaitée ?</th><td>Oui : <input type=radio name=interaction value="O"> Non : <input type=radio name=interaction value="N" checked></td><td colspan="2" class=noborder><input type=submit value="Valider"></td></tr></table>';
echo $html;

?>