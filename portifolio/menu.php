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
session_name(SESSION_NAME); session_start(); security();

include($caminhoBiblioteca."/portfolio.inc.php");
//include($caminhoBiblioteca."/pessoa.inc.php");
//include($caminhoBiblioteca."/aluno.inc.php");
include($caminhoBiblioteca."/curso.inc.php");

$instanciaAtual = new InstanciaNivel(getNivelAtual(),getCodInstanciaNivelAtual());

?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>
<body>

<table width="150" border="0" cellspacing="0" cellpadding="0">
	<tr> 
		<td colspan="2" valign="top"> 
		
			<table width="150" height='100%' border="0" cellpadding="0" cellspacing="0" >
				<tr> 
					<td>
	
						<?php
						/*
						if  ( $_SESSION["codInstanciaGlobal"] == "" ) {
							echo "ola estou entrando do if ".$_SESSION["codInstanciaGlobal"] ;
							exit();
						}*/
					if ($instanciaAtual->relacionaPessoas()) {
						$rsCon = 	portifolio("");
					}
					else {
            $rsCon = listaTodosIntegrantes(new Aluno());
          }
						//trocar por listaTodosIntegrantes(new Aluno())
          //  $rsCon=listaTodosIntegrantes(new Aluno());
						$classe = "CelulaEscura";
						
						if ( $rsCon )
						{
							while ( $linha = mysql_fetch_array($rsCon) )
							{
							    
                  if($_SESSION['userRole']==PROFESSOR){
                    $retPortStatusQtde= PortStatus($_SESSION["COD_PROF"],"",$linha["COD_AL"],"qtde");
	                } 	
					
					$numeroDePortfolios=numeroDePortfolios($linha["COD_AL"], $_SESSION["codInstanciaGlobal"]);
  								print    "<tr>";
									print    " <td class='". $classe ."'>";
									print    "   <a href='interno.php?COD_AL=" . $linha["COD_AL"] . "' target='main'>" . $linha["NOME_PESSOA"] . "</a>";
                  
                  if($_SESSION['userRole']==PROFESSOR){
                    if($retPortStatusQtde > 0){
                    print "<br><font color='red'>[".$retPortStatusQtde." novo(s) arquivo(s)/ ".$numeroDePortfolios."]</font>";
                    }else{
					echo "<br>[".$numeroDePortfolios." arquivo(s) postado(s)]";}

                  }else
					{
					  echo "<br>[".$numeroDePortfolios." arquivo(s) postado(s)]";

				     }								
 									print    " </td>";
									print    "</tr>";
									print    "<tr>";
									print    " <td height='10'> &nbsp; </td> ";
									print    "</tr>";												
								 

								if ( $classe == "CelulaEscura" )
									$classe = "CelulaClara";
								else
									$classe = "CelulaEscura"; 
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
