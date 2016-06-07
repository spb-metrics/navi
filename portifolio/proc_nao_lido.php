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


include_once ("./../config.php");
include_once ($caminhoBiblioteca."/portfolio.inc.php");
session_name(SESSION_NAME); session_start(); security();
//inclui novamente os portifolio para leitura

if($_SESSION["userRole"] != PROFESSOR){
  print "vc nao tem acesso a esta p�gina";
  die();
}

$selected = explode(",",$_POST["selecionados"]);

foreach($selected as $s){
  $codArquivo   = $s;
  $codProfessor = $_SESSION["COD_PROF"];
   
  PortNovoLeitura($codArquivo, $codProfessor,false);
}

print "<html><body><script language='javascript'>alert('opera��o realizada com sucesso!');parent.document.location.href='index.php?COD_AL=".$_POST["cd_aluno"]."';</script></body></html>";
exit();
?>
