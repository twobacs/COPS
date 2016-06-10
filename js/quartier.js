function moreAntennes()
	{
	var rep='<ul><li><a href="?component=quartier&action=afficher&type=antennes">Afficher</a> les antennes existantes</li><li><a href="?component=quartier&action=ajouter&type=antennes">Ajouter</a> une antenne de quartier</li><li><a href="?component=quartier&action=modifier&type=antennes">Modifier</a> une antenne de quartier</li><li><a href="?component=quartier&action=assocqa">Associer</a> un quartier à une antenne</li></ul>';
	$('#moreAntennes').html(rep);
	}
	
function moreQuartiers()
	{
	var rep='<ul><li><a href="?component=quartier&action=afficher&type=quartier">Afficher</a> les quartiers existants</li><li><a href="?component=quartier&action=ajouter&type=quartier">Ajouter</a> un quartier</li><li><a href="?component=quartier&action=modifier&type=quartier">Modifier</a> un quartier</li><li><a href="?component=quartier&action=assocrq">Associer</a> une rue à un quartier</li></ul>';
	$('#moreQuartiers').html(rep);
	}

function moreAgents()
	{
	var rep='<ul><li><a href="?component=quartier&action=afficher&type=agent">Afficher</a> les agents existants</li><li><a href="?component=quartier&action=ajouter&type=agent">Lier</a> un agent à un quartier</li><li><a href="?component=quartier&action=modifier&type=agent">Modifier</a> liaison agent / quartier</li></ul>';
	$('#moreAgents').html(rep);
	}
	
function moreRecherches()
	{
	var rep='<ul><li>Sur base du <a href="?component=quartier&action=search&type=rue">nom de rue</a></li><li>Sur base de <a href="?component=quartier&action=search&type=agent">l\'agent</a></li><li>Sur base de <a href="?component=quartier&action=search&type=antenne">l\'antenne</a></li></ul>';
	$('#moreRecherches').html(rep);
	}
	
function addAntenne()
	{
	var donnees=new Array();
	var erreur=new Array();
	donnees[0]=document.getElementById('denom').value;
	donnees[1]=document.getElementById('adresse').value;
	donnees[2]=document.getElementById('phone').value;
	donnees[3]=document.getElementById('fax').value;
	donnees[4]=document.getElementById('num').value;
	donnees[5]=document.getElementById('resp').value;
	
	for (var i=0;i<donnees.length;i++)
		{
		if (donnees[i]=='')
			{
			erreur[i]=false;
			}
		}
		
	document.getElementById('denom').style.backgroundColor = ((erreur[0]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('adresse').style.backgroundColor = ((erreur[1]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('phone').style.backgroundColor = ((erreur[2]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('fax').style.backgroundColor = ((erreur[3]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('num').style.backgroundColor = ((erreur[4]==false) ? "#E8582E" : "#FFFFFF");
	
	if(erreur.length>0)
		{
		alert ('Attention, erreur(s) d\'encodage. \nVérifiez les champs colorés');
		}
		
	else
		{
		var ok=confirm('Confirmez-vous l\'enregistrement sur base de ces données ?');
		if (ok)
			{
			$.ajax(
				{
				type: "GET",
				url: "js/php/quartier/recNewAntenne.php",
				data:
					{
					denom : donnees[0],
					adresse : donnees[1],
					tel : donnees[2],
					fax : donnees[3],
					num : donnees[4],
					resp : donnees[5],
					},
				success:updateRep,
				error:notOk,			
				});
			}
		}
	}
	
function modifAntenne(id){
	$.ajax(
		{
		type: "GET",
		url: "js/php/quartier/modifAntenne.php",
		data:
			{
			id : id,
			},
		success:function (html){
		$('#'+id).html(html);
		},
		error:notOk,			
		});
	}
	
function recModifsAnt(id)
	{
	var donnees=new Array();
	donnees[0]=document.getElementById('denom'+id).value;
	donnees[1]=document.getElementById('adresse'+id).value;
	donnees[2]=document.getElementById('phone'+id).value;
	donnees[3]=document.getElementById('fax'+id).value;
	donnees[4]=document.getElementById('num'+id).value;
	donnees[5]=document.getElementById('resp'+id).value;

	var ok=confirm('Confirmez-vous l\'enregistrement sur base de ces données ?');
	if (ok)
		{
		$.ajax(
			{
			type: "GET",
			url: "js/php/quartier/recModifAntenne.php",
			data:
				{
				id : id,
				denom : donnees[0],
				adresse : donnees[1],
				tel : donnees[2],
				fax : donnees[3],
				num : donnees[4],
				resp : donnees[5],
				},
			success:updateRep,
			error:notOk,			
			});
		}
	}
	
function addQuartier(){
	var donnees=new Array();
	var erreur=new Array();
	donnees[0]=document.getElementById('denom').value;
	donnees[1]=document.getElementById('gsm').value;
	
	for (var i=0;i<donnees.length;i++){
		if (donnees[i]==''){
			erreur[i]=false;
			}
		if (donnees[1]=='04xx / xxx xxx'){
			erreur[1]=false;
			}
		}
		
	document.getElementById('denom').style.backgroundColor = ((erreur[0]==false) ? "#E8582E" : "#FFFFFF");
	document.getElementById('gsm').style.backgroundColor = ((erreur[1]==false) ? "#E8582E" : "#FFFFFF");		

	if (erreur.length>0){
		alert ('Attention, erreur(s) d\'encodage. \nVérifiez les champs colorés');
		}
		
	else{
		var ok=confirm('Confirmez-vous l\'enregistrement sur base de ces données ?');
		if (ok){
			$.ajax(
				{
				type: "GET",
				url: "js/php/quartier/recNewQuartier.php",
				data:
					{
					denom : donnees[0],
					gsm : donnees[1],
					},
				success:updateRep,
				error:notOk,			
				});
			}
		}
		
	}
	
function modifQuartier(id){
	$.ajax(
		{
		type: "GET",
		url: "js/php/quartier/modifQuartier.php",
		data:
			{
			id : id,
			},
		success:function (html){
		$('#'+id).html(html);
		},
		error:notOk,			
		});
	}
	
function recModifsQuart(id)
	{
	var donnees=new Array();
	donnees[0]=document.getElementById('denom'+id).value;
	donnees[1]=document.getElementById('gsm'+id).value;
		
	var ok=confirm('Confirmez-vous l\'enregistrement sur base de ces données ?');
	if (ok)
		{
		$.ajax(
			{
			type: "GET",
			url: "js/php/quartier/recModifQuartier.php",
			data:
				{
				id : id,
				denom : donnees[0],
				gsm : donnees[1],
				},
			success:updateRep,
			error:notOk,			
			});
		}
	}
	
function assocrq(){
	var rue=document.getElementById('rue').value;
	var lpb=parseInt(document.getElementById('lpb').value);
	var lph=parseInt(document.getElementById('lph').value);
	var lib=parseInt(document.getElementById('lib').value);
	var lih=parseInt(document.getElementById('lih').value);
	var quartier=document.getElementById('quartier').value;
	
	var erreur=new Array();
	
	if ((lpb % 2)>0){
		erreur[0]=false;
		}
	if ((lph % 2)>0){
		erreur[1]=false;
		}
	if ((lib % 2)==0){
		erreur[1]=false;
		}
	if ((lih % 2)==0){
		erreur[1]=false;
		}
	if (erreur.length>0){
		alert ('Il y a une ou des erreurs dans les numérotations, vérifiez que les valeurs soient correctes (paires / impaires).');
		}
	else{
		$.ajax(
			{
			type: "GET",
			url: "js/php/quartier/associer.php",
			data:
				{
				rue : rue,
				lpb : lpb,
				lph : lph,
				lib : lib,
				lih : lih,
				quartier : quartier,
				},
			success:updateRep,
			error:notOk,			
			});
		}
	}
	
function checkAqQuart(){
	var quartier=document.getElementById('quartier').value;
	if (quartier!=''){
		$.ajax(
			{
			type: "GET",
			url: "js/php/quartier/checkAqQuart.php",
			data:
				{
				quartier : quartier,
				},
			success:updateRep,
			error:notOk,			
			});
		}
	}
	
function addLiaisonAqQuart(){
	var quartier=document.getElementById('quartier').value;
	var agent=document.getElementById('agent').value;
	$.ajax(
		{
		type: "GET",
		url: "js/php/quartier/addLiaisonAqQuart.php",
		data:
			{
			agent : agent,
			quartier : quartier,
			},
		success:updateRep,
		error:notOk,			
		});	
	}
	
function infosAgent(){
	var agent=document.getElementById('agent').value;
	$.ajax(
		{
		type: "GET",
		url: "js/php/quartier/infosAgent.php",
		data:
			{
			agent : agent,
			},
		success:updateRep,
		error:notOk,			
		});	
	}
	
function updateAQQuartier(){
	var agent=document.getElementById('agent').value;
	var newQ=document.getElementById('newQuart').value;
	$.ajax(
		{
		type: "GET",
		url: "js/php/quartier/updateAQQuartier.php",
		data:
			{
			agent : agent,
			quartier : newQ,
			},
		success:updateRep,
		error:notOk,			
		});	
	}
	
function assocqa(){
	var quartier=document.getElementById('quartier').value;
	var antenne=document.getElementById('antenne').value;
	
	if ((quartier=='') || (antenne=='')){
		alert ('Un des champs n\'est pas sélectionné, veuillez corriger.');
		}
		
	else{
		var ok=confirm('Etes-vous sur de vouloir procéder à l\'enregistrement sur base de ces données ?');
		if (ok){
			$.ajax(
				{
				type: "GET",
				url: "js/php/quartier/assocqa.php",
				data:
					{
					antenne : antenne,
					quartier : quartier,
					},
				success:updateRep,
				error:notOk,			
				});	
			}
		
		}
	}
	
function infosAntenne(){
	var quartier=document.getElementById('quartier').value;
	if (quartier!=''){
		$.ajax(
			{
			type: "GET",
			url: "js/php/quartier/infosAntenne.php",
			data:
				{
				quartier : quartier,
				},
			success:updateRep,
			error:notOk,			
			});		
		}
	}
	
function showInfosBy(data){
	var info=document.getElementById('info').value;
	$.ajax(
		{
		type: "GET",
		url: "js/php/quartier/showInfos.php",
		data:
			{
			type : data,
			id : info,
			},
		success:updateRep,
		error:notOk,			
		});		
	}