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


//inclui a biblioteca magpierss
include('rss_fetch.inc');

//define para fazer cache
define('MAGPIE_CACHE_ON',true);

//define o diretorio para cache de RSS
define('MAGPIE_CACHE_DIR', '/temp/magpie_cache');

 function cmp($a,$b) {
     return ($a['date_timestamp'] > $b['date_timestamp']) ? -1 : 1;
  }   
/**
 * Classe para manipulacao de uma feed RSS
 */ 
class RSSFeed {
  
  //o objeto RSS
  var $rssObj;
  
  //construtor
  function RSSFeed($url) {
    $this->parseRSS($url);
  }
    
  //le e faz o parse de um recurso RSS
  function parseRSS($url) {
    $this->rssObj = fetch_rss($url);
  }
  
  //retorna os itens deste RSS
  function getItens() {
    return $this->rssObj->items;
  }
  
}

/**
 * Classe para manipulacao de varias feeds RSS agragadas
 * 
 */ 
class RSSFeedAgregator {
  var $itens;
  
  //construtor
  //recebe como parametro um array das feeds que devem ser lidas
  function RSSFeedAgregator($urlFeeds,$orderByDate=1) {
    $this->itens = array();
  
    foreach($urlFeeds as $url) {
      //cria a feed para cada uma das urls
      $feed = new RSSFeed($url);
      $itens = $feed->getItens();
       
      foreach($itens as $item) {
        //acrescenta, para cada item, o titulo do canal, para saber de onde 
        //veio a noticia
        $item['channelTitle'] = $feed->rssObj->channel['title'];
        $this->itens[] = $item;
      
      }
    } 
    //agora ordena as entradas pela data
    if ($orderByDate) {
     //funcao para comparacao entre as datas de publicacao
        usort($this->itens, "cmp");
    }
  }  

  //retorna os itens desse feed agragator
  function getItens() {
    return $this->itens;
  } 
  
 

}?>
