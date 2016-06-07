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

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include_once ("../config.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
session_name(SESSION_NAME); session_start(); security();
//include_once ($caminhoBiblioteca."/noticia.inc.php");
//include_once ($caminhoBiblioteca."/perfil.inc.php");

//Verifica se este nível possui relacionamento com alunos e/ou professores
$nivelAtual = getNivelAtual();
if (empty($nivelAtual->nomeFisicoTabelaRelacionamentoProfessores) && empty($nivelAtual->nomeFisicoTabelaRelacionamentoAlunos) ) {
  echo "<td align='center'><strong>Em ".$nivelAtual->nome." n&atilde;o h&aacute; relacionamento com alunos ou professores.</strong>";
  echo "</td></tr></table>";
  exit(); 
}
?>
<style type="text/css">
@media print {
  .listachamada { display:none; }
}
</style>
<body style='font-family:Verdana,Arial; font-size:10px;'>
<div align='right' class='listachamada'><a href='javascript:window.print();'>Imprimir</a></div>
<?
echo "Lista de Chamada - ".date('d/m/Y')."<br>";
$instancia = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());
echo $instancia->getAbreviaturaOuNomeComPai();

?> 
<hr>
<?php

$rsConN = listaAlunos($numPagina);

echo "<table><tr><td><b><strong><i>Aluno</i></strong></td><td><b><i>Assinatura</b></i></td></tr>";


if (!empty($rsConN)) {
  while ($linha = mysql_fetch_array($rsConN)){
	echo "<tr><td>".$linha["NOME_PESSOA"]. "</td><td>_______________________________</td></tr>";   
  }
}
?>
</table>
<p align="center" class='listachamada'><a href="javascript:history.back()">Voltar</a></p>          
</body>
</html>