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
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//include_once("../funcoes_bd.php");
include_once ("../config.php");
include_once ($caminhoBiblioteca."/cadastro.inc.php");
session_name(SESSION_NAME); session_start(); security();

// precisa verificar se ja exite alguem com o mesmo nome de usuario, criar a pessoa como aluno..
?>

<html>
	<head>
		<title>Alunos</title>
		<link rel="stylesheet" href="../cursos.css" type="text/css">
		<link rel="stylesheet" href="../cadastro/sca.css" type="text/css">
		<script language="JavaScript">
			function validaForm()
			{
				var msg = "";
				var obj = "";
				var preen = true;

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
				 	document.f1.DATA_NASC_PESSOA_MODIFICADA.value = data.charAt(6)+data.charAt(7)+data.charAt(8)+data.charAt(9)+"-"+data.charAt(3)+data.charAt(4)+"-"+data.charAt(0)+data.charAt(1);
				 }

				// Valida sexo - radio
				if ((document.f1.COD_SEXO[document.f1.COD_SEXO.selectedIndex].value == null) || (document.f1.COD_SEXO[document.f1.COD_SEXO.selectedIndex].value == ""))
				{
					msg += "=> Sexo não selecionado;\n";
				 }
			function enviaForm()
			{
				if(validaForm())
				{
					document.f1.action = "./aluno_envio.php";
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
			}//selecionaCurso

		</script>
	</head>
<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<center><br>
  <table cellpadding="10" cellspacing="0" border="0" width="85%"  align="center">
    <tr> 
      <td colspan=2>
        <center>
          <font size="4"><b>Alunos</b></font> 
        </center>
      </td>
    </tr>
    <tr> 
      <td  align=left>&nbsp;</td>
      <td align=right><a href="./../ferramentas.php" target="_parent">Ferramentas 
        de Gerência</a> - <a href="javascript:history.back()">Voltar</a></td>
    </tr>
    <tr> 
      <td colspan=2>
        <form name="form1" method="post" action="./aluno_envio.php">
          <table width="75%" border="0" cellspacing="0" align=center>
            <tr> 
              <td colspan=4><b>Nome Completo: </b></td>
            </tr>
            <tr> 
              <td colspan=4><b> 
                <input type="text" class="input1" name="NOME_PESSOA">
                </b></td>
            </tr>
            <tr> 
              <td colspan=4> 
                <table width="100%" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td width="36%"><b>Data de Nascimento:</b></td>
                    <td width="30%"><b>Sexo:</b></td>
                    <td width="34%"><b>Documento de Identidade:</b></td>
                  </tr>
                  <tr> 
                    <td width="36%"><b> 
                      <input type="text" name="DATA_NASC_PESSOA" class="input3">
                      </b></td>
                    <td width="30%"><b> 
                      <select name="COD_SEXO" class="input3">
					  <option value="">sexo</option>
					  <?php
					  	$rsConSexo = sexo();
						
						if ( $rsConSexo )
						while ( $linhaSexo = mysql_fetch_array($rsConSexo) )
							echo "<option value='" . $linhaSexo["COD_SEXO"]. "'>". $linhaSexo["DESC_SEXO"]."</option>";
					  ?>
                      </select>
                      </b></td>
                    <td width="34%"><b> 
                      <input type="text" name="DOC_ID_PESSOA" class="input3">
                      </b></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr> 
              <td colspan=2><b>E-mail:</b></td>
              <td colspan=2 width="44%"><b>CPF:</b></td>
            </tr>
            <tr> 
              <td colspan=2> <b> 
                <input type="text" class="input2" name="EMAIL_PESSOA">
                </b></td>
              <td colspan=2 width="44%"> <b> 
                <input type="text" class="input2" name="CPF_PESSOA">
                </b></td>
            </tr>
            <tr> 
              <td colspan=2><b>Usu&aacute;rio:</b></td>
              <td width="44%" colspan=2><b>Senha:</b></td>
            </tr>
            <tr> 
              <td colspan=2> <b> 
                <input type="text" class="input2" name="USER_PESSOA" onKeyUp="confereUser(event)">
                </b></td>
              <td width="44%" colspan=2> <b> 
                <input type="password" name="SENHA_PESSOA" class="input2">
                </b></td>
            </tr>
			<tr> 
              <td colspan=2></td>
              <td width="44%" colspan=2><b>Repetir Senha:</b></td>
            </tr>
            <tr> 
              <td colspan=2>
              </td>
              <td width="44%" colspan=2> <b> 
                <input type="password" name="SENHA_PESSOA2" class="input2">
                </b></td>
            </tr>
            <tr> 
              <td colspan=4><b>Frase para lembrar senha </b></td>
            </tr>
            <tr> 
              <td colspan=4> <b> 
                <input type="text"  class="input1" name="FRASE_SENHA_PESSOA">
                </b></td>
            </tr>
          </table>
          <p align="center"> 
            <input type="submit" name="Submit" class="input3" value="Criar Aluno" onclick="javascript:enviaForm()">
          </p>
        </form>
        <center>
        </center>
      </td>
    </tr>
  </table>
</center>
</body>
</html>

