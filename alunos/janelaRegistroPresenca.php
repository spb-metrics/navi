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
//include_once ($caminhoBiblioteca."/curso.inc.php");
include($caminhoBiblioteca."/registropresenca.inc.php");
session_name(SESSION_NAME); session_start(); security();
if (!podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) { die; }
//include_once ($caminhoBiblioteca."/noticia.inc.php");
//include_once ($caminhoBiblioteca."/perfil.inc.php");
//Verifica se este nível possui relacionamento com alunos e/ou professores
$nivelAtual = getNivelAtual();

/*
if (empty($nivelAtual->nomeFisicoTabelaRelacionamentoProfessores) && empty($nivelAtual->nomeFisicoTabelaRelacionamentoAlunos) ) {
  echo "<td align='center'><strong>Em ".$nivelAtual->nome." n&atilde;o h&aacute; relacionamento com alunos ou professores.</strong>";
  echo "</td></tr></table>";
  exit(); 
}

*/

?>
<head>
<title>Configurações</title>
<link rel="stylesheet" href="./sca.css" type="text/css">
<link rel="stylesheet" href="./../cursos.css" type="text/css">
<link rel="stylesheet" href="./../css/endereco.css" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<style type="text/css">
@media print {
  .listachamada { display:none; }
}
</style>

<SCRIPT language=javascript>

var reDecimal = /^[+-]?((\d{1,2}|\d{1,2}(\,\d{1}))(\,\d)?|\,\d)$/;

function doDecimal(campo){
  if (reDecimal.test(campo.value)) {} 
  else if (campo.value != null && campo.value != "") {
    alert(campo.value + " NÃO é uma nota válida.");
    document.getElementById(campo).focus();

  }
}

function func(form){
  if (document.form.metodoAvaliacaoFinal[0].checked){
    document.getElementById("idDefinicaoFF").style.display="";
  }  else{
    document.getElementById("idDefinicaoFF").style.display="none";
  }


  if (document.form.metodoAvaliacao[1].checked && document.form.metodoAvaliacaoFinal[0].checked){
    document.getElementById("idDefinicao").style.display="";
  }
  else{
    document.getElementById("idDefinicao").style.display="none";
  }

}

</script>

<body bgcolor='#ffffff' style='font-family:Verdana,Arial; font-size:10px;'>
<?
////////////////////////////////////////////////////////////////////////////////////////////////////////

function get_post_action($name)
{
    $params = func_get_args();
    
    foreach ($params as $name) {
        if (isset($_POST[$name])) {
            return $name;
        }
    }
}
$codInstanciaGlobal = $_SESSION['codInstanciaGlobal'];

switch (get_post_action('', 'salvar', 'cancelar')) {
  case '': 
 
       
 
    $instancia = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());


    echo "<B>".$nivelAtual->nome." ".$instancia->getAbreviaturaOuNomeComPai()."</B>";

    echo "<hr>";

    $rsInstancia = getConfiguracaoRegistroPresenca($codInstanciaGlobal); 
    $instancia = mysql_fetch_array($rsInstancia); 
    $arredBaixo = $instancia['arredBaixo'];
    $numeroAulas = $instancia['numeroAulas'];
    $percentPresencaMin = $instancia['percentPresencaMin'];
    $metodoAvaliacao = $instancia['metodoAvaliacao'];
    $metodoAvaliacaoFinal = $instancia['metodoAvaliacaoFinal'];
    $nroAvaliacoes = $instancia['nroAvaliacoes'];
    $mostrarNotaFinal = $instancia['mostrarNotaFinal'];
    $checkFaltas = $instancia['checkFaltas'];
  
    echo "<form name=form method=post action='janelaRegistroPresenca.php'>";
    echo "<b>Defina o número total de aulas para esta turma:";
    echo "<input type='text' name='numeroAulas' size='5' value='".$numeroAulas."'><br>";
    echo "Defina o percentual mínimo de presença:";
    echo "<input type='text' name='percentPresencaMin' size='5' value='".$percentPresencaMin."'><br>";
    echo "Defina o arredondamento do percentual de presenças do aluno:<br>";
    echo "<input type='radio' name='arredBaixo' value='0' ";
    if ($arredBaixo==0){echo"checked";}
    echo "> Para Cima<br>";
    echo "<input type='radio' name='arredBaixo' value='1' ";
    if ($arredBaixo==1){echo"checked";}
    echo "> Para Baixo<br><br>";
    echo "Defina o número de notas:";
    echo "<input type='text' name='nroAvaliacoes' size='5' value='".$nroAvaliacoes."'><br><br>";
    echo "Defina o método de avaliação das notas intermediárias:<br>";

echo"<table><tr><td valign=top>";
    echo "<input type='radio' name='metodoAvaliacao' value='0' onclick='func(form)'";
    if ($metodoAvaliacao==0){echo"checked";}
    echo "> Conceito(A, B, C, D, E, FF)<br>";
    echo "<input type='radio' name='metodoAvaliacao' value='1' onclick='func(form)'";
    if ($metodoAvaliacao==1){echo"checked";}
    echo "> Nota(0-10)<br><br>";


    echo "<b><u>Nota Final:</u><br>";
    echo "<input type='checkbox'";
    if ($mostrarNotaFinal==1){echo" checked ";}
    echo"name='mostrarNotaFinal' value='1'>";
    echo "Mostrar<br>";
    echo "<input type='radio' name='metodoAvaliacaoFinal' value='0' onclick='func(form)'";
    if ($metodoAvaliacaoFinal==0){echo"checked";}
    echo "> Conceito(A, B, C, D, E, FF)<br>";
    echo "<input type='radio' name='metodoAvaliacaoFinal' value='1' onclick='func(form)'";
    if ($metodoAvaliacaoFinal==1){echo"checked";}
    echo "> Nota(0-10)<br><br>";
echo"</td><td>";


echo"<table><tr><td id='idDefinicaoFF'  colspan=3 ";

if($metodoAvaliacaoFinal==0){echo" style='display:' ";}else{echo" style='display:none' ";}
echo" ><input type=checkbox "; 
if ($checkFaltas==1){echo" checked ";}
echo" name=checkFaltas>Marcar FF automaticamente<br>para aluno com número de faltas<br>superior ao estipulado.</td></tr>";

echo"<tr><td><table id='idDefinicao' ";
if($metodoAvaliacao==1 && $metodoAvaliacaoFinal==0){echo" style='display:' ";}else{echo" style='display:none' ";}

echo"<tr><td></td><td align=center>Inicial</td><td align=center>Final</td></tr>";
$instancia['aInicial']=strtr($instancia['aInicial'],".",",");
$instancia['aFinal']=strtr($instancia['aFinal'],".",",");
$instancia['bInicial']=strtr($instancia['bInicial'],".",",");
$instancia['bFinal']=strtr($instancia['bFinal'],".",",");
$instancia['cInicial']=strtr($instancia['cInicial'],".",",");
$instancia['cFinal']=strtr($instancia['cFinal'],".",",");
$instancia['dInicial']=strtr($instancia['dInicial'],".",",");
$instancia['dFinal']=strtr($instancia['dFinal'],".",",");
$instancia['eInicial']=strtr($instancia['eInicial'],".",",");
$instancia['eFinal']=strtr($instancia['eFinal'],".",",");

echo"<tr><td>A</td><td><input type=text name='aInicial' size=5 onBlur='doDecimal(this);'";
if($instancia['aInicial']){echo"value='".$instancia['aInicial']."'";}else{echo"value='9'";}
echo" ></td><td><input type=text name='aFinal' size=5 onBlur='doDecimal(this);'";
if($instancia['aFinal']){echo"value='".$instancia['aFinal']."'";}else{echo"value='10'";}
echo" ></td></tr>";

echo"<tr><td>B</td><td><input type=text name='bInicial' size=5 onBlur='doDecimal(this);'";
if($instancia['bInicial']){echo"value='".$instancia['bInicial']."'";}else{echo"value='7,5'";}
echo" ></td><td><input type=text name='bFinal' size=5 onBlur='doDecimal(this);'";
if($instancia['bFinal']){echo"value='".$instancia['bFinal']."'";}else{echo"value='8,9'";}
echo" ></td></tr>";


echo"<tr><td>C</td><td><input type=text name='cInicial' size=5 onBlur='doDecimal(this);'";
if($instancia['cInicial']){echo"value='".$instancia['cInicial']."'";}else{echo"value='6,0'";}
echo" ></td><td><input type=text name='cFinal' size=5 onBlur='doDecimal(this);'";
if($instancia['cFinal']){echo"value='".$instancia['cFinal']."'";}else{echo"value='7,4'";}
echo" ></td></tr>";

echo"<tr id='idDefinicao' ";
if($metodoAvaliacao==1 && $metodoAvaliacaoFinal==0){echo" style='display:' ";}else{echo" style='display:none' ";}

echo"><td>D</td><td><input type=text name='dInicial' size=5 onBlur='doDecimal(this);'";
if($instancia['dInicial']){echo"value='".$instancia['dInicial']."'";}else{echo"value='4,0'";}
echo" ></td><td><input type=text name='dFinal' size=5 onBlur='doDecimal(this);'";
if($instancia['dFinal']){echo"value='".$instancia['dFinal']."'";}else{echo"value='5,9'";}
echo" ></td></tr>";

echo"<tr><td>E</td><td><input type=text name='eInicial' size=5 onBlur='doDecimal(this);'";
if($instancia['eInicial']){echo"value='".$instancia['eInicial']."'";}else{echo"value='0'";}
echo" ></td><td><input type=text name='eFinal' size=5 onBlur='doDecimal(this);'";
if($instancia['eFinal']){echo"value='".$instancia['eFinal']."'";}else{echo"value='3,9'";}
echo" ></td></tr>";
echo"</table></td></tr></table>";


echo"</td></tr></table>";
    
    echo"<input type=submit name=salvar value='Salvar'  class='okButton'>";
    echo"<input type=submit name=cancelar value='Cancelar'  class='cancelButton'></form>";
  break;
    
    case 'salvar': //echo"<pre>";print_r($_REQUEST);die;

      if ($_REQUEST['checkFaltas']){$checkFaltas=1;}else{$checkFaltas=0;}
      if ($_REQUEST['mostrarNotaFinal']==1){$mostrarNotaFinal=1;}else{$mostrarNotaFinal=0;}
      if ($_REQUEST['calcularNotaFinal']==1){$calcularNotaFinal=1;}else{$calcularNotaFinal=0;}

$_REQUEST['aInicial']=strtr($_REQUEST['aInicial'],",",".");
$_REQUEST['aFinal']=strtr($_REQUEST['aFinal'],",",".");
$_REQUEST['bInicial']=strtr($_REQUEST['bInicial'],",",".");
$_REQUEST['bFinal']=strtr($_REQUEST['bFinal'],",",".");
$_REQUEST['cInicial']=strtr($_REQUEST['cInicial'],",",".");
$_REQUEST['cFinal']=strtr($_REQUEST['cFinal'],",",".");
$_REQUEST['dInicial']=strtr($_REQUEST['dInicial'],",",".");
$_REQUEST['dFinal']=strtr($_REQUEST['dFinal'],",",".");
$_REQUEST['eInicial']=strtr($_REQUEST['eInicial'],",",".");
$_REQUEST['eFinal']=strtr($_REQUEST['eFinal'],",",".");

      $rs = gravaconfiguracaoRegistroPresenca($codInstanciaGlobal,$_REQUEST['numeroAulas'],$_REQUEST['percentPresencaMin'],$_REQUEST['arredBaixo'],$_REQUEST['nroAvaliacoes'],$_REQUEST['metodoAvaliacao'],$_REQUEST['metodoAvaliacaoFinal'],$mostrarNotaFinal,$_REQUEST['aInicial'],$_REQUEST['aFinal'],$_REQUEST['bInicial'],$_REQUEST['bFinal'],$_REQUEST['cInicial'],$_REQUEST['cFinal'],$_REQUEST['dInicial'],$_REQUEST['dFinal'],$_REQUEST['eInicial'],$_REQUEST['eFinal'],$checkFaltas);


      echo '<script type="text/javascript">
            opener.document.location.reload(true);
            self.close();
            </script>';
    break;
    
    case 'cancelar':
      echo '<script type="text/javascript">
            self.close();
            </script>';
        break;
}
?>
</body>
</html>