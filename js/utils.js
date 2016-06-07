

function marcaCombos(idformulario,valorCombo) {
  formulario = document.getElementById(idformulario);
  for (i=0;i<formulario.elements.length;i++) 
    if(formulario.elements[i].type == 'checkbox')  
      formulario.elements[i].checked=valorCombo;
}

	  
    function mostraForm(a){
  	   aa = document.getElementById(a).style.display;
		   if (aa == "inline"){document.getElementById(a).style.display = "none";}
       else{	document.getElementById(a).style.display = "inline";}
   } 
 
	 var status = 1;
	function mudaFigura(obj,url){
    if (status == 0) {
      document.getElementById(obj.id).src = ''+url+'/imagens/aumenta.gif';
      //alert(''+url+'/imagens/aumenta.gif');
      status = 1;
    } 
    else {
      document.getElementById(obj.id).src = ''+url+'/imagens/diminui.gif';
    // alert(''+url+'/imagens/diminui.gif');
      status = 0;
    }
   
 }
