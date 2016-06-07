

//scripts para movimentacao de itens em selects em forma de listas
// This script and many more are available free online at ";
// The JavaScript Source!! http://javascript.internet.com ";
sortitems = 0;  // Automatically sort items within lists? (1 or 0)


	 
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
  if (document.getElementById("selectDestino[]").options.length > 0 ){
    document.getElementById("botaoSubmit").disabled = false;
    document.getElementById("botaoSubmit2").disabled = false;
  }
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

function addListSend(boxOr,boxDes) {
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

//criar menu.js

//itensOrigem=> array que contem os itens do (select) id=selectOrigem[] 
//itensDestino=>array que contem os itens do (select)  id=selectDestino[]
//atributosItensMenu=> array que contem os itens do (select) id=itensMenu 
//idRepositorioItensOrigem=>id da div que vai mostrar as imgs orimgem
//idRepositorioItensDestino=>id da div que vai mostrar as imgs destino

function atualizaItens(itensOrigem,itensDestino, atributosItensMenu, idRepositorioItensOrigem, idRepositorioItensDestino) {

var	repositorioOrigem = document.getElementById(idRepositorioItensOrigem);
var	repositorioDestino = document.getElementById(idRepositorioItensDestino);	

	//imprimindo imagens Inativas
	repositorioOrigem.innerHTML="";
	for (var i=0; i<itensOrigem.options.length; i++){
		for (var j=0;j<atributosItensMenu.options.length;j++){
			var	tempOr    = atributosItensMenu.options[j].value;
				tempOr    = tempOr.split("|");
			var rootImgOr = tempOr[0];
			var titleOr   = tempOr[1];
			if(itensOrigem.options[i].value==atributosItensMenu.options[j].text && itensOrigem.options[i].value!="") {
				repositorioOrigem.innerHTML += "<img  style='width:20px; height: 20px;'  src='" + rootImgOr + "' title='" + titleOr + "' border='no'>";
				break;
					
			}
		}
	}

// imprimindo img Ativas
	repositorioDestino.innerHTML="";
	for (var i=0; i<itensDestino.options.length; i++){
		for (var j=0;j<atributosItensMenu.options.length;j++){
			var	temp    = atributosItensMenu.options[j].value;
				temp    = temp.split("|");
			var rootImgDes = temp[0];
			var titleDes   = temp[1];
			if(itensDestino.options[i].value==atributosItensMenu.options[j].text && itensDestino.options[i].value!="") {
					repositorioDestino.innerHTML += "<img  style='width:20px; height: 20px;'  src='" + rootImgDes + "' title='" + titleDes + "' border='no'>";
				break;
					
			}
		}
	}




}
