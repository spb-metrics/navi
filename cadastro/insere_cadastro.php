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
include_once("../config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once ($caminhoBiblioteca."/cadastro.inc.php");
session_name(SESSION_NAME); session_start(); security();

$rsCon = pessoa($_REQUEST["USER_PESSOA"]);

if ( ($rsCon) and (mysql_num_rows($rsCon) > 0) )
{
	echo "<html><body><script>alert('O nome de usuário que você escolheu já existe em nosso banco de dados. \\n Por favor escolha outro.'); history.back();</script><body><html>";
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


//primeiro mail -- enviado para o aluno avisando que recebemos a inscrição

//	$body = "Escola de Administração - Universidade Federal do Rio Grande do Sul "."\n \n" .
//	  "Caro(a) Sr(a). " . $_REQUEST["NOME_PESSOA"] . "\n \n" .
//	  "Comunicamos o recebimento de suas informações para o cadastro em nosso sistema"." \n" .
//	  "Após pagamento da taxa de inscrição, você receberá um nome de usuário e senha" .
//	  " que possibilitará o acesso ao curso. Estamos à disposição para solucionar problemas ou "  .
//	  "dirimir eventuais dúvidas, através do mail navi@ea.ufrgs.br, ou pelo telefone (0xx51) 3316 3699". "\n \n" .
//	  "Atenciosamente, "."\n" .
//	  "Equipe NAVi - Escola de Administração da UFRGS ". "\n" .
//	  "Universidade Federal do Rio Grande do Sul". "\n" .
//	  "F:3316 3699 E-mail:navi@ea.ufrgs.br"."\n".
//	  "http://navi.adm.ufrgs.br";

//
//    body = "Escola de Administração - Universidade Federal do Rio Grande do Sul" & vbCrLf & vbCrLf &_
//		   "Caro(a) Sr(a). ." & Request("NOME_PESSOA") & vbCrLf & vbCrLf &_
//	       "Comunicamos o recebimento de suas informações para o cadastro em nosso sistema. Aguarde, senha de acesso, após processo de seleção "&_
//	      
//	       "  Estamos à disposição para solucionar problemas ou "  &_
//	       "dirimir eventuais dúvidas, através do mail navi@ea.ufrgs.br, ou pelo telefone (0xx51) 3316 3699." & vbCrLf & vbCrLf &_
//          
//      "Atenciosamente," &vbCrLf &_
//      "Equipe NAVi - Escola de Administração da UFRGS" & vbCrLf &_
//		"Universidade Federal do Rio Grande do Sul" & vbcrlf &_
//		"F:3316 3699 E-mail:navi@ea.ufrgs.br" & vbcrlf &_
//		"http://navi.adm.ufrgs.br"

// modificado temporariamente até ser reformulado para o "GU"
// Retirado da msg antiga por tiago:	  
//		"sistema. Após pagamento da taxa de inscrição,você receberá um nome de" &_
//		"TAXA DE INSCRIÇÃO"& vbCrLf &_
//		"R$ 250 (duzentos e cinqüenta reais), cada curso de 30 horas/aula." & vbCrLf & vbCrLf &_
//		"O pagamento deverá ser realizado via depósito, na Conta corrente nº:" &_
//		"300.000-1 - Agência: 3798-2 - Banco do Brasil - Código identificador:" &_
//		"1492-3 - em nome de FAURGS. CNPJ: 74.704.008/0001-75; enviar o" &_
//		"comprovante do pagamento, devidamente identificado, para o fax" &_
//		"3316-3286." & vbCrLf & vbCrLf &_
//		"PERÍODO DO CURSO: Duração de 45 dias a contar do recebimento da senha de acesso " & vbCrLf & vbCrLf &_

$rsConCad = listaAcesso(1, "", "", "");	
	if ($rsConCad)
		while($linhaCad = mysql_fetch_array($rsConCad))
			if ($_REQUEST["curso" . $linhaCad["COD_CURSO"]] != "" )
				{
					$body =  "Escola de Administração - Universidade Federal do Rio Grande do Sul \n \n" .
							"Caro(a) Sr(a). " . $_REQUEST["NOME_PESSOA"] . " \n \n" .
							"Comunicamos o recebimento de suas informações para cadastro em nosso " .
							"sistema.\n".
							// Após pagamento da taxa de inscrição, você receberá um nome de " .
							//"usuário e senha que possibilitará o acesso ao curso: " . $linhaCad["DESC_CURSO"] ." \n".
							//"TAXA DE INSCRIÇÃO\n".
							//"R$". $linhaCad["VALOR"].", duração do curso de ". $linhaCad["HORAS_AULA"] ." horas/aula.\n\n" .
							//"O pagamento deverá ser realizado via depósito, na Conta corrente nº:" .
							//"300.000-1 - Agência: 3798-2 - Banco do Brasil - Código identificador:" .
							//"1492-3 - em nome de FAURGS. CNPJ: 74.704.008/0001-75; enviar o" .
							//"comprovante do pagamento, devidamente identificado, para o fax" .
							//"3316-3286.\n\n" .
						      $linhaCad["COMENTARIO_CURSO"]."\n\n".
							
							" Estamos à " .
							"disposição para solucionar problemas ou dirimir eventuais dúvidas, " .
							"através do mail navi@ea.ufrgs.br, ou pelo telefone (0xx51) 3316 3699. \n \n" .
							"Atenciosamente, \n" .
							"Equipe NAVi - Escola de Administração da UFRGS \n" .
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

	$body = "Escola de Administração - Universidade Federal do Rio Grande do Sul"."\n\n" . 
		  "Novo usuário cadastrado com os seguintes campos:"."\n\n" . 
		  "Usuário: "					  . $_REQUEST["USER_PESSOA"] . "\n" .
		  "Nome: "						  . $_REQUEST["NOME_PESSOA"] . "\n" .
		  "Email: "				 		  . $_REQUEST["EMAIL_PESSOA"] . "\n" .
		  "Forma de Pagamento: " 		  . $_REQUEST["PAGAMENTO_FORMA"] . "\n" .
		  "Condição de Pagamento: " 	  . $_REQUEST["PAGAMENTO_CONDICAO"] . "\n" .
		  "Parcelas de Pagamento: "		  . $_REQUEST["PAGAMENTO_PARCELA"] . "\n" .
		  "Responsável de Pagamento: "	  . $_REQUEST["PAGAMENTO_RESPONSAVEL"] . "\n" .
		  "Nome do Responsável: "		  . $_REQUEST["PAGAMENTO_RESP_NOME"] . "\n" .
		  "CNPJ do Responsável: "		  . $_REQUEST["PAGAMENTO_RESP_CNPJ"] . "\n" .
		  "Razão Social do Responsável: " . $_REQUEST["PAGAMENTO_RESP_RAZAO_SOC"] . "\n" .
		  "Endereço: "					  . $_REQUEST["DESC_END"] . "\n" .
		  "Bairro: "					  . $_REQUEST["BAIRRO_END"] . "\n" .
		  "Cidade: "					  . $_REQUEST["CIDADE_END"] . "\n" .
		  "Estado: "					  . $_REQUEST["UF_END"] . "\n" .
		  "País: "						  . $_REQUEST["PAIS_END"] . "\n" .
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

  <p>Suas informações básicas já estão no nosso sistema.</p>

  <p>
   Estamos à disposição para solucionar problemas ou dirimir eventuais dúvidas, através do mail navi@ea.ufrgs.br, ou pelo telefone (0xx51) 3316 3699.
 
  </p>
  <p>
	<a href="./../principal.php">Voltar</a>
  </p> 		  

</blockquote>

</body>

</html>

