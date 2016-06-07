<?php
session_name('multinavi'); session_start();

include_once("../config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once ($caminhoBiblioteca."/cadastro.inc.php");


if ($_SESSION["COD_PESSOA"] <> "")
{
	echo "<script> alert(\"Atenção: para você criar um novo usuário, você deve sair do sistema. Para sair, clique em \\\"SAIR\\\", no topo da página.\");history.back();</script>";
	exit();
 }

?>

<html>
	<head>
		<title>Cadastro</title>

		<link rel="stylesheet" href="./sca.css" type="text/css">
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		
		<script language="JavaScript">
			function validaForm()
			{
				var msg = "";
				var obj = "";
				var preen = true;

				<?php 
//				if ($SENHA_PESSOA == "")
								
				if (! isset($SENHA_PESSOA))
				{
				?>
					// Valida senha - text
					if ((document.f1.SENHA_PESSOA.value == null) || (document.f1.SENHA_PESSOA.value == ""))
					{
						msg += "=> Senha não preenchida;\n";
						preen = false;
					 }

					if ((document.f1.SENHA_PESSOA2.value == null) || (document.f1.SENHA_PESSOA2.value == ""))
					{
						msg += "=> Confirmação de senha não preenchida;\n";
						preen = false;
					 }

					if (preen) 
					{
						if (document.f1.SENHA_PESSOA.value != document.f1.SENHA_PESSOA2.value)
						{
							msg += "=> Confirme novamente a sua senha;\n";
						 }
					 }
			
					/* / Valida FRASE senha - text
					if ((document.f1.FRASE_SENHA_PESSOA.value == null) || (document.f1.FRASE_SENHA_PESSOA.value == ""))
					{
						msg += "=> Frase para lembrar a senha não preenchida;\n";
					 }
					*/
	
				<?php
				 }
				?>
	
				// Valida curso de interesse - text
				//if ((document.f1.CURSO_INTERESSE.value == null) || (document.f1.CURSO_INTERESSE.value == ""))
				if (((document.f1.curso8.checked) || (document.f1.curso9.checked) || (document.f1.curso10.checked) || (document.f1.curso11.checked))==false)
				{
					msg += "=> Curso de interesse não preenchido;\n";
					preen = false;
				 }

				// Valida nome - text
				if ((document.f1.NOME_PESSOA.value == null) || (document.f1.NOME_PESSOA.value == ""))
				{
					msg += "=> Nome não preenchido;\n";
					preen = false;
				 }
	
				// Verifica data_nasc (se não é vazia) - text
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
				if ((document.f1.COD_SEXO[document.f1.COD_SEXO.selectedIndex].value == null) || (document.f1.COD_SEXO[document.f1.COD_SEXO.selectedIndex].value == ""))
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
	
				/* Valida cpf - text
				if ((document.f1.CPF_PESSOA.value == null) || (document.f1.CPF_PESSOA.value == "")) 
				{
					msg += "=> CPF não preenchido;\n";
					preen = false;
				 }
				*/
	
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
			}//validaForm

			function checaCPF (CPF) 
			{
				if (CPF.length != 11 || CPF == "00000000000" || CPF == "11111111111" ||
					CPF == "22222222222" ||	CPF == "33333333333" || CPF == "44444444444" ||
					CPF == "55555555555" || CPF == "66666666666" || CPF == "77777777777" ||
					CPF == "88888888888" /*|| CPF == "99999999999"*/)
				{
					return false;
				 }

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

			function enviaForm()
			{
				if(validaForm())
				{
					document.f1.action = "./insere_cadastro.php";
					document.f1.submit();
				}
			}
			
			function confereUser(teclapres) {
				var tecla = teclapres.keyCode;
				vr = document.f1.USER_PESSOA.value;
				vr = vr.toLowerCase();
				for(i=0; i<vr.length; i++)
				if (vr.charCodeAt(i)>122 || vr.charCodeAt(i)<97){
					vr = vr.replace(vr.charAt(i),"")
				}
				document.f1.USER_PESSOA.value = vr;
			}
			
			function isDate(val)
			 {
			  // created by jignesh gandhi jignesh@hotbiz.com
			  // returns true if the string passed is a valid date.
			  // formato adaptado: dd/mm/aaaa
			  var sep1 = parseInt(val.indexOf("/"));
			  var sep2 = parseInt(val.indexOf("/",sep1+1));
			  var len  = parseInt(val.length);
			  
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
			
			function selecionaCurso(){
				// Se o curso é AMERF, redireciona para o site do AMERF
			//	if (document.f1.curso4.checked){ document.location = "http://cursosnavi.ea.ufrgs.br/amerf"; }
				var B1= (document.f1.curso8.checked);
				var B2= (document.f1.curso9.checked);
				var B3= (document.f1.curso10.checked);
				var B4= (document.f1.curso11.checked);
				if (!((!B1 && !B2 && !B3 && !B4) || (B2 && B3 && B4) || (B1 && B3 && B4) || (B1 && B2 && B4) || (B2 && B3 && B1) )){
					f1.PAGAMENTO_CONDICAO.VISTA.checked = true;
					f1.PAGAMENTO_CONDICAO.PARCELADO.disabled = true;
					f1.PAGAMENTO_PARCELA.disabled = true;
				} else {
					f1.PAGAMENTO_CONDICAO.PARCELADO.disabled = false;
					f1.PAGAMENTO_PARCELA.disabled = false;
				}
			}//selecionaCurso

		</script>
	</head>
	

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<?php

include_once("../funcoes_bd.php");
//include_once("tabela_topo.php");

?>

<h4 align="center">Inscri&ccedil;&atilde;o:</h4>

<form name="f1" method="post" action="" onSubmit="return validaForm()">
	<table border="0" cellspacing="0" cellpadding="0" align="center" width="668">
	 	<tr> 
    		<td height="15" colspan="3"><b>Solicito a minha inscrição nos seguintes cursos:*</b></td>      
	    </tr>
		
		<tr> 
	    	
      <td height="15" colspan="3"> <br>
		  			<?php					
					$rsConCad = listaAcesso(1, "", "", "");

					if($rsConCad)
					while($linhaCad = mysql_fetch_array($rsConCad))
					{
						if ($linhaCad["ABREV_CURSO"] <> "GC")
							echo "<input type=\"checkbox\" name=\"curso\" value=\"" . $linhaCad["COD_CURSO"] . "\" onClick=\"selecionaCurso()\">&nbsp;" . $linhaCad["DESC_CURSO_ORIGEM"] . " - " . $linhaCad["ABREV_CURSO"] . " - " . $linhaCad["DESC_CURSO"] . "<br>";
					 }																				
					 ?>
				 <br><br><br><br>
			</td>      
		</tr>
		<tr> 
      		<td width="160" height="15"><b>Nome Completo* </b></td>
		    <td width="310">&nbsp;</td>
		    <td width="334">&nbsp;</td>
		</tr>
		
		<tr> 
      		<td colspan="3" height="28"> 
        		<input type="text" name="NOME_PESSOA" class="input1">
      		</td>
    	</tr>

		<tr> 
      		<td height="15" width="200"><b>Data de Nascimento* <br> (dd/mm/aaaa)</b>
			</td>
      		<td><b>Sexo</b></td>
      		<td width="334"><b>Documento de Identifica&ccedil;&atilde;o (RG)* </b></td>
    	</tr>

	    <tr> 
      		<td height="28" width="160"> 
        		<input type="text" name="DATA_NASC_PESSOA" class="input3">
      		</td>
      		<td> 
        		<select name="COD_SEXO" class="input3">
          			<option value=""></option>
					<?php
					$rsConCad = sexo("");
					
					if ($rsConCad)
						while ($linhaCad = mysql_fetch_array($rsConCad))
							echo "<option value='" . $linhaCad["COD_SEXO"] . "' >" . $linhaCad["DESC_SEXO"] . "</option>";
					?>
				</select>
			</td>
			
			<td width="334"> 
        		<input type="text" name="DOC_ID_PESSOA" class="input2">
		    </td>
		</tr>

    <tr> 
      <td height="15" width="160" colspan=2><b>E-mail*</b></td>
      <td width="334"><b>CPF (apenas n&uacute;meros, sem barras ou tra&ccedil;os)* 
        </b></td>
    </tr>
    <tr> 
      <td colspan="2" height="28"> 
        <input type="text" name="EMAIL_PESSOA" class="input3">
      </td>
      <td width="334"> 
        <input type="text" name="CPF_PESSOA" class="input2">
      </td>
    </tr>
    <tr> 
      <td height="15" colspan="3">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2" height="15"><b>Nome de Usu&aacute;rio ou apelido (somente 
        letras minúsculas)*</b></td>
      <td width="334">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top" height="28" colspan="2"> 
        <input type="text" class="input2" name="USER_PESSOA" onKeyUp="confereUser(event)">
      </td>
      <td valign="top" width="334">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2" height="15"><b>Digite uma senha*</b></td>
      <td width="334"><b>Confirme a sua senha* </b></td>
    </tr>
    <tr> 
      <td colspan="2" height="15"> 
        <input type="password" name="SENHA_PESSOA" class="input3">
      </td>
      <td width="334"> 
        <input type="password" name="SENHA_PESSOA2" class="input3">
      </td>
    </tr>
    <tr> 
      <td colspan="3" height="15"><b>Digite uma frase para lembrar da sua senha</b> 
      </td>
    </tr>
    <tr> 
      <td colspan="3" height="15"> 
        <input type="text" name="FRASE_SENHA_PESSOA" class="input1">
      </td>
    </tr>
    <tr> 
      <td colspan="2" height="40"> 
	  	<b>Endereço</b><br>
        <input type="text" name="DESC_END" class="input2">
      </td>
      <td width="334"> 
	  	<b>Bairro</b><br>
        <input type="text" name="BAIRRO_END " class="input2">
      </td>
    </tr>
    <tr> 
      <td colspan="2" height="40"> 
	  	<b>Cidade</b><br>
        <input type="text" name="CIDADE_END" class="input2">
      </td>
      <td width="334"> 
	  <table  border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
	  	<tr><td>
	  	<b>Estado</b><br>
        <input type="text" name="UF_END" class="input3">
		</td><td>
	  	<b>País</b><br>
        <input type="text" name="PAIS_END" class="input3">
		</td></tr>
	  </table>
      </td>
    </tr>
    <tr> 
      <td colspan="3" height="40"> 
	  	<b>CEP</b><br>
        <input type="text" name="CEP_END" class="input2">
      </td>
    </tr>
    <tr> 
      <td height="30" colspan="3" valign="bottom"> 
        <b>Informa&ccedil;&otilde;es sobre Telefone</b><br>
      </td>
    </tr>
    <tr> 
      <td height="40"> 
	  	Codigo Internacional<br>
        <input type="text" name="COD_INTERNAC_FONE" class="input3">
      </td>
      <td> 
	  	Codigo de Area<br>
        <input type="text" name="COD_AREA_FONE" class="input3">
      </td>
      <td width="334"> 
	  <table  border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
	  	<tr><td>
	  	Telefone<br>
        <input type="text" name="NRO_FONE" class="input3">
		</td><td>
	  	Ramal<br>
        <input type="text" name="RAMAL_FONE" class="input3">
		</td></tr>
	  </table>
      </td>
    </tr>
	<!--
    <tr> 
      <td height="179" colspan="3"> 
        <p> <br>
          <b>Nome do pai<br>
          </b> 
          <input type="text" name="NOME_PAI" class="input1">
          <br>
          <br>
          <b>Nome da m&atilde;e</b><br>
          <input type="text" name="NOME_MAE" class="input1">
          <br>
        </p>
      </td>
    </tr>
    <tr> 
      <td height="39" colspan="2"><br>
        <b>&Oacute;rg&atilde;o emissor do RG</b><br>
        <input type="text" name="RG_ORGAO" class="input2">
        <br>
        <br>
      </td>
      <td height="39"><br>
        <b>Data de emiss&atilde;o do RG (dd/mm/aaaa)</b><br>
        <input type="text" name="RG_DATA_EMISSAO" class="input2">
        <br>
      </td>
    </tr>
    <tr> 
      <td height="37" colspan="2"><br>
        <b>Nacionalidade</b><br>
        <input type="text" name="NACIONALIDADE" class="input2">
        <br>
        <br>
      </td>
      <td height="37"><br>
        <b>Naturalidade<br>
        </b> 
        <input type="text" name="NATURALIDADE" class="input2">
        <br>
      </td>
    </tr>
    <tr> 
      <td height="18" colspan="3"><br>
        <b>Estado civil<br>
        </b> 
        <input type="text" name="ESTADO_CIVIL">        
        <br>
        <br>
        <b>T&iacute;tulo de eleitor n&ordm; :</b> <br>
        <input type="text" name="TITULO_ELEITOR" class="input2">
        <br>
        <br>
        <b>Registro profissional:</b> <br>
        <input type="text" name="REG_PROF" class="input2">
        <br>
        <br>
      </td>
    </tr>
	-->
    <tr> 
      <td height="70" colspan="3" valign="bottom"> 
        <p><b>Informa&ccedil;&otilde;es sobre pagamento<br>
          <br>
          </b></p>
      </td>
    </tr>
    <tr> 
      <td height="8" width="160"><b>Forma</b>: <br>
        <br>
        <br>
        <br>
      </td>
      <td height="7" width="310" colspan=2> 
        <input type="radio" name="PAGAMENTO_FORMA" value="Via Depósito." checked>
        <b>Via Depósito</b><br>
        <br>
        <input type="radio" name="PAGAMENTO_FORMA" value="Via DOC.">
        <b>Via DOC<br>
        <br>
        </b></td>
    </tr>
    <tr> 
      <td height="8" width="160"><b>Condição</b>: <br>
        <br>
        <br>
        <br>
      </td>
      <td height="7" width="310"  colspan=2> 
        <input type="radio" name="PAGAMENTO_CONDICAO" id="VISTA" value="&Agrave; vista." checked>
        <b>&Agrave; vista.</b><br>
        <br>
        <input type="radio" name="PAGAMENTO_CONDICAO" id="PARCELADO" value="Parcelado.">
        <b>Parcelado.&nbsp;&nbsp;
		       		<select name="PAGAMENTO_PARCELA">
						<?php
						for ($i=1;$i<=2;$i++)
						echo "<option value=". $i .">" . $i . "</option>";
						?>
					</select>

		<br>
        <br>
        </b></td>
    </tr>
    <tr valign="top"> 
      <td height="8" width="160"> <b>Respons&aacute;vel:</b></td>
      <td height="8" width="310"  colspan=2> 
        <input type="radio" name="PAGAMENTO_RESPONSAVEL" value="Participante" checked>
        <b>Pr&oacute;prio participante.</b> <br>
        <br>
      </td>
    </tr>
    <tr valign="top"> 
      <td height="8" width="160">&nbsp; </td>
      <td height="8" width="310"  colspan=2> 
        <input type="radio" name="PAGAMENTO_RESPONSAVEL" value="Institui&ccedil;&atilde;o">
        <b>Institui&ccedil;&atilde;o de atua&ccedil;&atilde;o. </b><br>
        <br>
        <b>CNPJ n&ordm;: </b> (caso seja pago por institui&ccedil;&atilde;o)<br>
        <input type="text" name="PAGAMENTO_RESP_CNPJ" class="input2">
        <br>
        <b>Raz&atilde;o social: </b> (caso seja pago por institui&ccedil;&atilde;o)<br>
        <input type="text" name="PAGAMENTO_RESP_RAZAO_SOC" class="input2">
        <br>
        <b>Nome do respons&aacute;vel:</b> (caso seja pago por institui&ccedil;&atilde;o)<br>
        <input type="text" name="PAGAMENTO_RESP_NOME " class="input2">
        <br>
        <br>
      </td>
    </tr>
	<!--
    <tr> 
      <td height="15" colspan="3"><b>Forma&ccedil;&atilde;o (incluindo cursos 
        e datas de in&iacute;cio/conclus&atilde;o):</b><br>
        <br>
        <b>Gradua&ccedil;&atilde;o:</b> <br>
        <textarea name="FORMACAO_GRAD" class="input1" cols="100" rows="2"></textarea>
        <br>
        <br>
        <b>Especializa&ccedil;&atilde;o:</b> <br>
        <textarea name="FORMACAO_ESP" class="input1" cols="100" rows="2"></textarea>
        <br>
        <br>
        <b>Mestrado:</b> <br>
        <textarea name="FORMACAO_MESTR" class="input1" cols="100" rows="2"></textarea>
        <br>
        <br>
        <b>Doutorado:</b> <br>
        <textarea name="FORMACAO_DOUT" class="input1" cols="100" rows="2"></textarea>
        <br>
        <br>
        <br>
        <b>Experi&ecirc;ncia profissional (incluindo cargos, munic&iacute;pios 
        e institui&ccedil;&otilde;es):</b> <br>
        <textarea name="EXPERIENCIA" cols="100" rows="5"></textarea>
        <br>
        <br>
        <b>Por que voc&ecirc; est&aacute; se candidatando a este curso?</b> <br>
        <textarea name="MOTIVACAO" cols="100" rows="5"></textarea>
        <br>
        <br>
      </td>
    </tr>
	-->
    <tr> 
      <td height="15" colspan="3"> 
        <div align="center"> 
          <input type="button" name="OK" value="OK" class="input3" onclick="javascript:enviaForm()">
          <input type="button" name="CANCELAR" value="CANCELAR" class="input3" onClick="javascript:history.back()">
          <br>
          <br>
          <br>
          <b>* campos obrigat&oacute;rios<br>
          <br>
          </b></div>
      </td>
    </tr>
  </table>
  <input type="hidden" name="DATA_CADASTRO_PESSOA" value="<%=now%>">
  <input type="hidden" name="COD_PESSOA" value="">
  <input type="hidden" name="NRO_ITEM_END">
  <input type="hidden" name="NRO_ITEM_FONE">
  <input type="hidden" name="DATA_NASC_PESSOA_MODIFICADA" value="">     
</form></body>
</html>
