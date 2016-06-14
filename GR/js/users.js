function verifOldPwd(user){
	var oldPwd=document.getElementById('oldPwd').value;
	$.ajax({
		type:"GET",
		url:"js/php/users/oldPwd.php",
		data:{
			oldPwd:oldPwd,
			user:user,
		},
		success : function(retour){
			if(retour=='1'){
				document.getElementById('oldPwd').style.backgroundColor = '#66FF99';
			}
			else{
				document.getElementById('oldPwd').style.backgroundColor = 'red';
			}
		},
	});
}

function validNewPwd(){
	var newPwd1=document.getElementById('newPwd1').value;
	var maj=0;
	var min=0;
	var entier=0;
	var message='';
	if(newPwd1.length>7){
		for(var i=0; i<newPwd1.length; i++){
			if(!isNaN(newPwd1.substr(i,1))){entier=1;}
			else{
				if(newPwd1.substr(i,1).toUpperCase()!=newPwd1.substr(i,1)){min=1;}
				if(newPwd1.substr(i,1).toUpperCase()==newPwd1.substr(i,1)){maj=1;}
			}
		}
		if((maj==0)||(min==0)||(entier==0)){
		if(maj==0){
			message+='Votre mot de passe ne contient pas de majuscule\n';
			}
		if(min==0){
			message+='Votre mot de passe ne contient pas de minuscule\n';
			}
		if(entier==0){
			message+='Votre mot de passe ne contient pas de chiffre\n';
			}
		alert(message);
		document.getElementById("trSubmit").style.display = "none";
		document.getElementById('newPwd1').value='';
		}
		else{
			
		}
	}
	else {
		alert('Ce mot de passe est trop court. (Min. 8 caractères)');
	}
}

function verifNewPwd(){
	var newPwd1=document.getElementById('newPwd1').value;
	if(newPwd1.length>7){
		var newPwd1=document.getElementById('newPwd1').value;
		var newPwd2=document.getElementById('newPwd2').value;
		if(newPwd1==newPwd2){
			document.getElementById('newPwd2').style.backgroundColor = '#66FF99';
			document.getElementById('newPwd1').style.backgroundColor = '#66FF99';
			document.getElementById("trSubmit").style.display = "block";
		}
		else{
			document.getElementById('newPwd2').style.backgroundColor = 'red';
			document.getElementById("trSubmit").style.display = "none";
		}
	}
}

function showTrSubmit(){
	var newPwd1=document.getElementById('newPwd1').value;
	if(newPwd1.length>7){
		var newPwd1=document.getElementById('newPwd1').value;
		var newPwd2=document.getElementById('newPwd2').value;
		if(newPwd1==newPwd2){
			$('#trSubmit').slideToggle(500);
		}
		else{
			document.getElementById("trSubmit").style.display = "flex";
		}
	}
}

function resetmdp2(){
	document.getElementById('newPwd2').value='';
}