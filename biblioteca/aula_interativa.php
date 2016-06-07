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

ini_set("display_errors",0);
error_reporting(E_ALL ^ E_NOTICE);
//include_once("../funcoes_bd.php");
include("../config.php");
include($caminhoBiblioteca."/acervo.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body>

	<?php
	
	if ( ( $_SESSION["COD_PESSOA"] == "" ) OR ( $_SESSION["codInstanciaGlobal"] == "" ) )	{
		echo "<p align='center'> <b>Acervo dispon&iacute;vel apenas para alunos cadastrados.</b> </p>";
		exit();
	}

	$rsCon = acervoAulaInterativa();

	
	$mesNome = array(1=> "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");

				
  if ( $rsCon )	{
		
	  if( $linha = mysql_fetch_array($rsCon) ) {  			
      echo  "<p align='left'> <font size='2' color='#000099'>";
      $primeiroLoop = true;

      $anoAtual = 0;
      $mesAtual = 0;
      $diaAtual = 0;
        
      while ( $linha ) { 
         
        list($dia,$mes,$ano)= explode( "/", $linha["DATA"]);
							
				if ( ( $anoAtual != $ano) OR ( $mesAtual != $mes) ) {						
						$anoAtual = $ano;
						$mesAtual = $mes;
						$diaAtual = $dia;
						
						$novaData = $ano . $mes . $dia;
						
						//if ( $mes < 10 )
						//	$mesAtual_2 = $mes[1];
						 
						if ( !$primeiroLoop ) { echo "<br> <br>"; }
						
						echo  " <b> &nbsp; &nbsp;" . $anoAtual . " - " . $mesNome[intVal($mes)] . "</b><br><br> &nbsp; &nbsp; &nbsp; &nbsp;".
							  " Dias: <font size='2'> <a href='aula_interativa_mostrar.php?DATA=" . $novaData . "'>" . $dia . "</a> </font>";
						
						$primeiroLoop = false;
					 }
					else
					{
						if ( $diaAtual != $dia )
						{
							$diaAtual = $dia;
							$novaData = $ano . $mes . $dia;
	
							echo "<font size='2'> - <a href='aula_interativa_mostrar.php?DATA=" . $novaData . "'>" . $dia . "</a> </font>";
						 }
					 }
					
//					Calculo de ano bisexto nao foi utilizado
//					Result := (Year mod 4 = 0) and ( Year mod 100 <> 0);								

					$linha = mysql_fetch_array($rsCon);
				 }

				echo "</font> </p>";
			 }
		 
		 else
		 {
		
				echo  "<p align='center'>".
					  "		<b><font size='2'>Acervo Vazio</font></b>".
					  "</p>";
		  }
	  }
	 ?>
</body>
</html>