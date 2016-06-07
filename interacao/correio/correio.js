


/**
 * Checa se algum dos anexos possui nomes iguais
 */ 
function checkAnexosFileNames() {
  form = document.forms[0];

  //descobre qtos inputs file existem
  //le todos os inputs
  inputs = form.getElementsByTagName('INPUT');
    
  sucesso = true;  
    
  //variavel q gaurda os inputs arquivos  
  iptsFile = new Array();  
    
  //varre os inputs para descobrir quais sao os inputs arquivos
  for(i=0; i < inputs.length; i++) {
    if (inputs[i].type.toLowerCase() == 'file') {
      iptsFile.push(inputs[i]);
    }  
  }
  
  //agora percorre os inputs file para ver se existe algum com nomes de arquivo iguais
  for(i=0; i < iptsFile.length; i++) {
    temNomeIgual = false;

    if (iptsFile[i].value != "") {
  
      for(j=0; j < i; j++) {
        if (iptsFile[i].value == iptsFile[j].value) {
          temNomeIgual = true;
          break;
        }
      } 
      
      if (temNomeIgual) {
        //tem nome igual, retorna falso
        sucesso = false;
        break;
      }
    } 
  }

  return sucesso;
}

/**
 * Adiciona um novo componente de envio de arquivo para os anexos 
 */ 
function addNewAnexoCtrl() {
  form = document.forms[0];
  
  //descobre qtos inputs file existem
  inputs = form.getElementsByTagName('INPUT');
  numInputsFile = 0;     
    
  for(i=0; i < inputs.length; i++) {
    if (inputs[i].type.toLowerCase() == 'file') {
      numInputsFile++;
      if (inputs[i].value == '') {
        //se tiver algum input file que nao esteja preenchido ainda
        //entao simplesmente sai da funcao, nao insere um novo input file
        return;
      } 
    }
  }
  
  //cria o novo input file
  newInputFile = document.createElement('INPUT');
  newInputFile.type = 'file';
  newInputFile.name = 'anexo_' + (numInputsFile + 1);
  newInputFile.id = newInputFile.name;
  newInputFile.onchange = addNewAnexoCtrl;
  
  //adiciona ele a tabela
  tbl = document.getElementById('tblCompoeMsg');
  tbodyRes = tbl.getElementsByTagName('TBODY');
  tbody = tbodyRes[0];

  tr = document.createElement('TR');
  tr.id = 'linhaAnexo' + (numInputsFile + 1);
  td = document.createElement('TD');
  td.appendChild(newInputFile);
  td.setAttribute('colSpan',3);
  tr.appendChild(td);

  //le a linha (TR) do ultimo input file
  linhaUltimoIptFile = document.getElementById('linhaAnexo' + numInputsFile);
  //no caso, insere o nova linha antes do proximo irmao da ultima linha do ipt file
  tbody.insertBefore(tr,linhaUltimoIptFile.nextSibling); 
}
