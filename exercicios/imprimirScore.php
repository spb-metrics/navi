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
echo "<B>Voc� n�o est� autorizado a imprimir!</B>";
}

?>       
</body>
</html>
