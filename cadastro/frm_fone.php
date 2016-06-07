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
include_once ($caminhoBiblioteca."/cadastro.inc.php");
session_name(SESSION_NAME); session_start(); security();
if( !isset($_REQUEST["NRO_ITEM_FONE"]) )
	$_REQUEST["NRO_ITEM_FONE"] = "";
	
if ( !isset($COD_INTERNAC_FONE) )
	$COD_INTERNAC_FONE = "";
if ( !isset($COD_AREA_FONE) )
	$COD_AREA_FONE = "";
if ( !isset($NRO_FONE) )
	$NRO_FONE = "";
if ( !isset($RAMAL_FONE) )
	$RAMAL_FONE = "";
	
if ($_SESSION["NOME_PESSOA"] == "")
{
	echo "Você não está logado no sistema. Esta página só pode ser acessada por um usuário logado.";
	exit();
 }

if ( $_REQUEST["NRO_ITEM_FONE"] != "" )
{
	$rsConT = telefone($_REQUEST["NRO_ITEM_FONE"]);
	
  	if ($rsConT)
	{
		while ($linhaT = mysql_fetch_array($rsConT))
		{
			$COD_TIPO_FONE     = $linhaT["COD_TIPO_FONE"];
			$COD_INTERNAC_FONE = $linhaT["COD_INTERNAC_FONE"];
			$COD_AREA_FONE     = $linhaT["COD_AREA_FONE"];
			$NRO_FONE          = $linhaT["NRO_FONE"];
			$RAMAL_FONE        = $linhaT["RAMAL_FONE"];
		 }
	 }
	else
	{
		echo " Problemas no acesso ao banco de dados. ";
		exit();
	 }
}

?>

<html>
	<head>
		<title>Altera&ccedil;&atilde;o/Adi&ccedil;&atilde;o de Telefone</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href="./sca.css" type="text/css">
		<link rel="stylesheet" href="./../cursos.css" type="text/css">

		<script language="JavaScript">
			function excluir()
			{
				form1.ACTION.value = "excluir"
				form1.submit();
			}
		</script>
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<? //include_once("tabela_topo.php"); ?>

  <h4 align="center">Adicionar / Editar Telefone</h4>
	
	<form name="form1" method="post" action="./atualiza_fone.php">

  <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="4">Tipo</td>
    </tr>
    <tr> 
      <td colspan="4"> 
        <select name="COD_TIPO_FONE" class="input3">
			<?php
				$rsCon = tipo_telefone();
				
			if ($rsCon)			
			while ($linhaTipo = mysql_fetch_array($rsCon))
			{
				echo "<option value='" . $linhaTipo["COD_TIPO_FONE"] . "'";
				
				if ($COD_TIPO_FONE = $linhaTipo["COD_TIPO_FONE"])
					echo " selected";
				
				echo ">" . $linhaTipo["DESC_TIPO_FONE"] . "</option>";
			 }
			?>
        </select>
      </td>
    </tr>
    <tr> 
      <td><BR>C&oacute;digo Internacional</td>
      <td><BR>C&oacute;digo &Aacute;rea</td>
      <td><BR>N&uacute;mero</td>
      <td><BR>Ramal</td>
    </tr>
    <tr> 
      <td> 
        <input type="text" name="COD_INTERNAC_FONE" class="input3" value="<?= $COD_INTERNAC_FONE; ?>">
      </td>
      <td> 
        <input type="text" name="COD_AREA_FONE" class="input3" value="<?= $COD_AREA_FONE; ?>">
      </td>
      <td> 
        <input type="text" name="NRO_FONE" class="input3" value="<?= $NRO_FONE; ?>">
      </td>
      <td> 
        <input type="text" name="RAMAL_FONE" class="input3" value="<?= $RAMAL_FONE; ?>">
      </td>
    </tr>
  </table>
    <br><br>

	<div align="center">
		<input type="hidden" name="NRO_ITEM_FONE" value="<?= $_REQUEST["NRO_ITEM_FONE"]?>">
		<input type="hidden" name="ACTION" value="">
	
	    <input type="button" name="Voltar" value="Voltar" class="input3" onclick="javascript:location.href='./frm_atualiza_cadastro.php'">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		<?php if ($_REQUEST["NRO_ITEM_FONE"] != "")
		{?>
    		<input type="button" name="Excluir" value="Excluir" class="input3" onclick=excluir()>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
		<?php } ?>

        <input type="button" name="Salvar" value="Salvar" class="input3" onclick="form1.submit()"> 
	
	</div>
</form>
</body>
</html>
