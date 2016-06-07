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



include_once ("../config.php");
include_once ($caminhoBiblioteca."/cadastro.inc.php");
//include_once ("../funcoes_bd.php");
session_name(SESSION_NAME); session_start(); security();
if ( !isset($_REQUEST["NRO_ITEM_END"]) )
	$_REQUEST["NRO_ITEM_END"] = "";

if ( !isset($DESC_END) )	
	$DESC_END = "";
if ( !isset($BAIRRO_END) )
	$BAIRRO_END	= "";
if ( !isset($CIDADE_END) )
	$CIDADE_END = "";
if ( !isset($UF_END) )
	$UF_END = "";
if ( !isset($PAIS_END) )
	$PAIS_END = "";
if ( !isset($CEP_END) )
	$CEP_END = "";
if ( !isset($EMPRESA_END) )
	$EMPRESA_END = "";
if ( !isset($SETOR_END) )
	$SETOR_END = "";
if ( !isset($CARGO_END) )
	$CARGO_END = "";
if ( !isset($COD_TIPO_END) )
	$COD_TIPO_END = "";

if ($_SESSION["NOME_PESSOA"] == "")
{
	echo "Você não está logado no sistema. Esta página só pode ser acessada por um usuário logado.";
	exit();
 }

if ( $_REQUEST["NRO_ITEM_END"] != "" )
{
	$rsConE = endereco($_REQUEST["NRO_ITEM_END"]);
	
	if ( $rsConE )
	{
		$linhaE = mysql_fetch_array($rsConE);
		
			 $DESC_END 	      = $linhaE["DESC_END"];
			 $BAIRRO_END	  = $linhaE["BAIRRO_END"];
			 $CIDADE_END	  = $linhaE["CIDADE_END"];
			 $UF_END		  = $linhaE["UF_END"];
			 $PAIS_END	      = $linhaE["PAIS_END"];		
			 $CEP_END	      = $linhaE["CEP_END"];
			 $EMPRESA_END     = $linhaE["EMPRESA_END"];
			 $SETOR_END 	  = $linhaE["SETOR_END"];
			 $CARGO_END 	  = $linhaE["CARGO_END"];
			 $COD_TIPO_END    = $linhaE["COD_TIPO_END"];
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
		<title>Cadastro</title>
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

<?php
//include_once("tabela_topo.php");
?>

<h4 align="center"> Adicionar / Editar Endere&ccedil;o </h4>
  
<form name="form1" method="post" action="atualiza_endereco.php">
  <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="4"><BR>Tipo</td>
    </tr>
    <tr> 
      <td colspan="4"> 
        <select name="COD_TIPO_END" class="input3">
			<?php		  				
				$rsCon = tipo_endereco();
				
				if ($rsCon)
					while ($linha = mysql_fetch_array($rsCon))
					{
						echo "<option value='" . $linha["COD_TIPO_END"] . "'";
						
						if ($COD_TIPO_END == $linha["COD_TIPO_END"])
							echo " selected";
						
						echo ">" . $linha["DESC_TIPO_END"] . "</option>";
					 }

			?>
        </select>
      </td>
    </tr>
    <tr> 
      <td><BR>Endere&ccedil;o</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="4"> 
        <input type="text" name="DESC_END" class="input1" value="<?=$DESC_END;?>">
      </td>
    </tr>
    <tr> 
      <td><BR>Bairro</td>
      <td>Cidade</td>
      <td>UF</td>
      <td>Pa&iacute;s</td>
    </tr>
    <tr> 
      <td> 
        <input type="text" name="BAIRRO_END" class="input3" value="<?=$BAIRRO_END;?>">
      </td>
      <td> 
        <input type="text" name="CIDADE_END" class="input3" value="<?=$CIDADE_END;?>">
      </td>
      <td> 
        <input type="text" name="UF_END" class="input3" value="<?=$UF_END;?>">
      </td>
      <td> 
        <input type="text" name="PAIS_END" class="input3" value="<?=$PAIS_END;?>">
      </td>
    </tr>
    <tr> 
      <td><BR>CEP</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td> 
        <input type="text" name="CEP_END" class="input3" value="<?=$CEP_END;?>">
      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><BR>Empresa</td>
      <td>&nbsp;</td>
      <td>Setor</td>
      <td>Cargo</td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <input type="text" name="EMPRESA_END" class="input2" value="<?=$EMPRESA_END;?>">
      </td>
      <td> 
        <input type="text" name="SETOR_END" class="input3" value="<?=$SETOR_END;?>">
      </td>
      <td> 
        <input type="text" name="CARGO_END" class="input3" value="<?=$CARGO_END;?>">
      </td>
    </tr>
  </table>
   <br><br> 
   
	<div align="center">
		<input type="hidden" name="NRO_ITEM_END" value="<?= $_REQUEST["NRO_ITEM_END"]; ?>">
		<input type="hidden" name="ACTION" value="">

	    <input type="button" name="Voltar" value="Voltar" class="input3" onclick="javascript:location.href='./frm_atualiza_cadastro.php'">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		<% if Request("NRO_ITEM_END") <> "" then %>
    		<input type="button" name="Excluir" value="Excluir" class="input3" onclick=excluir()>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
		<%end if %>

        <input type="button" name="Salvar" value="Salvar" class="input3" onclick="form1.submit()"> 

	</div>
  
</form>

</body>
</html>
