function upPresta(){
var fonction=document.getElementById('2').value;
$.ajax(
		{
		type: "GET",
		url: "js/php/patrouilles/selectPrestation.php",
		data:
			{
			fonction : fonction,
			},
		success:function (html){
		$('#presta').html(html);
		},
		error:notOk,			
		});
	}
	
function delPat(idPat)
	{
	var ok = confirm("Etes-vous sur de vouloir supprimer cette patrouille ? Les liens avec les missions éventuellement attribuées seront également supprimés.");
	if (ok)
		{
		$.ajax({
			type : "GET",
			url : "js/php/patrouilles/delPat.php",
			data :
				{
				idPat : idPat,
				},
			success : function (){location.reload();},
			error : notOk,
			});
		}
	}
	
function formNewRecu()
	{
	var t=document.getElementById('TPat');
	var type=t.options[t.selectedIndex].value;
	$.ajax({
		type:"GET",
		url:"js/php/patrouilles/formNewRecu.php",
		data:
			{
			type : type,
			},
		success:function(retour){
			$('#laSuite').html(retour);
			},
		essor:notOk,
		});
	}