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

/* Classe Padrao de exibi��o
 * Divide a pagina em : 
 * cabecalho html
 * logotipo, escolha da Instancia do subNivel
 *  menu (com iframe da ferramenta escolhida) e rodape
 *  
 *
 *
 * ESTE ARQUIVO CONTEM O CODIGO DO GOOGLE ANALYTICS
 * 
 */
class CompetenciaPage extends DefaultPage {
  function CompetenciaPage($nivel,$instanciaNivel,$direitosUsuario,$userRole,&$navegacao,$param='') {
  
    $this->DefaultPage($nivel,$instanciaNivel,$direitosUsuario,$userRole,$navegacao,$param='');
  }

  function imprime() {
    
    echo '<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-4757147-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>';
    parent::imprime();    
  }
  
 
}
?>
