function aqui(type,user){
	$.ajax(
			{
			type: "GET",
			url: "js/php/logistique/aqui.php",
			data:
				{
				type : type,
				user : user,
				},
			success:function(retour){alert(retour)},
			});	
}


function updateDate(type,id,subAction){
	switch(subAction){
		case 'livraison':
			var newDate=document.getElementById('newDateL').value;
			break;
		case 'ctrlArmu':
			var newDate=document.getElementById('newDateC').value;
			break;
		case 'validite':
			var newDate=document.getElementById('newDate'+id).value;
			break;
	}
	
	$.ajax(
			{
			type: "GET",
			url: "js/php/logistique/updateLivraison.php",
			data:
				{
				type : type,
				id : id,
				date : newDate,
				sub : subAction,
				},
			// success:function(retour){alert(id+' '+type+' '+newDate+' '+subAction)},
			});	
}