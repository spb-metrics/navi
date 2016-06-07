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

//include($caminhoBiblioteca."/defaultpage.inc.php");
//ini_set("display_errors",1);
//error_reporting(E_ALL ^ E_NOTICE);
/* Classe Padrao de exibi��o
 * Divide a pagina em : 
 * cabecalho html
 * logotipo, escolha da Instancia do subNivel
 *  menu (com iframe da ferramenta escolhida) e rodape
 *  
 *
 *
 *
 * As partes ficam dentro dos atributos, alimentados pelos m�todos, 
 * e o m�todo imprime faz a montagem final da p�gina
 * 
 */
class RedeSANPage extends DefaultPage {
  function RedeSANPage($nivel,$instanciaNivel,$direitosUsuario,$userRole,&$navegacao,$param='') {
  
    $this->DefaultPage($nivel,$instanciaNivel,$direitosUsuario,$userRole,$navegacao,$param='');
  }

  function initLogotipos() {
    global $urlImagem;
    
    //Mostra a imagem pr�pria da instancia sistemica, se houver. 
    //Se nao usa uma padr�o
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
    echo $this->espacoRecursos; //espa�o de ativa��o do recurso
    
    //echo "</div>";
    
    echo $this->caixaTorpedos;
    echo $this->rodape;
  }
  
 
}
?>
