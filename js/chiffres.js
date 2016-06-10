function selectPrest(){
	var idFonct=document.getElementById('fonctionnalite').value;
	$.ajax(
	{
		type:"GET",
		url: "js/php/chiffres/selectPrestByFonct.php",
		data:
		{
			idFonct:idFonct,
		},
		success:function(html){
			$('#prestation').html(html);
		},
		error:notOk,
	}
	);
}