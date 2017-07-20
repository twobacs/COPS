function slide(part,other){
	var display=($('#'+part).css('display'));
	if(display=='block'){
		$('#'+part).hide(1200);
	}
	else{
		if(typeof(other) == 'undefined'){
			$('#'+part).slideToggle(500);
		}
		else{
			$('#slide1').hide(500);
			$('#slide2').hide(500);
			$('#slide3').hide(500);
			$('#slide4').hide(500);
			$('#'+part).slideToggle(500);
			
			$('#addUMesure').hide(500);
			$('#addCateg').hide(500);
			$('#addFournisseur').hide(500);
			document.getElementById('bAddCateg').value='Ajouter';
			document.getElementById('bAddUM').value='Ajouter';
			document.getElementById('bAddFourn').value='Ajouter';
			
		}
	}	
}

function slide2(part,other){
	var display=($('#'+part).css('display'));
	if(display=='block'){
		$('#'+part).hide(1200);
		document.getElementById('bAddCateg').value='Ajouter';
		document.getElementById('bAddUM').value='Ajouter';
		document.getElementById('bAddFourn').value='Ajouter';
	}
	else{
		if(typeof(other) == 'undefined'){
			$('#'+part).slideToggle(500);
			document.getElementById('bAddCateg').value='Fermer ce menu';
			document.getElementById('bAddUM').value='Fermer ce menu';
			document.getElementById('bAddFourn').value='Fermer ce menu';
		}
	}
}

function searchUser(type){
	var element = document.getElementById("resultSearch");
	var entry = document.getElementById(type).value;
	$.ajax(
			{
			type: "GET",
			url: "js/php/users/searchUser.php",
			data:
				{
				type : type,
				entry : entry,
				},
			success:function(retour){element.innerHTML = retour;},
			});	

}

function resetMDP(id){
	var ok=confirm('Etes-vous certain de vouloir faire ça ?');
	if(ok){
		$.ajax(
			{
			type: "GET",
			url: "js/php/users/resetMDP.php",
			data:
				{
				id : id,
				},
			success : function(){alert('Mot de passe réinitialisé (azerty)');},
			});
	}
	else{
		alert('Action annulée');
	}
}
function autocompleteRRN(){
	var RRN = document.getElementById('rrn').value;
	if(RRN.length!=11){
		var naissance=document.getElementById('naissance').value;
		var a=naissance.substr(2, 2);
		var m=naissance.substr(5, 2);
		var j=naissance.substr(8, 2);
		document.getElementById('rrn').value=a+m+j;
	}
}
function verifMat(){
	var matricule=document.getElementById('matricule').value;
	var controle = matricule.substr(7, 2);
	var enTete = matricule.substr(0, 7);
	if(controle!=(enTete % 97)){document.getElementById('tdMat').style.backgroundColor = 'red';document.getElementById('matricule').value='';}
	else{document.getElementById('tdMat').style.backgroundColor = 'green';}
}

function verifRRN(){
	var RRN=document.getElementById('rrn').value;
	var naissance=document.getElementById('naissance').value;
	var annee = naissance.substr(0, 4);	
	if(annee<2000){
		var enTete = RRN.substr(0, 9);		
	}
	else{
		var enTete = '2'+(RRN.substr(0, 9));
	}
	var controle = RRN.substr(9, 2);
	if(controle!=(97-(enTete % 97))){document.getElementById('tdRRN').style.backgroundColor = 'red'; var error='Erreur ! Le numéro national ne semble pas correct.';}
	else{document.getElementById('tdRRN').style.backgroundColor = 'green';}
	var a=naissance.substr(2, 2);
	var m=naissance.substr(5, 2);
	var j=naissance.substr(8, 2);
	var ar=RRN.substr(0, 2);
	var mr=RRN.substr(2, 2);
	var jr=RRN.substr(4, 2);
	if((a!=ar)||(m!=mr)||(j!=jr)){
		var error='Erreur ! Les 6 premiers chiffres doivent correspondre à la date de naissance inversée.';
	}
	if(RRN.length!=11){
		var error='Erreur ! Le numéro national doit comporter 11 chiffres.';
	}
	if(error){
		alert(error);
	}
}

function verifLogin(){
	var login=document.getElementById('login').value;
	$.ajax({
		type:"GET",
		url:"js/php/users/verifLogin.php",
		data:{
			login:login,
		},
		success : function(retour){
			if(retour=='1'){
				alert('Erreur ! Cette valeur est déjà utilisée.');
				document.getElementById('login').value='';
			}
		},
	});
}

function recNewMotif(objet,motif){
	var cause=document.getElementById('newMotif').value;
	var i;
	var tab = document.getElementsByName('dispo');
	for (i=0;i<tab.length;i++)
		{
			if(tab[i].checked)
			{
				var dispo = tab[i].value;
				break;
			}
		}
	$.ajax({
		type:"GET",
		url:"js/php/logistique/addMotif.php",
		data:{
			objet:objet,
			motif:motif,
			cause:cause,
			dispo:dispo,
		},
		success : function(){
			alert('Valeur ajoutée');
			window.location.reload();
		},
	});
}

function updateField(objet,field,id){
	var value=document.getElementById(field).value;
	$.ajax({
		type:"GET",
		url:"js/php/updateField.php",
		data:{
			objet:objet,
			field:field,
			id:id,
			value:value,
		},
		// success:function(html){alert(html);},
	});	
}

function modifField(source,field,id,valeur){
	switch(source){
		case 'Categ':
			var elem='denCategArticle';
			break;
		case 'Mesure':
			var elem='denUMesure';
			break;
        case 'Fournisseur':
            var elem='denFournisseur';
			break;
			
	}
	if(source==='Fournisseur'){
		document.getElementById('bModifFournisseur'+id).innerHTML='<input type="button" value="Modifications en cours" style="cursor:not-allowed">';
		$.ajax({
			type:"GET",
			url:"js/php/logistique/formModifFournById.php",
			data:{
				idFourn:id,
			},
			success:function(retour){document.getElementById('msgSlide4').innerHTML=retour;}
		});
		// document.getElementById('msgSlide4').innerHTML='coucou';
	}
	else{
		document.getElementById(field).innerHTML='<input type="text" id="new'+field+'" value="'+valeur+'" autofocus>';
		document.getElementById('bModif'+source+id).innerHTML='<input type="button" value="Enregistrer" onclick="recModifField(\''+elem+'\',\'new'+field+'\',\''+id+'\');" id="bEnregistrer">';
	}
}

function recModifField(objet,field,id){
	switch(objet){
		case 'denCategArticle':
			var ok=confirm('Ceci changera la dénomination pour tous les objets de cette catégorie.  Voulez-vous continuer ?');
			var bouton='Categ';
			var td='idCateg';
			break;
		case 'denUMesure':
			var ok=confirm('Ceci changera l\'unité de mesure pour tous les objets l\'ayant.  Voulez-vous continuer ?');
			var bouton='Mesure';
			var td='idMesure';
			break;
		case 'denFournisseur':
			var ok=confirm('Ceci changera le fournisseur pour tous les objets s\'y référant.  Voulez-vous continuer ?');
			var bouton='Fournisseur';
			var td='idFournisseur';
			break;
	}
	if(ok){
		var value=document.getElementById(field).value;
		$.ajax({
			type:"GET",
			url:"js/php/updateField.php",
			data:{
				objet:objet,
				field:field,
				id:id,
				value:value,
			},
			success:function(html){
				document.getElementById(td+id).innerHTML=html;
				document.getElementById('bModif'+bouton+id).innerHTML='<input type="button" value="Enregistrement Ok" style="cursor:not-allowed;">';
			},
		});
	}
}

function delCategArt(id, denom){
	var ok=confirm('Voulez-vous supprimer cette catégorie d\'articles ? ('+denom+')');
	if (ok){
		$.ajax({
			type:"GET",
			url:"js/php/logistique/delCategArt.php",
		data:{
			id:id,
		},
		success:function(retour){
			if(retour==0){
				window.location.href="?component=logistique&action=gestPMB&visible=categ";
			}
			else{
				$.ajax({
					type:"GET",
					url:"js/php/logistique/selectArtByCateg.php",
					data:{
						id:id,
						why:0,
					},
					success:function(retour){
						alert('Des articles sont encore liés à cette catégorie, veuillez apporter les modifications préalables.');
						document.getElementById('msgSlide2').innerHTML=retour;
						var display=($('#msgSlide2').css('display'));
							if(display=='none'){
							$('#msgSlide2').slideToggle(500);
						}
					},
				});
			}
			},
		});
	}
}

function delUMesure(id, denom){
	var ok=confirm('Voulez-vous supprimer cette unité de mesure ? ('+denom+')');
	if (ok){
		$.ajax({
			type:"GET",
			url:"js/php/logistique/delUMesure.php",
		data:{
			id:id,
		},
		success:function(retour){
			if(retour==0){
				window.location.href="?component=logistique&action=gestPMB&visible=categ";
			}
			else{
				$.ajax({
					type:"GET",
					url:"js/php/logistique/selectArtByUMesure.php",
					data:{
						id:id,
					},
					success:function(retour){
						alert('Des articles sont encore liés à cette catégorie, veuillez apporter les modifications préalables.');
						document.getElementById('msgSlide3').innerHTML=retour;
						var display=($('#msgSlide3').css('display'));
							if(display=='none'){
							$('#msgSlide3').slideToggle(500);
						}
					},
				});
			}
			},
		});
	}
}

function updateCategByIdArt(idArt){
	var newCateg=(document.getElementById('newCategArt_'+idArt).value);
	$.ajax({
		type:"GET",
		url:"js/php/logistique/updateCategByIdArt.php",
		data:{
			idArt:idArt,
			idCateg:newCateg,
		},
		success:function(){document.getElementById('confirmSpace_'+idArt).innerHTML="<img src=\"./media/icons/valid.gif\">";}
	});
}

function updateUMesureByIdArt(idArt){
	var newUmesure=(document.getElementById('newUmesure_'+idArt).value);
	$.ajax({
		type:"GET",
		url:"js/php/logistique/updateUMesureByIdArt.php",
		data:{
			idArt:idArt,
			idMesure:newUmesure,
		},
		success:function(){document.getElementById('confirmSpace_'+idArt).innerHTML="<img src=\"./media/icons/valid.gif\">";}
	});
}

function addCategArt(){
	var newCateg=document.getElementById('denNewCateg').value;
	if(newCateg!=''){
		$.ajax({
			type:"GET",
			url:"js/php/logistique/addCategArt.php",
			data:{
				newCateg:newCateg,
			},
			success:function(){document.getElementById('confirmSpaceAddArt').innerHTML="<img src=\"./media/icons/wait.gif\" height=\"20\">";
			setTimeout(function(){
				window.location.href="?component=logistique&action=gestPMB&visible=categ";
			},
			2000);
			},
			
		});
	}	
	else{
		document.getElementById('confirmSpaceAddArt').innerHTML="<img src=\"./media/icons/delete.gif\">";
	}
}

function addNewUMesure(){
	var newMesure=document.getElementById('denNewUMesure').value;
	if(newMesure!=''){
		$.ajax({
			type:"GET",
			url:"js/php/logistique/addNewMesure.php",
			data:{
				newMesure:newMesure,
			},
			success:function(){document.getElementById('confirmSpaceAddUMesure').innerHTML="<img src=\"./media/icons/wait2.gif\" height=\"20\">";
			setTimeout(function(){
				window.location.href="?component=logistique&action=gestPMB&visible=mesures";
			},
			2000);
			},
			
		});
	}	
	else{
		document.getElementById('confirmSpaceAddArt').innerHTML="<img src=\"./media/icons/delete.gif\">";
	}
}


function showPic(){
	document.getElementById('pop_picture').innerHTML="<img src=\"./media/icons/more_icon.png\">";
	slidePop();
}

function slidePop(){
	$('#pop_picture').slideToggle(200);
}
// onmouseover="showPic();" onmouseout="hidePic();"
function hidePic(){
	// document.getElementById('pop_picture').innerHTML="";
	$('#pop_picture').hide(200);
}

function addNewFourn(){
	var denNewFourn=document.getElementById('denNewFourn').value;
	var numEntr=document.getElementById('numEntr').value;
	var descFourn=document.getElementById('descFourn').value;
	var rueFourn=document.getElementById('rueFourn').value;
	var numRueFourn=document.getElementById('numRueFourn').value;
	var cpFourn=document.getElementById('cpFourn').value;
	var villeFourn=document.getElementById('villeFourn').value;
	var paysFourn=document.getElementById('paysFourn').value;
	var telFourn=document.getElementById('telFourn').value;
	var faxFourn=document.getElementById('faxFourn').value;
	var mailFourn=document.getElementById('mailFourn').value;

	
	if((denNewFourn!='')){
		document.getElementById('main').style.cursor="wait";
		$.ajax({
			type:"GET",
			url:"js/php/logistique/addNewFourn.php",
			data:{
				denNewFourn:denNewFourn,
				numEntr:numEntr,
				descFourn:descFourn,
				rueFourn:rueFourn,
				numRueFourn:numRueFourn,
				cpFourn:cpFourn,
				villeFourn:villeFourn,
				paysFourn:paysFourn,
				telFourn:telFourn,
				faxFourn:faxFourn,
				mailFourn:mailFourn,
			},
			// success:function(retour){alert(retour);},
			success:setTimeout(function(){
				window.location.href="?component=logistique&action=gestPMB&visible=fournisseurs";
			},
			2000),

		});

	}
	else{
		alert('Les champs obligatoires ne sont pas remplis');
	}

	
}

function delFournisseur(id,nom){

	var ok = confirm('Voulez-vous supprimer '+nom+' ?');
	if(ok){
		document.getElementById('main').style.cursor="wait";
		$.ajax({
			type:"GET",
			url:"js/php/logistique/desactFourn.php",
			data:{
				id:id,
				

			},
			success:setTimeout(function(){
				window.location.href="?component=logistique&action=gestPMB&visible=fournisseurs";
				document.getElementById('main').style.cursor="wait";				
			},
			2000),

		});

	}
	else{
		window.location.href="?component=logistique&action=gestPMB&visible=fournisseurs";
	}
}

function updateFourn(id){
	var denNewFourn=document.getElementById('newNomFourn'+id).value;
	var numEntr=document.getElementById('newNumEntreprise'+id).value;
	var descFourn=document.getElementById('newDescFourn'+id).value;
	var rueFourn=document.getElementById('newRueFourn'+id).value;
	var numRueFourn=document.getElementById('newNumFourn'+id).value;
	var cpFourn=document.getElementById('newCPFourn'+id).value;
	var villeFourn=document.getElementById('newVilleFourn'+id).value;
	var paysFourn=document.getElementById('newPaysFourn'+id).value;
	var telFourn=document.getElementById('newTelFourn'+id).value;
	var faxFourn=document.getElementById('newFaxFourn'+id).value;
	var mailFourn=document.getElementById('newMailFourn'+id).value;
	if((denNewFourn!='')){
		document.getElementById('main').style.cursor="wait";
		$.ajax({
			type:"GET",
			url:"js/php/logistique/UpdateFourn.php",
			data:{
				idFourn:id,
				denNewFourn:denNewFourn,
				numEntr:numEntr,
				descFourn:descFourn,
				rueFourn:rueFourn,
				numRueFourn:numRueFourn,
				cpFourn:cpFourn,
				villeFourn:villeFourn,
				paysFourn:paysFourn,
				telFourn:telFourn,
				faxFourn:faxFourn,
				mailFourn:mailFourn,
			},
			success:setTimeout(function(){
				window.location.href="?component=logistique&action=gestPMB&visible=fournisseurs";
			},
			2000),
			// success:function(retour){alert(retour);},

		});

	}
	else{
		alert('Veuillez compléter au moins le nom du fournisseur !');
	}	
}

function deActiveArtById(idArt,denomArt){
	var ok = confirm('Etes-vous sûr de vouloir désactiver cet article ? ('+denomArt+')');
	if(ok){
		document.getElementById('main').style.cursor="wait";
		$.ajax({
			type:"GET",
			url:"js/php/logistique/deActiveArtById.php",
			data:{
				idArt:idArt,
			},
			success:function(retour){
				if(retour==0){
					alert('Une erreur s\'est produite');
					document.getElementById('main').style.cursor="default";
				}
				else{
					setTimeout(function(){window.location.href="?component=logistique&action=gestPMB&visible=articles"},2000);
				}
				},
			
		});
	}
}

function reActiveArtById(idArt,denomArt){
	var ok = confirm('Etes-vous sûr de vouloir réactiver cet article ? ('+denomArt+')');
	if(ok){
		document.getElementById('main').style.cursor="wait";
		$.ajax({
			type:"GET",
			url:"js/php/logistique/reActiveArtById.php",
			data:{
				idArt:idArt,
			},
			success:function(retour){
				if(retour==0){
					alert('Une erreur s\'est produite');
					document.getElementById('main').style.cursor="default";
				}
				else{
					setTimeout(function(){window.location.href="?component=logistique&action=gestPMB&visible=articles"},2000);
				}
				},
			
		});
	}
}

function formModifArtById(id,denom){
	var tableInit=document.getElementById('tableArt'+id).innerHTML;
	$.ajax({
		type:"GET",
		url:"js/php/logistique/formModifArtById.php",
		data:{
			idArt:id,
		},
		success:function(retour){
			// alert(retour);
			document.getElementById('formModifArt'+id).innerHTML=retour;
		}
	});
	document.getElementById('tableArt'+id).innerHTML=tableInit;
}

function recordModifsArticlesById(id){
	document.getElementById('main').style.cursor="wait";
	var denom = document.getElementById('newDenomArt'+id).value;
	var categ = document.getElementById('newCategArt'+id).value;
	var stock = document.getElementById('newStockArt'+id).value;
	var uMesure = document.getElementById('newUMesureArt'+id).value;
	var commentaire = document.getElementById('newComArt'+id).value;
	var fourn = document.getElementById('newFournArt'+id).value;
	var qmin = document.getElementById('newQminArt'+id).value;
	var PA = document.getElementById('newPaArt'+id).value;
	$.ajax({
		type:"GET",
		url:"js/php/logistique/recordModifsArtById.php",
		data:{
			idArt : id,
			denom : denom,
			categ: categ,
			stock : stock,
			uMesure : uMesure,
			commentaire : commentaire,
			fourn : fourn,
			qmin : qmin,
			PA : PA,
		},
		success:function(retour){
			if(retour==0){
					alert('Une erreur s\'est produite');
					document.getElementById('main').style.cursor="default";
				}
				else{
					setTimeout(function(){window.location.href="?component=logistique&action=gestPMB&visible=articles"},2000);
				}},
	});
}

function formAddDocArt(id,type){
	var formulaire='<form class="form-inline" method="POST" action="?component=logistique&action=addDocToArt&art='+id+'&type='+type+'" enctype="multipart/form-data">';
	formulaire+='<div class="form-group">';
	formulaire+='<div class="input-group">';
	formulaire+='<div class="input-group-addon">Fichier ('+type+')</div>';
	formulaire+='<input type="file" name="fileToUpload" id="fileToUpload" class="form-control">';
	formulaire+='</div>';
	formulaire+='</div><br />';
	formulaire+='<div class="form-group">';
	formulaire+='<div class="input-group">';
	formulaire+='<input type="submit" class="btn btn-primary" value="Ajouter ce fichier" style="width:416px">';
	formulaire+='</div>';
	formulaire+='</div>';
	
	
	document.getElementById('toAdd'+id).innerHTML=formulaire;
	
}

function searchArtByInput(idPanier,idUser){
	var art=document.getElementById('denomArt').value;
	$.ajax({
		type:"GET",
		url:"js/php/logistique/selectArtByCateg.php",
		data:{
			idUser:idUser,
			idPanier:idPanier,
			id:art,
			why:2,
		},
		success:function(retour){
					document.getElementById('repSearch').innerHTML=retour;
				},
	});
}

function selectArtByCateg(idPanier,idUser){
	var categ=document.getElementById('categArt').value;
	$.ajax({
		type:"GET",
		url:"js/php/logistique/selectArtByCateg.php",
		data:{
			idUser:idUser,
			idPanier:idPanier,
			id:categ,
			why:1,
		},
		success:function(retour){
					document.getElementById('repSearch').innerHTML=retour;
				},
	});		
}

function addToCart(idArt,user,idPanier,why){
	$.ajax({
		type:"GET",
		url:"js/php/logistique/addToCart.php",
		data:{
			idArt:idArt,
			idUser:user,
			idPanier:idPanier,
		},
		success:function(retour){
			// alert(retour);
			updateNbArtInCart(retour);
			if(why==1){
				selectArtByCateg(retour,user);			
			}
			else if(why==2){
				searchArtByInput(retour,user);
			}
		},
		
	});
}

function updateNbArtInCart(idPanier){
	$.ajax({
		type:"GET",
		url:"js/php/logistique/updateNbArtInCart.php",
		data:{
			idPanier:idPanier,
		},
		success:function(retour){
			// alert(retour);
			document.getElementById('nbArtInCart').innerHTML=retour;
		},
	});
}

function modifQArt(art,panier,op){
	$.ajax({
		type:"GET",
		url:"js/php/logistique/modifQArt.php",
		data:{
			art:art,
			panier:panier,
			op:op,
		},
		success:function(retour){
			if(retour=='e'){
				alert('Stock insuffisant');
			}
			else{
			document.getElementById('qArt'+art).innerHTML=retour;
			}
		}
	});
	
}

function showDetailsOrder(idPanier){
	$.ajax({
		type:"GET",
		url:"js/php/logistique/showDetailsOrder.php",
		data:{
			idPanier:idPanier,
		},
		success:function(retour){
			document.getElementById('detailsCommande').innerHTML=retour;
		}
	});
	
}

function editRowOrder(i,art){
	$.ajax({
		type:"GET",
		url:"js/php/logistique/editRowOrder.php",
		data:{
			art:art,
			i:i,
		},
		success:function(retour){
			document.getElementById('editRowOrder'+i).innerHTML=retour;
		}
	});
}

function updateLignePanier_Log(panier){
	var log=document.getElementById('newLog_'+panier).value;
		$.ajax({
		type:"GET",
		url:"js/php/logistique/updateLignePanier_Log.php",
		data:{
			panier:panier,
			log:log,
		},
		success:function(retour){
			if(retour!="1"){
				alert('Une erreur s\'est produite');
			}
		}
	});
}

function updateLignePanier_Date(panier){
	var date=document.getElementById('dateLiv_'+panier).value;
	$.ajax({
		type:"GET",
		url:"js/php/logistique/updateLignePanier_Date.php",
		data:{
			panier:panier,
			date:date,
		},
		success:function(retour){
			if(retour!="1"){
				alert('Une erreur s\'est produite');
			}
		}
	});
}

function modifQLivree(panier,ope){
	$.ajax({
		type:"GET",
		url:"js/php/logistique/modifQLivree.php",
		data:{
			panier:panier,
			ope:ope,
		},
		success:function(retour){
			document.getElementById('qLivree_'+panier).innerHTML=retour;	
		}

	});
}

function updateComLogRowPanier(row){
		var com=document.getElementById('ComLog'+row).value;
		$.ajax({
		type:"GET",
		url:"js/php/logistique/updateComLogRowPanier.php",
		data:{
			row:row,
			com:com,
		},
		// success:function(retour){alert(retour);}
			// document.getElementById('qLivree_'+panier).innerHTML=retour;	
		// }

	});
}

function recGL(type){
    
    if(type==='bat'){
        var nBat=document.getElementById('nBat').value;
        var nLvl=0;
        var nLcl=0;
        var com=0;
    }
    else if (type==='lvl'){
        var temp=document.getElementById('existBat');
        var nBat=temp.options[temp.selectedIndex].value;
        var nLvl=document.getElementById('nLvl').value;
        var nLcl=0;
        var com=0;
    }
    else if (type==='lcl'){
        var temp=document.getElementById('existBat2');
        var nBat=temp.options[temp.selectedIndex].value;
        var temp=document.getElementById('idNivToNewLcl');
        var nLvl=temp.options[temp.selectedIndex].value;
        var nLcl=document.getElementById('nLcl').value;
        var com=document.getElementById('comNLcl').value;
    }
    $.ajax({
        type:"GET",
        url:"js/php/logistique/addNewGestLoc.php",
        data:{
            nBat : nBat,
            nLvl : nLvl,
            nLcl : nLcl,
            com : com
        },
        success:function(retour){
            if(retour==1){
                location.reload();
            }
        }
    });
}

function addInfosNewLcl(){
    var temp=document.getElementById('existBat2');
    var idBat=temp.options[temp.selectedIndex].value;
    //alert(idBat);
    $.ajax({
        type:"GET",
        url:"js/php/logistique/getLvlByIdBat.php",
        data:{
            idBat : idBat
        },
        success:function(retour){document.getElementById('selectLclByBat').innerHTML=retour;}
    });
    //document.getElementById('selectLclByBat').innerHTML='<input type="text" value="Yo msieur White">';
}

function addInputTextForNewLcl(){
    document.getElementById("inputTextForNewLcl").innerHTML='<input type="text" class="form-control" id="nLcl" placeHolder="Nom local">';
    document.getElementById("inputCommentForNewLcl").innerHTML='<input type="text" class="form-control" id="comNLcl" placeHolder="Commentaire">';
}

function checkAddCatg(){
    var temp=document.getElementById('newMobSelectCateg');
    var rep=temp.options[temp.selectedIndex].value;
    if(rep=='-1'){
        slide('inputAddCateg');        
        document.getElementById('denomNewMobCateg').focus();
    }
    else{
        var status=document.getElementById('inputAddCateg').style.display;
        if((status=='block')||(status=='')){
            slide('inputAddCateg');           
        }        
         document.getElementById('denomNewMob').focus();
    }
}

function addNewMobCateg(host,url){
   
    var url='http://'+host+url+'&reload=menuAddMob';
    var newCateg=document.getElementById('denomNewMobCateg').value;
    $.ajax({
        type:"GET",
        url:"js/php/logistique/addNewMobCateg.php",
        data:{
            newCateg : newCateg,
        },
        success:function(retour){
            if(retour=='1'){
                window.location.replace(url);
            }
        }
    });
}

function editLcl(idLoc){
    $.ajax({
        type:"GET",
        url:"js/php/logistique/getInfoLclById.php",
        data:{
            idLoc : idLoc,
        },
        success:function(retour){
            document.getElementById('editingLcl'+idLoc).innerHTML=retour;
        },
    });    
}

function recordModifLcl(idLoc){
    var nDenom=document.getElementById('editingDenomLcl'+idLoc).value;
    var nCom=document.getElementById('editingComLcl'+idLoc).value;
    $.ajax({
        type:"GET",
        url:"js/php/logistique/recordModifLcl.php",
        data:{
            idLoc : idLoc,
            nDenom : nDenom,
            nCom : nCom,
        },
        success:function(retour){
            if(retour=='1'){
                
                alert('Modifications enregistrées');
            }
        },
    });     
}

function addSelectLvlByIdBat(){
    var temp=document.getElementById('selectedBatNewMob');
    var idBat=temp.options[temp.selectedIndex].value;
    if (idBat!='-1'){
        $.ajax({
            type:"GET",
            url:"js/php/logistique/addSelectLvlByIdBat.php",
            data:{
                idBat : idBat,
            },
            success:function(retour){
                document.getElementById('addSelectLvlNewMob').innerHTML=retour;
                var display=($('#addSelectLvlNewMob').css('display'));
                //alert(display);
                if(display=='none'){
                    $('#addSelectLvlNewMob').slideToggle(500);
                }
            }});
    }
    else{
        document.getElementById('addSelectLvlNewMob').innerHTML='<div class="input-group" style="width:650px;"><span class="input-group-addon" style="min-width:200px;">Niveau</span><input type="text" name="temp" class="form-control">';
        var display=($('#addSelectLvlNewMob').css('display'));
        if(display=='block'){
            slide('addSelectLvlNewMob');
        }
    }
}

function addSelectLclByIdBat(){
    var temp=document.getElementById('selectedNivNewMob');
    var idBat=temp.options[temp.selectedIndex].value;
    if (idBat!='-1'){
        $.ajax({
            type:"GET",
            url:"js/php/logistique/addSelectLclByIdBat.php",
            data:{
                idBat : idBat,
            },
            success:function(retour){
                document.getElementById('addSelectLclNewMob').innerHTML=retour;
                var display=($('#addSelectLclNewMob').css('display'));
                //alert(display);
                if(display=='none'){
                    $('#addSelectLclNewMob').slideToggle(500);
                }
            }});
    }
    else{
        document.getElementById('addSelectLclNewMob').innerHTML='<div class="input-group" style="width:650px;"><span class="input-group-addon" style="min-width:200px;">Local</span><input type="text" name="temp" class="form-control">';
        var display=($('#addSelectLclNewMob').css('display'));
        if(display=='block'){
            slide('addSelectLclNewMob');
        }
    }
}

function closeRow(i){
	document.getElementById('editRowOrder'+i).innerHTML='';
}

function infobulle(div,msg){
	document.getElementById(div).value=msg;
}

function hideInfobulle(div,msg){
	document.getElementById(div).value=msg;
}
