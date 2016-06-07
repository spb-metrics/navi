

//By Martin Reus 
function confirmaAcao(local,mensagem) {
  var confirmar = confirm(mensagem)
  if (confirmar){
    eval("document.location.href = './?" + local + "'")
  }
}


function atualizaQuestoes(campo){
  document.getElementById("alternativas").innerHTML = "";
  if (campo.value < 2){
    document.getElementById("submit").disabled = true;
  }else{
    document.getElementById("submit").disabled = false;
  }
  for ($i = 1; $i<= campo.value ; ++$i){
    //criação dos campos de alternativas
    document.getElementById("alternativas").innerHTML += '<br>' + $i + ' - <textarea class="textarea" id="' + $i + '" rows="3" cols="130" name="alternativa' + $i + '"></textarea><input type="radio" id="resposta" name="resposta" value="' + $i + '">';
  }
}


function verificaCampos(){

  //testa pra ver se questao ta vazia
  if (document.getElementById("textarea").value == ""){
    alert("Preencha a questão!");
    document.getElementById("textarea").focus();
    return false;
  }else{
    //o $i aqui eh a id da textarea das alternativas, portanto ele vai testar da id 0 ate 2, por exemplo, se tiver 3 alternativas escolhidas pelo usuario
    for ($i = 1; $i <= document.getElementById("numeroQuestoes").value; ++$i){
      if (document.getElementById($i).value == ""){
        alert("Preencha todas as alternativas!");
        document.getElementById($i).focus();
        return false;
      }
    }
  }
  //testa radios 
  if(!checkRadioControl('resposta','Escolha a resposta certa')){
    return false;
  }
  return true;
}

function verificaCamposExercicio(text,select){
  //testa pra ver se questao ta vazia
  if (document.getElementById(text).value == ""){
    alert("Preencha o nome do Exercício!");
    document.getElementById(text).focus();
    return false;
  }else if (document.getElementById(select).options.length < 2){
    alert("Escolha ao menos 2 questões para compor a prova!");
    return false;
  } else {
    return true;
  }
}


//nao eh minha essa funcao!!
//so funciona com Iexplorer (mas funciona...)
function checkRadioControl(strFieldName,strMessage){
     var objFormField = document.forms[0].elements[strFieldName]
     intControlLength = objFormField.length
     bolSelected = false;
     for (i=0;i<intControlLength;i++){
          if(objFormField[i].checked){
                    bolSelected = true;
                    break;
          }
     }     
     if(! bolSelected){
          alert(strMessage);
          return false;
     }else{
          return true;
     }
}

function OneElement(select){
 if (document.getElementById(select).options.length < 1){
    alert("Escolha pelo menos um Menu!");
    return false;
  } else {
    return true;
  }
}
