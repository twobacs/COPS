function getCateg()
{
	var sec=document.getElementById('get_Section');
	var idSec=sec.options[sec.selectedIndex].value;
	$.ajax(
		{
		type: "GET",
		url: "js/php/cops/showCategsFromSection.php",
		data:
			{
			sec : idSec,
			},
		success:function(html){
			$('#getSection').html(html);
			},
		error:notOk,
		});	
}

function getLaSuite()
{
	var cat=document.getElementById('get_Categ');
	var idCateg=cat.options[cat.selectedIndex].value;
	var sec=document.getElementById('get_Section');
	var idSec=sec.options[sec.selectedIndex].value;	
	$.ajax(
		{
		type: "GET",
		url: "js/php/cops/showMoreNewFiche.php",
		data:
			{
			sec : idSec,
			categ : idCateg,
			},
		success:function(html){
			$('#laSuite').html(html);
			},
		error:notOk,
		});	
}

function endNowFiche(idfiche)
	{
	// alert (idfiche);
	var ok = confirm('Voulez-vous vraiment clôturer cette fiche maintenant ?');
	if (ok)
		{
		$.ajax(
			{
			type:"GET",
			url:"js/php/cops/endNowFiche.php",
			data:
				{
				id:idfiche,
				},
				success:function(html){
				$('#datefin').html(html);
				},
			error:notOk,
			});
		}
	}

function recSecCateg(idfiche)
	{
	var categ=document.getElementById('get_Categ');
	var idCateg=categ.options[categ.selectedIndex].value;
	$.ajax(
		{
		type: "GET",
		url: "js/php/cops/updateCateg.php",
		data:
			{
			fiche : idfiche,
			categ : idCateg,
			},
		success:function(html){
			$('#repSecCat').html(html);
			},
		error:notOk,
		});		
	}
	
function recInteraction(idfiche)
	{
	var select=document.getElementById('interactionO').checked;
	if (select==true)
		{
		var selected='O';
		}
	else
		{
		var selected='N';
		}
	$.ajax(
		{
		type: "GET",
		url: "js/php/cops/updateInteraction.php",
		data:
			{
			fiche : idfiche,
			select : selected,
			},
		// success:function(retour){alert(retour);},
		error:notOk,
		});		
	
	}
	
function recValidite(idfiche)
	{
	var DD=document.getElementById('dateDebut').value;
	var HD=document.getElementById('heureDebut').value;
	var DF=document.getElementById('dateFin').value;
	var HF=document.getElementById('heureFin').value;
	$.ajax(
	{
	type:"GET",
	url:"js/php/cops/updateValiditeFiche.php",
	data:
		{
		DD : DD,
		HD : HD,
		DF : DF,
		HF : HF,
		fiche : idfiche,
		},
	success:function(){
		alert('Changements enregistrés');
		},
	error : notOk,
	});
	}
	
function recModifsPersonnes(i,idpers,idFiche)
	{
	// alert (i+' '+idpers+' '+idfiche);
	var nom=document.getElementById('nom'+i).value;
	var prenom=document.getElementById('prenom'+i).value;
	var naissance=document.getElementById('DN'+i).value;
	var pays=document.getElementById('pays'+i).value;
	var CP=document.getElementById('CP'+i).value;
	var ville=document.getElementById('ville'+i).value;
	var rue=document.getElementById('rue'+i).value;
	var num=document.getElementById('num'+i).value;
	var desc=document.getElementById('desc'+i).value;
	var implication=document.getElementById('implication'+i);
	var idImplication=implication.options[implication.selectedIndex].value;	
	$.ajax(
	{
	type:"GET",
	url:"js/php/cops/recModifsPersonnes.php",
	data:
		{
		nom : nom,
		prenom : prenom,
		naissance : naissance,
		pays : pays,
		CP : CP,
		ville : ville,
		rue : rue,
		num : num,
		desc : desc,
		implication : idImplication,
		idPers : idpers,
		idFiche : idFiche,
		},
	success : function(){
		alert('Changements enregistrés');
		},
	success : function(){document.location.href="index.php?component=cops&action=editFiche&idFiche="+idFiche+"#personne";},	
	error : notOk,
	});
	}

function delPersonne(idPers,idFiche)
	{
	var ok = confirm ('Etes-vous sûr de vouloir supprimer cette personne ? \n /!\\ Cette action est irréversible /!\\');
	if (ok)
		{
		$.ajax({
			type:"GET",
			url:"js/php/cops/delPersonne.php",
			data:
				{
				idFiche : idFiche,
				idPers : idPers,
				},
			success : function(){document.location.href="index.php?component=cops&action=editFiche&idFiche="+idFiche+"#personne";},
			error : notOk,
			});
		}
	}
	
function addPersonne(idFiche)
	{
	$.ajax(
		{
		type: "GET",
		url: "js/php/cops/addPersonne.php",
		data:
			{
			fiche : idFiche,
			},
		success:function(html){
			$('#newPers').html(html);
			},
		error:notOk,
		});		
	}
	
function recModifsVV(idvv,idfiche,i)
	{
	var marque=document.getElementById('marque'+i).value;
	var modele=document.getElementById('modele'+i).value;
	var couleur=document.getElementById('couleur'+i).value;
	var immat=document.getElementById('immat'+i).value;
	var chassis=document.getElementById('chassis'+i).value;
	var info=document.getElementById('infos'+i).value;
	var implication=document.getElementById('implicationvv'+i);
	var idImplication=implication.options[implication.selectedIndex].value;	
	if (idImplication==-1)
		{
		alert ('Veuillez sélectionner une implication.');
		}
	else
		{
		$.ajax({
			type:"GET",
			url:"js/php/cops/recModifsVV.php",
			data:
				{
				idvv : idvv,
				idfiche : idfiche,
				marque : marque,
				modele : modele,
				couleur : couleur,
				immat : immat,
				chassis : chassis,
				info : info,
				implication : idImplication,
				},
			success : function(){document.location.href="index.php?component=cops&action=editFiche&idFiche="+idfiche+"#vehicules";},	
			error : notOk,
			});
		}
	}
	
function delVV(idvv,idFiche,i)
	{
	var ok=confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ? \n /!\\ Action irréversible /!\\');
	if (ok)
		{
		$.ajax({
			type:"GET",
			url:"js/php/cops/delVV.php",
			data:
				{
				idvv : idvv,
				idfiche : idFiche,
				},
			success : function(){document.location.href="index.php?component=cops&action=editFiche&idFiche="+idFiche+"#vehicules";},
			error : notOk,
			});
		}
	}
	
function addVV(idFiche)
	{
	$.ajax({
		type:"GET",
		url:"js/php/cops/addVV.php",
		data:
			{
			idfiche : idFiche,
			},
		success : function(retour){$('#newVV').html(retour);},
		error:notOk,
		});
	}
	
function addLD(idFiche)
	{
	$.ajax({
		type:"GET",
		url:"js/php/cops/addLD.php",
		data:
			{
			idFiche : idFiche,
			},
		success : function(retour){$('#newLD').html(retour);},
		error : notOk,
		});
	}
	
function delLD(idLD,idFiche,i)
	{
	var ok=confirm('Etes-vous sûr de vouloir supprimer ce lieudit ? \n /!\\ Action irréversible /!\\');
	if (ok)
		{
		$.ajax({
			type:"GET",
			url:"js/php/cops/delLD.php",
			data:
				{
				idLD : idLD,
				idFiche : idFiche,
				},
			success : function(){window.location.reload();},	
			error:notOk,
			});
		}
	}
	
function recModifsLD(idLD,idFiche,i)
	{
	// alert('implicationLD'+i);
	var denomination=document.getElementById('denominationLD'+i).value;
	var implication=document.getElementById('implicationLD'+i);
	var idImplication=implication.options[implication.selectedIndex].value;	
	if (idImplication==-1)
		{
		alert ('Veuillez sélectionner une implication.');
		}
	else
		{
		$.ajax({
			type:"GET",
			url:"js/php/cops/recModifsLD.php",
			data:
				{
				idLD : idLD,
				idFiche : idFiche,
				denomination : denomination,
				implication : idImplication,
				},
				success : function(retour)
				{alert('Modifications enregistrées.');document.location.href="index.php?component=cops&action=editFiche&idFiche="+idFiche+"#lieudit";},	
				error : notOk,
			});
		}
	}
	
function recModifsCom(idCom,idFiche,i)
	{
	var denomination=document.getElementById('denominationCom'+i).value;
	var description=document.getElementById('descCom'+i).value;
	var CP=document.getElementById('CPCom'+i).value;
	var ville=document.getElementById('villeCom'+i).value;
	var selectedRue=document.getElementById('rueCom'+i);
	var idRue=selectedRue.options[selectedRue.selectedIndex].value;
	var num=document.getElementById('numCom'+i).value;
	var implication=document.getElementById('impCom'+i);
	var idImplication=implication.options[implication.selectedIndex].value;
	$.ajax({
		type:"GET",
		url:"js/php/cops/recModifsCom.php",
		data:
			{
			denomination : denomination,
			description : description,
			CP : CP,
			ville : ville,
			idRue : idRue,
			num : num,
			idImplication : idImplication,
			idCom : idCom,
			idFiche : idFiche,
			},
		success : function(retour){alert('Modifications enregistrées');document.location.href="index.php?component=cops&action=editFiche&idFiche="+idFiche+"#commerce";},
		error : notOk,
		
		});
	}
	
function delCom(idCom,idFiche,i)
	{
	var ok=confirm('Etes-vous sûr de vouloir supprimer ce commerce ? \n /!\\ Action irréversible /!\\');
	if (ok)
		{
		$.ajax({
			type:"GET",
			url:"js/php/cops/delCom.php",
			data:
				{
				idCom : idCom,
				idFiche : idFiche,
				},
			success : function(){window.location.reload();},	
			error:notOk,
			});
		}
	}

function addCom(idFiche)
	{
	$.ajax({
		type:"GET",
		url:"js/php/cops/addCom.php",
		data:
			{
			idFiche : idFiche,
			},
		success : function(retour){$('#newCom').html(retour);},
		error : notOk,
		});
	}
	
function recModifsTL(idTxt,idFiche,i)
	{
	var titre=document.getElementById('titreTxt'+i).value;
	var texte=document.getElementById('TextTxt'+i).value;
	$.ajax({
		type:"GET",
		url:"js/php/cops/recModifsTL.php",
		data:
			{
			idTxt : idTxt,
			idFiche : idFiche,
			titre : titre,
			texte : texte,
			},
		success : function(retour){alert('Modifications enregistrées');document.location.href="index.php?component=cops&action=editFiche&idFiche="+idFiche+"#TL";},
		error : notOk,
		});
	}
	
function delTL(idTxt,idFiche,i)
	{
	var ok=confirm('Etes-vous sûr de vouloir supprimer ce texte libre ? \n /!\\ Action irréversible /!\\');
	if (ok)
		{
		$.ajax({
			type:"GET",
			url:"js/php/cops/delTL.php",
			data:
				{
				idTxt : idTxt,
				idFiche : idFiche,
				},
			success : function (){window.location.reload();},
			error : notOk,
			});
		}
	}
	
function addTL(idFiche)
	{
	$.ajax({
		type:"GET",
		url:"js/php/cops/addTL.php",
		data:
			{
			idFiche : idFiche,
			},
		success : function(retour){$('#newTL').html(retour);},	
		error : notOk,
		});
	}
	
function recModifsPic(idPic,idFiche,i)
	{
	var commentaire=document.getElementById('comPic'+i).value;
	$.ajax({
		type:"GET",
		url:"js/php/cops/recModifsPic.php",
		data:
			{
			idPic : idPic,
			idFiche : idFiche,
			commentaire : commentaire,
			},
		success : function(retour)
			{
			alert('Modifications enregistrées');
			document.location.href="index.php?component=cops&action=editFiche&idFiche="+idFiche+"#photos";
			},
		error : notOk,
		});
	}
	
function delPic(idPic,idFiche,i)
	{
	var ok=confirm('Etes-vous sûr de vouloir supprimer cette photo ? \n /!\\ Action irréversible /!\\');
	if (ok)
		{
		$.ajax({
			type:"GET",
			url:"js/php/cops/delPic.php",
			data:
				{
				idPic : idPic,
				idFiche : idFiche,
				},
			success : function(){window.location.reload();},
			error : notOk,
			});
		}
	}
	
function addPic(idFiche)
	{
	$.ajax({
		type:"GET",
		url:"js/php/cops/addPic.php",
		data:
			{
			idFiche : idFiche,
			},
		success : function(retour){$('#newPic').html(retour);},	
		error : notOk,	
		});
	}

function AddPersonneFiche(i)
	{
	$.ajax({
		type:"GET",
		url:"js/php/cops/newInfoFicheById.php",
		data:
		{
			nb : i,
		},
		success : function(retour){$('#AddPersonneFiche').html(retour);},
		error:notOk,
		});	
	}
	
function hidePers(i)
	{
	$('#tabPers'+i).html('');
	}
	
function AddVVFiche(i)
	{
	$.ajax({
		type:"GET",
		url:"js/php/cops/newInfoFicheVVById.php",
		data:
		{
			nb : i,
		},
		success : function(retour){$('#AddVVFiche').html(retour);},
		error:notOk,
		});	
	}
function hideVV(i)
	{
	$('#tabVV'+i).html('');
	}
	
function AddLDFiche(i)
	{
	$.ajax({
		type:"GET",
		url:"js/php/cops/newInfoFicheLDById.php",
		data:
		{
			nb : i,
		},
		success : function(retour){$('#AddLDFiche').html(retour);},
		error:notOk,
		});		
	}
	
function hideLD(i)
	{
	$('#tabLD'+i).html('');
	}
	
function AddCommerceFiche(i)
	{
	$.ajax({
		type:"GET",
		url:"js/php/cops/newInfoFicheCommerceById.php",
		data:
		{
			nb : i,
		},
		success : function(retour){$('#AddCommerceFiche_').html(retour);},
		error:notOk,
		});		
	}
	
function hideCom(i)
	{
	$('#tabCom'+i).html('');
	}
	
function hideTL(i)
	{
	$('#tabTL'+i).html('');
	}
	
function AddTLFiche(i)
	{
	$.ajax({
		type:"GET",
		url:"js/php/cops/newInfoFicheTLById.php",
		data:
		{
			nb : i,
		},
		success : function(retour){$('#AddTLFiche_').html(retour);},
		error:notOk,
		});		
	}
	
function hidePic(i)
	{
	$('#tabPic'+i).html('');
	}	
	
function AddPicFiche(i)
	{
	$.ajax({
		type:"GET",
		url:"js/php/cops/newInfoFichePicById.php",
		data:
		{
			nb : i,
		},
		success : function(retour){$('#AddPicFiche_').html(retour);},
		error:notOk,
		});		
	}	