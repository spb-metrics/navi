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

/*
 * Este arquivo nao usa o config.php por performance, para nao precisar carregar toda a biblioteca,
 * bem como tem as consultas diretamente 
 */ 
include('online.inc.php');
session_name(SESSION_NAME); session_start();// security(); 


//CONEXAO AO BD
mysql_connect(BD_HOST, BD_USER, BD_SENHA);
mysql_select_db(BD_NAME);
$codPessoa=(int)$_SESSION['COD_PESSOA'];

//Marca alive
$result = mysql_query('update pessoa SET alive='.time().' Where COD_PESSOA='.$codPessoa);

//busca torpedos
$sql = "Select codPessoaOrigem,date_format(data,'%d/%c/%Y %H:%i') as data,mensagem from ".TABELA_TORPEDO. 
       " Where lido=0 and codPessoaDestino=".$codPessoa;

//echo $sql;       
$result = mysql_query($sql);

if (mysql_num_rows($result)) {
  header("Content-Type: text/html; charset=ISO-8859-1"); //acentua��o do portugu�s
  echo "<div align='right'><img src='".$url."/imagens/fecha.png' onClick='showTorpedos();' style='cursor:hand;'></div>";
  
  while ($linha = mysql_fetch_assoc($result)) {
    $tempQuery = mysql_query('Select NOME_PESSOA FROM pessoa Where COD_PESSOA='.$linha['codPessoaOrigem']);
    $pessoaOrigem = mysql_fetch_assoc($tempQuery);
    echo "<a href='".$url."/alunos/enviatorpedo.php?fechar=1&codPessoaDestino=".$linha['codPessoaOrigem']."' target='_blank' title='Clique para responder'>";
    echo "<b>".$pessoaOrigem['NOME_PESSOA']."<br>".$linha['data']."</b><br>";
    echo nl2br(strip_tags($linha['mensagem'])); //por hora retiramos todo o HTML
    echo "</a>";
    echo "<br><br>";
  }
  
  mysql_query("update ".TABELA_TORPEDO." set lido=1 where codPessoaDestino=".$codPessoa );  
}
?>