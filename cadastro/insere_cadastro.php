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



//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once ($caminhoBiblioteca."/cadastro.inc.php");
session_name(SESSION_NAME); session_start(); security();

$rsCon = pessoa($_REQUEST["USER_PESSOA"]);

if ( ($rsCon) and (mysql_num_rows($rsCon) > 0) )
{
	echo "<html><body><script>alert('O nome de usu�rio que voc� escolheu j� existe em nosso banco de dados. \\n Por favor escolha outro.'); history.back();</script><body><html>";
	exit();
}

if (! isset($_REQUEST["USER_PESSOA"]) )
	$_REQUEST["USER_PESSOA"] = "";
if (! isset($_REQUEST["NOME_PESSOA"]) )
	$_REQUEST["NOME_PESSOA"] = "";
if (! isset($_REQUEST["DATA_NASC_PESSOA_MODIFICADA"]) )
	$_REQUEST["DATA_NASC_PESSOA_MODIFICADA"] = "";
if (! isset($_REQUEST["COD_SEXO"]) )	
	$_REQUEST["COD_SEXO"] = "";
if (! isset($_REQUEST["DOC_ID_PESSOA"]) )	
	$_REQUEST["DOC_ID_PESSOA"] = "";
if (! isset($_REQUEST["EMAIL_PESSOA"]) )
	$_REQUEST["EMAIL_PESSOA"] = "";
if (! isset($_REQUEST["CPF_PESSOA"]) )
	$_REQUEST["CPF_PESSOA"] = "";
if (! isset($_REQUEST["SENHA_PESSOA"]) )
	$_REQUEST["SENHA_PESSOA"] = "";
if (! isset($_REQUEST["FRASE_SENHA_PESSOA"]) )
	$_REQUEST["FRASE_SENHA_PESSOA"] = "";
if (! isset($_REQUEST["DEC_END"]) )
	$_REQUEST["DEC_END"] = "";
if (! isset($_REQUEST["BAIRRO_END"]) )
	$_REQUEST["BAIRRO_END"] = "";
if (! isset($_REQUEST["CIDADE_END"]) )
	$_REQUEST["CIDADE_END"] = "";
if (! isset($_REQUEST["UF_END"]) )
	$_REQUEST["UF_END"] = "";
if (! isset($_REQUEST["PAIS_END"]) )
	$_REQUEST["PAIS_END"] = "";
if (! isset($_REQUEST["CEP_END"]) )
	$_REQUEST["CEP_END"] = "";
if (! isset($_REQUEST["COD_INTERNAC_FONE"]) )
	$_REQUEST["COD_INTERNAC_FONE"] = "";
if (! isset($_REQUEST["COD_AREA_FONE"]) )
	$_REQUEST["COD_AREA_FONE"] = "";
if (! isset($_REQUEST["NRO_FONE"]) )
	$_REQUEST["NRO_FONE"] = "";
if (! isset($_REQUEST["RAMAL_FONE"]) )
	$_REQUEST["RAMAL_FONE"] = "";


if (! isset($_REQUEST["NOME_PAI"]) )
	$_REQUEST["NOME_PAI"] = "";
if (! isset($_REQUEST["NOME_MAE"]) )
	$_REQUEST["NOME_MAE"] = "";
if (! isset($_REQUEST["RG_ORGAO"]) )
	$_REQUEST["RG_ORGAO"] = "";
if (! isset($_REQUEST["RG_DATA_EMISSAO"]) )
	$_REQUEST["RG_DATA_EMISSAO"] = "";
if (! isset($_REQUEST["ESTADO_CIVIL"]) )
	$_REQUEST["ESTADO_CIVIL"] = "";
if (! isset($_REQUEST["NACIONALIDADE"]) )
	$_REQUEST["NACIONALIDADE"] = "";
if (! isset($_REQUEST["NATURALIDADE"]) )
	$_REQUEST["NATURALIDADE"] = "";
if (! isset($_REQUEST["TITULO_ELEITOR"]) )
	$_REQUEST["TITULO_ELEITOR"] = "";
if (! isset($_REQUEST["REG_PROF"]) )
	$_REQUEST["REG_PROF"] = "";
if (! isset($_REQUEST["PAGAMENTO_FORMA"]) )
	$_REQUEST["PAGAMENTO_FORMA"] = "";
if (! isset($_REQUEST["PAGAMENTO_CONDICAO"]) )
	$_REQUEST["PAGAMENTO_CONDICAO"] = "";
if (! isset($_REQUEST["PAGAMENTO_RESPONSAVEL"]) )
	$_REQUEST["PAGAMENTO_RESPONSAVEL"] = "";
if (! isset($_REQUEST["PAGAMENTO_RESP_NOME"]) )
	$_REQUEST["PAGAMENTO_RESP_NOME"] = "";
if (! isset($_REQUEST["PAGAMENTO_RESP_CNPJ"]) )
	$_REQUEST["PAGAMENTO_RESP_CNPJ"] = "";
if (! isset($_REQUEST["PAGAMENTO_RESP_RAZAO_SOC"]) )
	$_REQUEST["PAGAMENTO_RESP_RAZAO_SOC"] = "";
if (! isset($_REQUEST["FORMACAO_GRAD"]) )
	$_REQUEST["FORMACAO_GRAD"] = "";
if (! isset($_REQUEST["FORMACAO_ESP"]) )
	$_REQUEST["FORMACAO_ESP"] = "";
if (! isset($_REQUEST["FORMACAO_MESTR"]) )
	$_REQUEST["FORMACAO_MESTR"] = "";
if (! isset($_REQUEST["FORMACAO_DOUT"]) )
	$_REQUEST["FORMACAO_DOUT"] = "";
if (! isset($_REQUEST["EXPERIENCIA"]) )
	$_REQUEST["EXPERIENCIA"] = "";
if (! isset($_REQUEST["MOTIVACAO"]) )
	$_REQUEST["MOTIVACAO"] = "";


	$ok = cadastro($_REQUEST["USER_PESSOA"], $_REQUEST["NOME_PESSOA"], $_REQUEST["DATA_NASC_PESSOA_MODIFICADA"], $_REQUEST["COD_SEXO"], $_REQUEST["DOC_ID_PESSOA"], $_REQUEST["EMAIL_PESSOA"], $_REQUEST["CPF_PESSOA"], $_REQUEST["SENHA_PESSOA"], $_REQUEST["FRASE_SENHA_PESSOA"], $_REQUEST["DEC_END"], $_REQUEST["BAIRRO_END"], $_REQUEST["CIDADE_END"], $_REQUEST["UF_END"], $_REQUEST["PAIS_END"], $_REQUEST["CEP_END"], $_REQUEST["COD_INTERNAC_FONE"], $_REQUEST["COD_AREA_FONE"], $_REQUEST["NRO_FONE"], $_REQUEST["RAMAL_FONE"]);

	$rsConCad = listaAcesso(1, "", "", "");	
	if ($rsConCad)
		while($linhaCad = mysql_fetch_array($rsConCad))
		if(!isset($_REQUEST["curso" . $linhaCad["COD_CURSO"]]))
		{
			$_REQUEST["curso" . $linhaCad["COD_CURSO"]]="";
			
			if ($_REQUEST["curso" . $linhaCad["COD_CURSO"]] != "" )
				cadastro_solicit ($_REQUEST["USER_PESSOA"],$linhaCad["COD_CURSO"]);
		}
if ( ( $_REQUEST["NOME_PAI"] <> "") or ( $_REQUEST["NOME_MAE"] <> "") or ( $_REQUEST["RG_ORGAO"] <> "") or ( $_REQUEST["RG_DATA_EMISSAO"] <> "") or ( $_REQUEST["ESTADO_CIVIL"] <> "") or ( $_REQUEST["NACIONALIDADE"] <> "") or ( $_REQUEST["NATURALIDADE"] <> "") or ( $_REQUEST["TITULO_ELEITOR"] <> "") or ( $_REQUEST["REG_PROF"] <> "") or ( $_REQUEST["PAGAMENTO_FORMA"] <> "") or ( $_REQUEST["PAGAMENTO_CONDICAO"] <> "") or
     ( $_REQUEST["PAGAMENTO_RESPONSAVEL"] <> "") or ( $_REQUEST["PAGAMENTO_RESP_NOME"] <> "") or ( $_REQUEST["PAGAMENTO_RESP_CNPJ"] <> "") or ( $_REQUEST["PAGAMENTO_RESP_RAZAO_SOC"] <> "") or ( $_REQUEST["FORMACAO_GRAD"] <> "") or ( $_REQUEST["FORMACAO_ESP"] <> "") or ( $_REQUEST["FORMACAO_MESTR"] <> "") or ( $_REQUEST["FORMACAO_DOUT"] <> "") or ( $_REQUEST["EXPERIENCIA"] <> "") or ( $_REQUEST["MOTIVACAO"]<> "") )
{	 
	$ok = cadastro_espec($_REQUEST["NOME_PAI"], $_REQUEST["NOME_MAE"], $_REQUEST["RG_ORGAO"], $_REQUEST["RG_DATA_EMISSAO"], $_REQUEST["ESTADO_CIVIL"], $_REQUEST["NACIONALIDADE"], $_REQUEST["NATURALIDADE"], $_REQUEST["TITULO_ELEITOR"], $_REQUEST["REG_PROF"], $_REQUEST["PAGAMENTO_FORMA"], $_REQUEST["PAGAMENTO_CONDICAO"], $_REQUEST["PAGAMENTO_PARCELA"], 
		  $_REQUEST["PAGAMENTO_RESPONSAVEL"], $_REQUEST["PAGAMENTO_RESP_NOME"], $_REQUEST["PAGAMENTO_RESP_CNPJ"], $_REQUEST["PAGAMENTO_RESP_RAZAO_SOC"], $_REQUEST["FORMACAO_GRAD"], $_REQUEST["FORMACAO_ESP"], $_REQUEST["FORMACAO_MESTR"], $_REQUEST["FORMACAO_DOUT"], $_REQUEST["EXPERIENCIA"], $_REQUEST["MOTIVACAO"], $_REQUEST["USER_PESSOA"] );
}


//primeiro mail -- enviado para o aluno avisando que recebemos a inscri��o

//	$body = "Escola de Administra��o - Universidade Federal do Rio Grande do Sul "."\n \n" .
//	  "Caro(a) Sr(a). " . $_REQUEST["NOME_PESSOA"] . "\n \n" .
//	  "Comunicamos o recebimento de suas informa��es para o cadastro em nosso sistema"." \n" .
//	  "Ap�s pagamento da taxa de inscri��o, voc� receber� um nome de usu�rio e senha" .
//	  " que possibilitar� o acesso ao curso. Estamos � disposi��o para solucionar problemas ou "  .
//	  "dirimir eventuais d�vidas, atrav�s do mail navi@ea.ufrgs.br, ou pelo telefone (0xx51) 3316 3699". "\n \n" .
//	  "Atenciosamente, "."\n" .
//	  "Equipe NAVi - Escola de Administra��o da UFRGS ". "\n" .
//	  "Universidade Federal do Rio Grande do Sul". "\n" .
//	  "F:3316 3699 E-mail:navi@ea.ufrgs.br"."\n".
//	  "http://navi.adm.ufrgs.br";

//
//    body = "Escola de Administra��o - Universidade Federal do Rio Grande do Sul" & vbCrLf & vbCrLf &_
//		   "Caro(a) Sr(a). ." & Request("NOME_PESSOA") & vbCrLf & vbCrLf &_
//	       "Comunicamos o recebimento de suas informa��es para o cadastro em nosso sistema. Aguarde, senha de acesso, ap�s processo de sele��o "&_
//	      
//	       "  Estamos � disposi��o para solucionar problemas ou "  &_
//	       "dirimir eventuais d�vidas, atrav�s do mail navi@ea.ufrgs.br, ou pelo telefone (0xx51) 3316 3699." & vbCrLf & vbCrLf &_
//          
//      "Atenciosamente," &vbCrLf &_
//      "Equipe NAVi - Escola de Administra��o da UFRGS" & vbCrLf &_
//		"Universidade Federal do Rio Grande do Sul" & vbcrlf &_
//		"F:3316 3699 E-mail:navi@ea.ufrgs.br" & vbcrlf &_
//		"http://navi.adm.ufrgs.br"

// modificado temporariamente at� ser reformulado para o "GU"
// Retirado da msg antiga por tiago:	  
//		"sistema. Ap�s pagamento da taxa de inscri��o,voc� receber� um nome de" &_
//		"TAXA DE INSCRI��O"& vbCrLf &_
//		"R$ 250 (duzentos e cinq�enta reais), cada curso de 30 horas/aula." & vbCrLf & vbCrLf &_
//		"O pagamento dever� ser realizado via dep�sito, na Conta corrente n�:" &_
//		"300.000-1 - Ag�ncia: 3798-2 - Banco do Brasil - C�digo identificador:" &_
//		"1492-3 - em nome de FAURGS. CNPJ: 74.704.008/0001-75; enviar o" &_
//		"comprovante do pagamento, devidamente identificado, para o fax" &_
//		"3316-3286." & vbCrLf & vbCrLf &_
//		"PER�ODO DO CURSO: Dura��o de 45 dias a contar do recebimento da senha de acesso " & vbCrLf & vbCrLf &_

$rsConCad = listaAcesso(1, "", "", "");	
	if ($rsConCad)
		while($linhaCad = mysql_fetch_array($rsConCad))
			if ($_REQUEST["curso" . $linhaCad["COD_CURSO"]] != "" )
				{
					$body =  "Escola de Administra��o - Universidade Federal do Rio Grande do Sul \n \n" .
							"Caro(a) Sr(a). " . $_REQUEST["NOME_PESSOA"] . " \n \n" .
							"Comunicamos o recebimento de suas informa��es para cadastro em nosso " .
							"sistema.\n".
							// Ap�s pagamento da taxa de inscri��o, voc� receber� um nome de " .
							//"usu�rio e senha que possibilitar� o acesso ao curso: " . $linhaCad["DESC_CURSO"] ." \n".
							//"TAXA DE INSCRI��O\n".
							//"R$". $linhaCad["VALOR"].", dura��o do curso de ". $linhaCad["HORAS_AULA"] ." horas/aula.\n\n" .
							//"O pagamento dever� ser realizado via dep�sito, na Conta corrente n�:" .
							//"300.000-1 - Ag�ncia: 3798-2 - Banco do Brasil - C�digo identificador:" .
							//"1492-3 - em nome de FAURGS. CNPJ: 74.704.008/0001-75; enviar o" .
							//"comprovante do pagamento, devidamente identificado, para o fax" .
							//"3316-3286.\n\n" .
						      $linhaCad["COMENTARIO_CURSO"]."\n\n".
							
							" Estamos � " .
							"disposi��o para solucionar problemas ou dirimir eventuais d�vidas, " .
							"atrav�s do mail navi@ea.ufrgs.br, ou pelo telefone (0xx51) 3316 3699. \n \n" .
							"Atenciosamente, \n" .
							"Equipe NAVi - Escola de Administra��o da UFRGS \n" .
							"Universidade Federal do Rio Grande do Sul \n " .
							"F:3316 3699 E-mail:navi@ea.ufrgs.br \n ".
							"http://navi.adm.ufrgs.br";

					mail ( 	$_REQUEST["EMAIL_PESSOA"],
							"Seu cadastro foi feito com sucesso!", 
							$body, 
							"Bcc: navi@ea.ufrgs.br\r\n" . 
							"From: navi@adm.ufrgs.br\r\n" . 
							"Reply-To: navi@adm.ufrgs.br\r\n" . 
							"X-Mailer: PHP/". phpversion());
				}
//			"Bcc: navi@adm.ufrgs.br\r\n" . 

//segundo mail -- enviado para o navi com os dados do novo aluno

	$body = "Escola de Administra��o - Universidade Federal do Rio Grande do Sul"."\n\n" . 
		  "Novo usu�rio cadastrado com os seguintes campos:"."\n\n" . 
		  "Usu�rio: "					  . $_REQUEST["USER_PESSOA"] . "\n" .
		  "Nome: "						  . $_REQUEST["NOME_PESSOA"] . "\n" .
		  "Email: "				 		  . $_REQUEST["EMAIL_PESSOA"] . "\n" .
		  "Forma de Pagamento: " 		  . $_REQUEST["PAGAMENTO_FORMA"] . "\n" .
		  "Condi��o de Pagamento: " 	  . $_REQUEST["PAGAMENTO_CONDICAO"] . "\n" .
		  "Parcelas de Pagamento: "		  . $_REQUEST["PAGAMENTO_PARCELA"] . "\n" .
		  "Respons�vel de Pagamento: "	  . $_REQUEST["PAGAMENTO_RESPONSAVEL"] . "\n" .
		  "Nome do Respons�vel: "		  . $_REQUEST["PAGAMENTO_RESP_NOME"] . "\n" .
		  "CNPJ do Respons�vel: "		  . $_REQUEST["PAGAMENTO_RESP_CNPJ"] . "\n" .
		  "Raz�o Social do Respons�vel: " . $_REQUEST["PAGAMENTO_RESP_RAZAO_SOC"] . "\n" .
		  "Endere�o: "					  . $_REQUEST["DESC_END"] . "\n" .
		  "Bairro: "					  . $_REQUEST["BAIRRO_END"] . "\n" .
		  "Cidade: "					  . $_REQUEST["CIDADE_END"] . "\n" .
		  "Estado: "					  . $_REQUEST["UF_END"] . "\n" .
		  "Pa�s: "						  . $_REQUEST["PAIS_END"] . "\n" .
		  "CEP: "						  . $_REQUEST["CEP_END"] . "\n" .
		  "Telefone: "					  . $_REQUEST["COD_INTERNAC_FONE"] . " "  . $_REQUEST["COD_AREA_FONE"] ." ". $_REQUEST["NRO_FONE"] . "\n" .
		  "Ramal: "						  . $_REQUEST["RAMAL_FONE"] . "\n" .
		  "Matriculado nos seguintes cursos:\n";
		  
		  	
	$rsConCad = listaAcesso(1, "", "", "");
	
	if ($rsConCad)
		while ($linhaCad = mysql_fetch_array($rsConCad))
		{
			if ($_REQUEST["curso" . $linhaCad["COD_CURSO"]] != "")
				$body = $body . " - " . $linhaCad["DESC_CURSO"] . "\n";
		}
	
	mail ( 	"navi@ea.ufrgs.br",
			"NOVO ALUNO CADASTRADO", 
			$body, 
			"From: navi@adm.ufrgs.br\r\n" . 
			"Reply-To: navi@adm.ufrgs.br\r\n" . 
			"X-Mailer: PHP/". phpversion());


?>

<html>
	<head>
		<title>Cadastro</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">


<?php
//include_once("tabela_topo.php");
?>

<p>&nbsp;</p>
<blockquote>
  <p>Bem-vindo(a) ao nosso ambiente, <?=$_REQUEST["NOME_PESSOA"]?>. </p>

  <p>Suas informa��es b�sicas j� est�o no nosso sistema.</p>

  <p>
   Estamos � disposi��o para solucionar problemas ou dirimir eventuais d�vidas, atrav�s do mail navi@ea.ufrgs.br, ou pelo telefone (0xx51) 3316 3699.
 
  </p>
  <p>
	<a href="./../principal.php">Voltar</a>
  </p> 		  

</blockquote>

</body>

</html>

