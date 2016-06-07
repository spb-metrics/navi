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
?>
<html>
<head>
	<link rel="stylesheet" href="././chat.css" type="text/css">

	<script>
		function mover_texto() {
		    if (document.form1.scrool_text.checked) {
		        window.parent.frames[1].scrollBy(0,10000);
		    }
		}

		function submit_exit() {
		    parent.frames[2].document.form1.MESSAGE.value = "/quit";
		    parent.frames[2].document.form1.submit();
		}
	</script>
	
</head>

<body TEXT="#FFFFFF" leftmargin="5" topmargin="5">
	<center>
		<BR>
		<form name='form1' method='post'>
    <table width="100%">
			<tr>
				<td align="center">
			<!--<input type='button' name='reload' value='Atualizar Nomes' onClick="javascript:parent.frames[2].location.href='C.php';">
							<!--&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; -->
			</td>
	<!--		<td align="center">
      <input type='checkbox' name='scrool_text' value='yes' checked> <font face="Verdana, Arial, Helvetica, sans-serif" size="1">Rolagem Automatica</font> 
							<!--&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
			</td>-->
			<td align="center">
     <!-- <input type='button' name='exit' value=' Sair '	onClick="javascript:submit_exit()">-->
      </td>
      </tr>
      </table>
		</form>
    </center>
  </body>

</html>