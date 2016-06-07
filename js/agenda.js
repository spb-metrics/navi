
/*function validaArqUrl(el) {

  alert(el.id); alert(el.value);	
  if (el.id=='url' && el.value!='') {
    arquivo = document.getElementById('arquivo');
    descricao = document.getElementById('descricao');
	alert('entrou!') 
	arquivo.disabled=true;
  }
  else {
    if ((el.id=='arquivo') || (el.id=='descricao' && el.value!='')) {
      url = document.getElementById('url');
      url.disabled=true;
    }
  }   
}*/

function validaFormEdita() {

  cont=0;
  if ((document.editaAula.arqNovo.value!='' && document.editaAula.arqDescricao.value=='' ) || (document.editaAula.arqUrl.value!='' && document.editaAula.arqDescricao.value=='')) {
	 
	 alert('Para colocarum arquivo ou link, preencha corretamente a descrição!');
     document.editaAula.arqDescricao.focus();
	 return false;     

  }
  if (document.editaAula.arqNovo.value!='') cont++;
  if (document.editaAula.codArquivoLocal.value!='') cont++;
  if (document.editaAula.arqUrl.value!='') cont++;
  if (cont>1)
  {
    alert('Insira um arquivo por vez. Use OU um novo arquivo OU um arquivo já exixtente OU um link!');
    document.editaAula.arqNovo.value='';
    document.editaAula.arqUrl.value='';
    document.editaAula.codArquivoLocal.value='';
    document.editaAula.arqDescricao.value='';
    return false;
  }
  
  if (document.editaAula.edata.value==""){
	alert('A aula não pode conter o campo DATA vazio. Preencha-o corretamente.');
	document.editaAula.edata.focus();
	return false;
  }

  return true;

}


function validaFormInsere() {

  cont=0;
  if ((document.insereAula.arqNovo.value!='' && document.insereAula.arqDescricao.value=='' ) || (document.insereAula.arqUrl.value!='' && document.insereAula.arqDescricao.value=='')) {
	  alert('Para colocar um arquivo ou link, preencha corretamente a descrição!');
    document.insereAula.arqDescricao.focus();
	  return false;     
  }
  
  if (document.insereAula.arqNovo.value!='') cont++;
  if (document.insereAula.codArquivoLocal.value!='') cont++;
  if (document.insereAula.arqUrl.value!='') cont++;
  if (cont>1)
  {
    alert('Insira um arquivo por vez. Use OU um novo arquivo OU um arquivo já exixtente OU um link!');
    document.insereAula.arqNovo.value='';
    document.insereAula.arqUrl.value='';
    document.insereAula.codArquivoLocal.value='';
    document.insereAula.arqDescricao.value='';
    return false;
  }
  
  if (document.insereAula.dataAula.value==""){
	alert('A aula não pode conter o campo DATA vazio. Preencha-o corretamente.');
	document.insereAula.dataAula.focus();
	return false;
  }

  return true;

}




