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

//include($caminhoBiblioteca."/defaultpage.inc.php");
//ini_set("display_errors",1);
//error_reporting(E_ALL ^ E_NOTICE);
/* Classe Padrao de exibição
 * Divide a pagina em : 
 * cabecalho html
 * logotipo, escolha da Instancia do subNivel
 *  menu (com iframe da ferramenta escolhida) e rodape
 *  
 *
 *
 *
 * As partes ficam dentro dos atributos, alimentados pelos métodos, 
 * e o método imprime faz a montagem final da página
 * 
 */
class RedeSANPage extends DefaultPage {
  function RedeSANPage($nivel,$instanciaNivel,$direitosUsuario,$userRole,&$navegacao,$param='') {
  
    $this->DefaultPage($nivel,$instanciaNivel,$direitosUsuario,$userRole,$navegacao,$param='');
  }

  function initLogotipos() {
    global $urlImagem;
    
    //Mostra a imagem própria da instancia sistemica, se houver. 
    //Se nao usa uma padrão
    /*
    if (!empty($this->instanciaNivel->codArquivoLogotipo)) {
      $codArquivoLogotipo=$this->instanciaNivel->codArquivoLogotipo;
    }
    else {
      $codArquivoLogotipo = LOGOTIPO_PADRAO; 
    }
    $arq = new ArquivoMultiNavi($codArquivoLogotipo);
    $this->logotipos.="<img src='".$urlImagem."/".PASTA_LOGOTIPOS.
                      "/".$arq->caminhoFisico."'>";
    */
    //Logotipo do NAVi
    $this->logotipos.="<a href='http://navi.ea.ufrgs.br' target='_blank' ><img src='".$urlImagem."/logo.gif' border='0' height='35px' width='75px'></a>";

  }

  /*
   * Formata as subpartes e joga a pagina para o navegador 
   */
  function imprime() {
    global $urlImagem;
    
    echo $this->cabecalho;
    //Div geral
    //echo "<div class='geral' id='idGeral'>";

    //tabela que contem os widgets acima do menu
    echo "<table cellpadding='0' cellspacing='0' width='100%'>";
    echo '<tr>';
    echo '<td><img src="'.$urlImagem.'/'.PASTA_LOGOTIPOS.'/topo_final_redesan.jpg">';
    echo '</td>';  
    echo '<td bgcolor="#FBCD06" width="100%"><img src="'.$urlImagem.'/'.PASTA_LOGOTIPOS.'/mds.png"></td>';
    echo '<td bgcolor="#FBCD06" width="100%"><img src="'.$urlImagem.'/'.PASTA_LOGOTIPOS.'/brasil.gif"></td>';
    echo '</tr></table>';

    echo "<table class='topo' cellpadding='0' cellspacing='0'>";     
    echo "<tr><td class='colunaImagens'>".$this->logotipos."</td>";
    echo "<td class='colunaSubNivelCaixaLogin'>".
         "<span class='escolhaSubNivel'>".$this->escolhaSubNivel."</span>".
         "<span class='caixaLogin'>".$this->caixaLogin."</span></td>";  
    echo "<td class='caixaInstancias'>".$this->caixaInstancias."</td>";
    echo "<td class='widgetsFinais'>".$this->widgetsFinais.
         $this->widgetComunidades."</td>";    
    echo "</tr></table>";
    echo $this->menu;           //Menu de recursos ativados e tambem os fixos
    echo $this->espacoRecursos; //espaço de ativação do recurso
    
    //echo "</div>";
    
    echo $this->caixaTorpedos;
    echo $this->rodape;
  }
  
 
}
?>
