function step2()
	{
	var dn=document.getElementById('teams');
	$.ajax(
		{
		type: "GET",
		url: "js/php/missions/addStep2.php",
		data:
			{
			pat : pat,
			},
		success:function(html){
			$('#step2').html(html);
			},
		error:notOk,
		});	
	}
	
function getListRues()
	{
	var idQ=document.getElementById('quartier');
	var quartier=idQ.options[idQ.selectedIndex].value;
	$.ajax(
		{
		type: "GET",
		url: "js/php/missions/addListRues.php",
		data:
			{
			idQ : quartier,
			},
		success:function(html){
			$('#listRues').html(html);
			},
		error:notOk,
		});	

	}
	
function attriMissions(level)
	{
	var dhb=document.getElementById('dhb').value;
	var dhh=document.getElementById('dhh').value;
	// alert(dhb);
	if(level<20)
		{
		alert ('Vous n\'avez pas accès à cette application');
		}
	else
		{
		$.ajax({
			type:"GET",
			url:"js/php/missions/attriMissions.php",
			data:
				{
				dhh : dhh,
				dhb : dhb,
				level : level,
				},
			success:function(retour){
				$('#attriMissions').html(retour);
				},	
			error:notOk,
			});
		// alert (dhb+' '+dhh);
		}
	}
	
function changeCbAttrib(equipe,mission,i,j)
	{
	var val=document.getElementById('cbAttrib'+i+j).checked;
	$.ajax({
		type : "GET",
		url : "js/php/missions/changeCbAttrib.php",
		data :
			{
			equipe : equipe,
			mission : mission,
			status : val,
			},
		// success : function(retour){$('#attriMissions').html(retour);},
		error : notOk,
		});
	}
	
function changeCbVacAttrib(equipe,mission,i,j)
	{
	var val=document.getElementById('cbVacAttrib'+i+j).checked;
	$.ajax({
		type : "GET",
		url : "js/php/missions/changeCbVacAttrib.php",
		data :
			{
			equipe : equipe,
			mission : mission,
			status : val,
			},
		// success : function(retour){$('#attriMissions').html(retour);},
		error : notOk,
		});
	}	
	
function changeAllCbAttrib(mission,debut,fin,i,j)
	{
	var val=document.getElementById('cbAllRow'+i).checked;
	$.ajax({
		type : "GET",
		url : "js/php/missions/changeAllCbAttrib.php",
		data :{
			status : val,
			mission : mission,
			debut : debut,
			fin : fin,
			},
		success : function(){
			for(a=0;a<j;a++)
				{
				if (val==true)
					{
					document.getElementById('cbAttrib'+i+a).checked = true;
					}
				else if (val==false)
					{
					document.getElementById('cbAttrib'+i+a).checked = false;
					}
				}
			},	
		error : notOk,	
		});
	}
	
function changeAllCbVacAttrib(mission,debut,fin,i,j)
	{
	var val=document.getElementById('cbVacAllRow'+i).checked;
	$.ajax({
		type : "GET",
		url : "js/php/missions/changeAllCbVacAttrib.php",
		data :{
			status : val,
			mission : mission,
			debut : debut,
			fin : fin,
			},
		success : function(){
			for(a=0;a<j;a++)
				{
				if (val==true)
					{
					document.getElementById('cbVacAttrib'+i+a).checked = true;
					}
				else if (val==false)
					{
					document.getElementById('cbVacAttrib'+i+a).checked = false;
					}
				}
			},	
		error : notOk,	
		});
	}
	
function gestTypesMissions()
	{
	$('#Add').html('essai Types');
	}
	
function gestLieuxMissions(lvl,level)
	{
	$.ajax({
		type:"GET",
		url:"js/php/missions/gestLieuMission.php",
		data:{
			lvl : lvl,
			level : level,
			},
	success : function(retour){$('#Add').html(retour);},
	error : notOk,	
	});
	
	}
	
function addOtherMission(level)
	{
	var mission=document.getElementById('mission');
	var idMission=mission.options[mission.selectedIndex].value;
	var lieu=document.getElementById('lieu');
	var idLieu=lieu.options[lieu.selectedIndex].value;
	var equipe=document.getElementById('equipe');
	var idEquipe=equipe.options[equipe.selectedIndex].value;
	if ((idMission!=-1) && (idLieu!=-1) && (idEquipe!=-1))
		{
		$.ajax({
			type : "GET",
			url : "js/php/missions/addOtherMission.php",
			data : {
				idMission : idMission,
				idLieu : idLieu,
				idEquipe : idEquipe,
				},
			// success : function(retour){$('#Add').html(retour);},
			success : attriMissions(level),
			error : notOk,
			});
		}
	else
		{
		alert ('Tous les champs n\'ont pas été sélectionnés, veuillez corriger.');
		}
	}
	
function delOtherMission(idMission,level)
	{
	$.ajax({
		type:"GET",
		url:"js/php/missions/delOtherMission.php",
		data : {
			idMission : idMission,
			},
			success : attriMissions(level),
			error : notOk,			
		});
	}
	
function addLieuMission(lvl,level)
	{
	$.ajax({
		type:"GET",
		url:"js/php/missions/gestLieuMission.php",
		data:{
			lvl : lvl,
			level : level,
			},
			success : function(retour){$('#newLieu').html(retour);},
			error : notOk,
		});
	}
	
function recNewLieu(level)
	{
	var NewLieu=document.getElementById('NewLieu').value;
	$.ajax({
		type:"GET",
		url:"js/php/missions/recNewLieu.php",
		data:{
			NewLieu : NewLieu,
			},
		success : attriMissions(level),
		error : notOk,		
		});
	}
	
function delLieuMission(idLieu,level)
	{
	var ok = confirm('Etes-vous sûr de vouloir supprimer ce lieu ?');
	if (ok)
		{
		$.ajax({
			type:"GET",
			url:"js/php/missions/delLieuMission.php",
			data:{
				idLieu : idLieu,
				},
			success : attriMissions(level),
			error : notOk,		
			});
		}
	}
	
function modifLieuMission(idLieu,level)
	{
	$.ajax({
		type:"GET",
		url:"js/php/missions/modifLieuMission.php",
		data:{
			idLieu : idLieu,
			level : level,
			},
		success : function(retour){$('#lieu'+idLieu).html(retour);},
		error : notOk,		
		});	
	}
	
function recModifsLieu(idLieu,level)
	{
	var newLieu=document.getElementById('newLieu'+idLieu).value;
	$.ajax({
		type:"GET",
		url:"js/php/missions/recLieuMission.php",
		data:{
			idLieu : idLieu,
			newLieu : newLieu,
			},
		success : attriMissions(level),
		error : notOk,		
		});	
	}