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
include_once ($caminhoBiblioteca."/curso.inc.php");
include_once ($caminhoBiblioteca."/estudo_de_casos.inc.php");
session_name(SESSION_NAME); session_start(); security();
function printHeader($params="") {
  echo "<html>".
	   "<head>".
		"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">".

		"<link rel=\"stylesheet\" href=\".././cursos.css\" type=\"text/css\">".

		"<script language=\"JavaScript\" src=\".././funcoes_js.js\"></script>";
  if (!empty($params["titulo"]))
    echo "<title>{$params["titulo"]}</title>".
		 "</head>".
		 "<body {$params["body"]}>".
		 "<h3 class=\"titulo\">{$params["tituloPagina"]}</h3>";

}


$params["titulo"]="N&uacute;cleo de Aprendizagem Virtual";
 printHeader($params);


echo"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">".
	"<tr>".
	"<td width=\"200\" valign=\"top\">&nbsp;&nbsp". 
//			include "../noticias_menu_esq_turma.php" 
	"</td>".
	"<td width=\"740\" valign=\"top\">". 
    "<table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">".
    "<tr><td>".
    "<p align=\"right\"><a href=\"./index.php\"> Voltar </a></p>";

  
    if (! isset($_REQUEST["ORDER"]))
		$_REQUEST["ORDER"] = "";
	
	if ( ($_SESSION["COD_ADM"] != "") or ($_SESSION["COD_PROF"] != "") or ($_SESSION["COD_AL"] != "") )
		$rsCon = casoLista($_REQUEST["ORDER"]);
	else
	{
		echo "<p align='center'> <b>Casos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
	 }

		if (! $rsCon )
			echo " <p align='center'> <b>N&atilde;o h&aacute; casos dispon&iacute;veis no momento.</b> <br><br> </p>";
		else
		{
			echo "<p align='center'>";
			echo "<b>Casos dispon&iacute;veis:</b> <br><br> ";
							
			$ant = "";
			
			if ($_REQUEST["ORDER"] == "nome")
			{
				while ($linha = mysql_fetch_array($rsCon))
				{
					if ($ant != $linha["NOME_PESSOA"]){
						echo "</p><p align=\"justify\"><b><font style=\"text-transform: capitalize;\">" . strToLower($linha["NOME_PESSOA"]) . "</font></b>".
							 "<br>";
					}
					else
						echo "<br>";					

					if ( $linha["COD_PESSOA"] == $_SESSION["COD_PESSOA"] )
					{
						if($linha["emConstrucao"])
							$status=" <b>&nbsp;&nbsp;(em construção )</b>" ;
						else
							$status="<b>&nbsp;&nbsp;( entregue)</b>";

						echo	"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
								"		<a href='./casos_listar.php?ORDER=". $_REQUEST["ORDER"] ."' onClick=\"if (confirm('Deseja mesmo excluir este Estudo de Caso ?')) { window.open('./casos_apagar.php?COD_CASO=" . $linha["COD_CASO"] ."&ORDER=". $_REQUEST["ORDER"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">".
								"			<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">".
								"		</a>&nbsp;&nbsp;&nbsp;".
								"		<a href=\"casos_alterar.php?COD_CASO=" . $linha["COD_CASO"] ."&ORDER=". $_REQUEST["ORDER"] ."\">".
								"			<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">".
								"		</a>".
								"<a href='./casos_mostrar.php?ORDER=". $_REQUEST["ORDER"] . "&COD_CASO=" . $linha["COD_CASO"] . "'>".
								$linha["TITULO"].$status.
								"</a>";
					 }
					else
					{
						if($linha["emConstrucao"]){
						  echo  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font color='red'>".$linha["TITULO"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Em Construção)</font></b>";
							  
						}else{
						   echo  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
							  " <a href='./casos_mostrar.php?ORDER=". $_REQUEST["ORDER"] . "&COD_CASO=" . $linha["COD_CASO"] . "'>".
							  $linha["TITULO"] .
							  "</a>";
            }
					 }
					
					$ant = $linha["NOME_PESSOA"];
				 }
				echo "</p><p align='center'><a href=\"?ORDER=titulo\">Ordenar por Titulo </a></p>";
			
      }
			else
			{
       
				if ($rsCon)
				{
					while ($linha = mysql_fetch_array($rsCon))
					{
						if ( $ant != $linha["COD_CASO"] )
						{											
								echo "</p><p align=\"justify\">";
								
									
								if (casoAcesso("ok", $linha["COD_CASO"]))
								{
									if($linha["emConstrucao"])
										$status=" <b>&nbsp;&nbsp;(em construção )</b>" ;
									else
										$status="<b>&nbsp;&nbsp;( entregue)</b>";

									echo	"		<a href='./casos_listar.php?ORDER=". $_REQUEST["ORDER"] ."' onClick=\"if (confirm('Deseja mesmo excluir este Estudo de Caso ?')) { window.open('./casos_apagar.php?COD_CASO=" . $linha["COD_CASO"] ."&ORDER=". $_REQUEST["ORDER"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">".
											"			<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">".
											"		</a>&nbsp;&nbsp;&nbsp;".
											"		<a href=\"casos_alterar.php?COD_CASO=" . $linha["COD_CASO"] ."&ORDER=". $_REQUEST["ORDER"] ."\">".
											"			<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">".
											"		</a>";
											echo	"<a href='./casos_mostrar.php?ORDER=". $_REQUEST["ORDER"] . "&COD_CASO=" . $linha["COD_CASO"] . "'> <b>".
										$linha["TITULO"] .$status.
										" </b> </a>";
									$codCaso=$linha["COD_CASO"];
									
								 }else{
								  if(!$linha["emConstrucao"]&&$codCaso!=$linha["COD_CASO"]){
								  echo	"<a href='./casos_mostrar.php?ORDER=". $_REQUEST["ORDER"] . "&COD_CASO=" . $linha["COD_CASO"] . "'> <b>".
								    		$linha["TITULO"] .
								    		" </b> </a>";
							   	}else{
							   		echo	"<b><font color='red'>".$linha["TITULO"] ." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Em Construção)</font></b>";
                  }
                }
								
								
						 }
						//	if(casoAcesso("ok", $linha["COD_CASO"]))
            //	if(!$linha["emConstrucao"]||casoAcesso("ok", $linha["COD_CASO"]))
							echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <font style=\"text-transform: capitalize;\">" . strToLower($linha["NOME_PESSOA"]) . "</font>";
						
						$ant = $linha["COD_CASO"];
					 }
				 }				 
				echo "</p><p align='center'><a href=\"?ORDER=nome\">Ordenar por Nome </a></p>";
			 }
		 }
		
		echo "</p><p align='center'><a href=\"./casos_enviar.php\">Escreva seu Estudo de Caso </a></p>";

	//	echo "<p align='left'><a href=\"./index.php\"><b>< VOLTAR</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			

		echo "  </td>".
             "   </tr>".
             " </table>".
		"</td>".
	"</tr>".
"</table>".
 
 "</body>".

"</html>";
