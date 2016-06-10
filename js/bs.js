function viewBS(idPat,idBS,urlFrom)
	{
	$.ajax({
		type : "GET",
		url : "js/php/bs/viewBS.php",
		data :
			{
			idPat : idPat,
			idbs : idBS,
			urlFrom : urlFrom,
			},
		success : function(retour){$('#Content').html(retour);},
		erroro : notOk,
		});
	
	}

function imprimer()
	{
	var headstr = "<html><head><link rel=\"stylesheet\" href=\"templates/mytpl/css/style.css\"><title></title></head><body>";
      var footstr = "</body>";
      var newstr = document.all.item('BS').innerHTML;
      var oldstr = document.body.innerHTML;
      document.body.innerHTML = headstr+newstr+footstr;
      window.print();
      document.body.innerHTML = oldstr;
      return false;
	}