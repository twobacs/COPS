function reloadPage(ancre) 
	{
	// var x =location.href+'#'+ancre;
	// alert (x);
	window.location.href=window.location.href;
	}

function vac_checkNewDemandeur()
	{
	var nom=document.getElementById('nom').value;
	var prenom=document.getElementById('prenom').value;
	var dn=document.getElementById('dn').value;
	
	$.ajax(
		{
		type: "GET",
		url: "js/php/checkNewDemandeur.php",
		data:
			{
			nom : nom,
			prenom : prenom,
			dn : dn,
			},
		success:demStep1,
		error:notOk,
		});
	}
	
function demStep1(rep)
	{
	$('#step1').html(rep);
	}
	
function remploiDonneesHab(id,idDem)
	{
	$.ajax(
		{
		type: "GET",
		url: "js/php/getFormInsertVac.php",
		data:
			{
			id : id,
			dem : idDem,
			},
		success:demStep1,
		error:notOk,
		});	
	}
	
function addFormVV(id)
	{
	var q=document.getElementById('nbVV').value;
	$.ajax(
		{
		type: "GET",
		url: "js/php/getFormAddVV.php",
		data:
			{
			quantite : q,
			idhab : id,
			},
		success:updateVV,
		error:notOk,
		});
	}

function updateVV(rep)
	{
	$('#vv').html(rep);
	}
	
function addFormContact(id)
	{
	var q=document.getElementById('nbContact').value;
	// alert (q);
	$.ajax(
		{
		type: "GET",
		url: "js/php/getFormAddContact.php",
		data:
			{
			quantite : q,
			idhab : id,
			},
		success:updateContact,
		error:notOk,
		});
	}	
	
function updateContact(rep)
	{
	$('#contact').html(rep);
	$('#boutonEnregistrer').html('<tr><td colspan="4"><input type=submit value="Enregistrer"></td></tr>');
	}
	
function moreInfos(id,lvl)
	{
	$.ajax(
		{
		type: "GET",
		url: "js/php/getMoreInfosVac.php",
		data:
			{
			idhab : id,
			level:lvl,
			},
		success:function(html){
		$('#'+id).html(html);
		},
		error:notOk,
		});
	}
	
function lessInfos(id,lvl)
	{
	$('#'+id).html('');
	}

function updateDiv(html,id)
	{
	$('#'+id).html(html);
	}

	
function cleanDiv(id)
	{
	// var div='moreInfos'+id;
	// alert (div);
	$('#'+id).html('');
	}
	
function showContact(adresse,num,CP,ville,tel,tel2)
	{
	alert ('Adresse contact : \n'+adresse+' '+num+'\n'+CP+' '+ville+'.\n'+tel+'\n'+tel2);
	// $('#Left').html('<b>Adresse contact :</b><br />'+adresse+' '+num+'<br />'+CP+' '+ville+'<br />'+tel+'<br />'+tel2);
	}
	
function updateSearchForm(type)
	{
	$.ajax(
		{
		type: "GET",
		url: "js/php/updateSearchForm.php",
		data:
			{
			type : type,
			},
		success:function(html){
		$('#searchForm').html(html);
		},
		error:notOk,
		});	
	}

function RAS(idvac,iduser,idpat)
	{
	var td=document.getElementById(idvac).innerHTML;
	// if (navigator.geolocation)
		// {
		  // navigator.geolocation.getCurrentPosition(function(position)
		  // {
			// var latitude = position.coords.latitude;
			// var longitude = position.coords.longitude;
			 $.ajax(
				{
				type: "GET",
				url: "js/php/vacancier/ras.php",
				data:
					{
					vac : idvac,
					user : iduser,
					pat : idpat,
					// lat : latitude,
					// long : longitude,
					},
				success:function(html){
				$('#'+idvac).html(td+html);
				},
				error:notOk,
				}); 
		  // });
		// }
	// else
		// {
		// $('#'+idvac).html('<b>Adresse contact :</b><br />');
		// }
	}

function incident(idvac,iduser,idpat)
	{
	var td=document.getElementById(idvac).innerHTML;
	$('#'+idvac).html(td+'<br /><b>Incident constaté :</b><br /><textarea id="remarques" rows="5" cols="15"></textarea><br /><input type=button value="Envoyer" onclick=recordIncident('+idvac+','+iduser+',"'+idpat+'");>');	
	}
	
function recordIncident(idvac,iduser,idpat)
	{
	var td=document.getElementById(idvac).innerHTML;	
	var comment=document.getElementById('remarques').value;
	// if (navigator.geolocation)
		// {
		  // navigator.geolocation.getCurrentPosition(function(position)
		  // {
			// var latitude = position.coords.latitude;
			// var longitude = position.coords.longitude;
			 $.ajax(
				{
				type: "GET",
				url: "js/php/vacancier/incident.php",
				data:
					{
					vac : idvac,
					user : iduser,
					// lat : latitude,
					pat : idpat,
					// longi : longitude,
					commentaire : comment,
					},
				// success:function(html){
				// $('#'+idvac).html(td+html);
				// },
				success:function(){window.location.reload(), alert('Enregistrement effectué');},
				error:notOk,
				}); 
		  // });
		// }	
	// else
		// {
			// alert('Veuillez activer la géolocalisation');
		// }	
	}

function searchVac()
	{
	var machin=document.getElementById("searchVacancier").value;
	//$('#rep').html(machin);
	 $.ajax(
		{
		type: "GET",
		url: "js/php/vacancier/searchVac.php",
		data:
			{
			champs : machin,
			},
		success:updateRep,
		error:notOk,
		}); 
	}
	
function notOk()
	{
	alert ('Une erreur s\'est produite.');	
	}

function updateRep(html)
	{
	$('#rep').html(html);	
	}

function addInfo(type,idVac)
	{
	// alert('#'+type);
	$.ajax(
		{
		type: "GET",
		url: "js/php/vacancier/addInfo.php",
		data:
			{
			type : type,
			idVac : idVac,
			},
		success:function (html){
		$('#'+type).html(html);
		},
		error:notOk,			
		});
	}
	
function delVV(idVV,idVac,ancre)
	{
	$.ajax(
		{
		type: "GET",
		url: "js/php/vacancier/delVV.php",
		data:
			{
			vv : idVV,
			vac : idVac,
			},
		// success:reloadPage(ancre),
		success:location.reload(),
		error:notOk,
		});
	}

function addVV(idvac)
	{
	$.ajax(
		{
		type: "GET",
		url: "js/php/vacancier/addInfo.php",
		data:
			{
			type : 'AddVV',
			idVac : idvac,
			},
		success:function (html){
		$('#AddVV').html(html);
		},
		error:notOk,
		});	
	}
	
function delContact(idCont)
	{	
	$.ajax(
		{
		type: "GET",
		url: "js/php/vacancier/delContact.php",
		data:
			{
			id : idCont,
			},
		// success:reloadPage(ancre),
		success:location.reload(),
		error:notOk,
		});	
	}
	
function addRowToCr(idVac){
	// alert(idVac);
		$.ajax(
		{
		type: "GET",
		url: "js/php/vacancier/addRowToCr.php",
		data:
			{
			idVac : idVac,
			},
		success:function(retour){$('#rowToAdd').html(retour);},
		error:notOk,
		});	
}