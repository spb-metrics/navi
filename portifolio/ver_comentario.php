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
include_once ("../config.php");
include_once ($caminhoBiblioteca."/portfolio.inc.php");
include_once ($caminhoBiblioteca."/miscelania.inc.php");
session_name(SESSION_NAME); session_start(); security();

if(!isset($_REQUEST["COD_ALUNO_ARQUIVO"]))
$_REQUEST["COD_ALUNO_ARQUIVO"]="";
?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href=".././cursos.css" type="text/css">
		<link rel="stylesheet" href="portfolio.css" type="text/css">

		<script language="JavaScript" src=".././funcoes_js.js"></script>
	</head>

<body bgcolor="#FFFFFF" text="#000000">

              <table width="93%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td>
	                 <p align="right"><a href="./interno.php?COD_AL=<?=$_REQUEST["COD_AL"]?>"> Voltar </a></p>
						<?php			
									$rsCon2 = portifolioComentario($_REQUEST["COD_ALUNO_ARQUIVO"]);
									if ($rsCon2)
									{
										$usuario=portComUsuario($_REQUEST["COD_ALUNO_ARQUIVO"]);
										$usuario=mysql_fetch_array($usuario);

										echo "<div align='left' class='tipoProfessor'><b>Portf�lio do aluno:<font color=\"#0033CC\"> ".$usuario["NOME_PESSOA"]."</font></b><br>";

										echo "<b>Coment�rios do arquivo:<font color=\"#0033CC\"> ".$usuario["DESC_ARQUIVO_INSTANCIA"]."</font></b></div><br>";

										$arquivosComentario=getArquivoComentario($_REQUEST["COD_ALUNO_ARQUIVO"]);
										while ($linha2 = mysql_fetch_array($rsCon2))
												{ 	

															$imagemProf=verificaSePessoaProfessor($linha2["COD_PESSOA"], $_SESSION["codInstanciaGlobal"]);
															$imagemProf=mysql_fetch_array($imagemProf);

															echo "<p align=\"left\"  style=\"background-color: #ededed\">". str_replace("\n", "<br>", $linha2["TEXTO"]);

															if(!empty($linha2["codArquivoComentario"])){
																echo"<br><b>Acesse:</b><a href=\"#\" onClick=\"javascript:window.open('./mostrar.php?COD_ARQUIVO=" . $arquivosComentario[$linha2["COD_COMENTARIO"]]["COD_ARQUIVO"] . "&COM=1','', 'fullscreen=yes, scrollbars=none');\">".$arquivosComentario[$linha2["COD_COMENTARIO"]]["DESC_ARQUIVO"] ."</a>";

															}




															echo "<br><div align=Right class='tipoProfessor'><b>";
															if($imagemProf){

																echo "<img src=\"../alunos/tipoProfessor.php?codTipoProfessor=".$imagemProf["codTipoProfessor"]."\" max-height=\"40\" max-width=\"60\" title='".$imagemProf["descTipoProfessor"]."'>";

															//echo Professor::iconeTipoProfessor($imagemProf["codTipoProfessor"],$imagemProf["descTipoProfessor"]);
															}
													
															echo $linha2["NOME_PESSOA"] ." - ". $linha2["DATA_MODIFICADA"]  ."</b></div></p>";
															
												}
											
														
									}
									else{echo"<p align=\"justyfy\">N�o h� no momento coment�rios sobre este texto</p>";}
									
									
								
						?>
  					</td>
                </tr>
        </table>	
</body>
</html>
	