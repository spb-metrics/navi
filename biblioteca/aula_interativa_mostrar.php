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
include_once($caminhoBiblioteca."/acervo.inc.php");
session_name(SESSION_NAME); session_start(); security();

if ( !isset($_REQUEST["DATA"]) )
   $_REQUEST["DATA"]= "";
?>
<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
<style type="text/css">
BODY {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color:#FFFFFF; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px;  }
TABLE,  TR, TD {  font-size: 11px}
TH { font-size:12px; font-weight:bold; background-color:navy; color:white;}
.msgNormal {padding:2px; border-bottom:1px solid #C0C0C0;  }
.linhaImpar {background-color:#DFDFCF;}
.linhaPar {background-color:#EDEDDD; }
.msgProfessor { color:red; font-weight:bold; }
.msgPergunta {color:green; font-weight:bold; }
.horario { color: #043004; display:normal;}
</style>
	</head>

<body>

	<?php
	if(!empty($_REQUEST["COD_PESSOA"]))
		echo "<p align='right'><a href='javascript:window.close()'>fechar</a>&nbsp; &nbsp; </p>";
	else
		echo "<p align='right'><a href='javascript:history.back()'>Voltar</a>&nbsp; &nbsp; </p>";


	if (($_SESSION["COD_PESSOA"] == "") OR ($_SESSION["codInstanciaGlobal"] == "" ))
	{
		echo "<p align='center'> <b>Acervo dispon&iacute;vel apenas para alunos cadastrados.</b> </p>";
		exit();
	 }	
	
	$DATA = $_REQUEST["DATA"];	

	$param['dataInicio']=$_REQUEST["dataInicio"];
	$param['dataFim']=$_REQUEST["dataFim"];
	$param['COD_PESSOA']=$_REQUEST["COD_PESSOA"];

		
	$rsCon = acervoAulaInterativaDia($DATA,$param);
		if ($rsCon)
		{

			if(! $linha = mysql_fetch_array($rsCon))
			{
				echo  "<p align='center'>".
					  "		<b><font size='1'>N�o h� mensagens enviadas nesta data.</font></b>".
					  "</p>";
			}
			else
			{			
		   
	            //list($hora,$minuto,$segundo)= explode (":",$linha["HOUR"]);
		    echo "<table cellspacing=0 valign='center'><th>Nome</th><th>Mensagem</th>"; 
        $par=0;
		    while($linha )				{
		      if ($par) { $classe='linhaImpar'; $par=0;} else { $classe='linhaPar'; $par=1;}
		      echo "<tr class='msgNormal ".$classe."' align='center'>";
		      
			    
					if( $linha["RESERVADO"] == 1)
					{
						if (($linha["NOME_ENVIA"] == $_SESSION["NOME_PESSOA"]) OR ($linha["NOME_RECEBE"] == $_SESSION["NOME_PESSOA"]))
						 {
							echo "<td width='50'>";
							$nome = $linha["NOME_CHAT"]; if (empty($nome)) { $nome=$linha["NOME_ENVIA"]; }
							echo "<span title='".$linha["NOME_ENVIA"].", ".$linha["HOUR"]."'>".$nome."</span>";		
							echo " <br> fala reservadamente para " . $linha["NOME_RECEBE"];
				
							echo "</td> ";
							
//							$mensagem = $linha["MENSAGEM"];
//							$mensagem = str_replace($mensagem,"&","&amp;");
//							$mensagem = str_replace($mensagem,"<","&lt;");
//							$mensagem = str_replace($mensagem,"\"","&quote;");
							
							echo "<td width='300'>".htmlentities($linha["MENSAGEM"]) . "</td>";
						  }
					 }
					else
					{
						echo "<td width='50'>";
						$nome = $linha["NOME_CHAT"]; if (empty($nome)) { $nome=$linha["NOME_ENVIA"]; }
						echo "<span title='".$linha["NOME_ENVIA"].", ".$linha["HOUR"]."'>".$nome."</span>";		
				
						if ( $linha["NOME_RECEBE"]!= ("#" . $_SESSION["codInstanciaGlobal"]) )
							echo "<br> " . " para  " . $linha["NOME_RECEBE"];
						
							
						echo "</td> ";

//						$mensagem = $linha["MENSAGEM"];
//						$mensagem = str_replace($mensagem,"&","&amp;");
//						$mensagem = str_replace($mensagem,"<","&lt;");
//						$mensagem = str_replace($mensagem,"\"","&quote;");
						
						echo "<td>".htmlentities($linha["MENSAGEM"]) . "</td>";						
					
					  }
					$linha = mysql_fetch_array($rsCon);
					echo "</tr>";
				}
				echo "</table>";
      }
  }
	//	echo </font> </p>";				
	   ?>
</body>
</html>
