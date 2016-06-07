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
include_once("../config.php");
include_once($caminhoBiblioteca."/enquete.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>Enquete</title>
		<link rel="stylesheet" href="../cursos.css" type="text/css">
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">
<?php
if ( !isset($_REQUEST["COD_RESPOSTA"]) )
			$_REQUEST["COD_RESPOSTA"] = "";

if ( !isset($_REQUEST["COD_ENQUETE"]) )
			$_REQUEST["COD_ENQUETE"] = "";
			
if ( !isset($_REQUEST["TEXTO_RESPOSTA"]) )
			$_REQUEST["TEXTO_RESPOSTA"] = "";

if ( !isset($_REQUEST["TEXTO_ENQUETE"]) )
			$_REQUEST["TEXTO_ENQUETE"] = "";
			
$texto_enquete = str_replace("\n", "<br>", $_REQUEST["TEXTO_ENQUETE"]);
$cod_enquete = str_replace("\n", "<br>", $_REQUEST["COD_ENQUETE"]);
$cod_resposta = str_replace("\n", "<br>", $_REQUEST["COD_RESPOSTA"]);


$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
		
if ( ( $acesso != 1 ) AND ( $acesso != 2 ) AND ( $acesso != 3 ) )
{
	echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>Página Principal</a></p>";
	exit();
}

switch ( $_REQUEST["OPCAO"])
	{
	case ("Inserir"):

//INSERINDO UMA ENQUETE		
		if ($_REQUEST["COD_ENQUETE"]== "" ){
			
			$sucesso = EnqueteInsere($_REQUEST["TEXTO_ENQUETE"]);
		
			if ( $sucesso )
			{
				
				$rsCon = RecebeCodEnqute ($_REQUEST["TEXTO_ENQUETE"]);
				if($rsCon)
				{
					$linha= mysql_fetch_array($rsCon);
					$cod_enquete= $linha["COD_ENQUETE"];
					/*echo "<script> location.href=\"./enquete_operacao.php?OPCAO=Alterar&COD_ENQUETE=". $cod_enquete."\"</script>";*/
					echo "<script> location.href=\"./enquete_operacao.php?PAGINA=".$_REQUEST['PAGINA']."&OPCAO=Alterar&COD_ENQUETE=". $cod_enquete ."&ENQUETE_ENVIO=inserir\";</script>";
				
				}				
				
			 }						
			else
			{
				echo "ERRO na Inserção<br>".
				"<a href=\"javascript:history.back()\">Voltar</a>";
			 }
		 }
			
//INSERINDO AS RESPOSTAS
			
			$texto_reposta = str_replace("\n", "<br>", $_REQUEST["TEXTO_RESPOSTA"]);
			if ($_REQUEST["COD_ENQUETE"]!= "" OR $_REQUEST["TEXTO_ENQUETE"]!= ""){
								
			$sucesso = EnqueteResInsere($_REQUEST["COD_ENQUETE"],$_REQUEST["TEXTO_RESPOSTA"]);
		
			if( $sucesso ){
							
					echo "<br><br><p align=center>Resposta anexada<br>\n".
						 "<a href='enquete_operacao.php?PAGINA=".$_REQUEST['PAGINA']."&OPCAO=Alterar&PAGINA=enquete&COD_ENQUETE=" . $_REQUEST["COD_ENQUETE"] ."'>Fechar</a>\n".
						 "<script>\n".
						 "window.opener.location.reload(true)\n".
						 "</script>\n";
			}
		}
		
		break;
	//CRIAR UM CASO PRA QUANDO IRA ALTERAR, PARA PODER USAR ESSA PARTE DE BAIXO
	case ("Alterar"):
		
		if ( EnqueteVerificaAcesso($_REQUEST["COD_ENQUETE"]) )
			{							
				$sucesso = EnqueteAltera($cod_enquete, $texto_enquete);
				if ( $sucesso )
				{
				
					$tamanho=count($_REQUEST["TEXTO_RESPOSTA"]);
					for($i=0;$i<$tamanho; $i++){
						
						$sucesso =EnqueteRespostaAltera($_REQUEST["COD_RESPOSTA"][$i],$_REQUEST["TEXTO_RESPOSTA"][$i]);
						}
						if ($sucesso)
						echo "<script> location.href=\"./enquete_operacao.php?PAGINA=".$_REQUEST['PAGINA']."&OPCAO=Alterar&COD_ENQUETE=". $_REQUEST["COD_ENQUETE"] ."&ENQUETE_ENVIO=alterar\"</script>";
				}	
				else
				{
					echo "ERRO na Alteração<br>".
						 "<a href=\"javascript:history.back()\">Voltar</a>";
				 }
			 }
		
		break;
		
	case ("Remover"):
			
			if ( EnqueteVerificaAcesso($_REQUEST["COD_ENQUETE"]) )
			{
				
				$sucesso = EnqueteExclue($_REQUEST["COD_ENQUETE"],$_REQUEST["TEXTO_ENQUETE"]);
				if ( $sucesso )
				{
					//session("atualizar") = true
					
					echo "<br><br>Enquete Removida com sucesso - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
					
					if ( !isset($_REQUEST["PAGINA"]) )
						$_REQUEST["PAGINA"] = "";
						
					if ( $_REQUEST["PAGINA"] == "enquete" )
						echo "<script> window.opener.location.href='./enquete.php?PAGINA=".$_REQUEST['PAGINA2']."&'; </script>";
					else
						echo "<script> window.opener.location.reload(true) </script>";
				 }
				else	
					echo "<br><br>ERRO na Remoção - <a href=\"javascript:window.close()\" >Fechar Janela</a>";
			 }
		break;

	 }
	?>		
		
		

</body>
</html>
