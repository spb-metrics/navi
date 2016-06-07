<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de dúvidas e/ou sugestões, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, portão K. Porto Alegre - RS. CEP: 90035-003

Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo sob os termos da Licença Pública Geral GNU conforme publicada pela Free Software Foundation; tanto a versão 2 da Licença, como (a seu critério) qualquer versão posterior.

    Este programa é distribuído na expectativa de que seja útil, porém, SEM NENHUMA GARANTIA;
    nem mesmo a garantia implícita de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.
    Consulte a Licença Pública Geral do GNU para mais detalhes.
    

    Você deve ter recebido uma cópia da Licença Pública Geral do GNU junto com este programa;
    se não, escreva para a Free Software Foundation, Inc., 
    no endereço 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} */

//include_once("../funcoes_bd.php");

include_once("../config.php");
include_once($caminhoBiblioteca."/menu.inc.php");
//include_once($caminhoBiblioteca."/pessoa.inc.php");
include_once($caminhoBiblioteca."/imagens.inc.php");
include_once($caminhoBiblioteca."/backup.inc.php");
session_name(SESSION_NAME); session_start(); 
if ($_REQUEST['acao']=='envia') {
  security(0,1);
}
else { security(); }

//funcao para download do arquivo de backup
function headerDownload($nome,$mimeType="",$tamanho="") {
  
  if (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE"))
    header("Content-Disposition: filename=".$nome);
  else
    header("Content-Disposition: attachment; filename=".$nome);
  header("Content-type: ".$mimeType);
	  
  if (!empty($tamanho))
    header("Content-Length: ".$tamanho);
}


switch($_REQUEST['acao']){
  case '':

?>
<html>
<head>
  <title>Backup</title>
  <link rel="stylesheet" href="./../cursos.css" type="text/css">
  <link rel="stylesheet" href="<? echo $urlCss;?>/celula.css" type="text/css">
</head>

<body class='bodybg'>

<?php

    echo"<div align=center> <font size=4> <b>Backup</b> </font> </div>";
    echo"<script language='JavaScript'>
          function checkTodos()  {
            if (document.form['c1'].checked == true) {
              for(var i=0; i < document.form['check[]'].length; i++) {
                document.form['check[]'][i].checked = true;
              }
            }
            else {
              for(var i=0; i < document.form['check[]'].length; i++) {
                document.form['check[]'][i].checked = false;
              }
            }
          }
          </script>";

    echo "<form name='form' method=post action='backup.php?acao=envia' onSubmit='if(!varreCampos(\"backup\")){ return false;}else{return true;}'>";
    //echo "<input type=hidden name=checkTodos id=checkTodos value=false>";
    echo"<center><fieldset style='width: 400px;'><legend><input type=checkbox name='c1' id='c1' onClick='checkTodos()'> <span id='checar'>Marcar todos</span></legend>";
    echo "<table align='center'>";


    $ItensMenusAtivos=getItensMenuAtivos($_SESSION['codInstanciaGlobal']);

    while ($linha = mysql_fetch_array($ItensMenusAtivos)){

      if(!empty($linha['urlToolsEditar'])||!empty($linha['urlToolsCriar'])){
        if($linha['nomeMenu']=='Apresentação'){
  	  $nomeMenu="Lembretes";
        }else{
          $nomeMenu=$linha['nomeMenu'];
        }

        echo"<tr>";
        echo"<td align=\"center\"><input type=checkbox name='check[]' value='".$nomeMenu."'><br></td>";
        echo"<td class=\"letra\">".$nomeMenu."</td>";
        echo"</tr>";
      }
    }
    echo"</table><center><input type=submit value='Criar Backup'></form>";
  break;

  case 'envia':

    $backup = new Backup();

    $sqls = $backup->doBackup($_SESSION['codInstanciaGlobal'],$_REQUEST['check']);
    
    $arquivo = $backup->executaBackup($sqls[0],$sqls[1]);
    
    
    
    if ($arquivo == "error") {
      echo"<br><br><br><center><font color=red>Houve um erro ao executar esta operação";
      echo"<br><a href='backup.php'>Voltar</a>";
    }
    else {
      $today = date("j_n_Y"); 
      $nome = "backup_navi_".$today.".txt";
      $mimeType = "application/force-download";
      headerDownload($nome,$mimeType="",$tamanho="");
      echo $arquivo;
    }
  break;
  
  case 'restaura':
    
  ?>
  <html>
  <head>
    <title>Restauração de Dados</title>
    <link rel="stylesheet" href="./../cursos.css" type="text/css">
    <link rel="stylesheet" href="<? echo $urlCss;?>/celula.css" type="text/css">
  </head>
  <body class='bodybg'><?
    
    echo "<table>";
    echo "<form name=form_restaura id=form_restaura method=POST action=backup.php?acao=restauraMake enctype=\"multipart/form-data\">";
    echo "<tr><b>Endereço do Arquivo: </b><br> <input type=\"file\" name=\"arquivo\" id=arquivo size=\"40\"></td></tr>";
    echo "<tr><td align=center><input type=checkbox name=substituir id=substituir checked=true>Substituir Dados Atuais</td></tr>";
    echo "<tr><td align=center><input type=submit value='Restaurar Dados'></td></tr>";
    echo "</form>";
    echo "</table>";
  break;

  case 'restauraMake':
    //note($_FILES);
  
    $backup = new Backup();
    $backup->restore($_FILES['arquivo']['tmp_name']);
  break;
}
?>
