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
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); ini_set("display_errors",1);
include("online.inc.php");
session_name(SESSION_NAME); session_start(); 
//CONEXAO AO BD
mysql_connect(BD_HOST, BD_USER, BD_SENHA);
mysql_select_db(BD_NAME);
//marca o usuario como estando offline
mysql_query('update pessoa set alive=0 where COD_PESSOA='.(int)$_SESSION['COD_PESSOA']);

$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
   //echo "vou pedalar ".session_name();
   setcookie(session_name(), '', time()-42000, '/');
}

//Deleta os demais cookies
//cookie para garantir que é a mesma pessoa
setcookie('codPessoa','', time()-42000,'','',1);	
//codkie do servidor para garantir que é o mesmo servidor
setcookie('server','', time()-42000,'','',1);	
//cookie de conferencia
setcookie('id','', time()-42000,'','',1);	
unset($_SESSION);
session_destroy();

header("Location: ".$url);
?>