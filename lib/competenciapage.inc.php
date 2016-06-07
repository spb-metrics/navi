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

/* Classe Padrao de exibição
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
