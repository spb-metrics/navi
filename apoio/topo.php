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
include_once($caminhoBiblioteca."/arquivo.inc.php");
session_name(SESSION_NAME); session_start(); security();

?>

<html>
	<head>
		<title>Texto</title>	
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="../cursos.css" type="text/css">
	</head>
<body>

  <?php

	if ( ($_SESSION["codInstanciaGlobal"] == "") OR ($_REQUEST["COD_ARQUIVO"] == "") )
	{
		echo "Acesso Negado";
		exit();
	 }
	
	$rsCon   = apoioCaminho($_REQUEST["COD_ARQUIVO"]);
	if ( (! $rsCon) or (mysql_num_rows($rsCon) == 0) ) {
		exit();
	}

	$linha = mysql_fetch_array($rsCon);
	if ( ($_SESSION["COD_PESSOA"] != "") and ($linha["COD_TIPO_ACESSO"] == 1) )
		exit();
			
		?>
			<table width="700" align="center" >
				<tr>
					<td align="center" width="550" >
         
             <span onMouseOver="ajuda.style.visibility = 'visible'" onMouseOut="ajuda.style.visibility = 'hidden'">
             <font color="red">
             <b>Problemas em 
             <?if ($_REQUEST["download"]) { echo " fazer download do"; } else { echo " visualizar o"; }?> arquivo? Passe o mouse aqui. </b></font>
             </span>
               <div id=ajuda align="justify" style="position: absolute; overflow: visible; visibility: hidden; width: 800; left: 10%; top: 5%; background-color: white; border: 1px solid black; z-index:3;">					
						<b> <?= $linha["DESC_ARQUIVO_INSTANCIA"] ?> </b><br>
            Seu navegador pode ter restrições de segurança. Se
            aparecer uma faixa amarela, então clique sobre ela, e depois em
            "Fazer download de arquivo" ou expressão semelhante. Se a tela estiver em branco aguarde, que o sistema está
            buscando e abrindo o arquivo chamado.
            </div>
					</td>
          
					<td align="right">
						<!-- <a class="menu" href="javascript:parent.location.href='./index.php'" >Fechar arquivo</a> -->
            <? 
            if (!$_REQUEST["download"]) {
						  echo "<a class='menu' href='javascript:top.close();' >Fechar arquivo</a>";
            }
            ?>
          </td>
				</tr>
			</table>										
</body>
</html>
