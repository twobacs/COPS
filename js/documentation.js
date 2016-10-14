/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function menuAddFile(){
    $.ajax({
       type:"GET",
       url:"js/php/documentation/getCategFile.php",
       success:function(retour){$('#menuAddFile').html(retour);},
       });
}
