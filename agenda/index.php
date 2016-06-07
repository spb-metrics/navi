<?
error_reporting(E_ALL ^ E_NOTICE);
include ("../config.php");
include ($caminhoBiblioteca."/agenda.inc.php");
include ($caminhoBiblioteca."/pessoa.inc.php");
include ($caminhoBiblioteca."/funcoesftp.inc.php");
include ($caminhoBiblioteca."/imagens.inc.php");

include ($caminhoBiblioteca."/utils.inc.php");


session_name(SESSION_NAME); session_start(); security();

session_write_close();

if (!empty($_SESSION['agendaCurso']) && $_SESSION['instanciaDestinoAgenda']==$_SESSION['codInstanciaGlobal']) { 
 $codInstanciaGlobal=$_SESSION['agendaCurso'];  
}
else {
 $codInstanciaGlobal=$_SESSION['codInstanciaGlobal'];  
}

if (empty($codInstanciaGlobal) || empty($_SESSION['COD_PESSOA']))	{ msg('Agenda dispon&iacute;vel somente para usu?rios cadastrados'); die(); }

function printHeader() {
  global $urlCss,$url;
  
  echo "<style type='text/css'> 
        @import url(".$urlCss."/padraogeral.css);       
        .tituloAgenda     {font-size:14px; font-weight:bold; }
        .agendaLinhaImpar { background-color: #D5e3f8; }
        .agendaLinhaPar   { background-color: #E5EFFF;} 
        .agendaLinhaInvisivel   { background-color: #D3D3D3;}      
        </style>";
  echo "<script> window.status='Agenda'; </script>";
  echo "<script language='JavaScript' src='".$url."/js/agenda.js'></script>";
  
  echo"<script language='JavaScript' src='".$url."/js/editor.js'></script>";
  echo"<script language='javascript' type='text/javascript' src='".$url."/js/tiny_mce/tiny_mce.js'></script>"; 
 
  
  echo "<body class='bodybg'>";
}

switch($_REQUEST["acao"]) {


/**
 *exibe a agenda
 */ 
  case "":
    printHeader();
    if (!empty($_REQUEST['msgErro'])) { echo "<div style='color:red'>".$_REQUEST['msgErro']."</div>"; }
    
    mostraAgenda($codInstanciaGlobal,Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage']),$codAula, $_REQUEST['codArquivoLocal'], $_REQUEST['descArquivoLocal']);      
    break;

/**
 *remove aulas
 */ 
  case "A_remove":
    $arquivosAula=getArquivosAula($_REQUEST['codAula']); // procura todos arquivos da aula para apaga-los
    while ($row = mysql_fetch_array($arquivosAula))  {
      /*
      $arquivo=getArquivo($row['COD_ARQUIVO']);
      $caminho=$row['CAMINHO_LOCAL_ARQUIVO'];
      $arq = $caminhoUpload.$caminho;
      unlink($arq);
      delete_via_ftp($caminho);
      */
      $remArq=removeArquivoAula($row['COD_ARQUIVO'],$_REQUEST['codAula']);
    }
	  //remove todas os arquivos da aula
    $sql = "DELETE FROM aula_agenda WHERE codAula=".quote_smart($_REQUEST['codAula']);
    $result = mysql_query($sql);
    echo '<script>window.location.href="./index.php";</script>';
    break;
       
  /**
   *  remove arquivo da aula
   */   
  case "A_removeArquivo":
    //pega variaveis get
    $codArquivo = (int)$_REQUEST['codArquivo'];
    $codAula = (int)$_REQUEST['codAula'];
    
    // busca o arquivo para exclui-lo  
    //$arquivo=getArquivo($codArquivo);  
    //exclui o arquivo fisicamente
    //$row = mysql_fetch_assoc($arquivo); 
 	  //$caminho=$row["CAMINHO_LOCAL_ARQUIVO"];
   	//$arq = $caminhoUpload.$caminho;
    
    //unlink($arq);
    //delete_via_ftp($caminho);
    //exclui do banco
    removeArquivoAula($codArquivo,$codAula);
    echo '<script>window.location.href="index.php?acao=A_edita&codAula='.$_REQUEST["codAula"].'";</script>';
    break;    


  /**
   *  edita aulas (form de edi??o)
   */   
  case "A_edita":
    printHeader();
    if (!isset($_REQUEST['codAula'])) { die(); }
    $codAula = (int)$_REQUEST['codAula'];
    echo "<big><b>Editando aula</b</big><br><br>";
    echo "<a href='".$url."/agenda/'>Clique para voltar para visualiza&ccedil;&atilde;o da agenda e inser&ccedil;&atilde;o de novas aulas</a><br><br><br>";
    echo "<br /><div align='center'>".ativaDesativaEditorHtml()."<br /></div>";
    mostraAgenda($codInstanciaGlobal,Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage']),$codAula);
   
    break;
         

  //edita aulas (d? update no banco)
  case "A_editaBanco": 
    //atualizacao da data e descricao
    $data=explode("/",$_REQUEST['edata']);
    $codAula = (int)$_REQUEST['codAula'];
    //if (empty($_REQUEST['invisivel'])) { $invisivel =1; } else { $invisivel= $_REQUEST['invisivel']; }
    $invisivel= $_REQUEST['invisivel'];
    atualizaAula($_REQUEST['codAula'],$data,$_REQUEST['edescricao'], $invisivel);

    atualizaNomesArquivo($_REQUEST);
    //novos arquivos
    $caminhoFTP="agenda/".confNum($_SESSION['COD_PESSOA']);
    $caminhoBD="/agenda/".confNum($_SESSION['COD_PESSOA'])."/";
    $caminhoFisico = $caminhoUpload.$caminhoBD."/";    
    if (!file_exists($caminhoFisico)){ mkdir($caminhoFisico); }
    
            
    if (!empty($_REQUEST['arqUrl'])) {
      $arquivoNovo['name'] = $_REQUEST['arqUrl']; 
      $arquivoNovo['type'] = 'text/html';
      insereArquivoAula($_SESSION['COD_PESSOA'],$arquivoNovo,$codAula,$_REQUEST['arqDescricao']);    
    }
    else if (move_uploaded_file($_FILES['arqNovo']["tmp_name"], $caminhoFisico.fileName($_FILES['arqNovo']["name"])) ) {
      duplica($caminhoFisico.fileName($_FILES['arqNovo']["name"]),fileName($_FILES['arqNovo']["name"]),$caminhoFTP); 
  	  $arquivoNovo = $_FILES['arqNovo'];
  	  $arquivoNovo['name'] = $caminhoBD."/".fileName($arquivoNovo['name']); 
      insereArquivoAula($_SESSION['COD_PESSOA'],$arquivoNovo,$codAula,$_REQUEST['arqDescricao']);      
    }
    else {       
      $msgErro="Houve um erro ao transferir o arquivo."; 
    }
    if ($_REQUEST['codArquivoLocal'] > 0) 
    {
      insereArquivoAulaAgenda($_REQUEST['codArquivoLocal'], $codAula, $_REQUEST['arqDescricao']);
    }  
    echo '<script>window.location.href="index.php?acao=A_edita&codAula='.$_REQUEST["codAula"].'";</script>';
    break;


  //insere aulas
  case "A_insereBanco":

    $data=explode("/",$_REQUEST['dataAula']); 
    $codAula = insereAula($_SESSION['codInstanciaGlobal'],$data,$_REQUEST['descricaoAula']);
    $caminhoFTP="agenda/".confNum($_SESSION['COD_PESSOA']);
    $caminhoBD="/agenda/".confNum($_SESSION['COD_PESSOA'])."/";

    $caminhoFisico = $caminhoUpload.$caminhoBD;
    
        	    
    if (!file_exists($caminhoFisico)){ mkdir($caminhoFisico); }      
        
    if (!empty($_REQUEST['arqUrl'])) {
      $arquivoNovo['name'] = $_REQUEST['arqUrl']; 
      $arquivoNovo['type'] = 'text/html';
      $last_id_arq = insereArquivoAula($_SESSION['COD_PESSOA'],$arquivoNovo,$codAula,$_REQUEST['arqDescricao']);       
    }
    else if (move_uploaded_file($_FILES['arqNovo']["tmp_name"], $caminhoFisico.fileName($_FILES['arqNovo']["name"])) ){ 
      duplica($caminhoFisico.fileName($_FILES['arqNovo']["name"]),fileName($_FILES['arqNovo']["name"]),$caminhoFTP); 
  	  $arquivoNovo = $_FILES['arqNovo'];
  	  $arquivoNovo['name'] = $caminhoBD.fileName($arquivoNovo['name']);
      $last_id_arq = insereArquivoAula($_SESSION['COD_PESSOA'],$arquivoNovo,$codAula,$_REQUEST['arqDescricao']);           
    }
    else {       
      $msgErro="Houve um erro ao transferir o arquivo."; 
    }
    if ($_REQUEST['codArquivoLocal'] > 0) 
    {
      insereArquivoAulaAgenda($_REQUEST['codArquivoLocal'], $codAula, $_REQUEST['arqDescricao']);
    }
    echo '<script>window.location.href="./index.php?acao=A_edita&codAula='.$codAula.'";</script>';  
    break;
    
  //importa Agenda de outra inst?ncia
  case "A_importaConteudos":
    if(empty($_REQUEST['turma_origem']) && empty($_REQUEST['turma_destino']))
    {
      echo "<link rel='stylesheet' href='./../cursos.css' type='text/css'>";
      echo "<center><br>";
      echo "Ao fazer-se a importação da Agenda de Aulas de uma turma para outra,<br>
            todos os <b>textos</b> e <b>arquivos</b> da Agenda de origem serão COPIADOS para dentro da Agenda da turma de destino.<br>
            Lembre-se de acertar as datas para o corrente semestre editando as aulas da agenda.<br><br>";
      $sqlO = mysql_query("SELECT I.codInstanciaGlobal, T.NOME_TURMA FROM instanciaglobal I
                            JOIN turma T ON (I.codInstanciaNivel = T.cod_turma)
                            JOIN professor_turma PT ON (T.cod_turma = PT.cod_turma)
                            JOIN professor P ON (PT.cod_prof = P.cod_prof) 
                            JOIN pessoa PE ON (P.cod_pessoa = PE.cod_pessoa)
                          WHERE I.codNivel=6 AND P.cod_pessoa=".$_SESSION['COD_PESSOA']); 
      echo "<form name='importaConteudo' method='post' enctype='multipart/form-data' action='".$url."/agenda/index.php?acao=A_importaConteudos' onSubmit='return validaImportaConteudos()'>";
      echo "<select name='turma_origem' id='turma_origem'>";
      echo "<option value=''>Selecione a turma de  ORIGEM</option>";
      while ($linhaO = mysql_fetch_array($sqlO)) 
      { 
        echo "<option value='".$linhaO["codInstanciaGlobal"]."'>".$linhaO["NOME_TURMA"]."</option>"; 
      }
      echo "</select>";
      echo "<br><br>";
      $sqlD = mysql_query("SELECT I.codInstanciaGlobal, T.NOME_TURMA FROM instanciaglobal I
                            JOIN turma T ON (I.codInstanciaNivel = T.cod_turma)
                            JOIN professor_turma PT ON (T.cod_turma = PT.cod_turma)
                            JOIN professor P ON (PT.cod_prof = P.cod_prof) 
                            JOIN pessoa PE ON (P.cod_pessoa = PE.cod_pessoa)
                          WHERE I.codNivel=6 AND P.cod_pessoa=".$_SESSION['COD_PESSOA']); 
      echo "<select name='turma_destino' id='turma_destino'>";
      echo "<option value=''>Selecione a turma de DESTINO</option>";
      while ($linhaD = mysql_fetch_array($sqlD)) 
      { 
        echo "<option value='".$linhaD["codInstanciaGlobal"]."'>".$linhaD["NOME_TURMA"]."</option>"; 
      }
      echo "</select>";
      echo "<br><br>";
      echo "<input type='submit' name='Submit' value='Importar Conteúdos'>";
      echo "</center>";
    }
    else {
      migraConteudosAgenda($_REQUEST['turma_origem'], $_REQUEST['turma_destino']);
      echo '<script>window.location.href="./index.php";</script>';
      //echo "<script type='text/javascript'>";
      //echo "alert('Conteúdos migrados com sucesso!')";
      //echo "</script>";
    }
  break;
  
  case "popup":
    $tipoAcao = $_REQUEST['form'];
    echo "<link rel='stylesheet' href='./../cursos.css' type='text/css'>";
    echo "<title>Localizar Arquivo</title>";
    echo "<a href='".$url."/agenda/index.php?acao=popup&form=".$tipoAcao."'>Todos Arquivos</a> | "; 
    echo "<a href='".$url."/agenda/index.php?acao=popup&form=".$tipoAcao."&ferramenta=arquivo_instancia'>Em Conteúdos</a> | ";
    echo "<a href='".$url."/agenda/index.php?acao=popup&form=".$tipoAcao."&ferramenta=biblioteca'>Em Acervo</a> | ";
    echo "<a href='".$url."/agenda/index.php?acao=popup&form=".$tipoAcao."&ferramenta=arquivo_aula_agenda'>Em Agenda</a>";
    echo "<br><br>";
    echo "<table><tr><td>Código |</td><td>Nome Arquivo</td></tr>";

    $arquivos = buscaArquivos($_REQUEST['ferramenta']);

    if ($arquivos) 
    {
      while ($linhaN = mysql_fetch_array($arquivos)) 
      {
        echo "<tr>";
        echo "<td>".$linhaN["COD_ARQUIVO"]."</td>";
        echo "<td><a href='#' onClick=\"".$tipoAcao."('".$linhaN["COD_ARQUIVO"]."','".$linhaN["DESC_ARQUIVO"]."');self.close();\">".$linhaN["DESC_ARQUIVO"]."</a></td>";
        echo "</tr>";
      }
      echo "</table>";
  }  
  break; 
}  



function fileName($fileName) {

  return str_replace(" ","_",$fileName);
}
?>
<script type="text/javascript">
  function validaImportaConteudos() 
  {
      if (document.importaConteudo.turma_origem.value == document.importaConteudo.turma_destino.value)
      {
        alert("As turmas origem e destino não podem ser a mesma!");
        return false;
      }
      else if (document.importaConteudo.turma_origem.value == '')
      {
        alert("Selecione uma turma de origem!");
        return false;
      }
      else if (document.importaConteudo.turma_destino.value == '')
      {
        alert("Selecione uma turma de destino!");
        return false;
      }
      else
      {
        return true;
      }
  }
  function alimentaFormInsere(codArquivo, descArquivo)
  {
    window.opener.document.insereAula.codArquivoLocal.value = codArquivo;
    window.opener.document.insereAula.arqDescricao.value = descArquivo;
  }

  function alimentaFormEdita(codArquivo, descArquivo)
  {
    window.opener.document.editaAula.codArquivoLocal.value = codArquivo;
    window.opener.document.editaAula.arqDescricao.value = descArquivo;
  }
</script>