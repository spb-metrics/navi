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
include("../config.php");
//include($caminhoBiblioteca."/curso.inc.php");
include($caminhoBiblioteca."/registropresenca.inc.php");
session_name(SESSION_NAME); session_start(); 
$nivelAtual = getNivelAtual(); 
?>
<html>
<link rel="stylesheet" href="../cursos.css" type="text/css">

<style type="text/css">
@media print {
  .listachamada { display:none; }
}

</style>

<SCRIPT language=javascript>

var reDate  = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{4}$/;
//var reDate = reDate1;


function doDate(dataNovaAula){
  //var reDate = /^\d{1,2}\/\d{1,2}\/\d{1,4}$/;
  //var reDate = reDate1;
  //eval("reDate = reDate");

  if (reDate.test(dataNovaAula)) {
    location.href='registropresenca.php?dataNovaAula=' + dataNovaAula ;
  } 
  else if (dataNovaAula != null && dataNovaAula != "") {
    alert("A data informada N+O Ú vßlida! \n Formato: dd/mm/aaaa");
  }
}

function reload(valor){
  document.location.reload();
}

function iniciaFocus(){
  document.getElementById("notaFinal[1]").focus();

}

var reDecimal = /^[+-]?((\d{1,2}|\d{1,2}(\,\d{1}))(\,\d)?|\,\d)$/;

function doDecimal(campo){
  if (reDecimal.test(campo.value)) {} 
  else if (campo.value != null && campo.value != "") {
    alert(campo.value + " N+O Ú uma nota vßlida.");
//    document.getElementById(campo.focus());
  }
}

///////////////////////////////////////////////////////

var totalPeso=0;
 
function mostraTabelaNotas(numNotas,numAlunos,notaVisualizada,metodoAvaliacao) {

  for(i=1;i<=numNotas;i++) {
    for(j=0;j<=numAlunos;j++) {
      if (notaVisualizada.value==0 || i==notaVisualizada.value) {  
        document.getElementById("cel"+j+"_"+i).style.display="";
//
      }
      else {
        document.getElementById("cel"+j+"_"+i).style.display="none";//none
      }
    }
  }
  if(notaVisualizada.value==0){        
    if(metodoAvaliacao==1){ 
      document.getElementById("input1_1").focus();
    }
  }else{
    if(metodoAvaliacao==1){ 
      document.getElementById("input1_"+notaVisualizada.value).focus();
    }
   }
}


</SCRIPT>
</head>

<body bgcolor='#ffffff' style='font-family:Verdana,Arial; font-size:10px;' >

<div align='right' class='listachamada'><a href='javascript:window.print();'><img src='../imagens/print.png'border=no>&nbsp;&nbsp;&nbsp;<br>Imprimir</a></div>
<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////
//<body bgcolor='#ffffff' style='font-family:Verdana,Arial; font-size:10px;' onLoad='iniciaFocus();'>
$codInstanciaGlobal = $_SESSION['codInstanciaGlobal'];

$rsDatas = listaDatasAulas($codInstanciaGlobal);
$ttAulaRealizado = mysql_num_rows(listaDatasAulas($codInstanciaGlobal));
if ($_SESSION['userRole']== PROFESSOR || $_SESSION['userRole']== ADMINISTRADOR_GERAL) {
echo "
<div align='right'><table><tr><td>
  <form name= 'formDataNovaAula' method='post' action='#' onsubmit='doDate(this.dataNovaAula.value); return false;'>
    Nova aula:<br><input type='text' name='dataNovaAula' id='dataNovaAula' size='12' maxlength='10' value='";

    if ($_REQUEST['dataNovaAula']==''){
      echo date('d/m/Y');
    }else {function reload(){
    document.location.reload();
}

      echo $_REQUEST['dataNovaAula'];
    }

echo "'>&nbsp;<input type='submit' value='ok'></form>
</td>	<td width=200px align=right>
<form name='form1' method=post action=registropresenca.php>Editar presenças do dia:<br><SELECT NAME='datas' id='datas'onChange='form1.submit();'>";
//echo "<OPTION selected='selected'>".date('d/m/Y')."</OPTION>";
  if (!empty($rsDatas)) { 
    while ($linhaRsData = mysql_fetch_array($rsDatas)){
      $dtTimeStamp = strtotime($linhaRsData["dataAula"]);
      $dataConvertida = date('d/m/Y',$dtTimeStamp);
      if ($_REQUEST != '' && $dataConvertida == $_REQUEST['datas']){
        echo "<OPTION selected='selected'>".$dataConvertida."</OPTION>";
      }else{echo "<OPTION>".$dataConvertida."</OPTION>";}
    }
  }
echo "</SELECT></form></td></tr></table></div>";
} 

////////////////////////////////////////////////////////////////////////////////////////////////////////

switch ($_REQUEST['acao']){

case '':
  
  if ($_REQUEST['datas'] != ''){$data = $_REQUEST['datas'];}
  else if ($_REQUEST['dataNovaAula'] != ''){$data = $_REQUEST['dataNovaAula']; }
  else{$data = date('d/m/Y'); }
 
 $dataErrada = $_REQUEST['datas']; //echo '<br>'.$dataErrada;
  // adicinonado input para editar data das aulas ============================== 
  echo"<form name='dataForm' method='post' action='registropresenca.php?&acao=modificaDataAula'>";
  echo "Lista de Chamada - ";//echo $dataErrada;
  echo "<input type=\"text\" name=\"data\" value=\"".$data."\" size=\"12\" maxlength='10'>"; 
  echo "<input type=\"hidden\" name=\"dataErrada\" value=\"".$dataErrada."\" maxlength='10'>";
  echo "&nbsp<input type=\"submit\"  value=\"alterar\" >"; 
  echo "</form>";      
  //============================================================================
  $instancia = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());
  
  echo $nivelAtual->nome." ";
  echo $instancia->getAbreviaturaOuNomeComPai();

  //if ($_SESSION['userRole']== PROFESSOR || $_SESSION['userRole']== ADMINISTRADOR_GERAL) {
  if (Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage'])) { 
    echo "<br><a href='#' onClick='window.open(\"janelaRegistroPresenca.php\",\"JAN\",\"top=0,left=0,status=yes,toolbar=no,location=no,menu=no,width=400,height=480\");'>Configurar esta ".$nivelAtual->nome."</a>";
  }
  echo "<hr>";

  $rsTotalAulas = getConfiguracaoRegistroPresenca($codInstanciaGlobal); 

  $rsTotalAulas = mysql_fetch_array($rsTotalAulas);
  $ttAula = $rsTotalAulas["numeroAulas"];

  if ($ttAula=='') {$ttAula=0;}
  echo "<br><b>&nbsp;Total de Aulas desta ".$nivelAtual->nome.": <u>".$ttAula."</u></b><br>";
  
  echo "<table width=100%><tr><td valign=top><b>Total de Aulas já realizadas: <u>".$ttAulaRealizado."</u></b></td>";

  $rsTotalAulas = getConfiguracaoRegistroPresenca($codInstanciaGlobal); 
  $rsTotalAulas = mysql_fetch_array($rsTotalAulas);
  $ttAula = $rsTotalAulas["numeroAulas"];
  $nroAvaliacoes = $rsTotalAulas["nroAvaliacoes"];
  $metodoAvaliacao = $rsTotalAulas["metodoAvaliacao"]; 
  $metodoAvaliacaoFinal = $rsTotalAulas["metodoAvaliacaoFinal"]; 
  $mostrarNotaFinal = $rsTotalAulas["mostrarNotaFinal"];
  $checkFaltas = $rsTotalAulas["checkFaltas"];

  $aInicial = $rsTotalAulas["aInicial"];$aFinal = $rsTotalAulas["aFinal"];
  $bInicial = $rsTotalAulas["bInicial"];$bFinal = $rsTotalAulas["bFinal"];
  $cInicial = $rsTotalAulas["cInicial"];$cFinal = $rsTotalAulas["cFinal"];
  $dInicial = $rsTotalAulas["dInicial"];$dFinal = $rsTotalAulas["dFinal"];
  $eInicial = $rsTotalAulas["eInicial"];$eFinal = $rsTotalAulas["eFinal"];

  $nroAlunos=numeroAlunosTurma();

  if($metodoAvaliacaoFinal==0){$tipoFinal = 1;}else{$tipoFinal = 0;}

  echo "<td align=right>";
     echo"<form name='formPresenca' method='post' action='registropresenca.php?acao=gravaNovasPresencas'>";
  echo"Mostar Avaliações:<SELECT NAME='mostrarAvaliacoes' id='mostrarAvaliacoes' onChange='mostraTabelaNotas(".$nroAvaliacoes.",".$nroAlunos.",this,".$metodoAvaliacao.")'>";
  if (!empty($nroAvaliacoes)) {
    echo "<OPTION value='-1'>Nenhuma Avalição</OPTION><OPTION value='0'>Todas Avaliações</OPTION>";
    for($i=1; $i<=$nroAvaliacoes; $i++){ 
      echo "<OPTION value='".$i."'>".$i."ª Avaliações</OPTION>";
    }
  }
  echo "</SELECT>";
  echo"</td></tr></table>";  

  //armazena array com o numero parcial de presenþas cada aluno
  $rsAulasParc = getNumeroAulasParcial($codInstanciaGlobal);

  $aulasParcial= array();
  while ($linha = mysql_fetch_array($rsAulasParc)){
    $aulasParcial[$linha['COD_AL']] = $linha['numPresencas'];
  }
  
/*
      if($mostrarNotaFinal == 1){
        if ($_SESSION['userRole']== PROFESSOR || $_SESSION['userRole']== ADMINISTRADOR_GERAL) {
          echo"<div align=right><input type='checkbox' name='cb' value='cb' onClick='reload(this);'>Calcular MÚdia AritmÚtica automßticamente</div>";
        }
      }*/
//  echo "<table align=center bgcolor=black cellpadding='0' cellspacing='0'><tr><td><table cellpadding='0' cellspacing='1'>";

  echo "<table style=\"background-color:black\" align=center cellpadding='0' cellspacing='0'><tr><td><table border=0 cellpadding='1' cellspacing='1'>";
  echo  "<tr bgcolor='c6d3ff' style='font-weight: bold' align=center>";
      echo"<td align=center><i>Aluno</i></td>";
//sœ mostra essa <td> se for professor ou administrador
    if ($_SESSION['userRole']== PROFESSOR || $_SESSION['userRole']== ADMINISTRADOR_GERAL) {
      echo"<td style='padding-left: 5px;padding-right: 5px;'><i>Presente</i></td>";
    }

    $rsPercentInstancia = getConfiguracaoRegistroPresenca($codInstanciaGlobal); 
    $percentInstancia = mysql_fetch_array($rsPercentInstancia); 
    $arredBaixo = $percentInstancia['arredBaixo'];
    $percentInstancia = $percentInstancia['percentPresencaMin']; 
    $faltasPossiveis = ($ttAula * (100-$percentInstancia))/100; 
    
    $notas = getNotas($codInstanciaGlobal);
    //echo 'ERRO: '.mysql_error();
    if($nroAvaliacoes){
      $pesos=getPeso($codInstanciaGlobal,$nroAvaliacoes);
      $peso=mysql_fetch_array($pesos);
    }


    echo "<td style='padding-left: 5px;padding-right: 5px;'><i>Aulas Frequentadas</i></td>
          <td style='padding-left: 5px;padding-right: 5px;'><i>% total de Presen&ccedil;a</i></td>";

    if ($arredBaixo==1){$faltasPossiveis = floor($faltasPossiveis);}
    else{$faltasPossiveis = ceil($faltasPossiveis);}
    
    echo "<td style='padding-left: 5px;padding-right: 5px;'><i>Faltas (m&aacute;ximo ";echo $faltasPossiveis;echo")</i></td>";

    for($i=1; $i<=$nroAvaliacoes; $i++){
      echo "<td id='cel0_".$i."' style='padding-left: 5px;padding-right: 5px;display:none;'>Avaliações ".$i;//none
//      if($metodoAvaliacao==1){
        echo"<br>Peso <"; 
        if ($_SESSION['userRole']== ALUNO) {echo"label ";}else{echo "input style='text-align:center;border:1px solid white' onfocus=\"this.style.border='1px solid black'\" onblur=\"this.style.border='1px solid white'\" type=text maxlength=5 ";}
        echo" name='peso[".$i."]' id='peso[".$i."]'";

        if(!empty($peso[$i-1])){
          $peso[$i-1]=strtr($peso[$i-1],".",",");
          echo" value='".$peso[$i-1]."' ";
        }else{echo" value= 1 ";}
        echo"size=4 onkeypress='mask(this,event)' onkeyup='return calculaMediaAritmetica(".$nroAvaliacoes.",0,".$nroAlunos.",".$tipoFinal.",".$aInicial.");' onBlur='doDecimal(this);' >";
        if ($_SESSION['userRole']== ALUNO) {
          if (!empty($peso[$i-1])){ 
            echo$peso[$i-1]."</label>";
          }else{echo "1</label>";}  
        }
      //}
      echo"</td>";
     
    } 

    echo "<td style='padding-left: 5px;padding-right: 5px; ";
    if($mostrarNotaFinal==1){
      echo"display:;";
    }else{echo"display:none;";}//none
    echo"'>Nota Final</td>";
    
    echo "</tr>";
      if (($_REQUEST['datas']!='')||($_REQUEST['dataNovaAula']!='')){
         $nros=explode("/",$data);
         $data=trim($nros[2])."-".trim($nros[1])."-".trim($nros[0]); 
      }else{
        $data = date('Y-m-d');
       } 
  if ($_SESSION['userRole']== ALUNO) {
//  $rsConN = listaAlunosPresenca($codInstanciaGlobal,$data,$_SESSION['COD_PESSOA']);

  $rsConN = listaAlunosPresenca($codInstanciaGlobal,$data);
  }
  else { 
      
    $rsConN = listaAlunosPresenca($codInstanciaGlobal,$data);
  }

  $l=1;  //LINHAS DA TABLE
  if (!empty($rsConN)) { 
    $faltas = $ttAulaRealizado-$aulasParcial[$linha['COD_AL']];
    while ($linha = mysql_fetch_array($rsConN)){
      //echo $_SESSION['COD_ALUNO'];
      $faltas = $ttAulaRealizado-$aulasParcial[$linha['COD_AL']];
      echo "<tr";
      if($_SESSION['userRole']== ALUNO && $linha['COD_AL']!=$_SESSION['COD_AL']){echo" style='display:none;' ";}//none
      echo " bgcolor=white align=center ";
      $reprovado=0;
      if ($faltas > $faltasPossiveis){$reprovado=1;echo "style='color: red;' ";}
      echo " >";
      echo "<td style='padding-left: 5px;padding-right: 5px;'>".$linha["NOME_PESSOA"]."</td>";    

      //sœ mostra essa <td> se for professor ou administrador
      if ($_SESSION['userRole']== PROFESSOR || $_SESSION['userRole']== ADMINISTRADOR_GERAL) {
        echo "<td><input type=checkbox ";
        if ($linha['presente'] != ""){   echo " checked "; } else { echo " unchecked "; }
        echo " name=presenca[] value=".$linha['COD_AL']."></td>";
      }

      echo "<td>";
      if ($aulasParcial[$linha['COD_AL']]=='') { $aulasParcial[$linha['COD_AL']]=0;}
      echo $aulasParcial[$linha['COD_AL']]."</td>";
      if (($ttAula=='') || ($ttAula==0)) { $percenPresencas=0;
      }else{$percenPresencas = ($aulasParcial[$linha['COD_AL']] * 100) / $ttAula;}
      echo "<td>";  
      echo round($percenPresencas,1)."%</td>";

      echo "<td>";
      echo $faltas."</td>";
////////////////////////////////////////////////
        if($notas!=''){
        $nota = mysql_fetch_array($notas);
        }
        for($i=1; $i<=$nroAvaliacoes; $i++){ 
          $nota[$i+1]=strtr($nota[$i+1],".",",")  ;
          if($metodoAvaliacao==1) {// NOTA 0-10
            echo "<td id='cel".$l."_".$i."' style='display:none;' class=\"branco\"><";//none
            if ($_SESSION['userRole']== ALUNO) {echo"label ";}else{echo "input type=text maxlength=5 ";}
            echo"name='notas[".$linha['COD_AL']."][".$i."]' id='input".$l."_".$i."' style='border:0px;";
            if($reprovado==1){echo" color:red; ";}
            echo"text-align:center; width:71px; height:19px;' onfocus=\"focusCelula('cel".$l."_".$i."');\"  onBlur=\"onBlurCelula('cel".$l."_".$i."');\" value='".$nota[$i+1]."'";  
if(!($reprovado==1 && $checkFaltas==1 && $metodoAvaliacao==0)){
echo" onkeypress='mask(this,event)' onkeyup='return calculaMediaAritmetica(".$nroAvaliacoes.",".$l.",".$nroAlunos.",".$tipoFinal.");'";
}
          echo">"; 
          if ($_SESSION['userRole']== ALUNO) {echo $nota[$i+1]."</label>";}
          echo"</td>";
          }
          else{//CONCEITOS A-FF //none
            if ($_SESSION['userRole']== ALUNO) {echo"<td id='cel".$l."_".$i."' style='display:none;'>".$nota[$i+1]."</td>";}

echo "<td id='cel".$l."_".$i."' style='display:none;'><select "; if($reprovado==1){echo" style='color:red;border:0px'";}echo" id='conceito' name='notas[".$linha['COD_AL']."][".$i."]' style='border:0px solid;'><option name=''";if($nota[$i+1]==''){echo" selected ";}echo" value=''></option><option name='A'";if($nota[$i+1]=='A'){echo" selected ";}echo" value='A'>A</option> <option ";if($nota[$i+1]=='B'){echo" selected ";}echo" value='B'>B</option> <option ";if($nota[$i+1]=='C'){echo" selected ";}echo" value='C'>C</option> <option ";if($nota[$i+1]=='D'){echo" selected ";}echo" value='D'>D</option> <option ";if($nota[$i+1]=='E'){echo" selected ";}echo" value='E'>E</option> <option ";if($nota[$i+1]=='FF'){echo" selected ";}echo" value='FF'>FF</option></select></td>";
          } 
        }

          $nota['notaFinal']=strtr($nota['notaFinal'],".",",");
          if($metodoAvaliacaoFinal==1) {// NOTA FINAL (NOTA 0-10)
            echo "<td id=\"celu".$l."_".$i."\" class=\"branco\"><";
            if ($_SESSION['userRole']== ALUNO) {echo"label ";}else{echo "input  onfocus=\"focusCelula('celu".$l."_".$i."');\ onBlur=\"onBlurCelula('celu".$l."_".$i."');\" type=text maxlength=5 ";}
            echo "name='notaFinal[".$l."]' id='notaFinal[".$l."]' style='border:0px; "; 
            if($reprovado==1){echo" color:red; ";} 
            if($mostrarNotaFinal==1){
              echo"display:inline;";
            }else{echo"display:none;";}//none
            echo"text-align:center; width:71px; height:19px;' value='".$nota['notaFinal']."'> ";
            if ($_SESSION['userRole']== ALUNO) {echo$nota['notaFinal']."</label>";}
            echo"</td>";   
          }
          else{ //NOTA FINAL DOS CONCEITOS (COMBO)
            echo "<td>";
            if ($_SESSION['userRole']== ALUNO){
              echo"<label name='notaFinal[".$l."]' id='notaFinal[".$l."]' style='border:0px solid; ";
              if($mostrarNotaFinal==1){
                echo"display:inline;";
              }else{echo"display:none;";}//none
              echo"'>".$nota['notaFinal']."</label>";
            }
            else{
              echo"<select";if($reprovado==1){echo" style='color:red;border:0px'";}echo" name='notaFinal[".$l."]' id='notaFinal[".$l."]' style='border:0px solid; ";
              if($mostrarNotaFinal==1){
                echo"display:inline;";
              }else{echo"display:none;";}//none
              echo"'><option name=''";if($nota['notaFinal']==''){echo" selected ";}echo" value=''></option><option name='A'";if($nota['notaFinal']=='A'){echo" selected ";}echo" value='A'>A</option> <option ";if($nota['notaFinal']=='B'){echo" selected ";}echo" value='B'>B</option> <option ";if($nota['notaFinal']=='C'){echo" selected ";}echo" value='C'>C</option> <option ";if($nota['notaFinal']=='D'){echo" selected ";}echo" value='D'>D</option> <option ";if($nota['notaFinal']=='E'){echo" selected ";}echo" value='E'>E</option> <option ";if($nota['notaFinal']=='FF'){echo" selected ";}if($reprovado==1 && $checkFaltas==1){echo" selected ";}echo" value='FF'>FF</option></select> </td>";
             }
            }
////////////////////////////////////////////////////
      echo "</tr>";
    $l++;
    }

  } 
  //echo '393';
  echo "</table></td></tr></table>";
  echo "</td></tr></table>";


  //hiddens
  echo"<input type='hidden' name='nroAlunos'value='".$nroAlunos."'>
       <input type='hidden' name='avaliacao'value='".$_REQUEST['mostrarAvaliacoes']."'>
       <input type='hidden' name='nroAvaliacoes'value='".$nroAvaliacoes."'>
       <input type='hidden' name='data' value='".$data."'>
       <input type='hidden' name='codInstanciaGlobal' value='".$codInstanciaGlobal."'>"; 

  if (podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) { 
    echo "<center><input type='submit' value='Gravar Dados'>";
  }



break;

case 'gravaNovasPresencas':
  if (!podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) { die; }
//  echo "<PRE>"; print_r($_REQUEST);die;

  gravaPesoInstancia($_REQUEST['codInstanciaGlobal'],$_REQUEST['nroAvaliacoes'],$_REQUEST['peso']);

  gravaNotasinstancia($_REQUEST['nroAlunos'],$_REQUEST['nroAvaliacoes'],$_REQUEST['codInstanciaGlobal'],$_REQUEST['notas'],$_REQUEST['notaFinal']);


  $count = count($_REQUEST['presenca']);
  for($i=0 ;$i < $count;$i++){
    $a[$i] = $_REQUEST['presenca'][$i];
  }
    deletaRegistroPresenca($a, $_REQUEST['codInstanciaGlobal'],$_REQUEST['data']);
  
  $count = count($_REQUEST['presenca']);
  for($i=0 ;$i < $count;$i++){
    $a[$i] = $_REQUEST['presenca'][$i];
  }
  gravaRegistroPresenca($a,$_REQUEST['data'],$_REQUEST['codInstanciaGlobal']);
  
  echo "<center><br>Dados Gravados com sucesso!</center>";
  echo '<p align="center" class="listachamada"><a href="'.$_SERVER['PHP_SELF'].'">Voltar para lista de chamada</a></p>';  
  break;

case 'modificaDataAula';
  if (!podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) { die; }
  
  $dataCerta = $_REQUEST['data'];
  $dataErrada =$_REQUEST['dataErrada'];
    //echo $dataErrada .'<br />', $dataCerta.'<br />' "agora vejo as varißveis <br />";die();
  
  $array_novaData = explode("/",$dataCerta);
  $novaData = $array_novaData[2]."-".$array_novaData[1]."-".$array_novaData[0];
  
  $array_dataAnterior = explode ("/",$dataErrada); 
  $dataAnterior = $array_dataAnterior[2]."-".$array_dataAnterior[1]."-".$array_dataAnterior[0];
  
    //echo $dataAnterior .'<br />', $novaData.'<br />' "agora vejo as varißveis <br />";die();
      
  modificaDataAula($codInstanciaGlobal,$dataAnterior,$novaData);
  
  echo "<script> location.href='registropresenca.php'</script>";
 
break;

}

echo '<p align="center" class="listachamada"><a href="'.$_SERVER['PHP_SELF'].'">Voltar para apresenta&ccedil;&atilde;o</a></p>';          
?>
<SCRIPT language=javascript>
function calculaMediaAritmetica(numNotas,ordemAluno,numAlunos,tipoFinal) {

  var totalNotas=0;  
  var totalPeso=0;  

  if (ordemAluno==0){//ALTERANDO OS INPUTS DE PESO
    for(j=1;j<=numAlunos;j++) {
      var divisao=0;  
      var totalNotas=0;  
      var totalPeso=0;  
      var nota=0;  
      var peso=0;  
      for(i=1;i<=numNotas;i++) {
        var nota=document.getElementById("input"+j+"_"+i).value;
        var peso=document.getElementById("peso["+i+"]").value;
        totalNotas=-eval(0-(nota.replace(",",".")*peso.replace(",","."))-totalNotas);
        totalPeso=-eval(0-peso.replace(",",".")-totalPeso);
      }//fim for i
    var divisao = totalNotas/totalPeso;
    divisao = divisao.toFixed(2);

    if(document.getElementById("notaFinal["+j+"]").value !=''){

      document.getElementById("notaFinal["+j+"]").value = divisao;
      document.getElementById("notaFinal["+j+"]").value = document.getElementById("notaFinal["+j+"]").value.replace(".",",");
    }
    if(tipoFinal==1){
      if(document.getElementById("notaFinal["+j+"]").value !=''){
        if(divisao>= <?php echo $aInicial;?> && divisao<= <?php echo $aFinal;?>){var letra='1'}
        if(divisao>= <?php echo $bInicial;?> && divisao<= <?php echo $bFinal;?>){var letra='2'}
        if(divisao>= <?php echo $cInicial;?> && divisao<= <?php echo $cFinal;?>){var letra='3'}
        if(divisao>= <?php echo $dInicial;?> && divisao<= <?php echo $dFinal;?>){var letra='4'}
        if(divisao>= <?php echo $eInicial;?> && divisao<= <?php echo $eFinal;?>){var letra='5'}
        if (document.getElementById("notaFinal["+j+"]").selectedIndex != 6){
          document.getElementById("notaFinal["+j+"]").selectedIndex = letra;
        }
     }
    }//fim do else
   }//for j
  }//fim input
  else{//ALTERANDO AS NOTAS
   for(i=1;i<=numNotas;i++) {
    var nota=document.getElementById("input"+ordemAluno+"_"+i).value;
    var peso=document.getElementById("peso["+i+"]").value;
    totalNotas=-eval(0-(nota.replace("," , ".")*peso.replace("," , "."))-totalNotas);
    totalPeso=-eval(0-peso.replace("," , ".")-totalPeso);
   }
   var divisao = totalNotas/totalPeso;
   divisao = divisao.toFixed(2);

    if((document.getElementById("notaFinal["+ordemAluno+"]").value !='')||(document.getElementById("notaFinal["+ordemAluno+"]").selectedIndex !=6)){
     document.getElementById("notaFinal["+ordemAluno+"]").value = divisao;
     document.getElementById("notaFinal["+ordemAluno+"]").value = document.getElementById("notaFinal["+ordemAluno+"]").value.replace(".",",");
  }

    if(tipoFinal==1){
      if(document.getElementById("notaFinal["+ordemAluno+"]").selectedIndex !=6){
       if(divisao>= <?php echo $aInicial;?> && divisao<= <?php echo $aFinal;?>){var letra='1'}
       if(divisao>= <?php echo $bInicial;?> && divisao<= <?php echo $bFinal;?>){var letra='2'}
       if(divisao>= <?php echo $cInicial;?> && divisao<= <?php echo $cFinal;?>){var letra='3'}
       if(divisao>= <?php echo $dInicial;?> && divisao<= <?php echo $dFinal;?>){var letra='4'}
       if(divisao>= <?php echo $eInicial;?> && divisao<= <?php echo $eFinal;?>){var letra='5'}

	var selObj = document.getElementById("notaFinal["+ordemAluno+"]");
	selObj.selectedIndex = letra;
      }
   }

  }
}
function mask(obj,event){
    var str = obj.value;
    var Tecla = event.which;
    if(Tecla == null)
        Tecla = event.keyCode;


    if ( (Tecla < 48 || Tecla > 59) && (Tecla != 44) ){
        event.returnValue = false;
        return false;
    }
    event.returnValue = true;
    return true;
}
function focusCelula(id){
 document.getElementById(id).style.border ="2px solid black";
}

function onBlurCelula(id){
document.getElementById(id).style.border ="2px solid white";
}
</script>