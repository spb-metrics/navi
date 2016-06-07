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
