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

<table width="770" border="0" cellspacing="0" cellpadding="0"  class="princ" height="85">
  <tr> 
    <td> 
      <table width="770" border="0" cellspacing="0" cellpadding="0" height="70">
        <tr> 
          <td width="51"></td>
          <td width="268">&nbsp;</td>
          <td colspan="2"><a href="http://www.brasil.gov.br" target="_blank"><img src="../imagens/trans.gif" width="73" height="8" border="0"></a></td>
        </tr>
        <tr> 
          <td width="51" height="56" rowspan="2"></td>
          <td height="56" rowspan="2" width="268"><img src="../imagens/titulo.gif" width="185" height="32" usemap="#Map" border="0"></td>
        </tr>
        <tr> 
          <td align="right"> </td>
		  <td align="right">
	  
			<div align='center' class="menu"> 
				<?php
				include('../defineSession.php');
        session_name(SESSION_NAME); session_start(); security();
				
				if ($_SESSION["NOME_PESSOA"] <> "")
					echo "<BR> Bem-Vindo(a), " . $_SESSION["NOME_PESSOA"] . ".";
				?>
			</div>   
		  </td>
          <td width="84" height="33">&nbsp;</td>		  
		  		  				  
        </tr>
      </table>
      <table width="770" border="0" cellspacing="0" cellpadding="0" height="10">
        <tr> 
          <td class="menu">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<map name="Map">
  <area shape="rect" coords="1,1,202,77" href="./../principal.php" target="_self">
</map>
