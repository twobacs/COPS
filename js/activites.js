function modifyEntity(bs,field,operation)
{
// alert (field + operation);

$.ajax(
		{
		type: "GET",
		url: "js/php/activites/modifyEntity.php",
		data:
			{
			field : field,
			operation : operation,
			bs : bs,
			},
		success:function (html){
		$('#'+field).html(html);
		},
		error:notOk,			
		});
}

function getcoord()
	{
	var coord=new Array();
	if (navigator.geolocation)
		{
		  navigator.geolocation.getCurrentPosition(function(position)
			{
			var latitude = position.coords.latitude;
			var longitude = position.coords.longitude;
			coord["latitude"]=latitude;
			coord["longitude"]=longitude;
			// return coord;
			});
		return coord;
		}
	return coord;
	}
	
function intervention(pat,time,depuis)
	{
	var numFiche=document.getElementById('numFiche').value;
	// var coord=new Array();
	// if (navigator.geolocation)
		// {
		  // navigator.geolocation.getCurrentPosition(function(position)
			// {
			// var latitude = position.coords.latitude;
			// var longitude = position.coords.longitude;
			// coord["latitude"]=latitude;
			// coord["longitude"]=longitude;
			$.ajax(
				{
				type: "GET",
				url: "js/php/activites/intervention.php",
				data:
					{
					// latitude : latitude,
					// longitude : longitude,
					pat : pat,
					time : time,
					fiche : numFiche,
					depuis : depuis,
					},
				success:function (html){
				$('#button').html(html);
				$('#num_fiche').html('<textarea rows="4" cols="80" id="numFiche" name="numFiche" required>'+numFiche+'</textarea>');
				},
				error:notOk,			
				});
				if (depuis=='FIN')
				{
					window.location.replace("index.php?mode=m&component=users&action=fromMenuTablette");
				}
			// });
		// }
	}
	
function imprimer(divId)
	{
	var headstr = "<html><head><link rel=\"stylesheet\" href=\"templates/mytpl/css/style.css\"><title></title></head><body>";
      var footstr = "</body>";
      var newstr = document.all.item('divID').innerHTML;
      var oldstr = document.body.innerHTML;
      document.body.innerHTML = headstr+newstr+footstr;
      window.print();
      document.body.innerHTML = oldstr;
      return false;
	}
	
function checkPlein(i,idbs,vv)
	{
	pleinO = document.getElementById('pleinO'+i).checked;
	pleinN = document.getElementById('pleinN'+i).checked;
	if (pleinO==true)
		{
		var plein="O";
		}
	if (pleinN==true)
		{
		var plein="N";
		}
	// alert (vv);	
	$.ajax({
		type:"GET",
		url:"js/php/activites/modifPleinVV.php",
		data:{
			idbs : idbs,
			plein : plein,
			vv : vv,
			},
		});
		
	}
	
function DegatsVV(i,idbs,vv)
	{
	var degats=document.getElementById('DegatsVV'+i).value;
	$.ajax({
		type:"GET",
		url:"js/php/activites/modifDegatsVV.php",
		data:{
			idbs : idbs,
			degats : degats,
			vv : vv,
			},
		});
	}
	
function sendBS(idPat,idbs)
	{
	alert('Votre BS a été correctement sauvegardé.  Vous pouvez à présent vous déconnecter.');	
	}
	
function updateCom(idPat,idBS){
	var com=document.getElementById('commentaire').value;
	$.ajax({
		type:"GET",
		url:"js/php/activites/updateCom.php",
		data:{
			idPat : idPat,
			idBS : idBS,
			com : com,
		},
		// success:function (html){alert(html);}
	});
}

function delUserOfBS(user, idbs){
	$.ajax({
		type:"GET",
		url:"js/php/activites/modifBS.php",
		data:{
			idbs : idbs,
			user : user,
		},
		success:function (html){alert(html);window.location.reload();},
		});
}

function addUserToBS(bs){
	$.ajax({
		type:"GET",
		url:"js/php/activites/getUsers.php",
		success:function (html){$('#trAddUser').html(html);$('#trbAdd').html('<td colspan="2" align="center"><input type="button" onclick="recNewUserToBS(\''+bs+'\');" value="Enregistrer"></td>')},
		});
}

function recNewUserToBS(bs){
	var user=document.getElementById("newUser").value;
	$.ajax({
		type:"GET",
		url:"js/php/activites/recNewUserToBS.php",
		data:{
			idbs : bs,
			user : user,
		},
		success:function (){window.location.reload();},
		});
	
}

function updateBS(bs, type){
	switch (type){
		case 'arme':
			idSelect='newArmeCo';
			break;
		case 'photo':
			idSelect='newAppPhoto';
			break;
		case 'ett':
			idSelect='newETT';
			break;
		case 'gsm':
			idSelect='newGSM';
			break;
	}
	var objet = document.getElementById(idSelect).value;
	$.ajax({
		type:"GET",
		url:"js/php/activites/updateBS.php",
		data:{
			idbs : bs,
			objet : objet,
			type : type,
		},
		// success:function(html){alert(html);window.location.reload();},
		success:function(){;window.location.reload();},
		});
	// alert(objet);
}

function delVVOfBS(vv, idbs){
	alert(vv+' '+idbs);
	$.ajax({
		type:"GET",
		url:"js/php/activites/modifVVBS.php",
		data:{
			idbs : idbs,
			vv : vv,
		},
		success:function (html){alert(html);window.location.reload();},
		});
}

function addVVToBS(bs){
	$.ajax({
		type:"GET",
		url:"js/php/activites/getVV.php",
		success:function (html){$('#trAddVV').html(html);$('#trbAdd').html('<td colspan="2" align="center"><input type="button" onclick="recNewVVToBS(\''+bs+'\');" value="Enregistrer"></td>')},
		});
}

function recNewVVToBS(bs){
	var vv=document.getElementById("newVV").value;
	$.ajax({
		type:"GET",
		url:"js/php/activites/recNewVVToBS.php",
		data:{
			idbs : bs,
			vv : vv,
		},
		success:function (){window.location.reload();},
		});
	
}