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
include_once ("../config.php");

include_once ($caminhoBiblioteca."/portfolio.inc.php");

include_once ($caminhoBiblioteca."/funcoesftp.inc.php");


session_name(SESSION_NAME); session_start();security();
ini_set("display_errors",1);
error_reporting(E_ALL ^ E_NOTICE);

if (!function_exists('confNum'))
	{
  //define a funcao confNum se ela nao existe 
  //meio tosco... mas eh q a definicao dela esta em agenda.inc.php
  //e nao queria incluir este arquivo apenas para esta funcao, nao faz sentido
		function confNum($Num)
		{
			if ($Num < 10) 
				return "0" . strval($Num);
			else		
				return strval($Num);
		}
	}

?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href=".././cursos.css" type="text/css">
		<link rel="stylesheet" href="<?php echo $url;?>/css/padraogeral.css" type="text/css">

		<script language="JavaScript" src=".././funcoes_js.js"></script>
	  <script language="JavaScript" src="<?".$url."?>/js/editor.js"></script>
	  <script language="javascript" type="text/javascript" src="<?php echo $url;?>/js/tiny_mce/tiny_mce.js"></script>
  </head>

<body bgcolor="#FFFFFF" text="#000000">
<script language="JavaScript" type="text/javascript">initHtmlEditorCompleto('150'); 
function validaForm()
{
  el_desc_arquivo=document.getElementById('desc_arquivo'); 
  el_arquivo=document.getElementById('arquivo'); 
  if (el_arquivo.value!='')
  {
    if (el_desc_arquivo.value=='')
    {
      alert('Preencha a descricao!'); el_desc_arquivo.focus(); return false;
    } 
    else
    {
      return true;
    }
  }
  else
  {
    if (el_desc_arquivo.value!='')
    {
      alert('Você precisa selecionar um arquivo!'); el_arquivo.focus(); return false;
    } 
    else
    {
      return true;
    }
  }
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 

		<td width="200" valign="top"> 
			
		</td>

		
	    <td width="540" valign="top"> 
              <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td>

<?php
if (! isset($_REQUEST["COD_ALUNO_ARQUIVO"]))
	$_REQUEST["COD_ALUNO_ARQUIVO"] = "";
if (! isset($_REQUEST["COD_AL"]))
	$_REQUEST["COD_AL"] = "";

?>				  
				  
			<p align="right"><a href="./interno.php?COD_AL=<?=$_REQUEST["COD_AL"]?>"> Voltar </a></p>

  <?
	
	if ($_SESSION["COD_PESSOA"] == "" OR $_SESSION["codInstanciaGlobal"] == "" )
	{
		echo "<p align='center'> <b>Dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
	 }
	
	if (isset ($_REQUEST["COM"]))
	{
		if (portEnviaCom($_REQUEST["COD_ALUNO_ARQUIVO"],  $_REQUEST["COM"],$_REQUEST["COD_AL"]) )
		{
			echo "<script> location.href=\"./ver_comentario.php?COD_AL=".$_REQUEST["COD_AL"]."&COD_ALUNO_ARQUIVO=". $_REQUEST["COD_ALUNO_ARQUIVO"]. "\";</script>";
		 }
		else
		{
			echo "ERRO na Inserção<br> <a href=\"javascript:history.back()\">Voltar</a>";
			exit();
		 }
	 }
?>	
			<form name="form" method="post" enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>?COD_AL=<?=$_REQUEST["COD_AL"]?>" 
      onSubmit="return validaForm()">
			<table>
			<tr><td>
			<p>
				<b>Comentario :</b><br>
				<textarea name="COM" style="width: 70%; height: 100px;" ></textarea>
			</p>
			</td></tr>
			 <tr>
			  <td align="left" width="60%">			
				<p><b> Descrição do Arquivo: </b><br>
				 <input type="text" id="desc_arquivo" name="DESC_ARQUIVO" value="" size="60">
				</p>
			  </td></tr>
			 <tr><td>
			 <p><b> Endereço do Arquivo: </b><br> <input type="file" id="arquivo" name="ARQUIVO" size="60"> </p>
             </td></tr>
		<tr>
           <td>
	   
			<p>	

				<input type="hidden" name="COD_ALUNO_ARQUIVO" value="<?= $_REQUEST["COD_ALUNO_ARQUIVO"] ?>">
				<input type="submit" class="okButton"       value="Enviar">&nbsp;
				<input type="reset"  class="cancelButton"   value="Cancelar" onclick="javascript:history.back()">
			</p>
			</td></tr>
			</table>
			</form>
	
				  </td>
                </tr>
              </table>	
			</td>
		</tr>
	</table>			
</body>
</html>
