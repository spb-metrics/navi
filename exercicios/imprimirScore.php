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

include_once ("../config.php");
include_once ($caminhoBiblioteca."/exercicios.inc.php");
include_once($caminhoBiblioteca."/functionsDeEdicao.inc.php");
session_name(SESSION_NAME); session_start(); security();
echo "<html>".
		   "<head>".
		  // "<link rel=\"stylesheet\" href=\"./sca.css\" type=\"text/css\">".
		  // "<link rel=\"stylesheet\" href=\"./../cursos.css\" type=\"text/css\">".
		   "</head>";
echo $_SESSION['configMathml']['script'];
$nivel = getNivelAtual();
?>
<style type="text/css">
@media print {
  .listaexercicios { display:none; }
}
</style>
<body style='font-family:Verdana,Arial; font-size:10px;'>
<div align='right' class='listaexercicios'><a href='javascript:window.print();'>Imprimir</a></div>

<?

echo "<br><br>";
$exer= new Exercicio();
if(Pessoa::podeAdministrar($_SESSION['userRole'],$nivel,$_SESSION['interage'])){ 
  $alunos=$exer->getScoreImprimir($_REQUEST["codExercicio"],$_SESSION["codInstanciaGlobal"]);
  
}
else {
echo "<B>Você não está autorizado a imprimir!</B>";
}

?>       
</body>
</html>
