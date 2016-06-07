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
?>

 <?
include("../config.php");
include($caminhoBiblioteca."/curso.inc.php");
include($caminhoBiblioteca."/indicadoresaluno.inc.php");
include($caminhoBiblioteca."/calendario.inc.php");

$ini = parse_ini_file("../interacao/forum/forum.ini",1);

session_name(SESSION_NAME); session_start();
session_write_close();//apenas para leitura


echo "<html>";
echo "<link rel=\"stylesheet\" href=\"{$url}/cursos.css\" type=\"text/css\">";
echo "<link rel=\"stylesheet\" href=\"{$url}/indicadoresaluno/indicadoresaluno.css\" type=\"text/css\">";
echo "<script language=\"JavaScript\" src=\"".$url."/js/dateFormat.js\"></script>";
echo "<center>";?>
   <script language="JavaScript" type="text/javascript">
	function formatHidden(dateI,dateF){
      document.forms['calendario'].dataInicio.value =dateI.charAt(6)+dateI.charAt(7)+dateI.charAt(8)+dateI.charAt(9)+"-"+dateI.charAt(3)+dateI.charAt(4)+"-"+dateI.charAt(0)+dateI.charAt(1);
       document.forms['calendario'].dataFim.value =dateF.charAt(6)+dateF.charAt(7)+dateF.charAt(8)+dateF.charAt(9)+"-"+dateF.charAt(3)+dateF.charAt(4)+"-"+dateF.charAt(0)+dateF.charAt(1);

	}
 </script>
<link rel="stylesheet" media="screen" href="<?=$url?>/css/dynCalendar.css" />
<script language="javascript" type="text/javascript" src="<?=$url?>/js/browserSniffer.js"></script>
<script language="javascript" type="text/javascript" src="<?=$url?>/js/dynCalendar.js"></script>
<h3>Indicadores dos Alunos</h3><br>
  <form name="calendario" action="" method='GET'>
    <b>Desde:</b>&nbsp;<input type="text" maxlength="10"  name="dataInicioValue" onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" value="<?=@$_REQUEST['dataInicioValue']?>">
    <input type="hidden" name="dataInicio" value=''>

    <script language="JavaScript" type="text/javascript">
    //<!--
    /**
    * Example callback function
    */
    function getDataInicio(date, month, year)
    {
        if (String(month).length == 1) {
            month = '0' + month;
        }
    
        if (String(date).length == 1) {
            date = '0' + date;
        }    
        document.forms['calendario'].dataInicioValue.value = date + '/' + month + '/' +year ;
        document.forms['calendario'].dataInicio.value = year + '-' + month + '-' +date ;
    }
    calendar1 = new dynCalendar('calendar1', 'getDataInicio','../imagens/');
    calendar1.setMonthCombo(true);
    calendar1.setYearCombo(true);
    //-->
    </script>
    
    <br />
    <b>Até:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="dataFimValue" maxlength="10"  onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" value="<?=@$_REQUEST['dataFimValue']?>">
    <input type="hidden" name="dataFim" value="<?=@$_REQUEST['dataFim']?>">

    <script language="JavaScript" type="text/javascript">
   // <!--
    /**
    * Example callback function
    */
    function getDataFim(date, month, year)
    {
        if (String(month).length == 1) {
            month = '0' + month;
        }
    
        if (String(date).length == 1) {
            date = '0' + date;
        }    
        document.forms['calendario'].dataFimValue.value = date + '/' + month + '/' + year;
        document.forms['calendario'].dataFim.value = year + '-' + month + '-' + date;

    }
    calendar2 = new dynCalendar('calendar2', 'getDataFim','../imagens/');
    calendar2.setMonthCombo(true);
    calendar2.setYearCombo(true);
    //-->
    </script><br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type='hidden' name='acao'>
	<input type="button" style="background-color: #c5d3f8" onclick="forms['calendario'].acao.value ='buscaPorTempo';formatHidden(document.forms['calendario'].dataInicioValue.value,document.forms['calendario'].dataFimValue.value);submit();" value="Buscar">
	<input type="button" style="background-color:  #FCEEDE" onclick="forms['calendario'].acao.value ='restaura';forms['calendario'].dataInicioValue.value ='';forms['calendario'].dataFimValue.value ='';forms['calendario'].dataInicio.value ='';forms['calendario'].dataFim.value =''; submit();" value="Limpar">
	
	

</form>
<h4>1) Indicadores por Aluno&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h4><?$alunos= listaAlunos();


if($_REQUEST['acao']=='buscaPorTempo'){
$param['dataInicio']=$_REQUEST['dataInicio'];
$param['dataFim']=$_REQUEST['dataFim'];
}else{
$param['dataInicio']='';
$param['dataFim']='';
}



//print_r($param);
$mensChat = getNumMensagemChat($_SESSION["codInstanciaGlobal"],'COD_PESSOA',$param);
$mensChatsystem = getNumMensagemChatSystem($_SESSION["codInstanciaGlobal"],'COD_PESSOA',$param);
//Mensagems dos forums
$mensForum = getNumMensagens($_SESSION["codInstanciaGlobal"],$ini["Academico"]["tabelaMensagens"],$ini["Academico"]["tabelaSalas"],$param);
//$mensTecnico= getNumMensagens($_SESSION["codInstanciaGlobal"],$ini["Tecnico"]["tabelaMensagens"],$ini["Tecnico"]["tabelaSalas"]);
$mensCafeVirtual = getNumMensagens($_SESSION["codInstanciaGlobal"],$ini["CafeVirtual"]["tabelaMensagens"],$ini["CafeVirtual"]["tabelaSalas"],$param);
echo "<br>";
echo "<table class=\"tabela\"  cellpadding=\"3\" cellspacing=\"1\" align='center'><tr>";
echo "<th class=\"tabela\">Aluno</th>";
echo "<th class=\"tabela\">Chat</th>";
echo "<th class=\"tabela\">Fórum</th>";
//echo "<th>Fórum Técnico</th>";
echo "<th class=\"tabela\">Café Virtual</th>";
echo "<th class=\"tabela\">Último Acesso</th>";
echo "<th class=\"tabela\">Número de Acessos</th>";
echo "</tr>";
//print_r($mensChatsystem);


//buscar pelos alunos, mostrando todos os indicadores de cada um
while($linhas = mysql_fetch_array($alunos)) {

  echo "<tr>";
  echo "<td class=\"tabela\">".$linhas["NOME_PESSOA"]."&nbsp;</td>";
  if($mensChat[$linhas["COD_PESSOA"]])
    echo "<td class=\"tabela\"><a href='".$url."/biblioteca/aula_interativa_mostrar.php?dataInicio=".$_REQUEST['dataInicio']."&dataFim=".$_REQUEST['dataFim']."&COD_PESSOA=".$linhas["COD_PESSOA"]."' target='_blanck'>".$mensChat[$linhas["COD_PESSOA"]]."&nbsp;</a></td>";
  else
    if($mensChatsystem[$linhas["COD_PESSOA"]])
      echo "<td class=\"tabela\"><a href='".$url."/biblioteca/aula_interativa_mostrar.php?dataInicio=".$_REQUEST['dataInicio']."&dataFim=".$_REQUEST['dataFim']."&COD_PESSOA=".$linhas["COD_PESSOA"]."' target='_blanck'>0 &nbsp;</a></td>";
    else
      echo "<td class=\"tabela\">--</td>";
  echo "<td class=\"tabela\"><a href='".$url."/interacao/forum/forum.php?acao=filtro&indicadores=1&tipoForum=1&dataInicio=".$_REQUEST['dataInicio']."&dataFim=".$_REQUEST['dataFim']."&COD_PESSOA=".$linhas["COD_PESSOA"]."&dataInicioValue=".$_REQUEST['dataInicioValue']."&dataFimValue=".$_REQUEST['dataFimValue']."' >".$mensForum[$linhas["COD_PESSOA"]]."&nbsp;</a></td>";
 // echo "<td>".$mensTecnico[$linhas["COD_PESSOA"]]."&nbsp;</td>";
  echo "<td class=\"tabela\"><a href='".$url."/interacao/forum/forum.php?acao=filtro&indicadores=1&tipoForum=3&dataInicio=".$_REQUEST['dataInicio']."&dataFim=".$_REQUEST['dataFim']."&COD_PESSOA=".$linhas["COD_PESSOA"]."&dataInicioValue=".$_REQUEST['dataInicioValue']."&dataFimValue=".$_REQUEST['dataFimValue']."' >".$mensCafeVirtual[$linhas["COD_PESSOA"]]."&nbsp;</a></td>";
  echo "<td class=\"tabela\">".formataUltimoAcesso($linhas["ultimoAcesso"])."&nbsp;</td>";
  echo "<td class=\"tabela\">".getNumeroAcessos($linhas["COD_PESSOA"], $param)."&nbsp;</td>";
  echo "</tr>";

}
echo "</table>";


echo "<br><h4>2) Totais do Chat por nome de envio</h4>";
echo "<table class=\"tabela\" cellpadding=\"3\" cellspacing=\"1\"><tr>";
echo "<th class=\"tabela\">Pessoa</th>";
echo "<th class=\"tabela\">Núm.Mensagens Chat</th>";
//echo "<th>Núm.Mens.Fórum</th>";
echo "</tr>";
$mensChatNome = getNumMensagemChat($_SESSION["codInstanciaGlobal"],'NOME_ENVIA',$param);
$mensChatCodPessoa=getCodPessoaChatByNomeEnvia($_SESSION["codInstanciaGlobal"],$param);

//$mensChatNome = getNumMensagemChat($_SESSION["codInstanciaGlobal"],"NOME_ENVIA");
// Visualizar somente as mensagens do chat
foreach($mensChatNome as $nome=>$numMsg) {
  echo "<tr>";
  echo "<td class=\"tabela\">".$nome."&nbsp;</td>";
  echo "<td class=\"tabela\"><a href='".$url."/biblioteca/aula_interativa_mostrar.php?dataInicio=".$_REQUEST['dataInicio']."&dataFim=".$_REQUEST['dataFim']."&COD_PESSOA=".$mensChatCodPessoa[$nome]."' target='_blanck'>".$numMsg."</a>&nbsp;</td>";
  echo "</tr>";
}
echo "</table>";
echo "</center>";
echo "</html>";

?>
