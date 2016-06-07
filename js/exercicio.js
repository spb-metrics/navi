


 sortitems = 0;

function move(fbox,tbox) {
  for(var i=0; i<fbox.options.length; i++) {
    if(fbox.options[i].selected && fbox.options[i].value != "") {
      var no = new Option();
      no.value = fbox.options[i].value;
      no.text = fbox.options[i].text;
      tbox.options[tbox.options.length] = no;
      fbox.options[i].value = "";
      fbox.options[i].text = "";
     }
  }
  //adicionado por mim (pq se o cara n tiver javascript, o botao de submit nao habilita!)
 // if (document.getElementById("selectDestino[]").options.length > 0 ){
  //  document.getElementById("botaoSubmit").disabled = false;
  //}
  BumpUp(fbox);
  if (sortitems) SortD(tbox);
}


    
function BumpUp(box)  {
  for(var i=0; i<box.options.length; i++) {
    if(box.options[i].value == "")  {
      for(var j=i; j<(box.options.length-1); j++)  {
        box.options[j].value = box.options[j+1].value;
        box.options[j].text = box.options[j+1].text;
      }
      var ln = i;
      break;
    }
  }
  if(ln < box.options.length)  {
    box.options.length -= 1;
    BumpUp(box);
  }
}
        
function SortD(box)  {
  var temp_opts = new Array();
  var temp = new Object();
  for(var i=0; i<box.options.length; i++)  {
    temp_opts[i] = box.options[i];
  }
  for(var x=0; x<temp_opts.length-1; x++)  {
    for(var y=(x+1); y<temp_opts.length; y++)  {
      if(temp_opts[x].text > temp_opts[y].text)  {
        temp = temp_opts[x].text;
        temp_opts[x].text = temp_opts[y].text;
        temp_opts[y].text = temp;
        temp = temp_opts[x].value;
        temp_opts[x].value = temp_opts[y].value;
        temp_opts[y].value = temp;
      }
    }
  }
  for(var i=0; i<box.options.length; i++)  {
    box.options[i].value = temp_opts[i].value;
    box.options[i].text = temp_opts[i].text;
  }
}    



function enviar(boxOr,boxDes) {
    var contOr;
	var contDes;
    contOr = boxOr.options.length;
    for (var i=0;i<=contOr-1;i++){
        boxOr.options[i].selected = 1; 
    }
	contDes = boxDes.options.length;
    for (var i=0;i<=contDes-1;i++){
        boxDes.options[i].selected = 1; 
    }
}

//function formatHidden(dateF,dataI){
 //   alert("aqui");
//    if(dateI.length >0){
 //     document.forms['frmExercicio'].dataInicio.value =dateI.charAt(6)+dateI.charAt(7)+dateI.charAt(8)+dateI.charAt(9)+"-"+dateI.charAt(3)+dateI.charAt(4)+"-"+dateI.charAt(0)+dateI.charAt(1);
 //   }
  //  if (dateF.length>0){
 //     document.forms['frmExercicio'].dataExpiracao.value =dateF.charAt(6)+dateF.charAt(7)+dateF.charAt(8)+dateF.charAt(9)+"-"+dateF.charAt(3)+dateF.charAt(4)+"-"+dateF.charAt(0)+dateF.charAt(1);
 //   }
//}



