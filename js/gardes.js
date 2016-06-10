function garde_to_step1(from){
	if (from=='0'){
		$('#garde_step2').html('');
		$('#garde_step3').html('');
		}
	var select=document.getElementById("sv_garde");
	var selected=select.options[select.selectedIndex].value;	
	$.ajax({
		type:"GET",
		url:"js/php/gardes/goToStep1.php",
		data:{
			select : selected,
			},
		success:function(retour){$('#garde_step1').html(retour);},
		error:notOk,
		});
	}
	
function addNewType(){
	var newType=document.getElementById('newTypeGarde').value;
	$.ajax({
		type:"GET",
		url:"js/php/gardes/newTypeGarde.php",
		data:{
			newType : newType,
			},
		success:function(retour){$('#tabNewTypeGarde').html(retour);reloadMenuGestionTypeGarde();},
		error:notOk,
		});
	}
	
function modifTypeGarde(id,action){
	if(action=='-1'){
		var ok = confirm('Etes-vous certain de vouloir supprimer cette ligne ?');
	}
	else var ok = true;
	if (ok){
		$.ajax({
			type:"GET",
			url:"js/php/gardes/modifTypeGarde.php",
			data:{
				id : id,
				action : action,
				},
			success:function(retour){$('#trGarde_'+id).html(retour);reloadMenuGestionTypeGarde();},
			error:notOk,
			});	
		}
	}

function majTypeGarde(id){
	var denom=document.getElementById('newDenom_'+id).value;
	$.ajax({
		type:"GET",
		url:"js/php/gardes/majTypeGarde.php",
		data:{
			id : id,
			denom : denom,
			},
		success:function(retour){$('#trGarde_'+id).html(retour); reloadMenuGestionTypeGarde();},
		error:notOk,
		});
	}
	
function reloadMenuGestionTypeGarde(){
	$.ajax({
		type:"GET",
		url:"js/php/gardes/reloadMenuGestionTypeGarde.php",
		data:{},
		success:function(retour){$('#menuGarde').html(retour);},
		error:notOk,
	});
	}
	
function addSvGarde(idTypeGarde,step){
	if(step=='1'){
		var denom=document.getElementById('denomNewSvGarde').value;
		}
	else var denom='Vide';
	
	$.ajax({
		type:"GET",
		url:"js/php/gardes/addSvGarde.php",
		data:{
			idTypeGarde : idTypeGarde,
			step : step,
			denom : denom,
			},
		success:function(retour){$('#garde_step2').html(retour);garde_to_step1()},
		error:notOk,
		});
	}
	
function modifSvGarde(idSv,action){
	if(action=='delete'){
		var ok = confirm('Etes-vous sûr de vouloir supprimer ce service ?');
	}
	else var ok = true;
	if(ok){
		$.ajax({
			type:"GET",
			url:"js/php/gardes/modifSvGarde.php",
			data:{
				idSv : idSv,
				action : action,
				},
			success:function(retour){$('#trSV_'+idSv).html(retour);},	
			});
	}
}

function gestMembresSvGarde(idSv){
	$.ajax({
		type:"GET",
		url:"js/php/gardes/gestMembresSvGarde.php",
		data:{
			idSv : idSv,
			},
		success:function(retour){$('#tdSV_'+idSv).html(retour);},
		error:notOk,
		});
}

function addPersToSvGarde(idSv,step,reset){
	// alert(idSv);
	if (document.getElementById('selectTypeContact')){
		var select=document.getElementById('selectTypeContact');
		var typeOfPersSelected=select.options[select.selectedIndex].value;
		}
	else{
		var typeOfPersSelected='N';
		}
	if (document.getElementById('SelectedPersExt')){
		var selectPersExt=document.getElementById('SelectedPersExt');
		var idOfPersSelected=selectPersExt.options[selectPersExt.selectedIndex].value;
		}
	else{
		var idOfPersSelected='-1';
	}	
	if(reset=='reset'){
		$('#garde_step2').html('');
		$('#garde_step3').html('');				
		}
	$.ajax({
		type:"GET",
		url:"js/php/gardes/addPersToSvGarde.php",
		data:{
			idSv : idSv,
			step : step,
			typeOfPersSelected : typeOfPersSelected,
			idOfPersSelected : idOfPersSelected,
			},
		success:function(retour){
			if(step=='1'){
				$('#garde_step2').html(retour);
				}
			else if(step=='2')	{
				$('#garde_step3').html(retour);
				}
			},
		error:notOk,
		});
}

function recNewPersGarde(idSv,typePers){

		var nom=document.getElementById('nomPersExt').value;
		var prenom=document.getElementById('prenomPersExt').value;
		var tel=document.getElementById('telPersExt').value;
		var gsm=document.getElementById('gsmPersExt').value;
		var fax=document.getElementById('faxPersExt').value;
		var mail=document.getElementById('emailPersExt').value;
		var rue=document.getElementById('ruePersExt').value;
		var num=document.getElementById('numPersExt').value;
		var CP=document.getElementById('CPPersExt').value;
		var ville=document.getElementById('villePersExt').value;
		if ((document.getElementById('idPersE')) != 'undefined'){
			var idPersE=document.getElementById('idPersE').value;
		}
		else var idPersE='-1';
		// alert (idPersE);
		if(typePers=='I'){
			var select=document.getElementById('selectPersInt');
			var idPersInt=select.options[select.selectedIndex].value;			
			}
		else var idPersInt='-1';

	$.ajax({
		type:"GET",
		url:"js/php/gardes/recNewPersGarde.php",
		data:{
			idSv : idSv,
			typePers : typePers,
			nom : nom,
			prenom : prenom,
			tel : tel,
			gsm : gsm,
			fax : fax,
			mail : mail,
			rue : rue,
			num : num,
			CP : CP,
			ville : ville,
			idPersInt : idPersInt,
			idPersE : idPersE,
		},
		success : function(retour){
			alert(retour);
			$('#garde_step2').html('');
			$('#garde_step3').html('');
			garde_to_step1();
			},
		error : notOk,
		});
}

function showMoreFromUserInt(){
	var select=document.getElementById('selectPersInt');
	var idPersInt=select.options[select.selectedIndex].value;
	$.ajax({
		type:"GET",
		url:"js/php/gardes/showMoreFromUserIn.php",
		data:{
			idPersInt : idPersInt,
			},
		success:function(retour){$('#moreInfoUserInt').html(retour);},	
		error:notOk,
		});
}

function modifPersGarde(typePers,id,action,sv){
	if(action=='delete'){
		var ok=confirm('Etes-vous sûr de vouloir supprimer cette liaison ?');
	}
	else ok=true;
	
	if (action=='Record'){
		var nom=document.getElementById('nomPers').value;
		var prenom=document.getElementById('prenomPers').value;
		var fixe=document.getElementById('telPers').value;
		var gsm=document.getElementById('gsmPers').value;
		var fax=document.getElementById('faxPers').value;
		var mail=document.getElementById('mailPers').value;
		var rue=document.getElementById('ruePers').value;
		var numero=document.getElementById('numPers').value;
		var CP=document.getElementById('CPPers').value;
		var ville=document.getElementById('villePers').value;
		$.ajax({
			type:"GET",
			url:"js/php/gardes/modifPersGarde.php",
			data:{
				typePers : typePers,
				id : id,
				action : action,
				sv : sv,
				nom : nom,
				prenom : prenom,
				fixe : fixe,
				gsm : gsm,
				fax : fax,
				mail : mail,
				rue : rue,
				numero : numero,
				CP : CP,
				ville : ville,
				},
			success:function(retour){$('#garde_step3').html(retour); $('#garde_step4').html('');},
			error : notOk,
			});
		}
	else{	
		if (ok){
			$.ajax({
				type:"GET",
				url:"js/php/gardes/modifPersGarde.php",
				data:{
					typePers : typePers,
					id : id,
					action : action,
					sv : sv,
				},
				success:function(retour){
					if(action=='delete'){
					alert(retour);
					garde_to_step1();
					}
					else{
						$('#garde_step2').html(retour);
						$('#garde_step3').html('');
						$('#garde_step4').html('');
						}
					},
				error:notOk,
			});
		}
	}
}

function delGardeById(idGarde){
	var ok = confirm('Etes-vous sûr de vouloir supprimer ce rôle de garde ?');
	if (ok){
		$.ajax({
			type:"GET",
			url:"js/php/gardes/delGardeById.php",
			data:{
				idGarde : idGarde,
				},
			success:function(){document.location.href='?component=garde&action=mainMenu';},
			error : notOk,
			});
		}
}

function infosPersGarde(infos,id){
	alert(infos);
	// alert (type+' '+id);
	// $.ajax({
		// type : "GET",
		// url : "js/php/gardes/infosPersGarde.php",
		// data:{
			// type : type,
			// id : id,
			// },
		// success : function(retour){alert(retour);},
		// error : notOk,
		// });
}