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

										echo "<div align='left' class='tipoProfessor'><b>Portfólio do aluno:<font color=\"#0033CC\"> ".$usuario["NOME_PESSOA"]."</font></b><br>";

										echo "<b>Comentários do arquivo:<font color=\"#0033CC\"> ".$usuario["DESC_ARQUIVO_INSTANCIA"]."</font></b></div><br>";

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
									else{echo"<p align=\"justyfy\">Não há no momento comentários sobre este texto</p>";}
									
									
								
						?>
  					</td>
                </tr>
        </table>	
</body>
</html>
	