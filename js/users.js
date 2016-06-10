function RecNewUser(){
	var nom=document.getElementById('nom').value;
	var prenom=document.getElementById('prenom').value;
	var login=document.getElementById('login').value;
	var matricule=document.getElementById('matricule').value;
	var sexe=document.getElementById('sexe').value;
	var grade=document.getElementById('grade').value;
	var mail=document.getElementById('mail').value;
	var service=document.getElementById('service').value;
		
	var verif = new Array();
	
	//------------------------//
	//Contrôles sur les champs//
	//------------------------//
	
	verif[0] = ((nom>'') ? true : false);
	verif[1] = ((prenom>'') ? true : false);
	verif[2] = ((login>'') ? true : false);
	verif[3] = ((isEmail(mail)==true) ? true : false);
	
	//-------------------------//
	//Controle sur le matricule//
	//-------------------------//
	if (matricule=='')
		{
		matricule=000000000;
		}
	else
		{
		var controle=matricule.substr(7,2);
		var entete=matricule.substr(0,7);
		if (controle==(entete % 97))
			{
			verif[4]=true;
			}
		else {verif[4]=false;}
		}

	
	//-------------------------------------//
	//Coloration des inputs après contrôles//
	//-------------------------------------//
	document.getElementById('nom').style.backgroundColor = ((verif[0]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('prenom').style.backgroundColor = ((verif[1]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('login').style.backgroundColor = ((verif[2]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('mail').style.backgroundColor = ((verif[3]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('matricule').style.backgroundColor = ((verif[4]==false) ? "#E8582E" : "#FFFFFF");
	
	//-----------------------------------------------------------//
	//Affichage d'un message d'alerte pour les champs mal remplis//
	//-----------------------------------------------------------//
	var msg='Attention, erreur(s) d\'encodage. \nVérifiez les champs colorés';
	var erreur=0;
	for (var i=0;i<verif.length;i++){
		if(verif[i]==false) {erreur++;}
		}
	
	if (erreur>0){
	alert (msg+' \n('+erreur+' erreur(s))');}
	
	else {
		var ok=confirm('Toutes les données encodées semblent correctes.\n Confirmez-vous l\'enregistrement ?')
		if (ok){
			$.ajax(
				{
				type: "GET",
				url: "js/php/recNewUser.php",
				data:
					{
					nom : nom,
					prenom : prenom,
					login : login,
					matricule : matricule,
					sexe : sexe,
					grade : grade,
					mail : mail,
					service : service,
					},
				success:updateRep,
				error:notOk,
				});	
			}
	}
	
	
	}
	
function isEmail(myVar){
     // La 1ère étape consiste à définir l'expression régulière d'une adresse email
     var regEmail = new RegExp('^[0-9a-z._-]+@{1}[0-9a-z.-]{2,}[.]{1}[a-z]{2,5}$','i');     
	 return regEmail.test(myVar);
   }
   
 // function updateRep(rep)
	// {
	// $('#rep').html(rep);
	// }
	
function moreOptions()
	{
	updateRep('<ul><li>Sur base du <a href=?component=users&action=modifUser&type=OneUser>nom</a>.</li><li>Obtenir une <a href=?component=users&action=modifUser&type=ListUser>liste</a> des utilisateurs.</li></ul>');
	}
	
function searchUser()
	{
	var val=document.getElementById('nomUser').value;
	$.ajax(
			{
			type: "GET",
			url: "js/php/searchUser.php",
			data:
				{
				nom : val,
				},
			success:updateRep,
			error:notOk,
			});	

	}
	
function resetMdp(id)
	{
	var nom=document.getElementById('nom').value;
	var prenom=document.getElementById('prenom').value;
	var ok=confirm('Voulez-vous vraiment réinitialiser le mot de passe de cet utilisateur  ?\n ('+nom+' '+prenom+')');
	if (ok)
		{
		$.ajax(
			{
			type: "GET",
			url: "js/php/resetMdp.php",
			data:
				{
				id : id,
				},
			success:updateRep,
			error:notOk,
			});	
		}
	else 
		{
		alert ('ok, on abandonne cette idée');
		}
	}
	
function ModifUser(id)
	{
	var nom=document.getElementById('nom').value;
	var prenom=document.getElementById('prenom').value;
	var login=document.getElementById('login').value;
	var grade=document.getElementById('grade').value;
	var mail=document.getElementById('mail').value;
	var service=document.getElementById('service').value;
	
	var verif = new Array();
	
	//------------------------//
	//Contrôles sur les champs//
	//------------------------//
	
	verif[0] = ((nom>'') ? true : false);
	verif[1] = ((prenom>'') ? true : false);
	verif[2] = ((login>'') ? true : false);
	verif[3] = ((isEmail(mail)==true) ? true : false);
	
	//-------------------------------------//
	//Coloration des inputs après contrôles//
	//-------------------------------------//
	document.getElementById('nom').style.backgroundColor = ((verif[0]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('prenom').style.backgroundColor = ((verif[1]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('login').style.backgroundColor = ((verif[2]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('mail').style.backgroundColor = ((verif[3]==false) ? "#E8582E" : "#FFFFFF");

	//-----------------------------------------------------------//
	//Affichage d'un message d'alerte pour les champs mal remplis//
	//-----------------------------------------------------------//
	var msg='Attention, erreur(s) d\'encodage. \nVérifiez les champs colorés';
	var erreur=0;
	for (var i=0;i<verif.length;i++){
		if(verif[i]==false) {erreur++;}
		}
		
	if (erreur>0){
	alert (msg+' \n('+erreur+' erreur(s))');}
	
	else {
		var ok=confirm('Toutes les données encodées semblent correctes.\n Confirmez-vous l\'enregistrement ?')
		// alert (service);
		if (ok){
			$.ajax(
				{
				type: "GET",
				url: "js/php/updateUser.php",
				data:
					{
					nom : nom,
					prenom : prenom,
					login : login,
					mail : mail,
					grade : grade,
					service : service,
					id : id,
					},
				success:updateRep,
				error:notOk,
				});	
			}
	}
	}
	
// function tablette()
	// {
	// var tablette=document.getElementById('mode').value;
	// alert (tablette);
	// }
	
function selectUzi()
	{
	$.ajax(
				{
				type: "GET",
				url: "js/php/selectUZI.php",
				success:updateSelectUzi,
				error:notOk,
				});	
	
	}
function updateSelectUzi(rep)
	{
	$('#selectUzi').html(rep);
	}
	
function cleanUzi()
	{
	$('#selectUzi').html('');
	}
	
function selectAP()
	{
	$.ajax(
				{
				type: "GET",
				url: "js/php/selectAP.php",
				success:updateSelectAP,
				error:notOk,
				});	
	
	}
function updateSelectAP(rep)
	{
	$('#selectAP').html(rep);
	}
	
function cleanAP()
	{
	$('#selectAP').html('');
	}
	
function selectETT()
	{
	$.ajax(
				{
				type: "GET",
				url: "js/php/selectETT.php",
				success:updateSelectETT,
				error:notOk,
				});	
	
	}
function updateSelectETT(rep)
	{
	$('#selectETT').html(rep);
	}
	
function cleanETT()
	{
	$('#selectETT').html('');
	}
	
function selectGSM()
	{
	$.ajax(
				{
				type: "GET",
				url: "js/php/selectGSM.php",
				success:updateSelectGSM,
				error:notOk,
				});	
	
	}
function updateSelectGSM(rep)
	{
	$('#selectGSM').html(rep);
	}
	
function cleanGSM()
	{
	$('#selectGSM').html('');
	}
	
function showDenom()
	{
	var select=document.getElementById("indicatif");
	var selected=select.options[select.selectedIndex].value;
	
		$.ajax(
			{
			type: "GET",
			url: "js/php/updateDenom.php",
			data:
				{
				id : selected,
				},
			success:updateDenom,
			error:notOk,
			});
	}
	
function updateDenom(rep)
	{
	$('#denom').html(rep);
	$('#lasuite').html('<input type=submit value="La suite ...">');
	}
	
function chgOtherMission(step,i,idMission,idPat)
	{
	$.ajax({
		type:"GET",
		url: "js/php/users/chgOtherMission.php",
		data:{
			step : step,
			i : i,
			idMission : idMission,
			idPat : idPat,
			},
		success : function(retour){$('#rep'+i).html(retour);},
		error:notOk,
		});
	}
	
function boutonHot()
{
	if(document.getElementById("repHot").innerHTML=='')
	{
		$('#repHot').html('<ul><li><a href="#">Infos sans interaction</a></li><li><a href="?mode=m&component=infos&action=showInfos">Infos proposées</a></li><li><a href="?mode=newInfo&component=infos&action=newInfo" target="_blank">Encoder une info</a></li></ul>');
	}
	else
	{
		$('#repHot').html('');
	}
}

function details(type,id)
{
	$.ajax({
		type:"GET",
		url: "js/php/users/infosFCops.php",
		data:{
			type : type,
			id : id,
			},
		success : function(html){window.open(html, "_blank", "menubar=no, status=no, scrollbars=no, menubar=no fullscreen=no, width=1000, height=900");},
		error:notOk,
		});
}

function slide(part)
{
	$('.'+part).slideToggle();
}