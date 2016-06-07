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
ini_set("session.use_only_cookies",1);

//include_once("./../../funcoes_bd.php");
/*
$sRoomID = $_SESSION["ROOM_ID"];
$sUserID = $_SESSION["USER_ID"];
*/
include("./../../config.php");
include($caminhoBiblioteca."/videos.inc.php");
include($caminhoBiblioteca."/pessoa.inc.php");
session_name(SESSION_NAME); session_start(); security();
//le as variaveis de sessao q ira usar e ja encerra a sessao, para nao bloquear os outros frames 
session_write_close();
?>
<html>
	<head>
		<title>CHAT</title>
		<link rel="stylesheet" href="./../../cursos.css" type="text/css">		
	</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 
		<td colspan="2" valign="top"> 
			<table width="100%" height='100%' border="0" cellpadding="0" cellspacing="0" >
				<tr> 
					<td>
<?php
if ( !( ( $_SESSION["COD_ADM"] != "" ) OR ( $_SESSION["COD_PROF"] != "" ) OR ( $_SESSION["COD_AL"] != "" ) ) ) {
  msg("Aula interativa dispon&iacute;vel apenas para alunos cadastrados.");
  exit();
}

$rsChat = videoChat("consultar", "");
$linhaChat = mysql_fetch_array($rsChat);

if ( Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage']) ) {

  if ( $rsChat ) 	{
    if ($linhaChat)		{
  		echo "<div align='center'>";
  		echo "	<form name='Video' action='./video.php' >";
  		echo "O video está habilitado! <br><br>";
  		
  		if ( $linhaChat["EM_USO"] == 1 ) {
  			echo "O video está ativo. <br><br>";
        echo "<input type='submit' name='DESATIVAR' value='Desativar Video'> ";				
  		  echo "<br><br>* No final de cada aula com o uso do video é necessário 'Desativar Video'";
  		}
  		else {
  		  echo " O video está inativo. Clique em 'Ativar Video' para utilizar durante a aula.<br><br>";
  		  echo "		<input type='submit' name='ATIVAR' value='Ativar Video'> ";
      }							
  
  		echo "	</form>";
  		echo "</div>";				
  	}
  	echo "<hr/>";
  }
}

if ( ( $_SESSION["COD_PESSOA"] != "" ) AND ( $_SESSION["codInstanciaGlobal"] != "" ) ) {

	if ( $rsChat) 	{
		if ($linhaChat) {
			echo "<div align='center'>";

			if ( $linhaChat["EM_USO"] == 1 )
			{
				echo "O video está em uso nesta instância. <br> <br>";

				echo "	<form name='Video'   action='./index_2.php'  >";

				echo "		<input type='submit' name='COM_VIDEO' value='Entrar na Aula Interativa com Video'> ";
				echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
				echo "		<input type='submit' name='SEM_VIDEO' value='Entrar na Aula Interativa sem Video'  > ";
        //onClick=\"javascript:window.open('./index_2.php','', 'fullscreen=yes, scrollbars=none');\"
				echo "	</form>";
				echo "</div>";
			 }				
			else 
			{
				echo "	<form name='Video'   action='./index_2.php'  >";
				echo "		<input type='submit' name='SEM_VIDEO' value='Entrar na Aula Interativa' > ";
         //onClick=\"javascript:window.open('./index_2.php','', 'fullscreen=yes, scrollbars=none');\"
        echo "	</form>";
				echo "</div>";
			 }
		}
		else
		{
				echo "<div align='center'>";
				
				echo "	<form name='Video' action='./index_2.php' >";
				echo "		<input type='submit' name='SEM_VIDEO' value='Entrar na Aula Interativa'> ";
				echo "	</form>";
				echo "</div>";
		 }
	 }
 }
?>
					</td>
				</tr>
			</table>
		</td> 
	</tr>
</table>
</body>
</html>
