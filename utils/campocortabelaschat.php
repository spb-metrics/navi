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


define(BD_HOST,"mysql.tj.rs.gov.br");
define(BD_USER,"create_navi");
define(BD_SENHA,"3@d7jr5");
define(BD_NAME,"navi");

mysql_connect(BD_HOST, BD_USER, BD_SENHA);
mysql_select_db(BD_NAME);

//numero de tabelas de mensagem do chat
$numTabelasChat=1200;

for ($i=1;$i<=$numTabelasChat;$i++ ) {
  echo '<br>Tabela '.$i;
	mysql_query("ALTER TABLE chat_mensagem_".$i." ADD `COR` varchar( 30 ) NOT NULL default ''");
	echo mysql_error();
}

?>
