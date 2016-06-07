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

$rsConC = pessoa("");

if (! $rsConC)
{
	echo " Problemas no acesso ao banco de dados. ";
	exit();
 }

$linhaC = mysql_fetch_array($rsConC);

?>

<html>
	<head>
		<title>Gradua&ccedil;&atilde;o - Inscri&ccedil;&atilde;o</title>
		
		<link rel="stylesheet" href="./sca.css" type="text/css">
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
				
		<script language="JavaScript">
			function validaForm(){
				var data  = "";
				var msg   = "";
				var obj   = "";
				var preen = true;
			
				// Valida nome - text
				if ((document.f1.NOME_PESSOA.value == null) || (document.f1.NOME_PESSOA.value == ""))
				{
					msg += "=> Nome não preenchido;\n";
					preen = false;
				}
				
				// Verifica data_nasc (se não é vazioa) - text
				if ((document.f1.DATA_NASC_PESSOA.value == null) || (document.f1.DATA_NASC_PESSOA.value == ""))
				{
					msg += "=> Data de nascimento não preenchida;\n";
					preen = false;
				}
				
				// Valida data_nasc - text
				if (!isDate(document.f1.DATA_NASC_PESSOA.value))
				{
					msg += "=> Data de nascimento no formato incorreto. Preencha no formato 'dd/mm/aaaa';\n";
					preen = false;
				}
				else				 
				{	data = document.f1.DATA_NASC_PESSOA.value;
					//alert(data); 
				 	document.f1.DATA_NASC_PESSOA_MODIFICADA.value = data.charAt(6)+data.charAt(7)+data.charAt(8)+data.charAt(9)+"-"+data.charAt(3)+data.charAt(4)+"-"+data.charAt(0)+data.charAt(1);
					//alert(document.f1.DATA_NASC_PESSOA_MODIFICADA.value);
				 }
				  			
				// Valida sexo - radio
				if ((document.f1.COD_SEXO[document.f1.COD_SEXO.selectedIndex].value == null) 
					|| (document.f1.COD_SEXO[document.f1.COD_SEXO.selectedIndex].value == ""))
				{
					msg += "=> Sexo não selecionado;\n";
				}
				
				// Valida doc_id - text
				if ((document.f1.DOC_ID_PESSOA.value == null) || (document.f1.DOC_ID_PESSOA.value == ""))
				{
					msg += "=> Documento de Identidade não preenchido;\n";
					preen = false;
				}
				
				// Valida e-mail - text
				if ((document.f1.EMAIL_PESSOA.value == null) || (document.f1.EMAIL_PESSOA.value == ""))
				{
					msg += "=> E-mail não preenchido;\n";
					preen = false;
				}
				
				// Valida cpf - text
				if ((document.f1.CPF_PESSOA.value == null) || (document.f1.CPF_PESSOA.value == "")) 
				{
					msg += "=> CPF não preenchido;\n";
					preen = false;
				}
				
				if (!checaCPF(document.f1.CPF_PESSOA.value))
				{
					msg += "=> CPF inválido;\n";
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
			
			function checaCPF (CPF) {
				if (CPF.length != 11 || CPF == "00000000000" || CPF == "11111111111" ||
					CPF == "22222222222" ||	CPF == "33333333333" || CPF == "44444444444" ||
					CPF == "55555555555" || CPF == "66666666666" || CPF == "77777777777" ||
					CPF == "88888888888" /*|| CPF == "99999999999"*/)
					return false;
				soma = 0;
				for (i=0; i < 9; i ++)
					soma += parseInt(CPF.charAt(i)) * (10 - i);
				resto = 11 - (soma % 11);
				if (resto == 10 || resto == 11)
					resto = 0;
				if (resto != parseInt(CPF.charAt(9)))
					return false;
				soma = 0;
				for (i = 0; i < 10; i ++)
					soma += parseInt(CPF.charAt(i)) * (11 - i);
				resto = 11 - (soma % 11);
				if (resto == 10 || resto == 11)
					resto = 0;
				if (resto != parseInt(CPF.charAt(10)))
					return false;
				return true;
			 }
			
			function novoEndereco()
			{
				if(validaForm())
				{
					document.f1.PROX_PAG.value = "frm_endereco.php"
					document.f1.action = "atualiza_cadastro.php";
					document.f1.NRO_ITEM_END.value = "";
					document.f1.submit();
				}
			}
			function modificaEndereco(strNRO_ITEM_END)
			{
				if(validaForm())
				{
					document.f1.PROX_PAG.value = "frm_endereco.php"
					document.f1.action = "atualiza_cadastro.php";
					document.f1.NRO_ITEM_END.value = strNRO_ITEM_END;
					document.f1.submit();
				}
			}
			function novoFone()
			{
				if(validaForm())
				{
					document.f1.PROX_PAG.value = "frm_fone.php"
					document.f1.action = "atualiza_cadastro.php";
					document.f1.NRO_ITEM_FONE.value = "";
					document.f1.submit();
				}
			}
			function modificaFone(strNRO_ITEM_FONE)
			{
				if(validaForm())
				{
					document.f1.PROX_PAG.value = "frm_fone.php"
					document.f1.action = "atualiza_cadastro.php";
					document.f1.NRO_ITEM_FONE.value = strNRO_ITEM_FONE;
					document.f1.submit();
				}
			}
			function enviaForm()
			{	
				if(validaForm())
				{
					document.f1.action = "atualiza_cadastro.php";
					document.f1.submit();
				}
			}
			function mudaSenha()
			{
				if(validaForm())			
				{
					document.f1.PROX_PAG.value = "frm_atualiza_senha.php"
					document.f1.action = "atualiza_cadastro.php";
					document.f1.submit();
				 }
			}
			
			function isDate(val)
			 {
			  // created by jignesh gandhi jignesh@hotbiz.com
			  // returns true if the string passed is a valid date.
			  // formato adaptado: dd/mm/aaaa
			  var sep1 = parseInt(val.indexOf("/"));
			  var sep2 = parseInt(val.indexOf("/",sep1+1));
			  var len = parseInt(val.length);
			  
			  var dd = parseInt(val.substr(0,sep1),10);
			  var mm = parseInt(val.substr(sep1+1,sep2-sep1-1),10);
			  var yy = parseInt(val.substr(sep2+1,len-sep2-1),10);
			  if (isNaN(dd) || isNaN(mm) || isNaN(yy)) return false;
			  if (yy<1900) yy+=2000;
			  var leap = ((yy == (parseInt(yy/4) * 4)) && !(yy == (parseInt(yy/100) * 100)));
			  if (!((mm >= 1) && (mm <= 12))) return false;
			  if ((mm == 2) && (leap)) dom = 29;
			  if ((mm == 2) && !(leap)) dom = 28;
			  if ((mm == 1) || (mm == 3) || (mm == 5) || (mm == 7) || (mm == 8) || (mm == 10) || (mm == 12)) dom = 31;
			  if ((mm == 4) || (mm == 6) || (mm == 9) || (mm == 11)) dom = 30;
			  if (dd > dom) return false;
			  return true;
			 }

		</script>
		
</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">


<h4 align="center">Informa&ccedil;&otilde;es cadastrais:</h4>

<form name="f1" method="post" action="">
  <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td colspan="2" height="15"><b>Usu&aacute;rio</b></td>
    </tr>
    <tr> 
      <td valign="top" height="28" colspan="2"> 
      	<input type="text" class="input1" value="<?= $linhaC["USER_PESSOA"]?>" name="USER_PESSOA" disabled>
      </td>
    </tr>
	
    <tr> 
      <td colspan="2" height="15"><b>Senha </b></td>
    </tr>
    <tr> 
      <td colspan="2" height="15"> 
        <input type="button" name="MODIF_SENHA" value="Modificar Senha" class="input3" onclick="javascript:mudaSenha()">		
      </td>
    </tr>

    <tr> 
      <td height="15"><BR><b>Nome Completo</b></td>
    </tr>
    <tr> 
      <td colspan="3" height="28"> 
        <input type="text" name="NOME_PESSOA" class="input1" value="<?= $linhaC["NOME_PESSOA"]?>">
      </td>
    </tr>
    <tr> 
		<td>
			<table>
				<tr>
		      		<td width="200" height="15"><b>Data de Nascimento</b></td>
      				<td width="200" ><b>Sexo</b></td>
      				<td width="200" ><b>Documento de Identifica&ccedil;&atilde;o</b></td>	  
				</tr>
			</table>
		</td>
    </tr>
    <tr> 
		<td>
			<table>
				<tr>	
				    <td width="200" height="28"> 
			  		   <input type="text" name="DATA_NASC_PESSOA" class="input3" value="<?= $linhaC["DATA_NASC"]?>">
			        </td>
				    <td width="200" > 
				        <select name="COD_SEXO" class="input3">
						<?php
								$rsConSexo = sexo("");
							
									if ($rsConSexo)
									{
										while ($linhaSexo = mysql_fetch_array($rsConSexo))
										{
											echo "<option value='" . $linhaSexo["COD_SEXO"] . "' ";
											
											if ($linhaSexo["COD_SEXO"] == $linhaC["COD_SEXO"])
												echo "SELECTED";
						
											echo ">" . $linhaSexo["DESC_SEXO"] . "</option>";
										 }
									 }	
						?>
				        </select>
			      </td>
			      <td width="200" > 
				        <input type="text" name="DOC_ID_PESSOA" class="input3" value="<?= $linhaC["DOC_ID_PESSOA"]?>">
			      </td>
			 </tr>
		   </table>
		</td>
    </tr>
    <tr> 
		<td>
			<table>
				<tr>
			      <td width="300" height="15"><b>E-mail</b></td>
			      <td width="300"><b>CPF</b></td>
				</tr>
			</table>
		</td>
   </tr>
    <tr> 
		<td>
			<table>
				<tr>	
			      <td width="300" colspan="2" height="28"> 
			        <input type="text" name="EMAIL_PESSOA" class="input2" value="<?= $linhaC["EMAIL_PESSOA"]?>">
			      </td>
			      <td width="300"> 
			        <input type="text" name="CPF_PESSOA" class="input2" value="<?= $linhaC["CPF_PESSOA"]?>">
			      </td>
				</tr>
			</table>
		</td>				  
    </tr>
    <tr> 
      <td height="15" colspan="3"> 
        <b>Endere&ccedil;os</b> <BR><BR>
            <?php
			$rsConE = endereco("");
			
			if ($rsConE)			
			while ($linhaEnd = mysql_fetch_array($rsConE))
			{
				echo "<table width='600' border='1' cellspacing='0' cellpadding='0'><tr><td width='450'>";
				echo $linhaEnd["DESC_TIPO_END"];
				echo ":<br>";
				
				$Usar_ = false;
				
				echo $linhaEnd["DESC_END"];
				if ($linhaEnd["DESC_END"] != "")
					$Usar_ = true;										
				if ($Usar_ = true AND $linhaEnd["BAIRRO_END"] != "")
					echo " - " ;												
				echo $linhaEnd["BAIRRO_END"] . "<br>";
				
				$Usar_ = false;
				
				echo $linhaEnd["CEP_END"];
				if ($linhaEnd["CEP_END"] != "")
					$Usar_ = true;
				if ($Usar_ = true AND $linhaEnd["CIDADE_END"] != "")
					echo " - ";
																
				echo $linhaEnd["CIDADE_END"];
				if ($linhaEnd["CIDADE_END"] != "")
					$Usar_ = true;
				if ($Usar_ = true AND $linhaEnd["UF_END"] != "")
					echo " - " ;
																			
				echo $linhaEnd["UF_END"];
				if ($linhaEnd["UF_END"] != "")
					$Usar_ = true;
				if ($Usar_ = true AND $linhaEnd["PAIS_END"] != "")
					echo " - " ;
											
				echo $linhaEnd["PAIS_END"];
				
				echo "</td>";
				echo "<td width='150'>";
				echo "<input type='button' name='Endereco2' ";
				echo "value='Modificar' class='input3' ";
				echo "onclick='javascript:modificaEndereco(" . $linhaEnd["COD_ENDERECO"] . ")'>";
				echo "</td></tr></table>";
			 }
			?>
          <br> 
         <input type="button" name="Endereco" value="Adicionar Endere&ccedil;o" class="input3" onClick="javascript:novoEndereco()">
        <BR> <BR> <b>Telefones</b>  <BR><BR>
          <?php
			
			$rsConT = telefone("");
			
		  	if ($rsConT)

			while ($linhaTel = mysql_fetch_array($rsConT))
			{
				echo "<table width='600' border='1' cellspacing='0' cellpadding='0'><tr><td width='450'>";
				echo $linhaTel["DESC_TIPO_FONE"];
				echo ": ";
				if ($linhaTel["COD_INTERNAC_FONE"] != "")
					echo $linhaTel["COD_INTERNAC_FONE"] . " - ";
				
				if ($linhaTel["COD_AREA_FONE"] != "")
					echo "(" . $linhaTel["COD_AREA_FONE"] . ")";

				echo $linhaTel["NRO_FONE"];
				if ($linhaTel["RAMAL_FONE"] != "")
					echo " - Ramal: " . $linhaTel["RAMAL_FONE"];

				echo "</td>";
				echo "<td width='150'>";
				echo "<input type='button' name='Endereco2' ";
				echo "value='Modificar' class='input3' ";
				echo "onclick='javascript:modificaFone(" . $linhaTel["COD_FONE"] . ")'>";
				echo "</td></tr></table>";
			 }
		?>
          <br>
          <input type="button" name="Fone" value="Adicionar Fone" class="input3" onClick="javascript:novoFone()">
      </td>
    </tr>
    <tr> 
      <td height="15" colspan="2">&nbsp; </td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td height="15" colspan="2">&nbsp;</td>
      <td height="15">&nbsp;</td>
    </tr>
    <tr> 
      <td height="15" colspan="3"> 
        <div align="center">
		    <input type="button" name="Voltar" value="Ir para Página Principal" class="input3" onclick="location.href='./../principal.php'">		
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	        <input type="button" name="Salvar" value="Salvar" class="input3" onclick="javascript:enviaForm()"> 
        </div>
      </td>
    </tr>
  </table>
  <BR>
  <input type="hidden" name="NRO_ITEM_END"  value="">
  <input type="hidden" name="NRO_ITEM_FONE" value="">
  <input type="hidden" name="PROX_PAG"      value="">
  <input type="hidden" name="DATA_NASC_PESSOA_MODIFICADA" value="">   
</form></body>
</html>
