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