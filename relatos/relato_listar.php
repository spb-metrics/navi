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
include_once ($caminhoBiblioteca."/relato.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>
<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href=".././cursos.css" type="text/css">

		<script language="JavaScript" src=".././funcoes_js.js"></script>
	</head>

<body bgcolor="#FFFFFF" text="#000000">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 

			

		
	    <td width="100%" valign="top"> 
              <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td>
  <?php
    if (! isset($_REQUEST["ORDER"]))
		$_REQUEST["ORDER"] = "";
	
	if ( ($_SESSION["COD_ADM"] != "") or ($_SESSION["COD_PROF"] != "") or ($_SESSION["COD_AL"] != "") )
		$rsCon = relatoLista($_REQUEST["ORDER"]);
	else
	{
		echo "<p align='center' class='cabecalho'> <b>Relatos dispon&iacute;veis apenas para alunos cadastrados.</b> </p>";
		exit();
	 }

		if (! $rsCon )
			echo " <p align='center' class='menu'> <b>N&atilde;o h&aacute; Relatos dispon&iacute;veis no momento.</b> <br><br> </p>";
		else
		{
			echo "<p align='center' class='menu'>";
			echo "<b>Relatos dispon&iacute;veis:</b> <br><br> ";
							
			$ant = "";
			
			if ($_REQUEST["ORDER"] == "nome")
			{
				while ($linha = mysql_fetch_array($rsCon))
				{
					if ($ant != $linha["NOME_PESSOA"]){
//    $linha["NOME_PESSOA"]  ." - ". $linha["DATA_MODIFICADA"] - colocar a data da postagem
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
								"		<a href='./relato_listar.php?ORDER=". $_REQUEST["ORDER"] ."' onClick=\"if (confirm('Deseja mesmo excluir este Estudo de RELATO ?')) { window.open('./relato_apagar.php?COD_RELATO=" . $linha["COD_RELATO"] ."&ORDER=". $_REQUEST["ORDER"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">".
								"			<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">".
								"		</a>&nbsp;&nbsp;&nbsp;".
								"		<a href=\"relato_alterar.php?COD_RELATO=" . $linha["COD_RELATO"] ."&ORDER=". $_REQUEST["ORDER"] ."\">".
								"			<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">".
								"		</a>".
								"<a href='./relato_mostrar.php?ORDER=". $_REQUEST["ORDER"] . "&COD_RELATO=" . $linha["COD_RELATO"] . "'>".
								$linha["TITULO"].$status.
								"</a>";
					 }
					else
					{
						if($linha["emConstrucao"]){
						echo  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>". $linha["TITULO"] ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Em Construção)</font>";
						}else{
						echo  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
							  " <a href='./relato_mostrar.php?ORDER=". $_REQUEST["ORDER"] . "&COD_RELATO=" . $linha["COD_RELATO"] . "'>".
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
						if ( $ant != $linha["COD_RELATO"] )
						{											
								echo "</p><p align=\"justify\">";
								
									
								if (RELATOAcesso("ok", $linha["COD_RELATO"]))
								{
									if($linha["emConstrucao"])
										$status=" <b>&nbsp;&nbsp;(em construção )</b>" ;
									else
										$status="<b>&nbsp;&nbsp;( entregue)</b>";
									echo	"		<a href='./relato_listar.php?ORDER=". $_REQUEST["ORDER"] ."' onClick=\"if (confirm('Deseja mesmo excluir este Estudo de RELATO ?')) { window.open('./relato_apagar.php?COD_RELATO=" . $linha["COD_RELATO"] ."&ORDER=". $_REQUEST["ORDER"] ."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">".
											"			<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">".
											"		</a>&nbsp;&nbsp;&nbsp;".
											"		<a href=\"relato_alterar.php?COD_RELATO=" . $linha["COD_RELATO"] ."&ORDER=". $_REQUEST["ORDER"] ."\">".
											"			<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">".
											"		</a>";
											echo	"<a href='./relato_mostrar.php?ORDER=". $_REQUEST["ORDER"] . "&COD_RELATO=" . $linha["COD_RELATO"] . "'> <b>".
										$linha["TITULO"] .$status.
										" </b> </a>";
										$codRelato=$linha["COD_RELATO"];
								 }
								if(!$linha["emConstrucao"]&&$codRelato!=$linha["COD_RELATO"]){
								    echo	"<a href='./relato_mostrar.php?ORDER=". $_REQUEST["ORDER"] . "&COD_RELATO=" . $linha["COD_RELATO"] . "'> <b>".
										$linha["TITULO"] .
										" </b> </a>";
								}else{
										echo	"<font color='red'>".$linha["TITULO"] .	" (Em Construção)</font>";
                }
						 }
					//	if(!$linha["emConstrucao"]||RELATOAcesso("ok", $linha["COD_RELATO"]))
						echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <font style=\"text-transform: capitalize;\">" . strToLower($linha["NOME_PESSOA"]) . "</font>";
						
						$ant = $linha["COD_RELATO"];
					 }
				 }				 
				echo "</p><p align='center'><a href=\"?ORDER=nome\">Ordenar por Nome </a></p>";
			 }
		 }
		
		echo "</p><p align='center'><a href=\"./relato_enviar.php\">Escreva seu Relato </a></p>";

		echo "<p align='left'><a href=\"./index.php\"><b>< VOLTAR</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
?>
				  </td>
                </tr>
              </table>
		</td>
	</tr>
</table>
 
 </body>

</html>
