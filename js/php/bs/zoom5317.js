function afficheOk(rep)
	{

	}
	
function notOk()
	{
	alert ('Une erreur s\'est produite');
	}
	
function reloadPage() 
	{
	window.location.href=window.location.href;
	}

function changeDroits(id,app)
	{
	var newNiv=document.getElementById('NivAcces_'+id);
	var newNivUser=newNiv.value;
	
	$.ajax(
		{
		type: "GET",
		url: "js/php/updateDroitApp.php",
		data:
			{
			idUser : id,
			idApp : app,
			idNiv : newNivUser,
			},
		success:reloadPage,
		error:notOk,
		});
	}
	
 function updateRep(rep)
	{
	$('#rep').html(rep);
	}
	
function tablette()
	{
	var tablette=document.getElementById('mode').value;
	if (tablette==1)
		{
		$('#trCol').html('');
		$('#trVV').html('');
		}
	else if (tablette==3)
		{
		$('#trCol').html('');
		$('#trVV').html('');
		$('#trLog').html('');
		$('#trPass').html('');
		// alert('La plateforme "Gestion et ressources" n\'est pas encore disponible');
		}	
	else if (tablette==2)
		{
	$.ajax(
		{
		type: "GET",
		url: "js/php/zoom5317/rsgtsTab.php",
		data:
			{
			mode : tablette,
			},
		success:function(html){$('#trCol').html(html);},
		error:notOk,
		});
	$.ajax(
		{
		type: "GET",
		url: "js/php/zoom5317/rsgtsVV.php",
		data:
			{
			mode : tablette,
			},
		success:function(html){$('#trVV').html(html);},
		error:notOk,
		});		
		}
	}
	
function collabos()
	{
	var nb=document.getElementById('nbCol').value;
		$.ajax(
		{
		type: "GET",
		url: "js/php/collabos.php",
		data:
			{
			nb : nb,
			},
		success:updateCollabos,
		error:notOk,
		});
	}
	
function updateCollabos(rep)
	{
	$('#collabos').html(rep);
	}
	
function verifForm(rows)
	{
	var erreur = new Array();
	var bouton = 0;
	for (var i=0;i<=rows;i++)
		{
		verif = document.getElementById(i).value;
		if (verif=="")
			{
			document.getElementById(i).style.backgroundColor = "#E8582E";
			erreur[i]=1;
			}
		else
			{
			document.getElementById(i).style.backgroundColor = "#ffffff";
			erreur[i]=0;
			}
		}
	for (var i=0;i<=rows;i++)
		{
		if (erreur[i]==1)
			{
			bouton++;
			}
		}
	if (bouton==0)
		{
		return true;
		}
	else 
		{
		return false;
		}
		
	}
