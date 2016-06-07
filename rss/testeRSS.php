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


function note($x) {
  echo '<pre>'; print_r($x); echo '</pre>';
}

//inclui a biblioteca de rss
include('lib/rss/rss.inc.php');

echo '<b>Exemplo de leitura de uma varias feeds agregadas</b><br>';

//cria o rss agregator
$feedAgregator = new RSSFeedAgregator(array('http://rss.cnn.com/rss/cnn_topstories.rss',
                                            'http://rss.slashdot.org/Slashdot/slashdot'));

echo 'Not�cias do Slashdot e CNN:<br><br>';

//pega os itens
$itens = $feedAgregator->getItens();

//percorre os itens e imprime
foreach($itens as $item) {
  $data = date('d/m/Y H:i',$item['date_timestamp']);
  echo '<a href="'.$item['link'].'" target="_blank">['.$item['channelTitle'].' '.$data.']: '.$item['title'].'</a><br>';
}

echo '<b>Fim Exemplo de leitura de uma varias feeds agregadas</b><br>';








echo '<br><br>';
echo '<b>Exemplo de leitura de uma feed em particular</b><br>';

//le e faz o parse da feed RSS
$rss = new RSSFeed('http://rss.cnn.com/rss/cnn_topstories.rss');

//pega os itens desse RSS
$itens = $rss->getItens();

echo 'CNN top stories:<br><br>';

foreach($itens as $item) {
  echo '<a href="'.$item['link'].'" target="_blank">'.$item['title'].'</a><br>';
}

echo '<b>Fim do Exemplo de leitura de uma feed em particular</b><br>'; 
?>
