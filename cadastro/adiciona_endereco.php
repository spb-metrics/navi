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


// Por nao sobrescrever a variavel de sessao "pagina_atual" pode colocar um histpry.back()

if ($_SESSION["NOME_PESSOA"] == "")
{
	echo "Você não está logado no sistema. Esta página só pode ser acessada por um usuário logado.";
	exit();
}

$codigoTipoEndereco = $_POST['tp'];

if(!is_numeric($codigoTipoEndereco)){
	echo "erro 1 ao executar o script - parametro inválido!";
	exit();
}

/*
$rsConE = endereco($codigoEndereco);

if(mysql_num_rows($rsConE) == 0){
	echo "erro 2 ao executar o script - parametro inválido!";
	exit();
}*/

$colSpan = 2;
$tpEnd = tipo_endereco();
?>
<html>
<head>
<link rel="stylesheet" href="./../css/endereco.css" type="text/css"> 
<script language="javascript">
function mudaTipo(){
	var selecionado = document.form.tipo_endereco.value;
	<?	
	while($linhaTipoEndereco = mysql_fetch_array($tpEnd)){
		print " var e".$linhaTipoEndereco["COD_TIPO_END"]."='".$linhaTipoEndereco["DESC_TIPO_END"]."';\n";
	}
	?>
	for(var i=1; i<=<? print mysql_num_rows($tpEnd); ?>;i++)
	{
		if(selecionado == i){
			document.getElementById("tipohtml").innerHTML="<b>Adicionar Endere&ccedil;o "+eval('e'+i)+"</b>";
			document.form.cod_tipo_endereco.value = i;
			if(i==2) p("visible");
			else	 p("hidden")
		}
	}	
}

function p(newstyle){
	var aux=1;
	for(j=1;j<=3;j++){
		document.getElementById("profissional"+j).style.visibility=newstyle;
	}
}

function chkSePeloMenosUm(){ 
	var forme = document.form;
	var tpSelecionado = document.form.tipo_endereco.value;
	var um = false;
	var campo;
	
	for(var i=0; i<forme.length; i++){ 
		if(tpSelecionado != 2)
			campo = forme.elements[i];
		else if(	(forme.elements[i].name.value != "cargo")&&
				 	(forme.elements[i].name.value != "setor")&&
					(forme.elements[i].name.value != "empresa")){
			campo = forme.elements[i];
		}
		
		if((campo.type == "text") && (campo.value.length > 0))
			um = true;
	}
	
	if(um)
		document.form.submit();
	else
		alert("Preencha pelo menos um campo para cadastrar o endereço.");
}

function v(){
	history.back();
}

</script>
</head>
<body body bgcolor="#FFFFFF" text="#000000" class="bodybg" onLoad="mudaTipo()">
<form name="form" id="form" method="post" action="adiciona_endereco_proc.php">
<table id="endereco" align="center">	
<tr>
	<td colspan="<? print $colSpan; ?>" class="tituloEndereco" id="tipohtml">
		<b>Adicionar Endere&ccedil;o <? print $linhaTipoEndereco["DESC_TIPO_END"]; ?></b>
	</td>
</tr>
<tr>
	<td valign="top" style=" visibility:visible ">
		Tipo de Endereco:
	</td>
	<td>
		<?
			$tpEnd = tipo_endereco();
			
			print "<select name='tipo_endereco' onChange='mudaTipo()'>";
			while($linhaTipoEndereco = mysql_fetch_array($tpEnd)){
					if($linhaTipoEndereco["COD_TIPO_END"] == $codigoTipoEndereco)
						$selected	="selected";
					else
						$selected	=""; 
						
					print "<option value='".$linhaTipoEndereco["COD_TIPO_END"]."' ".$selected.">".$linhaTipoEndereco["DESC_TIPO_END"]."</option>";
			}	
			print "</select>";
		?>
		
	</td>
</tr>
<tr>
	<td valign="top">
		Descri&ccedil;&atilde;o: 
	</td>
	<td>
		<input type="text" name="descricao" id="descricao" value="" size="50"/>
	</td>
</tr>
<tr>	
	<td>
		Bairro:
	</td>
	<td>
		<input type="text" name="bairro" id="bairro" value=""/>
	</td>
</tr>

<tr>	
	<td>
		Cidade:
	</td>
	<td>
		<input type="text" name="cidade" id="cidade" value=""/>
	</td>
</tr>

<tr>	
	<td>
		UF:
	</td>
	<td>
		<input type="text" name="uf" id="uf" value="" size="2" maxlength="2"/>
	</td>
</tr>

<tr>	
	<td>
		Pa&iacute;s:
	</td>
	<td>
		<input type="text" name="pais" id="pais" value=""/>
	</td>
</tr>

<tr>	
	<td>
		cep:
	</td>
	<td>
		<input type="text" name="cep" id="cep" value=""/>
	</td>
</tr>


	<tr id="profissional1" style="visibility:hidden;">	
		<td>
			Empresa:	
		</td>
		<td>
			<input type="text" name="empresa" id="empresa" value=""/>
		</td>
	</tr>
	<tr id="profissional2" style="visibility:hidden;">
		<td>
		Setor:
		</td>
		<td>
			<input type="text" name="setor" id="setor" value=""/>
		</td>
	</tr>
	<tr id="profissional3" style="visibility:hidden;">
		<td>
		Cargo:
		</td>
		<td>
			<input type="text" name="cargo" id="cargo" value=""/>
		</td>
	</tr>

	<tr>
		<td colspan="<? print $colSpan; ?>" class="tdBranca" align="center">
			<input type="button" name="salvar" id="salvar" value="salvar" onClick="chkSePeloMenosUm()">
			<input type="button" name="voltar" id="voltar" value="voltar" onClick="v()">
			<input type="hidden" name="cod_tipo_endereco" id="cod_tipo_endereco" value="<? print $codigoTipoEndereco; ?>"/>
		</td>
	</tr>
</table>
</form>

</body>
</html>