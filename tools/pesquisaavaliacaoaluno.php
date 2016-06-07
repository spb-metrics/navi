<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de d�vidas e/ou sugest�es, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, port�o K. Porto Alegre - RS. CEP: 90035-003

Este programa � software livre; voc� pode redistribu�-lo e/ou modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme publicada pela Free Software Foundation; tanto a vers�o 2 da Licen�a, como (a seu crit�rio) qualquer vers�o posterior.

    Este programa � distribu�do na expectativa de que seja �til, por�m, SEM NENHUMA GARANTIA;
    nem mesmo a garantia impl�cita de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.
    Consulte a Licen�a P�blica Geral do GNU para mais detalhes.
    

    Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU junto com este programa;
    se n�o, escreva para a Free Software Foundation, Inc., 
    no endere�o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} */
?>


<?

include_once("../config.php");
include($caminhoBiblioteca."/pesquisaavaliacaoinstanciapeloaluno.inc.php");
include($caminhoBiblioteca."/noticia.inc.php");
@session_name(SESSION_NAME); @session_start(); security();
?>
<script language="JavaScript" type="text/javascript">
function formatHidden(dateF,dateI){
    document.forms['frm'].dataFim.value =dateF.charAt(6)+dateF.charAt(7)+dateF.charAt(8)+dateF.charAt(9)+"-"+dateF.charAt(3)+dateF.charAt(4)+"-"+dateF.charAt(0)+dateF.charAt(1);
    document.forms['frm'].dataInicio.value = dateI.charAt(6)+dateI.charAt(7)+dateI.charAt(8)+dateI.charAt(9)+"-"+dateI.charAt(3)+dateI.charAt(4)+"-"+dateI.charAt(0)+dateI.charAt(1);
}

</script>
<?
function printHeader($params="") {
  global $url;
  echo "<html>".
		   "<head>".
		   "<link rel=\"stylesheet\" href=\"./sca.css\" type=\"text/css\">".
		   "<link rel=\"stylesheet\" href=\"".$url."/css/configuracao.css\" type=\"text/css\">";
	echo "<script language=\"JavaScript\" src=\"".$url."/js/utils.js\"></script>".
       "<script language=\"JavaScript\" src=\"".$url."/js/dateFormat.js\"></script>";	   
  echo "<link rel=\"stylesheet\" media=\"screen\" href=\"".$url."/css/dynCalendar.css\" >";
  echo "<script language=\"javascript\" type=\"text/javascript\" src=\"".$url."/js/browserSniffer.js\"></script>";
  if (!empty($params["titulo"]))
  echo "<title>{$params["titulo"]}</title>";
  echo "</head>";
  echo "<body class=\"bodybg\"{$params["body"]}>";
  echo "<table  width=\"700\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
  echo "<tr><td><br></td></tr>";
  echo "<tr align=\"center\"><td class=\"nomeInstancia\">{$params["tituloPagina"]}</td></tr>";
  echo "<tr align=\"center\"><td valign='middle'>";
}


$avaliacao= getPesquisaAvaliacaoAluno($_SESSION["codInstanciaGlobal"]);

foreach($avaliacao->records as $linha){
       $instrucao=$linha->instrucoes;
       if(!empty($linha->rotulos)) $rotulos=explode("\n",$linha->rotulos);
       if(!empty($linha->opcoes))  $opcoes=explode("\n",$linha->opcoes);
       $marcarTdCampos=$linha->marcarTdCampos; 
       $espacoAberto= $linha->espacoAberto;
       $dataInicioValue= $linha->dataI;
       $dataExpiracaoValue=$linha->dataF;
}



$params["tituloPagina"]="Pesquisa de Avalia��o do Curso pelo Aluno";
printHeader($params);
switch($_REQUEST["acao"]) {
 case "":
echo "<table width=\"100%\">";
echo "<form name=\"frm\" method=\"post\" action=\"".$_SERVER["PHP_SELF"]."?acao=submit\">";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>Data de Inicio: &nbsp;";
echo "<input type=\"text\" name=\"dataInicioValue\" maxlength=\"10\"  size=\"10\" onFocus=\"javascript:vDateType='3'\" onKeyUp=\"DateFormat(this,this.value,event,false,'3')\"  value=\"".$dataInicioValue."\">";
echo "<input type=\"hidden\" name=\"dataInicio\" value=\"".$dataInicio."\">";
echo "<td>Data de Expira��o: &nbsp;";
echo "<input type=\"text\" name=\"dataFimValue\" maxlength=\"10\"  size=\"10\" onFocus=\"javascript:vDateType='3'\" onKeyUp=\"DateFormat(this,this.value,event,false,'3')\"   value=\"".$dataExpiracaoValue."\">";
echo "<input type=\"hidden\" name=\"dataFim\" value=\"".$dataExpiracao."\"></td></tr>";
echo "<tr><td>Instru��es:</td></tr>";
echo "<tr><td colspan=\"2\"><textarea name=\"instrucoes\" rows=\"20\" cols=\"110\">". $instrucao." </textarea></td></td>";
echo "<tr><td>".
     "<table width=\"45%\">";
echo "<tr><td>Quest�es:</td></tr>".
     "<tr><td><textarea name=\"rotulos\" rows=\"20\" cols=\"50\">";
for($i=0;$i<count($rotulos);$i++)     
     echo $rotulos[$i]."\n";
echo"</textarea></td></tr>";
echo "<tr><td><input type=\"checkbox\" name=\"espacoAberto\" value=\"1\"";
if($espacoAberto) echo "checked";
echo ">Incluir o espa�o Aberto para o aluno</td></tr>";
echo "</table>".
     "</td>";
echo "<td valign=\"top\">".
     "<table width=\"45%\">".
     "<tr><td>Op��es:</td></tr>".
     "<tr><td><textarea name=\"opcoes\" rows=\"10\" cols=\"50\">";
for($i=0;$i<count($opcoes);$i++)     
     echo $opcoes[$i]."\n";
echo "</textarea></td></tr>";
echo "<tr><td><input type=\"checkbox\" name=\"marcarTdCampos\" value=\"1\"";
if($marcarTdCampos) echo "checked";
echo ">Obrigar a pessoa preencher todos os campos</td></tr>";
echo "</table>".     
     "</td></tr>";
echo "<tr><td align=\"right\"><input type=\"submit\" name=\"submit\" value=\"Enviar\" class=\"okButton\" onclick=\"formatHidden(document.forms['frm'].dataFimValue.value,document.forms['frm'].dataInicioValue.value);\"></td>".
     "<td><input type=\"reset\" value=\"Cancelar\" onclick=\"location.href='../recursos_fixos.php'\" class=\"cancelButton\"></td></tr>";
echo "</form>";
echo "</table>";
break;

case "submit":
 //print_r($_REQUEST); die();
 inserePesquisaavaliacaopeloaluno($_SESSION["codInstanciaGlobal"],$_REQUEST);
 echo "<script>location.href=\"recursos_fixos.php\"</script>";
break;
}

?>
