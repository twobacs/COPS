function updateRep(rep)
	{
	$('#rep').html(rep);
	}
	
function moreOptions()
	{
	updateRep('<ul><li>Sur base du <a href=?component=rues&action=modifRue&type=OneRue>nom de rue</a>.</li><li>Obtenir une <a href=?component=rues&action=modifRue&type=ListRues>liste</a> des rues.</li></ul>');
	}
	
function searchRue()
	{
	var val=document.getElementById('nomRue').value;
	$.ajax(
			{
			type: "GET",
			url: "js/php/searchRue.php",
			data:
				{
				nom : val,
				},
			success:updateRep,
			error:notOk,
			});	
	}
	
function updateRue(id)
	{
	var nom=document.getElementById('nom').value;
	var naam=document.getElementById('naam').value;
	var ok=confirm('Etes vous sûr de vouloir apporter ces modifications ?');
	if (ok)
		{
		$.ajax(
			{
			type: "GET",
			url: "js/php/updateRue.php",
			data:
				{
				id : id,
				nom : nom,
				naam : naam,
				},
			success:updateRep,
			error:notOk,
			});
		}
	else
		{
		alert ('Ok, on abandonne cette idée');
		}
	}
	
function addRue()
	{
	var nom=document.getElementById('newNom').value;
	var naam=document.getElementById('newNaam').value;
	
	if ((nom=='') || (naam==''))
		{
		alert ('Veuillez compléter tous les champs.');
		}
	
	else
		{
		var ok=confirm('Etes-vous sûr de vouloir enregistrer les valeurs suivantes ? \nNouvelle rue : '+nom+' \nNieuwe straat : '+naam);
		if (ok)
			{
			$.ajax(
				{
				type: "GET",
				url: "js/php/addRue.php",
				data:
					{
					nom : nom,
					naam : naam,
					},
				success:updateRep,
				error:notOk,
				});
			}
		}
	}