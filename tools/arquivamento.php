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
include_once($caminhoBiblioteca."/arquivamento.inc.php");
session_name(SESSION_NAME); session_start(); security();

$nivel = getNivelAtual();
?>

<html>
<head>
  <title>Arquivamento</title>
  <link rel="stylesheet" href="./../cursos.css" type="text/css">
  <link rel="stylesheet" href="<? echo $urlCss;?>/celula.css" type="text/css">
</head>

<body class='bodybg'>
<br>
<div align=center> <font size=4> <b>Arquivamento</b> </font> </div>

<?php
switch($_REQUEST['acao']){
  case '':
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
    echo"<form name='form' method=post action='".$_SERVER['PHP_SELF']."?acao=envia' onSubmit='if(!varreCampos(\"arquivamento\")){ return false;}else{return true;}'>";
    //echo"<input type=hidden value=arquivar name=flag id=flag>";
    echo"<center><fieldset style='width: 400px;'><legend><input type=checkbox name='c1' onClick='checkTodos()'> <span id='checar'>Marcar todos</span></legend>";
    echo "<table align='center'>";


    $ItensMenusAtivos=getItensMenuAtivos($_SESSION['codInstanciaGlobal']);

    while ($linha = mysql_fetch_array($ItensMenusAtivos)){

      if(!empty($linha['urlToolsEditar']) || !empty($linha['urlToolsCriar'])) {
        if($linha['nomeMenu']=='Apresentação'){
  	      $nomeMenu="Lembretes";
        } else {
          $nomeMenu=$linha['nomeMenu'];
        }

        echo"<tr>";
        echo"<td align=\"center\"><input type=checkbox name='check[]' value='".$nomeMenu."'><br></td>";

        $retorno = array();
        $conta = new ArquivaRecurso();
        $retorno = $conta->contaReg($_SESSION['codInstanciaGlobal'],$nomeMenu);
    
        //echo "menu:$nomeMenu<br>";    
        //print_r($retorno);echo '<br><Br>';

        if ($retorno) { 
          if (is_array($retorno)) {
            //contagem eh um array de consultas
            //executa todas as consultas e vai somando os registros
            $ret['linhas'] = 0;

            foreach($retorno as $query) {
              $result = mysql_query($query);
              if ($result) {
                $resultFetched = mysql_fetch_array($result);
                $ret['linhas']+= $resultFetched['linhas'];
              }            
            }
            
            $retorno = $ret;
          }
          else {
            //contagem eh uma query simples
            //executa a query  
            $retorno = mysql_query($retorno);
            $retorno = mysql_fetch_array($retorno);
          }
        }
        else {
          $retorno['linhas']=0;
        }

        echo"<td class=\"letra\">".$nomeMenu."(".$retorno['linhas']." registros)"."</td>";
        echo"</tr>";
      }
    }
    echo"</table><table><tr>";
    echo"<td><input type=submit value='Arquivar Selecionados'></td>";
    //echo"<td><input type=button value='Restaurar Selecionados' onclick='document.form.flag.value=\"restaurar\"; document.form.submit();'></td></tr></table></form>";
  
  break;

  case 'envia':
    $arquivamento = new ArquivaRecurso();
    $retorno = $arquivamento->arquiva($_SESSION['codInstanciaGlobal'],$_REQUEST['check']);
    $executa = $arquivamento->executaArraySql($retorno);
    if($executa == 0){
      echo"<br><br><br><center>Recurso(s) arquivado(s) com sucesso!";
      echo"<br><a href='arquivamento.php'>Voltar</a>";
    }
    else{
      echo"<br><br><br><center><font color=red>Houve um erro ao executar esta operação";
      echo"<br><a href='arquivamento.php'>Voltar</a>";
    } 
  break;


}
?>