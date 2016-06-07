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


/**
 * Script para visualizacao de anexo, nao necessitando q o usuario esteja logado
 * Para permitir q pessoas que estejam lendo o email a partir de email externo
 * tenham acesso aos anexos  
 */ 
 
 

//confere se as variaveis foram passadas por parametro
if (!isset($_REQUEST['codMsg'])) { echo 'codMsg not set'; exit; }
if (!isset($_REQUEST['nomeArq'])) { echo 'nomeArq not set'; exit; }
if (!isset($_REQUEST['hash'])) { echo 'hash not set'; exit; }



//inclui arquivo de configuracao
include('../../config.php');
//inclui arquivo de biblioteca do correio
include($caminhoBiblioteca.'/correio.inc.php');

ini_set('display_errors',1);
error_reporting(E_ALL);

$anexoRes = correioMsgGetAnexos($_REQUEST['codMsg'], $_REQUEST['nomeArq']);

if (empty($anexoRes->records[0])) { echo 'empty anexoRes->records'; exit; }

$anexo = &$anexoRes->records[0];

//confere se o hash esta correto
if (correioGetMd5Anexo($anexo) != $_REQUEST['hash']) { 
  echo 'hash diferentes<br>';
  echo correioGetMd5Anexo($anexo).'<br>';
  echo $_REQUEST['hash'];
 
  exit;
  
}

//tudo conferiu ok, pode exibir anexo
enviaAnexoBrowser($anexo->codMsg,$anexo->nomeArq,$anexoRes);

?>
