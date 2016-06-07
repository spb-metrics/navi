<?

define("PREFIXO_ARQUIVO","arq_");
define("TAM_PREFIXO_ARQUIVO",4);



function getAulas($codInstanciaGlobal, $invisivel='') {
  if ($invisivel==1) {
    $sql = "SELECT * FROM aula_agenda AA WHERE AA.codInstanciaGlobal =".quote_smart($codInstanciaGlobal)." AND invisivel!=1 ORDER BY data";
  }
  else {
    $sql = "SELECT * FROM aula_agenda AA WHERE AA.codInstanciaGlobal =".quote_smart($codInstanciaGlobal)." ORDER BY data";
  }
  $result=mysql_query($sql);
  return $result;
}

function getArquivosAula($codAula) {
  $sql = " SELECT * FROM arquivo A INNER JOIN arquivo_aula_agenda AAA ON (A.COD_ARQUIVO = AAA.COD_ARQUIVO) WHERE AAA.codAula =".quote_smart($codAula);
  $sql.= " ORDER BY AAA.ordem";
  
  $result = mysql_query($sql);
  return $result;
}


function getArquivo($codArquivo) {
  $sql = "SELECT COD_ARQUIVO, CAMINHO_LOCAL_ARQUIVO,TIPO_ARQUIVO,DESC_ARQUIVO FROM arquivo WHERE COD_ARQUIVO =".quote_smart($codArquivo);

  $result = mysql_query($sql);
  return $result;
}


function removeArquivoAula($codArquivo,$codAula) {
  /*  tabela arquivo */
  //$sql = "DELETE FROM arquivo WHERE COD_ARQUIVO=".quote_smart($codArquivo);
  //mysql_query($sql);
  /*  relacionamento com arquivos da agenda */
  $sql = "DELETE FROM arquivo_aula_agenda WHERE COD_ARQUIVO=".quote_smart($codArquivo)." and codAula=".quote_smart($codAula);
  mysql_query($sql);
  //echo "ERRO: ".mysql_error(); die();
}

function exibeIconeMimeType($tipoMime) {
  $imagem['application/msword'] ="word.jpg";
  $imagem['application/pdf'] ="pdf.jpg";
  $imagem['text/plain'] ="txt.jpg";
  $imagem['application/vnd.ms-powerpoint'] ="pwrpnt.jpg";
  $imagem['image/jpg'] ="img.jpg";
  $imagem['image/jpeg'] ="img.jpg";
  $imagem['image/bmp'] ="img.jpg";
  $imagem['image/pjpeg'] ="img.jpg";
  $imagem['application/vnd.ms-excel'] ="excel.jpg";
  $imagem['text/html'] ="htm.jpg";
  $imagem['application/msaccess'] ="access.jpg";
  $imagem['application/zip'] ="zip.jpg";
  $imagem['audio/mpeg'] ="mp3.jpg";
  
  return $imagem[$tipoMime];
}


function insereArquivoAula($codPessoa,$arquivoNovo,$codAula,$arqDescricao) {
  $sqlInsereArquivo="INSERT INTO arquivo (COD_PESSOA,CAMINHO_LOCAL_ARQUIVO,TAMANHO_ARQUIVO,TIPO_ARQUIVO,DESC_ARQUIVO) VALUES('".$codPessoa."','".$arquivoNovo["name"]."','".$arquivoNovo["size"]."','".$arquivoNovo["type"]."','".$arqDescricao."')";
  mysql_query($sqlInsereArquivo);
  
  $last_id=mysql_insert_id();
  
  $sqlInsereArquivoAula="INSERT INTO arquivo_aula_agenda (COD_ARQUIVO,codAula, descArquivoAgenda) VALUES(".$last_id.",".$codAula.", '".$arqDescricao."')";
  mysql_query($sqlInsereArquivoAula);
  return $last_id;
}



function getVideo($codAula) {
  $sql = "SELECT * FROM video V INNER JOIN video_aula_agenda VAA ON (V.COD_VIDEO = VAA.COD_VIDEO) WHERE VAA.codAula =".quote_smart($codAula);
  $result = mysql_query($sql);
  return $result;
}


function mostraAgenda($codInstanciaGlobal,$canEditInsert=0,$codAulaEditar=0) {
  global $urlImagem;
  //busca as aulas
  if ($canEditInsert) { 
    $aulas=getAulas($codInstanciaGlobal);
    echo "<br><div align='right'><a href='index.php?acao=A_importaConteudos'>[ IMPORTAR CONTEÚDOS ] </a></div><br>"; 
  } 
  else {
    $aulas=getAulas($codInstanciaGlobal, 1);
  }
  //permite importar agenda de outras instâncias
  echo "<center><b><big>AGENDA DE AULAS</big></u><br> <center><br>";
  //cabecalho da tabela
  echo "<table style='background-color: #000000;' cellspacing='1' cellpadding='3'>";
  echo "<tr style='background-color: #c5d3f8' align=center>";
  echo "<td><b>AULA</td>";
  echo "<td><b>Data</td>";
  echo "<td><b>Resumo Aula </td>";
  echo "<td><b>Material</b><br><small>(clique no ícone para fazer o download do arquivo)</small></td>";

  if ($canEditInsert) { echo "<td><b>Editar | Excluir</td></tr>"; }


  $linhaTabela=1;

  while ($row = mysql_fetch_assoc($aulas)) {
    if ($linhaTabela % 2) { $classeLinha='agendaLinhaImpar'; } else { $classeLinha='agendaLinhaPar'; }
         
    //aula será exibida    
    if ($row['codAula']!=$codAulaEditar) { 
      if ($row['invisivel']==1) {$classeLinha='agendaLinhaInvisivel'; }
      echo "<tr class='".$classeLinha."'>"; /**classe de cor diferente a cada mudança de linha para facilitar a leitura*/
      echo ("<td align=center>".$linhaTabela."</td><td>".date('d/m/y',$row['data'])."</td><td>".$row['descricao']."</td><td>"); 
    }
    //aula será exibida    
    else {
      echo "<tr style='background-color: #EDEDDD'>"; //linha com cor fixa para destacar a alteração
      //form que edita a aula, cada coluna sendo editável
      echo "<form name='editaAula' method='post' enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."?acao=A_editaBanco&codAula=".$codAulaEditar."' onSubmit='return validaFormEdita()'>"; 
      echo "<td align=center>".$linhaTabela."</td>";
      echo "<td><input type='text' name='edata' value=".date('d/m/y',$row['data'])." size='10'></td>";   
      echo "<td><textarea name='edescricao' rows='20' cols='60' style='border:1px #000000 solid; display:block;'>".$row['descricao']."</textarea></td>";
      //coluna de arquivos
      echo "<td><span style='display:block; background-color:#c5d3f8; text-align:center; font-weight:bold;'>Novo material</span>";
      echo "Arquivo<br><input type=\"file\" name='arqNovo' size='20'>";
      echo "<br><span style='width:70;'>Arquivo existente: </span><input type=\"button\" name='localArq' value='Localizar Arquivo' size='20' onClick=\"window.open('".$url."/navi/agenda/index.php?acao=popup&form=alimentaFormEdita','popup','height=300, width=750, scrollbars=yes')\">";
      echo "<br><i>OU digite o link<br><input type='text' name='arqUrl' id='arqUrl' size='35'></i>";
      echo "<span style='width:70;VISIBILITY:hidden;display:none;'>COD_ARQUIVO: </span><input type='hidden' value='".$codArquivoLocal."' name='codArquivoLocal' id='codArquivoLocal' size='20'>";
      echo "<br><br>Descrição: <br><input type='text' name='arqDescricao' size='35'>";
      //cabecalho para mostrar a edição da descrição e exclusao de arquivos ja existentes
      echo "<br><br><span style='display:block; background-color:#c5d3f8; text-align:center; font-weight:bold;'>Arquivos atuais</span>";      
    }

    //carrega os arquivos da agenda
    $arquivosAula = getArquivosAula($row["codAula"]);
    while ($arquivo = mysql_fetch_assoc($arquivosAula))  {
      if ($arquivo["descArquivoAgenda"] == "" ) { $descArquivo = $arquivo["DESC_ARQUIVO"]; }
      else { $descArquivo = $arquivo["descArquivoAgenda"]; }
      //permite ver o arquivo
      $matArq  = "<a href='./pdf.php?COD_ARQUIVO=" . $arquivo["COD_ARQUIVO"] ."' target='_blank'><img src='".$urlImagem."/".exibeIconeMimeType($arquivo["TIPO_ARQUIVO"])."' border='no'>";

      /*
      aula será EDITADA
      */
      if ($arquivo['codAula']==$codAulaEditar && $canEditInsert) {
        $matArq .="</a>";
        /*
        permite editar o nome do arquivo
        */        
        $matArq .= "<input type='text' name='".PREFIXO_ARQUIVO.$arquivo["COD_ARQUIVO"]."' value='".$descArquivo."' size='30'>";

        $removeArquivoIcone = "<a href='index.php?acao=A_removeArquivo&codArquivo=".$arquivo['COD_ARQUIVO']."&linha=".$linhaTabela."&codAula=".$arquivo['codAula']."' title='Remover arquivo'><img src='../imagens/remove.gif' border='no'></a>";
        $matArq .= $removeArquivoIcone;
      }  
      else {
        //aula será exibida    
        $matArq .= $descArquivo."</a>";
      }

      echo "<div>".$matArq."</div>";
    }
    echo "</td>";  //fecha a coluna do material da aula
    /*
    se for professor ou administrador, permite edição
    */
    if ($canEditInsert) {

      echo "<td align=center>"; 
  		if ($row['codAula']!=$codAulaEditar) {
  	  	echo "<a href='index.php?acao=A_edita&codAula=".$row['codAula']."&linha=".$linhaTabela."'><img src='../imagens/edita.gif' border='no' title='Editar: edite a data, descrição e remova e/ou adicione arquivos.'></a>&nbsp;&nbsp;&nbsp;";
        echo "<a href='#' title='Remover: remove a aula.' onClick=\"if(confirm('Deseja mesmo excluir esta linha da Agenda? Você ainda poderá reusar os arquivos relacionados após a exclusão deste item da Agenda.')){ location.href='index.php?acao=A_remove&codAula=".$row['codAula']."&linha=".$linhaTabela."&codAula=".$row['codAula']."' }\"><img src='../imagens/remove.gif' border='no'></a>";
  		}
  	  else { 
  	    if ($row['invisivel']==1) { $radioInvisivel = 'CHECKED'; }
  	    echo "<input type='checkbox' name='invisivel' value='1' ".$radioInvisivel.">Invisível<br><br>";
        echo "<input type='submit' name='Submit' value='Alterar'>"; 
      }
  	}
    /*  
    /finaliza form de edição da aula
    */
    if ($codAulaEditar) { echo "</form>"; }
    
    echo "</td>"; 
    echo "</tr>";
 
    $linhaTabela++;  

  }
  echo "</table>";
  /*
  /se o usuario nao está no modo de edição, então já mostra o form para incluir uma nova aula na agenda
  */
  if (!$codAulaEditar && $canEditInsert) { 
    insereLinha($linhaTabela, $codArquivoLocal, $descArquivoLocal); 
  }
  echo "</center>"; 
}


function atualizaAula($codAula,$data,$descricao, $invisivel='') { 
    $sqlUpdate = "UPDATE aula_agenda SET data=".mktime(0, 0, 0, $data[1], $data[0], $data[2]).", descricao=".quote_smart($descricao).", invisivel=".quote_smart($invisivel)." WHERE codAula=".quote_smart($codAula);
    $result = mysql_query($sqlUpdate);
    return $result;
}

function insereAula($codInstanciaGlobal,$data,$descricao) {
    $sqlInsere = "INSERT INTO aula_agenda (codInstanciaGlobal,data,descricao) VALUES(".quote_smart($codInstanciaGlobal).",".mktime(0, 0, 0, $data[1], $data[0], $data[2]).",".quote_smart($descricao).")";
    mysql_query($sqlInsere);
    
    return mysql_insert_id();    
}

function insereLinha($linhaTabela) { // ultima linha da agenda (insere aula)
    $hoje=time();
    echo "<br /><br /><div>".ativaDesativaEditorHtml()."</div><big><b>Nova Aula</b></big><br />";
    
    
    
    echo "<table width='85%' style='background-color: #000000;' cellspacing='1' cellpadding='3'>";
    echo "<tr style='background-color: #c5d3f8' align=center>";
    echo "<td><b>AULA</td>";
    echo "<td><b>Data</td>";
    echo "<td><b>Resumo Aula</b></td>";
    echo "<td><b>Material</b><br><small>(clique no ícone para fazer o download do arquivo)</small></td>";
    echo "<td><b>Editar | Excluir</td></tr>"; 
    echo "<tr style='background-color: #EDEDDD'>";
    echo "<td align=center>".$linhaTabela."</td><form name='insereAula' method='POST' enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."?acao=A_insereBanco' onSubmit='return validaFormInsere();'>";
    echo "<td><input type='text' name='dataAula' value=".date('d/m/y',$hoje)." size='10'></td>";   
    
    echo "<td align='center'><textarea name='descricaoAula' rows='20' cols='60' style='border:1px #000000 solid;'></textarea></td>";
     
	 
	  
    //arquivos
    echo "<td><i>Arquivo para compor o material:</i>";
    echo "<br><span style='width:70;'>Novo Arquivo: </span><input type=\"file\" name='arqNovo' size='20'>";
    echo "<br><span style='width:70;'>Arquivo existente: </span><input type=\"button\" name='localArq' value='Localizar Arquivo' size='20' onClick=\"window.open('".$url."/navi/agenda/index.php?acao=popup&form=alimentaFormInsere','popup','height=300, width=750, scrollbars=yes')\">";
    echo "<br><span style='width:70;'>OU digite o link: </span><input type='text' name='arqUrl' id='arqUrl' size='20'>";
    echo "<span style='width:70;VISIBILITY:hidden;display:none;'>COD_ARQUIVO: </span><input type='hidden' value='".$codArquivoLocal."' name='codArquivoLocal' id='codArquivoLocal' size='20'>";
    echo "<hr><span style='width:70;'>Descrição: </span><input type='text' name='arqDescricao' size='20'></td>";
    //confirma form
    echo "<td><input type='submit' name='Submit' value='Adicionar'></form></td></tr>";
}

function atualizaNomesArquivo($request) {
  
  foreach($request as $variavel=>$valor) {
    if (substr($variavel,0,4)==PREFIXO_ARQUIVO) {
      $sql = " update arquivo_aula_agenda set descArquivoAgenda=".quote_smart($valor);
      $sql.= " Where COD_ARQUIVO=".quote_smart(substr($variavel,TAM_PREFIXO_ARQUIVO,(strlen($variavel)-TAM_PREFIXO_ARQUIVO)));
      /*echo "<br><br>VARIAVEL: ".$variavel;
      echo "<br>VALOR: ".$valor;
      echo "<br>TAM_PREFIXO_ARQUIVO: ".TAM_PREFIXO_ARQUIVO;
      echo "<br>tamanho: ".strlen($variavel);
      echo "<br>calc:".((strlen($variavel)-TAM_PREFIXO_ARQUIVO));
      echo $sql;*/
      mysql_query($sql); 
    }
  }
  
}

function confNum($Num) {
  if ($Num < 10) { 
  	return "0" . strval($Num);
  }
  else {		
  	return strval($Num);
  }
}

function insereArquivoAulaAgenda ($codArquivo, $codAula, $descArquivoAgenda) {
  $sql = "INSERT INTO arquivo_aula_agenda (COD_ARQUIVO, codAula, descArquivoAgenda) VALUES (".$codArquivo.",".$codAula.",'".$descArquivoAgenda."')";
  mysql_query($sql);
}

function buscaArquivos($ferramenta="") {
  $sql = "SELECT distinct arquivo.COD_ARQUIVO, arquivo.DESC_ARQUIVO FROM arquivo";
  if ($ferramenta != "") {
    $sql = $sql.", ".$ferramenta." WHERE arquivo.COD_ARQUIVO = ".$ferramenta.".COD_ARQUIVO"; 
    if ($_SESSION['userRole'] == PROFESSOR) {
      $sql = $sql." AND arquivo.COD_PESSOA = ".$_SESSION['COD_PESSOA'];
    }
  }
  elseif ($_SESSION['userRole'] == PROFESSOR) {
    $sql = $sql." WHERE arquivo.COD_PESSOA = ".$_SESSION['COD_PESSOA'];
  }
  return mysql_query($sql);	
}

function migraConteudosAgenda($codInstanciaGlobalOrigem, $codInstanciaGlobalDestino) 
{
  $aulasExportadas = mysql_query("select * from aula_agenda where codInstanciaGlobal=".$codInstanciaGlobalOrigem);
  while ($rowAulas = mysql_fetch_assoc($aulasExportadas)) 
  {
    $sqlInsert = mysql_query("insert into aula_agenda (codInstanciaGlobal, data, descricao) values (".$codInstanciaGlobalDestino.",'".$rowAulas['data']."', '".$rowAulas['descricao']."')");
    $codAula = mysql_insert_id(); //recupera o codAula da aula recém inserida
    $arquivosAula = mysql_query("select * from arquivo_aula_agenda where codAula=".$rowAulas['codAula']);
    while ($rowArquivos = mysql_fetch_assoc($arquivosAula)) 
    {
      $insereArquivos = mysql_query("insert into arquivo_aula_agenda (COD_ARQUIVO, codAula, ordem) values (".$rowArquivos['COD_ARQUIVO'].",".$codAula.",".$rowArquivos['ordem'].")");  
    }
  }
}

?>
