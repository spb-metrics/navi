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

//include_once ("../funcoes_bd.php");
include_once ("../config.php");
include_once ($caminhoBiblioteca."/cadastro.inc.php");
session_name(SESSION_NAME); session_start(); security();
if ($_SESSION["NOME_PESSOA"] == "")
{
	echo "Voc� n�o est� logado no sistema. Esta p�gina s� pode ser acessada por um usu�rio logado.";
	exit();
 }

$rsConC = pessoa("");

if ($rsConC)
	while (!$linha = mysql_fetch_array($rsConC))
		{
		echo " Problemas no acesso ao banco de dados. ";
		exit();
		 }
else
	{
	echo " Problemas no acesso ao banco de dados. ";
	exit();
	}
	
?>

<html>
	<head>
		<title>Altera&ccedil;&atilde;o de Senha</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="./sca.css" type="text/css">
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		
		<script language="JavaScript">
		
			function enviaForm()
			{
				if(validaForm())
				{
					document.f1.action = "atualiza_senha.php";
					document.f1.submit();
				}
			 }

			function validaForm()
			{
				var msg = "";
				var obj = "";
				var preen = true;

				// Valida senha - text
				if ((document.f1.SENHA_PESSOA.value == null) || (document.f1.SENHA_PESSOA.value == ""))
				{
					msg += "=> Senha n�o preenchida;\n";
					preen = false;
				}
				if ((document.f1.SENHA_PESSOA2.value == null) || (document.f1.SENHA_PESSOA2.value == ""))
				{
					msg += "=> Confirma��o de senha n�o preenchida;\n";
					preen = false;
				}
				if (preen) {
					if (document.f1.SENHA_PESSOA.value != document.f1.SENHA_PESSOA2.value)
						msg += "=> Confirme novamente a sua senha;\n";
				}
				
				// Valida frase - text
				if ((document.f1.FRASE_SENHA_PESSOA.value == null) || (document.f1.FRASE_SENHA_PESSOA.value == ""))
				{
					msg += "=> Frase n�o preenchida;\n";
					preen = false;
				}
				
				if (msg != "")
				{
					msg = "Ocorreram os seguintes erros:\n\n" + msg;
					alert (msg);
					return false;
				}
				else
				{
					return true;
				}
			}
		</script>
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<?php
//include_once ("tabela_topo.php");
?>

<h4 align="center">Altera&ccedil;&atilde;o/Adi&ccedil;&atilde;o de Senha</h4>

	<form name="f1" method="post" action="">
			<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr> 
					<td colspan="2">Usu&aacute;rio</td>
				</tr>
				<tr> 
					<td colspan="2"> 
						<input type="text" name="USER_PESSOA" class="input3" value="<?= $linha["USER_PESSOA"]?>" disabled>
					</td>
				</tr>
				<tr> 
					<td height="15">Digite uma senha: </td>
					<td>Confirme a sua senha:</td>
				</tr>
				<tr> 
					<td height="15"> 
						<input type="password" name="SENHA_PESSOA" class="input3">
					</td>
					<td> 
						<input type="password" name="SENHA_PESSOA2" class="input3">
					</td>
				</tr>
				<tr> 
					<td height="15">Digite uma frase para lembrar da sua senha:</td>
      				<td>&nbsp;</td>
    			</tr>
    			<tr> 
      				<td height="15" colspan="2"> 
        				<input type="text" name="FRASE_SENHA_PESSOA" class="input1" value="<?= $linha["FRASE_SENHA_PESSOA"]?>">
      				</td>
    			</tr>
  			</table>
    <br><br>
  <div align="center">
		    <input type="button" name="Voltar" value="Voltar" class="input3" onclick="javascript:location.href='./frm_atualiza_cadastro.php'">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" name="Salvar" value="Salvar" class="input3" onclick="javascript:enviaForm()"> 
    </div>
</form>
</body>
</html>