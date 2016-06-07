<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de d˙vidas e/ou sugestıes, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, port„o K. Porto Alegre - RS. CEP: 90035-003

Este programa È software livre; vocÍ pode redistribuÌ-lo e/ou modific·-lo sob os termos da LicenÁa P˙blica Geral GNU conforme publicada pela Free Software Foundation; tanto a vers„o 2 da LicenÁa, como (a seu critÈrio) qualquer vers„o posterior.

    Este programa È distribuÌdo na expectativa de que seja ˙til, porÈm, SEM NENHUMA GARANTIA;
    nem mesmo a garantia implÌcita de COMERCIABILIDADE OU ADEQUA«√O A UMA FINALIDADE ESPECÕFICA.
    Consulte a LicenÁa P˙blica Geral do GNU para mais detalhes.
    

    VocÍ deve ter recebido uma cÛpia da LicenÁa P˙blica Geral do GNU junto com este programa;
    se n„o, escreva para a Free Software Foundation, Inc., 
    no endereÁo 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} */

include("../config.php");
include($caminhoBiblioteca."/pesquisaavaliacaoinstanciapeloaluno.inc.php");
session_name(SESSION_NAME); session_start(); security(); session_write_close();


$avaliacao= getPesquisaAvaliacaoAluno($_SESSION["codInstanciaGlobal"]);

foreach($avaliacao->records as $linha){
   $instrucao=$linha->instrucoes;

   //deixa o array 1-bases e retira itens vazios do array
   if(!empty($linha->rotulos)){ $rotulos=string2Array1Based($linha->rotulos); }
   if(!empty($linha->opcoes)) { $opcoes = string2Array1Based($linha->opcoes); }
   $marcarTdCampos=$linha->marcarTdCampos; 
   $espacoAberto= $linha->espacoAberto;
}



?>
<html>

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<style>
<!--
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman";}
@page Section1
	{size:595.3pt 841.9pt;
	margin:70.85pt 3.0cm 70.85pt 3.0cm;}
div.Section1
	{page:Section1;}
.aviso { font-weight:bold; font-size:11px; color:#FF0000;}
INPUT, SELECT, TEXTAREA { border:1px #000000 solid;}
-->
</style>

<?if($marcarTdCampos){
  echo "<script> validaCamposPreenchidos();</script>";

}
switch ($_REQUEST['acao']) {

  case "":
    $naoAluno=0;
   
    if (empty($_SESSION['COD_AL']) || $_SESSION['userRole']!=ALUNO){
      $avisoNaoAluno = "<center><span class='aviso'>Avalia&ccedil;&atilde;o dispon›vel apenas para alunos!</span></center>";
      $naoAluno=1;
    }
    else if (alunoJaRespondeu($_SESSION['codInstanciaGlobal'],$_SESSION['COD_AL'])) {
      echo "</head><body><center><span class='aviso'>Avalia&ccedil;&atilde;o jÓ preenchida.</span></center></body></html>";
      die;
    }
    //alunoJaRespondeu($_SESSION['codInstanciaGlobal'],$_SESSION['COD_AL']);
?>

<title>Avalia›“o</title>
</head>

<body lang=PT-BR>

<div class=Section1>
<? 

 echo $avisoNaoAluno; ?>
<p class=MsoNormal align=center style='text-align:center'>
<b>AVALIA++O</b><br></p>

<?echo nl2br($instrucao);?>
<table cellpadding='5' cellspacing='0' border='1' border="000000" >
<form name='avaliacaoAluno' action='<?echo $_SERVER['PHP_SELF'];?>?acao=salva' method='POST' onSubmit="if (!validaCamposPreenchidos() || !confirm('Confirma? A avalia›“o sµ pode ser enviada uma vez!')  ) { return false; }">

Voc› poderÓ preencher apenas uma vez esta avalia›“o.
<?

$i=0; 
foreach($rotulos as $rot) {
  if (!is_numeric(substr($rot,0,1)))   { //+ um subtitulo 
    echo "<tr><td colspan=\"2\"><b><big>".$rot."</b></big></td></tr>";  
  }
  else { //+ uma questao, vamos imprimi-la bem como as op›ßes de resposta
    $c = $campos[$i]; $i++;
    echo "<tr><td>".$rot;
    opcoesAvaliacao($c,$opcoes)."</tr>";
  }
}
echo "</table>";
if($espacoAberto){
 echo "<p class='MsoNormal'>&nbsp;</p>";
 echo "<p class='MsoNormal'><b>Espa›o aberto</b></p>".
      "<p class='MsoNormal'>&nbsp;</p>".
      "<p class='MsoNormal'>Esse espa›o pode ser utilizado para complementar as suas ".
      "respostas e fazer sugestßes, cr›ticas e comentÓrios sobre a disciplina.</p>".
      "<textarea name='espacoAberto' id='espacoAberto' rows='10' cols='80' style='font-size:11px;'>".
      "</textarea>";
}
?>

<table width ="100%">
<? if (!$naoAluno) { ?>
<tr><td><center><input type='submit' name='submit' value='Enviar Avalia›“o'></center></td></tr>
<? } ?>
</table>
</form>

<p class=MsoNormal>&nbsp;</p>

</div>

</body>

</html>
<?  break;

  case "salva":
    registraAvaliacaoInstanciaAluno($_SESSION['codInstanciaGlobal'],$_SESSION['COD_AL']);
    echo "<center><span class='aviso'>Avalia&ccedil;&atilde;o preenchida corretamente, obrigado!</span></center>";
    break;

}
?>
