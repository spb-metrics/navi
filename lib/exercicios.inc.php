<?
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

/*
include_once ($caminhoBiblioteca."/pessoas.inc.php");
include_once($caminhoBiblioteca."/utils.inc.php");
include_once("questao.inc.php");
*/
include_once ("../config.php");

define('DEPOIS_EXPIRACAO','1');
define('DEPOIS_EXERCICIO_FEITO','2');

class Exercicio {
 var $publicadaNestaInstancia; 
  
  function Exercicio() {
  }
  /* 
   * Retorna o tempo de execu¦Êo para este exerc¦cio 
   */    
  function getRelogio($codExercicio) {
    $sql = "Select  DATE_FORMAT(dataExpiracao, '%Y%m%d') AS diaFinal,
    dataExpiracao,
    DATE_FORMAT(dataExpiracao, '%H') AS horaFim,
    DATE_FORMAT(dataExpiracao, '%i') AS minutoFim,
    mostrarRelogioExpiracao,tempoMinutosExecucaoDurantePeriodo ";
    $sql.= " from exercicio where codExercicio=".quote_smart($codExercicio);  
    $result = mysql_query($sql);
    $linha=mysql_fetch_object($result);
    return $linha;
  }
  
  /**
  *Layout para criar exercicio
  */
  function mostraLayout($acao,$codExercicio){
    global $url;
      if(!empty($codExercicio)){
        $exercicios= $this->imprimiExercicio($codExercicio);
        
        foreach($exercicios->records as $exe){
         $descricaoExercicio= $exe->descricaoExercicio;
         $alunoPodeVerResultados= $exe->alunoPodeVerResultados;
         $numeroQuestoesTela= $exe->numeroQuestoesTela;
         $numeroTentativas= $exe->numeroTentativas;
         $dataExpiracaoValue= $exe->dataFim;
         $mostrarRelogioExpiracao = $exe->mostrarRelogioExpiracao;
         $dataInicioValue = $exe->dataInicio;
         $imprimirAleatoriamente=$exe->imprimirQuestoesAleatoriamente;
         $imprimirAlternativaAleatoriamente=$exe->imprimirAlternativaAleatoriamente;
         $horaInicio=$exe->horaInicio;
         $horaExpiracao=$exe->horaFim;
         $tempoMinutosExecucaoDurantePeriodo=$exe->tempoMinutosExecucaoDurantePeriodo;       
       }
    
       echo "<table  align=\"left\"><tr><td align=\"left\">" . "\n";
		   echo "<a href=\"#\" onClick=\"if(confirm('Deseja mesmo excluir este exercicio ?')) href='exercicioLocal.php?opcao=removerGeral&codExercicio=".$codExercicio ."'\">" . "\n";
		   echo "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\"></a>";
			 echo "&nbsp;&nbsp;&nbsp;</td>" . "\n";
			 echo "<td><font color='red'>";
			 echo "<a href=\"#\" onClick=\"if(confirm('Deseja mesmo excluir este exercicio ?')) href='exercicioLocal.php?opcao=removerGeral&codExercicio=".$codExercicio ."'\">" . "\n";
			 echo "Excluir Exercicio</a></font>" . "\n";
			 echo "</td></tr></table><br><br>";
     }
     echo "<link rel=\"stylesheet\" media=\"screen\" href=\"".$url."/css/dynCalendar.css\" >";
     echo "<script language=\"javascript\" type=\"text/javascript\" src=\"".$url."/js/browserSniffer.js\"></script>";
     echo "<script language=\"javascript\" type=\"text/javascript\" src=\"".$url."/js/dynCalendar.js\"></script>";
    
     echo "<form name=\"frmExercicio\" method=\"POST\" action=\"".$acao."\">";
     echo "<table>";
     echo "<tr><td colspan='2'>Descri&ccedil;&atilde;o do exerc&iacute;cio:</td></tr>";
     echo "<tr><td colspan='2'><input type=\"text\" name=\"descricaoExercicio\" value=\"".$descricaoExercicio."\" size=\"80\"></td></tr>";
     echo "<tr><td>O aluno pode ver o resultado:&nbsp;&nbsp;&nbsp;";
     echo "<select name='alunoPodeVerResultados'><option value='0'";
     if ($alunoPodeVerResultados==0) { echo " selected"; }
     echo ">N&atilde;o</option><option value='1'";
     if ($alunoPodeVerResultados==1) { echo " selected"; }
     echo ">Somente depois da expira&ccedil;&atilde;o</option><option value='2'";
     if ($alunoPodeVerResultados==2) { echo " selected"; }
     echo ">Logo depois de ter feito o exerc&iacute;cio</option></select></td>";
     if(!empty($codExercicio)){
      echo "<td >Localiza&ccedil;&atilde;o:<br><br>";
      echo "<iframe name='locais' src='exercicioLocal.php?codExercicio=".$codExercicio."' frameborder='0' style='position:absolute; width:250px; height:185px; z-index: 2; overflow: visible; border:1px #000000 solid;'></iframe>";
      echo "</td>";
     }
     echo "</tr>";
     echo "<tr><td colspan='2'>N&uacute;mero de quest&otilde;es por tela:&nbsp;";
     echo "<select name=\"numeroQuestoesTela\">".
           "<option value=\"1\"";
     if ($numeroQuestoesTela==1) echo "selected"; 
     echo ">Uma</option>". 
           "<option value=\"2\"";
     if ($numeroQuestoesTela==2) echo "selected"; 
     echo ">Duas</option>".
           "<option value=\"3\"";
     if ($numeroQuestoesTela==3) echo "selected"; 
     echo ">Tr&ecirc;s</option>". 
           "<option value=\"4\"";
     if ($numeroQuestoesTela==4) echo "selected"; 
     echo ">Todas</option>". 
           "</select></td></tr>";
     echo "<tr><td colspan=\"3\">N&uacute;mero de Tentativas:&nbsp;";
     echo "<select name=\"numeroTentativas\">".
           "<option value=\"1\"";
      if ($numeroTentativas==1) echo "selected"; 
     echo ">Uma</option>". 
           "<option value=\"2\"";
     if ($numeroTentativas==2) echo "selected"; 
     echo ">Duas</option>". 
           "<option value=\"3\"";
     if ($numeroTentativas==3) echo "selected"; 
     echo ">Tr&ecirc;s</option>".
           "<option value=\"4\"";
     if ($numeroTentativas==4) echo "selected"; 
     echo ">Quatro</option>". 
           "<option value=\"10000\"";
     if ($numeroTentativas==10000) echo "selected"; 
     echo ">Ilimitadas</option>". 
           "</select></td></tr>";
     echo "<tr><td colspan='2'>Data de in&iacute;cio: &nbsp;";
  
     echo "<input type=\"text\" name=\"dataInicioValue\" maxlength=\"10\"  size=\"11\" onFocus=\"javascript:vDateType='3'\" onKeyUp=\"DateFormat(this,this.value,event,false,'3')\"  value=\"".$dataInicioValue."\">";
     echo "&nbsp;Hora:<input type=\"text\" name=\"horaInicio\" maxlength=\"5\"  size=\"5\" value=\"".$horaInicio."\">";
     echo "<input type=\"hidden\" name=\"dataInicio\" value=\"".$dataInicio."\"></td></tr>";
     echo "<tr><td colspan='2'>Data de expira&ccedil;&atilde;o: &nbsp;";
     echo "<input type=\"text\" name=\"dataExpiracaoValue\" maxlength=\"10\"  size=\"11\" onFocus=\"javascript:vDateType='3'\" onKeyUp=\"DateFormat(this,this.value,event,false,'3')\"   value=\"".$dataExpiracaoValue."\">";
     echo "&nbsp;Hora:<input type=\"text\" name=\"horaExpiracao\" maxlength=\"5\"  size=\"5\" value=\"".$horaExpiracao."\"></td>";
     echo "<tr><td>Mostrar rel&oacute;gio com tempo restante para a expira&ccedil;&atilde;o: ";
     echo "<select name='mostrarRelogioExpiracao'><option value='0'";
     if ($mostrarRelogioExpiracao==0) { echo " selected"; }
     echo ">N&atilde;o</option><option value='1'";
     if ($mostrarRelogioExpiracao==1) { echo "selected"; }
     echo ">Sim</option></select></td>";

     echo "<tr><td colspan='2'>Tempo em minutos para execu&ccedil;&atilde;o (substitui rel&oacute;gio):  <input type='text' name='tempoMinutosExecucaoDurantePeriodo' value='".$tempoMinutosExecucaoDurantePeriodo."' maxlength='3'  size='3'>";
     echo "<input type='hidden' name='dataExpiracao' value='".$dataExpiracao."'>";
?>
<script language="JavaScript" type="text/javascript">
function formatHidden(dateF,dateI){
    if(dateF.length>0) document.forms['frmExercicio'].dataExpiracao.value =dateF.charAt(6)+dateF.charAt(7)+dateF.charAt(8)+dateF.charAt(9)+"-"+dateF.charAt(3)+dateF.charAt(4)+"-"+dateF.charAt(0)+dateF.charAt(1);
    document.forms['frmExercicio'].dataInicio.value = dateI.charAt(6)+dateI.charAt(7)+dateI.charAt(8)+dateI.charAt(9)+"-"+dateI.charAt(3)+dateI.charAt(4)+"-"+dateI.charAt(0)+dateI.charAt(1);
}

</script>
<?
     if (!empty($codExercicio)) $ativaBanco= $this->exercicioAtivo($codExercicio,$_SESSION["codInstanciaGlobal"]);
    
     $questoes = $this->imprimiBancoQuestoes($_SESSION["COD_PESSOA"],$_SESSION["codInstanciaGlobal"],$codExercicio);
     echo "</td></tr>";
     echo "<tr><td>Exibir quest&otilde;es em ordem aleat&oacute;ria:&nbsp;";
     echo " <select name=\"imprimirAleatoriamente\">\n".
					"<option value=\"0\"";
     if ($imprimirAleatoriamente==0) echo "selected";
     echo ">N&atilde;o</option>\n".
					 "<option value=\"1\"";
     if ($imprimirAleatoriamente==1) echo "selected";
     echo ">Sim</option>\n".
					 "</select></td></tr>";
     echo "<tr><td colspan='2'>Exibir alternativas em ordem aleat&oacute;ria:&nbsp;";
     echo "<select name=\"imprimirAlternativaAleatoriamente\">\n".
					"<option value=\"0\"";
     if ($imprimirAlternativaAleatoriamente==0) echo "selected";
     echo ">N&atilde;o</option>\n".
					 "<option value=\"1\"";
     if ($imprimirAlternativaAleatoriamente==1) echo "selected";
     echo ">Sim</option>\n".
				  "</select></td></tr>";
     echo "</table>";
     echo "<br><br>";
     
     echo "<table>";
     echo "<tr><td>Banco de Quest&otilde;es</td><td>&nbsp;</td><td>Quest&otilde;es do Exerc&iacute;cio</td></tr>";
     echo "<tr><td width=\"47%\">";
	   echo "<select size=\"30\" multiple id=\"selectOrigem[]\" name=\"selectOrigem[]\" style=\"width: 345px;\">";
		 foreach($questoes->records as $questao){
        echo "<a href=\"#\"><option value=\"".$questao->codQuestao."\">".$questao->descricao."</option></a>";
     }
     echo "</select></td>";
     echo "<td align=\"center\" width=\"6%\" valign=\"center\">";
     echo "<input class=\"botao\" type=\"button\" onClick=\"move(this.form['selectOrigem[]'],this.form['selectDestino[]']);\" value=\"   >>   \" ";
     if(!empty($ativaBanco->records)) echo "disabled=\"disabled\"";
     echo "><br><br>";
     echo "<input class=\"botao\" type=\"button\" onClick=\"move(this.form['selectDestino[]'],this.form['selectOrigem[]']);\" value=\"   <<   \"";
     if(!empty($ativaBanco->records)) echo "disabled=\"disabled\"";
     echo "></td>";
     echo "<td align=\"center\" width=\"47%\">";
		 echo "<select size=\"30\" multiple id=\"selectDestino[]\" name=\"selectDestino[]\" style=\"width: 345px;\">";
		 if(!empty($codExercicio)){
       $questoesUsadas= $this->imprimiQuestoesUsadas($codExercicio);
       foreach($questoesUsadas->records as $usada){
         echo "<a href=\"#\"><option value=\"".$usada->codQuestao."\">".$usada->descricao."</option></a>";
       }
     }
     echo "<option value=\"\"></option>";
		 echo "</select></td></tr>";
     echo "<tr><td align=\"right\"><input type=\"submit\" name=\"submit\"  id=\"botaoSubmit\"  value=\"Enviar\" onclick=\"formatHidden(document.forms['frmExercicio'].dataExpiracaoValue.value,document.forms['frmExercicio'].dataInicioValue.value);enviar(this.form['selectOrigem[]'],this.form['selectDestino[]']);document.formulario.continuar.value=0;\" class='okButton'></td>";
     echo "<td align=\"center\">&nbsp;</td>";
     echo "<td align=\"left\"><input type=\"reset\" value=\"Cancelar\" onClick=\"window.location.href='".$_SERVER[PHP_SELF]."?'\"  class='cancelButton'></td></tr>";
     echo "<input type=\"hidden\" name=\"tipoQuestao\" value=\"Exercicio\">";
     echo "<input type=\"hidden\" name=\"codExercicio\" value=\"".$codExercicio."\">";
     echo "</table></form>";
  }
/**
*Fun¦Êo que insere a questÊo e a resposta no banco de dados
*Insere enunciado, insere alternativa , update da questao com o codAlternatiavCorreta
*/
 function gravar($codPessoa,$request){
  $dataInicio=$request["dataInicio"]." ".$request["horaInicio"];
  $dataExpiracao=$request["dataExpiracao"]." ".$request["horaExpiracao"];
//insere no banco de dados as informaè¦es do exercicio 
   if(empty($request["codExercicio"])) {
      $sql= "INSERT INTO exercicio (codPessoa,dataExpiracao,numeroTentativas,numeroQuestoesTela, alunoPodeVerResultados,descricaoExercicio, mostrarRelogioExpiracao, tempoMinutosExecucaoDurantePeriodo,dataInicio,imprimirQuestoesAleatoriamente,imprimirAlternativaAleatoriamente ) VALUES (".quote_smart($codPessoa).",".quote_smart($dataExpiracao).",".quote_smart($request["numeroTentativas"]).",".quote_smart($request["numeroQuestoesTela"]).", '".quote_smart($request['alunoPodeVerResultados'])."' ,".quote_smart($request['descricaoExercicio']).",'".quote_smart($mostrarRelogioExpiracao)."',".quote_smart($request['tempoMinutosExecucaoDurantePeriodo']);
      
      if($request["dataInicio"]!="--")
        $sql.=",".quote_smart($dataInicio);
      else{
       $dataAux=date("Y-m-d");
       $sql.=",".quote_smart($dataAux)."";
      }
      $sql.=",".$request["imprimirAleatoriamente"].",".$request["imprimirAlternativaAleatoriamente"].")";
      
      mysql_query($sql);
      //echo $sql; echo mysql_error(); die;
      $codExercicio =mysql_insert_id(); 
      //inserir as quest¦es no banco de dados  relacionadas com o exercicio
      $naoUsadas = $request["selectOrigem"];
      $usadas=$request["selectDestino"];
      $totalDeQuestoes=count($usadas);
      for($i=0; $i<$totalDeQuestoes; $i++){
          if(!empty($usadas[$i])){
            $sql= "INSERT INTO exercicioquestao (codExercicio,codQuestao) VALUES (".$codExercicio.",".$usadas[$i].")";
            // print_r($sql);
      
             mysql_query($sql);
          }
      }
      return $codExercicio;
    }
//altera no banco de dados as informaè¦es do exercicio 
    else{
      $this->alteraExercicio($codPessoa,$request);
      return $request["codExercicio"];
    }
}
/*
*alterar o exercicio no banco de dados
*/
  function alteraExercicio($codPessoa,$request){
    $dataInicio=$request["dataInicio"]." ".$request["horaInicio"];
    $dataExpiracao=$request["dataExpiracao"]." ".$request["horaExpiracao"]; 
    
    //atualizo a tabela exercicio
    $sql= "UPDATE exercicio SET ";
    if(!empty($request["dataExpiracao"])) $sql.= "dataExpiracao =".quote_smart($dataExpiracao).",";
    if($request["dataInicio"]!="0000-00-00")
      $sql.="dataInicio=".quote_smart($dataInicio).",";
    else{
       $dataAux=date("Y-m-d");
       $sql.="dataInicio=".quote_smart($dataAux).",";
    }
    $sql.='numeroTentativas='.quote_smart($request['numeroTentativas'])
    .',numeroQuestoesTela='.quote_smart($request['numeroQuestoesTela'])
    .",alunoPodeVerResultados= '".quote_smart($request['alunoPodeVerResultados'])."'"
    .',descricaoExercicio='.quote_smart($request['descricaoExercicio'])
    .', imprimirQuestoesAleatoriamente='.quote_smart($request['imprimirAleatoriamente'])
    .',imprimirAlternativaAleatoriamente='.quote_smart($request['imprimirAlternativaAleatoriamente'])
    .',tempoMinutosExecucaoDurantePeriodo='.quote_smart($request['tempoMinutosExecucaoDurantePeriodo'])
    .",mostrarRelogioExpiracao='".quote_smart($request['mostrarRelogioExpiracao'])."'"
    .' WHERE codExercicio='.$request['codExercicio'];
    //echo $sql; die();
     mysql_query($sql);
    //agora atuliza a tabela exercicioquestao 
    if (! mysql_errno()){
      $sql="DELETE FROM exercicioquestao WHERE codExercicio=".$request["codExercicio"]."";
      mysql_query($sql);
      if(! mysql_errno()){
        $usadas=$request["selectDestino"];
        $totalDeQuestoes=count($usadas);
        for($i=0; $i<$totalDeQuestoes; $i++){
           if(!empty($usadas[$i])){
              $sql= "INSERT INTO exercicioquestao (codExercicio,codQuestao) VALUES (".$request["codExercicio"].",".$usadas[$i].")";
              
              mysql_query($sql);
           }
        }
     } 
   }
   return;
 }
/**
* seleciona do banco de dados todas as questoes que a pessoa j¯ criou
*/
  function imprimiBancoQuestoes($codPessoa,$codInstanciaGlobal,$codExercicio=""){
      $sql="SELECT Q.codQuestao, Q.descricao, Q.enunciado FROM questao Q";
      if(!empty($codExercicio)) {
        $sql.=" LEFT OUTER JOIN exercicioquestao EQ ON (Q.codQuestao=EQ.codQuestao AND codExercicio=".$codExercicio.")";
      }  
      $sql.= " WHERE  Q.codPessoa=".$codPessoa.""; 
      if(!empty($codExercicio)) $sql.=" AND EQ.codQuestao is NULL";
      $sql.= " ORDER BY Q.enunciado ASC";
      $result=new RDCLQuery($sql);
      return $result; 
  }
/**
*layout para mostrar os exercicios da instancia
*/
function layoutListaExerciciosInstancia($nivel,$remover,$alterar,$ver,$verScore,$verScoreAluno,$codInstanciaGlobal){
  $exercicios=$this->listaExercicioInstancia($codInstanciaGlobal);
  
  $mathMl= ativaDesativaEditorMathMl($codInstanciaGlobal,$_SESSION["userRole"]);
  
  echo "<table width=\"85%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td>";
  echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"1\">";
 // echo "<tr><td colspan=\"2\" aling=\"center\"><a href=\"".$verScore."\">|SCORE DOS ALUNOS|</a></td></tr>";

  if(!empty($exercicios->records)){  
     foreach($exercicios->records as $exe) {

        $numeroTentativasAluno= $this->getNumeroTentativas($_SESSION["COD_PESSOA"],$exe->codExercicio);
        if(empty($numeroTentativasAluno)){
            $numeroTentativasAluno=0;
        }
        echo "<tr><td align =\"center\">";
        
        $hoje=date("YmdHi");
        
        //Divide a logica de exibição do exercicio para todos os usuarios 
        //e do score para os alunos em duas variaveis de controle,
        //$mostrarExercicio e $alunoPodeVerScore
        $alunoPodeVerScore=0; $mostrarExercicio=0;  $fazerExercicio=0;
        if ($hoje>=$exe->inicio && $hoje<=$exe->fim) {
          $mostrarExercicio=1;
          $fazerExercicio=1;
          if ($exe->alunoPodeVerResultados==DEPOIS_EXERCICIO_FEITO) {
            $alunoPodeVerScore=1;
          }
        }
        else if ($hoje>$exe->fim && $exe->alunoPodeVerResultados==DEPOIS_EXPIRACAO){ 
          $mostrarExercicio=1;
          $alunoPodeVerScore=1;
        }     
        //if((($this->dateDiff($hoje,$expiracao))==1 && $this->horaDiff($horaInicio,$horaFim)==1)|| ($exe->verResultadosAposConclusao)){
        if ($mostrarExercicio) {
          if (Pessoa::podeAdministrar($_SESSION['userRole'],$nivel,$_SESSION['interage'])) {
             echo "<a href=\"#\" onClick=\"if(confirm('Deseja mesmo excluir este exercicio dessa Inst+ncia?'))href='".$remover."&codExercicio=".$exe->codExercicio."'\"><img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\" title=\"Remover Exerc&iacute;cio\"></a>";
             echo "&nbsp;&nbsp;&nbsp;<a href=\"".$alterar."&codExercicio=".$exe->codExercicio."\"><img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\" title=\"Alterar Exerc&iacute;cio\"></a>";
             echo "&nbsp;&nbsp;&nbsp;<a href=\"".$verScore."&codExercicio=".$exe->codExercicio."\"><img src=\"../imagens/score.gif\" border=0 alt=\"Score\" title=\"Ver Score do Exerc&iacute;cio\"></a>";
          }
          else if ($alunoPodeVerScore) {
            echo "&nbsp;&nbsp;&nbsp;<a href=\"".$verScoreAluno."&codExercicio=".$exe->codExercicio."\"><img src=\"../imagens/score.gif\" border=0 alt=\"Score\" title=\"Ver Score do Exerc&iacute;cio\"></a>";
          }
         //echo "numero de tentaivas".$exe->numeroTentativas."----- numero de tenativas aluno".$numeroTentativasAluno; 
          echo "</td><td align=\"center\">"; 
          
                              
          if ($fazerExercicio) { //aluno pode fazer o exercicio                 
            if(!empty($_SESSION["COD_AL"])) {
              if($exe->numeroTentativas<=$numeroTentativasAluno) {
               echo $exe->descricaoExercicio. " (numero de tentativas m&aacute;ximo atingido)";
              }
              else {
               echo ' <a href="'.$ver.'&codExercicio='.$exe->codExercicio.'">'.$exe->descricaoExercicio."</a>";
              }
            }
            else {
              echo "<a href=\"".$ver."&codExercicio=".$exe->codExercicio."\">".$exe->descricaoExercicio."</a>";
            }          
          }
          else { //apenas lista a descricao
            //echo "hoje: ".$hoje."  INICIO: ".$exe->inicio."  FIM: ".$exe->fim;
            echo $exe->descricaoExercicio;          
          }

          echo "</td></tr>";   
        }
     }
    
  }
 else{
   echo "<tr><td align=\"center\"><b>N&atilde;o existem exercicios publicados neste local</b></td></tr>";
 }
 echo "</table>";
 echo "</tr></td></table>";
}
/**
* seleciona todos os exercicios ativos da instancia
*/
 function listaExercicioInstancia($codInstanciaGlobal){
  $dataAux=date("Y-m-d");
  $sql="SELECT  *,
  DATE_FORMAT(dataExpiracao, '%Y%m%d%H%i') AS fim, DATE_FORMAT(dataInicio, '%Y%m%d%H%i') AS inicio
  FROM exercicio E, exercicioinstancia EI WHERE EI.codInstanciaGlobal=".quote_smart($codInstanciaGlobal)." AND EI.codExercicio=E.codExercicio  AND dataInicio<='".$dataAux." 23:59:59'";

  $result = new RDCLQuery($sql);
  return $result;
}

/**
*seleciona os dados do exercicio
*/
 function imprimiExercicio($codExercicio){
   $sql="SELECT *, 
   DATE_FORMAT(dataExpiracao, '%d/%m/%Y') AS dataFim ,DATE_FORMAT(dataInicio, '%d/%m/%Y') AS dataInicio,
   DATE_FORMAT(dataExpiracao, '%H:%i') AS horaFim, DATE_FORMAT(dataInicio, '%H:%i') AS horaInicio
   FROM exercicio WHERE codExercicio=".$codExercicio."";
    $result=new RDCLQuery($sql);
   return $result; 
 }
/**
*seleciona todas as questoes usadas do exercicio
*/
function imprimiQuestoesUsadas($codExercicio){
    $sql="SELECT Q.descricao, Q.codQuestao FROM questao Q, exercicioquestao EQ WHERE EQ.codQuestao=Q.codQuestao AND EQ.codExercicio=".$codExercicio." ";
    $result= new RDCLQuery($sql);
    return $result;
}
/**
* Insere o exericio na turma
*/
function ExeLocalInsere($codExercicio, $codInstanciaGlobal){
 $sql="INSERT INTO exercicioinstancia (codExercicio,codInstanciaGlobal) VALUES (".$codExercicio.",".$codInstanciaGlobal.")";
  mysql_query($sql);
  return (! mysql_errno());
}
/**
* copia da fun¦Êo do arquivo config.php com a altera¦Êo do TIPO ACESSO que nÊo existe
*/
function imprimeLocaisExercicio($url,$pk,$valuePK,$numNiveisImprime=100,$instanciaGlobalAtual="") {
 
 $locais= $this->listaLocal("exercicioinstancia","codExercicio",$valuePK);
 $html = "<table>";
  foreach($locais->records as $local) {
    $nivel = new Nivel($local->codNivel);
    $instanciaNivel = new InstanciaNivel($nivel,$local->codInstanciaNivel);
    //verifica se j¯ foi publicado na instancia atual passada como par+metro
    if (!$this->publicadaNestaInstancia && !empty($instanciaGlobalAtual) &&  $instanciaNivel->codInstanciaGlobal == $instanciaGlobalAtual) {
       $this->publicadaNestaInstancia=1;
    }
    //Lista os pais at+ chegar no topo ou no numero de niveis requerido,
    //caso nao seja uma instancia 'comunidade'
    $nomesInstancias = array();
    $numNivel = 1;
    while(!$instanciaNivel->nivel->isFirst && $numNivel <= $numNiveisImprime && !$instanciaNivel->nivel->nivelComunidade) {
        array_unshift($nomesInstancias,$instanciaNivel->nome);
        $instanciaNivel = $instanciaNivel->getPai();
        $numNivel++;
    }
    $html.= "<tr><td height=\"22px\"><a  href='".$url."?opcao=removerLocal&codInstanciaGlobal=". $local->codInstanciaGlobal ."&".$pk."=" . $valuePK ."'><img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\"></a>&nbsp;&nbsp;&nbsp;".
            "</td>\n";
    $html.= "<td>";
    foreach($nomesInstancias as $nomeInst) {
      $html.= strtoupper($nomeInst)."&nbsp;";
    }
    $html.= "</td></tr>\n";
  }
  //echo "<h2>imrimi locai".$publicadaNivelAtual."</h2>";
	$html.= "</table>";
	return $html;
 }
/**
*copia da fun¦Êo do arquivo config.pho  sem o COD_ACESSO e com o codInstanciaGlobal minusculo
*/
function listaLocal($tableTool,$pkTool,$codInstanceTool,$labelTool="") {
	$sql = "SELECT f.{$pkTool}, ig.codInstanciaGlobal, ig.codNivel, ig.codInstanciaNivel ";
	if (!empty($labelTool))
		$sql.= ",".$labelTool;
	$sql.= " FROM {$tableTool} as f".
				 " INNER JOIN instanciaglobal AS ig ON (f.CodInstanciaGlobal = ig.codInstanciaGlobal)".
				 " WHERE f.{$pkTool} = ".quote_smart($codInstanceTool);		
  $result= new RDCLQuery($sql);
  return $result;
}
/**
*remove o exercicio da instancia
*/
  function ExercicioLocalRemove($codExercicio, $codInstanciaGlobal){
    $sql= "DELETE FROM exercicioinstancia WHERE codExercicio=".$codExercicio." AND codInstanciaGlobal=".$codInstanciaGlobal."";
    mysql_query($sql);
    return (! mysql_errno());
  }
/**
*remove o exercicio
*/
function excluirExercicio($codExercicio){
   $sql= "DELETE FROM exercicio WHERE codExercicio=".$codExercicio."";
   mysql_query($sql);
   return(!mysql_errno());
 }
/**
*layout do editar exercicios
*/
function layoutEditarExerciciosEQuestoes($local,$classe){
  
    $html= "<table width=\"100%\"  align=\"left\"><tr>".
           "<tr><td><br></td></tr>".
           "<tr><td><br></td></tr>";
    if($classe=="Questao")
    $html.="<td colspan=\"3\" align=\"left\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=criar_questao\">Criar novas Quest&otilde;es</a></td>";
    else
    $html.="<td colspan=\"3\" align=\"left\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=criar_exercicio\">Criar novo Exerc&iacute;cio</a></td>"; 
             //  "<td  colspan=\"3\" align=\"right\"><a href=\"./index.php\">Voltar</a></td>".
           "</tr>";
      $html.= "<tr><td><br></td></tr>";
      $html.= "<tr><td><br></td></tr>";
      $html.= "<tr><td></td></tr>";
          if($_SESSION["userRole"]==PROFESSOR OR $_SESSION["userRole"]==ADM_NIVEL){
            if($classe=="Questao")
             $html.="<tr><td align=\"left\"><a href=\"?FILTRO=meus&classe=Questao\"> Meu Banco de Quest&otilde;es</a></td></tr>";
            else{
             $html.= "<tr><td align=\"left\"><a href=\"?FILTRO=meus&classe=Exercicio\">Meus Exerc&iacute;cios</a></td>";
             $html.="<td align=\"right\"><a href=\"?FILTRO=instanciaAtual&classe=Exercicio\">Exerc&iacute;cios Publicados na ".$local." Atual</a></td></tr>";
            }
          }
          if($_SESSION["userRole"]==ADMINISTRADOR_GERAL ){
            if($classe=="Questao"){
             $html.= "<tr><td align=\"left\">Banco de:<a href=\"?FILTRO=todas&classe=Questao\">Quest&otilde;es</a></td>";
             $html.= "<td align=\"center\">Ativos:<a href=\"?FILTRO=algo&classe=Questao\">Quest&otilde;es</a></td>";
             $html.="<td></td><td align=\"right\">N&atilde;o Ativos:<a href=\"?FILTRO=nenhum&classe=Questao\">Quest&otilde;es</a></td></tr>";
             $html.= "<tr><td><br></td></tr>";
             $html.= "<tr><td align=\"left\"><a href=\"?FILTRO=meus&classe=Questao\"> Meu Banco de Quest&otilde;es</a></td>";
             //$html.= "<td align=\"right\" colspan=\"2\"><a href=\"?FILTRO=instanciaAtual&classe=Exercicio\">Exercicios Publicados na ".$local." Atual</a></td>";
             $html.= "</tr>";
            }
            else{
             $html.= "<tr><td align=\"left\">Banco de:<a href=\"?FILTRO=todas&classe=Exercicio\"> Exerc&iacute;cios</a></td>";
             $html.= "<td align=\"center\">Ativos:<a href=\"?FILTRO=algo&classe=Exercicio\">Exerc&iacute;cios </a></td>";
             $html.="<td></td><td align=\"right\">N&atilde;o Ativos:<a href=\"?FILTRO=nenhum&classe=Exercicio\">Exerc&iacute;cios</a></td></tr>";
             $html.= "<tr><td><br></td></tr>";
             $html.= "<tr><td align=\"left\"><a href=\"?FILTRO=meus&classe=Exercicio\">Meus Exerc&iacute;cios</a> </td>";
             $html.= "<td align=\"right\" colspan=\"2\"><a href=\"?FILTRO=instanciaAtual&classe=Exercicio\">Exerc&iacute;cios Publicados na ".$local." Atual</a></td>";
             $html.= "</tr>";
            }
          }
     
     $html.= "</table>";
   return $html;
  }
 /**
 *listagem das questoes dependendo do filtro e da pessoa
 */
 function listaExercicioAdm($codInstanciaGlobal,$local,$codPessoa){
   
    $strSQL = "SELECT E.codExercicio, E. descricaoExercicio";
      if ($local == "instanciaAtual"){
     
      $strSQL .= " FROM exercicio E, exercicioinstancia EI WHERE E.codExercicio = EI.codExercicio";
		  if ($codInstanciaGlobal != ""){$strSQL .= " AND EI.codInstanciaGlobal = '" . $codInstanciaGlobal . "'";}
    }
    
    if ($local == "nenhum"){
      $rsCon = mysql_query("SELECT EI.codExercicio FROM exercicioinstancia EI");
      $strSQL .= " FROM exercicio E WHERE E.codExercicio NOT IN (";
		  while ($linha = mysql_fetch_array($rsCon)) $strSQL .= $linha["codExercicio"] . ",";
		  $strSQL .= "0)";
    }
    if ($local == "algo"){
      $rsCon = mysql_query("SELECT EI.codExercicio FROM exercicioinstancia EI");
      $strSQL .= " FROM exercicio E WHERE E.codExercicio IN (";
      while ($linha = mysql_fetch_array($rsCon)) 	$strSQL .= $linha["codExercicio"] . ",";
		  $strSQL .= "0)";	
    }
    if ($local == "todas")  $strSQL .= " FROM exercicio E";
    if ($codPessoa != "")   $strSQL = "SELECT * FROM exercicio E WHERE E.codPessoa = '". $codPessoa ."'";
		$strSQL .= " ORDER BY E.codExercicio";
    $result = new RDCLQuery($strSQL);
    return $result;
  }
/**
*fun¦Êo de layout para listar exercicios e quest¦es
*/
  function layoutLista($linha,$remover,$alterar){
     global $url,$urlImagem;
       $html = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"85%\"  align=\"center\">";
			 $html.= '<tr><td width="80" align="center">';
      if(!empty($linha->records)){
       $html.= "<b>Excluir e Alterar </b></td>";
       $html.= '<td width="40" align="center"><b>Ver Score</b></td>';
       $html.= '<td width="40" align="right">&nbsp</td>';   
       $html.= "<td><b>Descri&ccedil;&atilde;o</b></td></tr>";
			 //$html.= "<tr><td colspan=\"3\">&nbsp;</td></tr>";
			  foreach($linha->records as $linhaN){
				  $html.= "<tr>\n".
				  	  	 "<td align=\"center\">".
				  	  	 "<a href=\"#\" onClick=\"if(confirm('Deseja mesmo excluir este exerc&iacute;cio ?')) href='".$remover."&codExercicio=".$linhaN->codExercicio."'\">".
				  		   "<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">".
				  		   "</a>&nbsp;&nbsp;&nbsp;".
				  		   "<a href=\"".$alterar."&codExercicio=".$linhaN->codExercicio."\">".
				  		   "<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">".
				  		   "</a>".
				  		   "</td>";
           //Visualização do exercício
				   $html.= '<td align="center"><a href="'.$url.'/exercicios/index.php?acao=verScore&codExercicio='.$linhaN->codExercicio.'"><img src="'.$urlImagem.'/score.gif" border="0" alt="Score" title="Ver Score do Exerc&iacute;cio"></a></td>';
				   $html.= '<td align="right">';
           $html.= $linhaN->codExercicio."&nbsp;&nbsp;</td>";
				   
           $html.= "<td align=\"left\">".$linhaN-> descricaoExercicio."</td>";
           $html.= "</tr>";
			  }
			}
        if(empty($linha->records)){$html.= "<tr><td> &nbsp;&nbsp;&nbsp; <b> N&atilde;o h&aacute; exercicios cadastrados. </b></td></tr>";}
        $html.= "</table></td></tr>";
      
			  $html.= "</table>";
        return $html;
}
/**
*inserir as respostas dos alunos no banco de dados
*/
function gravarResposta($codPessoa,$codInstanciaGlobal,$request){
  
  //trecho inserido para resolver o problema de codInstanciaGlobal
  $sql = 'select * from exercicioinstancia where codExercicio='.quote_smart($request["codExercicio"]).' and codInstanciaGlobal='.$codInstanciaGlobal;
  //echo $sql;
  //echo "<br>".$codInstanciaGlobal."<br>";
  $result = mysql_query($sql); 
  $array = mysql_fetch_array($result);
  //print_r($array);
  //echo "<br>".$array[0];
    
  //se o codInstanciaGlobal nao esta relacionado com o exercicio, retorna
  if (!in_array($codInstanciaGlobal,$array)) {
    global $url;
    die();
    //echo "<script>alert('Houve um erro. Responda o exercicio novamente.'); window.location.href='".$url."/exercicios/index.php';</script>";
  }
  
  //codInstanciaGlobal esta relacionada com o exercicio
  else {
    if(!empty($_SESSION["COD_AL"])){
      if(!empty($request["gravar"])){
      //echo "gravar resposta";
        for($i=0;$i<$request["numeroQuestoesTela"];$i++){
            list($codQuestao,$codAlternativa) = each($request['resposta']); 
            //verifica se o aluno j¯ realizou o exercicio em questao    
            $mySql="SELECT * FROM respostasaluno WHERE codPessoa=".$codPessoa." AND codInstanciaGlobal=".$codInstanciaGlobal." AND codExercicio=".$request["codExercicio"]." AND codQuestao='".$codQuestao."'";
          //  print_r($sql);
            echo "<br>";
            $result = mysql_query($mySql);
            $sucesso = mysql_fetch_assoc($result);
       
            if(empty($sucesso)){
               //constru¦Êo de uma matriz para colocar os valores das quest¦es com asalternativas
               $sql="INSERT INTO respostasaluno (codPessoa,codExercicio,codQuestao,codAlternativaEscolhida,codInstanciaGlobal)".
                     " VALUES (".$codPessoa.", ".$request["codExercicio"].",'".$codQuestao."',".quote_smart($codAlternativa).",".$codInstanciaGlobal.")";
         
           }
            else{
               //atualizacao da tabela respostasaluno
               $sql = "UPDATE respostasaluno SET codAlternativaEscolhida=".quote_smart($codAlternativa)."".
                      " WHERE codPessoa=".$codPessoa." AND codQuestao='".$codQuestao."' AND codExercicio=".$request["codExercicio"]." AND codInstanciaGlobal=".$codInstanciaGlobal."";
            }
    //     print_r($sql); 
         
         echo "<br>";
         mysql_query($sql);
         }
         return (! mysql_errno());
        }
        else { /*print("ultrapassei limite");*/ return;}
      }
      else {/*print("nÊo sou aluno");  die();*/ return;}
    }
}
/**
*incrementar o numero de tentativas de exercicios do aluno com exercicio
*/
function adicionaTentativaExercicio($codPessoa,$codExercicio){
   if(!empty($_SESSION["COD_AL"])){
   $numeroDeTentativa = $this->getNumeroTentativas($codPessoa,$codExercicio);
   
   //verifica se o aluno ja tentou alguma coisa
    if($numeroDeTentativa>="0"){
      $inc=$numeroDeTentativa+1;
      $sql="UPDATE alunotentativaexercicio SET numeroDeTentativa=".$inc." ".
           " WHERE codPessoa=".$codPessoa." AND codExercicio=".$codExercicio." AND codInstanciaGlobal=".$_SESSION["codInstanciaGlobal"]."";
   }
   else{
      $sql="INSERT INTO alunotentativaexercicio (codPessoa,codExercicio, numeroDeTentativa,codInstanciaGlobal)".
           " VALUES(".$codPessoa.",".$codExercicio.",1,".$_SESSION["codInstanciaGlobal"].")";
   }
  
//   print_r($sql);
   mysql_query($sql);
	 return (! mysql_errno());
  }
  else {return;}	
 }
/**
*retorna o numero de tentativas 
*/
 function getNumeroTentativas($codPessoa,$codExercicio){
   $sql="SELECT numeroDeTentativa FROM alunotentativaexercicio WHERE codPessoa=".$codPessoa." AND codExercicio=".$codExercicio." AND codInstanciaGlobal=".$_SESSION["codInstanciaGlobal"]."";
   $result = mysql_query($sql);
   $numero= mysql_fetch_assoc($result);
   return  $numero["numeroDeTentativa"];
 }
/**
 *layout para aluno ver resposta
 */ 
  function layoutVerResposta($codPessoa,$codExercicio,$voltar){
   
   global $urlImagem; 
   $dados=$this->imprimiExercicio($codExercicio);
   foreach($dados->records as $exercicio){
    $alunoPodeVerResultados=$exercicio->alunoPodeVerResultados;
    $descricaoExercicio=$exercicio->descricaoExercicio;
    $codExercicio=$exercicio->codExercicio;
    $dataExpiracao=$exercicio->dataExpiracao;
   }
    $datalocal=date("Y-m-d H:i:s");
  
  if( ($alunoPodeVerResultados==2) || (($alunoPodeVerResultados==1) && ($datalocal>$dataExpiracao)) ){
   echo "<table class=\"questao\" cellspacing='0' cellpadding='0'>";
  // echo "<tr><td colspan=\"2\" align=\"left\"><a href=\"".$voltar."\"><img src=\"".$urlImagem."/imagens/voltar.gif\" border=\"no\"><br><b>Voltar</b></a></td>";
   //echo "<td colspan=\"2\" align=\"right\"><a href=\"./imprimirScore.php?codExercicio=".$codExercicio."\"><img src=\"".$urlImagem."/impressaochamada.gif\"  border=\"no\"><br><b>Imprimir</b></a></td></tr>";
   echo "<tr><td colspan=\"4\" align=\"center\"><b>".$codExercicio."-".$descricaoExercicio."</b></td></tr>";
   echo "<tr><td class=\"tituloquestao\">Descri&ccedil;&atilde;o Questao</td>";
   echo "<td class=\"tituloquestao\">Resposta Correta</td>";
   echo "<td class=\"tituloquestao\">Sua Resposta</td>";
   echo "<td class=\"tituloquestao\">Corre&ccedil;&atilde;o</td></tr>";
   
   $questao=$this->getRespostasExercicio($codPessoa,$codExercicio,$_SESSION["codInstanciaGlobal"]);
   $obj=new Questao($codQuestao);
   $totalQuestao=0;
   $numAcertos=0;
     //seleciona tds os codQuestao do exercicio
      foreach($questao->records as $ques){
       $q=$obj->getQuestao($ques->codQuestao);
       foreach($q->records as $mostraquestao){
         $texto=$this->getAlternativa($mostraquestao->codQuestao,$mostraquestao->codAlternativaCorreta);
         $alternativaAluno=$this->getAlternativa($ques->codQuestao,$ques->codAlternativaEscolhida);           
         //if($mostraquestao->tipoQuestao=="VerdadeiroFalso"){if($alternativaAluno=="1"){$alternativaAluno=verdadeiro;} else{$alternativaAluno=falso;}}
        // if($mostraquestao->tipoQuestao=="VerdadeiroFalso"){if($texto=="1"){$texto=verdadeiro;} else{$texto=falso;}}
         if($ques->codAlternativaEscolhida==$mostraquestao->codAlternativaCorreta) {$correcao="<img src=\"".$urlImagem."/correto.gif\">"; $numAcertos=$numAcertos+1; $totalQuestao++;}
         else{$correcao="<img src=\"".$urlImagem."/errado.gif\">"; $totalQuestao++;}
 
             //mostra a questÊo que esta sendo selecionada
            echo"<tr><td class=\"linha\">&nbsp;".$mostraquestao->enunciado."</td>";
            //mostra a alternativa correta
            echo "<td class=\"linha\">&nbsp;".$texto."</td>";
            //mostra a alternativa escolhida pelo aluno
            echo "<td class=\"linha\">&nbsp;".$alternativaAluno."</td>"; 
            //verifica se o aluno acertou as resposta
            echo "<td class=\"linha\">&nbsp;".$correcao."</td></tr>";
    
      }
    }
   
   echo "<tr><td class=\"linha\">Total de acertos</td><td colspan=\"2\" class=\"linha\" align=\"center\">  ----- </td>";
   echo "<td class=\"linha\">&nbsp;".$numAcertos." de ".$totalQuestao."</td></tr>";
   echo "</table>";   
   }else{ echo "<p>Você não pode visualizar a(s) resposta(s) neste momento.</p>";}
  } 
/**
* seleciona as alternativas das questoes do exercicio
 */  
  function getRespostasExercicio($codPessoa,$codExercicio,$codInstanciaGlobal){
    $sql="SELECT * FROM respostasaluno".
         " WHERE codExercicio=".$codExercicio."".
         " AND codInstanciaGlobal=".$codInstanciaGlobal." AND codPessoa=".$codPessoa."".
         " ORDER BY codQuestao";
    $result = new RDCLQuery($sql);
    return $result;  
  }  
/**
*
*/ 
  function getAlternativa($codQuestao,$codAlternativa){
    if(($codAlternativa=="verdadeiro") or ($codAlernativa=="falso")){
      $return=$codAlternativa;
   }
    else{
      $sql="SELECT texto FROM alternativa WHERE codAlternativa=".$codAlternativa."";
      $result = mysql_query($sql);
      $texto= @mysql_fetch_assoc($result);
      $return=$texto["texto"];
    }
    return  $return;
  } 
 /**
  * layout para ver score
  */   
  function verScore($codExercicio) {
    global $urlImagem;
   
    $exercicio=$this->imprimiExercicio($codExercicio);
    echo "<br><table class=\"questao\" style=\"color:blue\">";
    //echo "<tr><td colspan=\"2\" align=\"left\"><a href=\"javascript:history.back()\"><img src=\"".$urlImagem."/imagens/voltar.gif\" border=\"no\"><br><b>Voltar</b></a></td>";
    $exe = $exercicio->records[0];
    echo '<tr><td align="center" colspan="2"><b>'.$exe->descricaoExercicio.'</b></td></tr>';
    echo "</tr><td align=\"left\" colspan=\"2\">Informa&ccedil;&otilde;es do Exerc&iacute;cio</td></tr>";
    echo "<tr><td>Data de Expira&ccedil;&atilde;o do Exerc&iacute;cio: ".$exe->dataExpiracaoValue."</td></tr>";
    if($exe->numeroTentativas==10000){$numero=Ilimitado;} else{$numero=$exe->numeroTentativas-1;}
    echo "<tr><td>N&uacute;mero de Tentativas permitidas: ".$numero."</td></tr>";
    echo "<tr><td>N&uacute;mero de questoes por tela: ".$exe->numeroQuestoesTela."</td></tr>";
    if($exe->alunoPodeVerResultados==1){$ok=Sim;} else{$ok=N&atilde;o;}
    echo "<tr><td>Aluno pode ver o Resultados: ".$ok."</td></tr>";
    echo "</table><br>";   
    $this->getScoreAlunoExercicio($codExercicio,$_SESSION["codInstanciaGlobal"]);
  

  } 
  /*
   *
   * lista as respostas dos alunos que fizeram o exercicio
   */
  function getScoreAlunoExercicio($codExercicio,$codInstanciaGlobal){
    global $urlImagem;
    
    $scores = $this->getScoreExercicio($codExercicio,$codInstanciaGlobal);
    $questoes = $this->getQuestoes($codExercicio);
    
    $totalQuestoes = 0;
    echo "<table class=\"questao\">";
    echo "<tr><td></td>";
    
    //imprime os enunciados
    while ($questao = mysql_fetch_object($questoes)) {
      echo "<th class=\"linha\" align=center>".$questao->enunciado."</th>";
      $totalQuestoes++;
      $codUltimaQuestao = $questao->codQuestao;
    }
    echo "<th class=\"linha\" align=center>Acertos</th></tr>";
    
    $codPessoaAtual = -1;
    $numeroTentativaAtual = -1;

    while ($reg = mysql_fetch_object($scores)) {//cada registro contem a resposta do aluno em uma questao do exercicio 
      if ($reg->codPessoa != $codPessoaAtual || $reg->numeroDeTentativa != $numeroTentativaAtual) { //se encontra um novo aluno ou encontra uma nova tentativa, imprime o nome
        $codPessoaAtual = $reg->codPessoa;
        $numeroTentativaAtual = $reg->numeroDeTentativa;
        $acertosAluno = 0;
        echo "<tr><td class=\"linha\" align=center>".$reg->nome_pessoa."</td>"; 
      }
      
      if (is_null($reg->codAlternativaEscolhida)) { //o aluno nao preencheu a questao
        echo "<td class=\"linha\" align=center>SR</td>";
      }  
      else if ($reg->codAlternativaEscolhida == "verdadeiro") {  //o aluno errou a questao de verdadeiro/falso
        echo "<td class=\"linha\" align=center>Verdadeiro<img src=\"".$urlImagem."/errado.gif\" border=\"no\"></td>";
      }
      else if ($reg->codAlternativaEscolhida == "falso") { //o aluno errou a questao de verdadeiro/falso
        echo "<td class=\"linha\" align=center>Falso<img src=\"".$urlImagem."/errado.gif\" border=\"no\"></td>";
      }
      else if ($reg->codAlternativaCorreta == $reg->codAlternativaEscolhida) { //acertou a questao
        $acertosAluno++;
        echo "<td class=\"linha\" align=center>".$reg->texto."<img src=\"".$urlImagem."/correto.gif\" border=\"no\"></td>"; 
      }
      else {  //errou a questao
        echo "<td class=\"linha\" align=center>".$reg->texto."<img src=\"".$urlImagem."/errado.gif\" border=\"no\"></td>";
      }      
        
      if ($reg->codQuestao == $codUltimaQuestao) { //eh a ultima questao, imprime o resultado final
        echo "<td class=\"linha\" align=center>".$acertosAluno." de ".$totalQuestoes."</td></tr>";  
      } 
    }
    echo "</table>";
  }  
  
  //exporta o escore do exercicio no formato CSV/xls
  function exportScoreExercicio($codExercicio,$codInstanciaGlobal) {
    $celulas = array(); //array que armazena o conteudo de cada celula para exportar no formato xls
    $linhas = 0;
    $colunas = 0;
    //$return = array();  
    $scores = $this->getScoreExercicio($codExercicio,$codInstanciaGlobal);
    $questoes = $this->getQuestoes($codExercicio);
    
    $totalQuestoes = 0;
    //$string = 'Nome/Questao,';
    $celulas[0][0] = 'Nome/Questao,';
    $aspas = array();
    $aspas['"'] = '""';
    
    //imprime os enunciados
    while ($questao = mysql_fetch_object($questoes)) {
      $enunciado = strtr(strip_tags(trim($questao->enunciado)),$aspas);
      //$string.= '"'.$enunciado.'",';
      $totalQuestoes++;
      $celulas[0][$totalQuestoes] = $enunciado;
      $codUltimaQuestao = $questao->codQuestao;
      //$return[0] = $questao->descricaoExercicio; 
      $celulas['descricaoExercicio'][0] = $questao->descricaoExercicio;
    }
    //$string.= "Acertos";
    $celulas[0][$totalQuestoes+1] = "Acertos";
    $celulas[0][$totalQuestoes+2] = "Total de Questoes";
    $codPessoaAtual = -1;
    $numeroTentativaAtual = -1;
    $linhas = 0;
    while ($reg = mysql_fetch_object($scores)) {//cada registro contem a resposta do aluno em uma questao do exercicio 
      if ($reg->codPessoa != $codPessoaAtual) { //se encontra um novo aluno ou encontra uma nova tentativa, imprime o nome
        $linhas++; $colunas=0;
        $codPessoaAtual = $reg->codPessoa;
        $numeroTentativaAtual = $reg->numeroDeTentativa;
        $acertosAluno = 0;
        //$string.= '"'.$reg->nome_pessoa.'",';
        $celulas[$linhas][$colunas] = $reg->nome_pessoa;
      }
      $colunas++;
      if (is_null($reg->codAlternativaEscolhida)) { //o aluno nao preencheu a questao
        //$string.= '"SR",';
        $celulas[$linhas][$colunas] = "SR";
      }  
      else if ($reg->codAlternativaEscolhida == "verdadeiro") {  //o aluno errou a questao de verdadeiro/falso
        //formato csv, se voltar a ser utilizado
        //$string.= '"Verdadeiro",';
        
        //em vez do texto, coloca 0 como erro
        //$celulas[$linhas][$colunas] = "Verdadeiro";
        $celulas[$linhas][$colunas] = '0';
      }
      else if ($reg->codAlternativaEscolhida == "falso") { //o aluno errou a questao de verdadeiro/falso
        //formato csv, se voltar a ser utilizado
        //$string.= '"Falso",';
        //em vez do texto, coloca 0 como erro
        //$celulas[$linhas][$colunas] = "Falso";
        $celulas[$linhas][$colunas] = '1';
      }
      else if ($reg->codAlternativaCorreta == $reg->codAlternativaEscolhida) { //acertou a questao
        $acertosAluno++;
        //formato csv, se voltar a ser utilizado
        //$string.= '"'.$texto.'",';        
        
        //em vez do texto, coloca 1 como acerto
        //$texto = strtr(strip_tags(trim($reg->texto)),$aspas);
        //$celulas[$linhas][$colunas] = $texto;
        $celulas[$linhas][$colunas] = 1;
      }
      else {  //errou a questao
        //formato csv, se voltar a ser utilizado
        //$string.= '"'.$texto.'",';
        
        //em vez do texto, coloca 0 como erro
        //$texto = strtr(strip_tags(trim($reg->texto)),$aspas);
        //$celulas[$linhas][$colunas] = $texto;
        $celulas[$linhas][$colunas] = 0;
      }      
      if ($reg->codQuestao == $codUltimaQuestao) { //eh a ultima questao, imprime o resultado final
        //$string.= '"'.$acertosAluno.' de '.$totalQuestoes.'"'."\r\n";
        
        //total de acertos
        $colunas++;
        $celulas[$linhas][$colunas] = $acertosAluno;
        //total de questoes
        $colunas++;
        $celulas[$linhas][$colunas] = $totalQuestoes;
      }
    }
    $celulas['linhas'][0] = $linhas;
    $celulas['colunas'][0] = $colunas;   
    //$return[1] = $string;
    return $celulas;
  }
    
  //obtem, para cada questao do exercicio, as respostas de cada aluno
  function getScoreExercicio($codExercicio, $codInstanciaGlobal,$codPessoa="") {
/*    $sql = "select q.codAlternativaCorreta, q.enunciado, q.descricao, q.codQuestao, ate.codPessoa, ate.numeroDeTentativa, ra.codAlternativaEscolhida, p.nome_pessoa, a.texto 
            from exercicio as e
            inner join exercicioquestao as eq on (e.codexercicio = eq.codexercicio)
            inner join questao as q on (eq.codquestao = q.codquestao)
            inner join exercicioinstancia as ei on (e.codexercicio = ei.codexercicio)
            inner join alunotentativaexercicio as ate on (e.codexercicio = ate.codexercicio and ate.codinstanciaglobal = ei.codinstanciaglobal)
            inner join pessoa as p on (ate.codpessoa = p.cod_pessoa)
            left outer join respostasaluno as ra on (e.codexercicio = ra.codexercicio and q.codquestao = ra.codquestao and ei.codinstanciaglobal = ra.codinstanciaglobal and ate.codpessoa = ra.codpessoa)
            left outer join alternativa as a on (ra.codAlternativaEscolhida = a.codAlternativa)
            where e.codexercicio=".quote_smart($codExercicio)." and ei.codinstanciaglobal=".quote_smart($codInstanciaGlobal)."
            order by p.NOME_PESSOA";//, ra.codquestao";
            ;*/
    //echo $sql;
    //exit;
       $sql = "select q.codAlternativaCorreta, q.enunciado, q.descricao, q.codQuestao, ate.codPessoa, ate.numeroDeTentativa, ra.codAlternativaEscolhida, p.nome_pessoa, a.texto 
            from exercicio as e
            inner join exercicioquestao as eq on (e.codexercicio = eq.codexercicio)
            inner join questao as q on (eq.codquestao = q.codquestao)
            inner join exercicioinstancia as ei on (e.codexercicio = ei.codexercicio)
            inner join alunotentativaexercicio as ate on (e.codexercicio = ate.codexercicio and ate.codinstanciaglobal = ei.codinstanciaglobal)
            inner join pessoa as p on (ate.codpessoa = p.cod_pessoa";
            
            if(!empty($codPessoa)){
    $sql .= " and ate.codpessoa=".$codPessoa;        
            }
    $sql .= " ) ";
    
    $sql .= "left outer join respostasaluno as ra on (e.codexercicio = ra.codexercicio and q.codquestao = ra.codquestao and ei.codinstanciaglobal = ra.codinstanciaglobal and ate.codpessoa = ra.codpessoa)
            left outer join alternativa as a on (ra.codAlternativaEscolhida = a.codAlternativa)
            where e.codexercicio=".quote_smart($codExercicio)." and ei.codinstanciaglobal=".quote_smart($codInstanciaGlobal)."
            order by p.NOME_PESSOA";//, ra.codquestao";
 
    $result = mysql_query($sql);
    return $result;
  } 
   
  //retorna as questoes de um exercicio
  function getQuestoes($codExercicio) {
    $sql = "select q.enunciado, q.codQuestao, e.descricaoExercicio
            from exercicioquestao as eq, questao as q, exercicio as e
            where eq.codquestao = q.codquestao
            and eq.codexercicio = e.codexercicio
            and eq.codexercicio=".quote_smart($codExercicio);
    $result = mysql_query($sql);
    return $result;
  }
 
  function getScoreAlternativa($codExercicio,$codInstanciaGlobal,$codPessoa,$codQuestao){
    $sql="SELECT A.codAlternativa,A.texto, RA.codAlternativaEscolhida,RA.codQuestao".
         " FROM alternativa A".
         " INNER JOIN respostasaluno RA ON (RA.codAlternativaEscolhida=A.codAlternativa)".
         " WHERE RA.codInstanciaGlobal=".$codInstanciaGlobal." AND RA.codExercicio=".$codExercicio." AND RA.codPessoa=".$codPessoa." AND RA.codQuestao=".$codQuestao."".
         " ORDER BY RA.codQuestao";
    $result= new RDCLQuery($sql);
    return $result;
  } 
  
  function getScoreQuestao($codExercicio,$codInstanciaGlobal){
    $sql="SELECT Q.enunciado,Q.codQuestao,Q.descricao,Q.codAlternativaCorreta ".
         " FROM questao Q".
         " INNER JOIN exercicioquestao RA ON (RA.codQuestao = Q.codQuestao)".
         " WHERE  RA.codExercicio =".$codExercicio."".
         " ORDER BY Q.codQuestao";
    $result= new RDCLQuery($sql);
    return $result;
  }
  
  function getNomeAlunoScore ($codExercicio,$codInstanciaGlobal){
    $sql=" SELECT P.NOME_PESSOA, P.COD_PESSOA".
        " FROM  pessoa P".
        " INNER JOIN alunotentativaexercicio RA ON(RA.codPessoa=P.COD_PESSOA)".
        " WHERE RA.codInstanciaGlobal=".$codInstanciaGlobal." AND  RA.codExercicio=".$codExercicio."".
        " ORDER BY COD_PESSOA";
    $result= new RDCLQuery($sql);
    return $result;
  }
  /*
   * Retorna os alunos que não tentaram preencher o exercício 
   */      
  function getAlunosFaltaramScore ($codExercicio,$codInstanciaGlobal,$codInstanciaNivel,$nivel){
    //rever!!!!
    $pk = Aluno::getPKRelacionamento($nivel);
    if (empty($pk)) { $pk='COD_AL';}
    $strSQL = 'SELECT P.NOME_PESSOA'.
    ' FROM '.Aluno::getTabelaRelacionamento($nivel).' AT '. 
    ' INNER JOIN '.Aluno::getTabela().' A ON (A.'.$pk.'=AT.'.$pk.')'.
    ' INNER JOIN pessoa P ON (A.COD_PESSOA=P.COD_PESSOA) '.
    ' LEFT JOIN alunotentativaexercicio ATE ON(ATE.codPessoa=P.COD_PESSOA)'.
    //' INNER JOIN respostasaluno RA ON(RA.codPessoa=P.COD_PESSOA)'.              
    ' WHERE '.
    ' AT.'.$nivel->nomeFisicoPK.' = '.$codInstanciaNivel. ' AND '.  
    //' ATE.codInstanciaGlobal='.$codInstanciaGlobal.
    //' AND  RA.codExercicio='.$codExercicio.
    ' ATE.codPessoa IS NULL'.
    ' ORDER BY P.NOME_PESSOA';
    //echo $strSQL;
    $result= new RDCLQuery($sql);
    
    return $result;
  }

 /**
  * funcÊo copiada da internet para comparar duas datas
  * http://br2.php.net/datetime 27/04/2007  
  */   
function datediff($date1, $date2) {
    $inicio=explode("/",$date1);
    $fim=explode("/",$date2);
   // print_r($fim);
   // print_r($inicio);
    if($fim[2]==0 or $fim[1]==0 or $fim[0]==0){  return 1;}
    if($inicio[2]>=$fim[2]){
      if ($inicio[2]==$fim[2]){
          if($inicio[1]>=$fim[1]){
            if($inicio[1]==$fim[1]){
                if($inicio[0]>=$fim[0]){
                    if($inicio[0]==$fim[0]) {  return 1;}
                    else $return -1;
                }
                else{  return 1;}
            }
            else return -1;
          }
          else { return 1;}  
      }
      else return -1;    
    }
    else{
       return 1;
    }
}

function horadiff($horaInicio,$horaFim){
    
    $horaIn=explode(":",$horaInicio);
    $horaOut=explode(":",$horaFim);
    $horaNow=explode(":",date("H:i"));
  /* print_r($horaIn);
   print_r($horaOut);
   print_r($horaNow);*/
   
  if($horaNow[0]>=$horaIn[0] && $horaNow[0]<=$horaOut[0]){
      if(($horaNow[1]>=$horaIn[1] && $horaNow[1]<=$horaOut[1])||($horaNow[0]==$horaOut[0] && $horaNow[1]<=$horaOut[1])|| ($horaIn[0]<$horaNow[0] && $horaNow[0]<$horaOut[0]))
         return 1;
      else
          return 0;
  }else
     return 0;

}
 /**
*selecionar todos os nomes dos alunos que fizeram o exercicio
*/
function getScoreImprimir($codExercicio,$codInstanciaGlobal){
   global $urlImagem;
   $linha=$this->getScoreQuestao($codExercicio,$codInstanciaGlobal);
   $linhaN= $this->getNomeAlunoScore($codExercicio,$codInstanciaGlobal);
   echo "<table width=\"100%\" cellspacing=\"0\" style=\"background-color:#000000\"><tr><td>";
   echo "<table width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">";
   echo "<tr><td style=\"background-color:#ffffff\"></td>";
   //coloquei os enunciados na tabela
   $totalQuestoes=0;
   foreach($linha->records as $questao){
      $enunciado =substr($questao->descricao,0,20);
      $enunciado.= "...";
   
      if( $linhaQuestao!=$questao->codQuestao) echo "<td style=\"background-color:#ffffff\">".$enunciado."</td>";
      $codQuestao[].=$questao->codQuestao;
      $linhaQuestao[$questao->codQuestao]=$questao->codAlternativaCorreta;
      $totalQuestoes= $totalQuestoes+1;
  }
   echo "<td style=\"background-color:#ffffff\">Acertos</td>";
   echo "</tr>";
   foreach($linhaN->records as $nome){
       echo "<tr><td style=\"background-color:#ffffff\">".$nome->NOME_PESSOA."</td>";
       $linhaNome=$nome->COD_PESSOA;
      $acertoAluno=0;
      for($i=0;$i<count($codQuestao);$i=$i+1){
        $linhaA= $this->getScoreAlternativa($codExercicio,$codInstanciaGlobal,$linhaNome,$codQuestao[$i]);
        if(!empty($linhaA->records)){
          foreach($linhaA->records as $alternativa){
            echo "<td style=\"background-color:#ffffff\" align=\"center\">";
            if($alternativa->codAlternativa==$linhaQuestao[$alternativa->codQuestao]){
               echo "Certo</td>";
               //echo "<img src=\"".$urlImagem."/correto.gif\" border=\"no\"></td>"; 
               $acertoAluno= $acertoAluno+1;
            }
            else {//echo "<img src=\"".$urlImagem."/errado.gif\" border=\"no\"></td>";
              echo "Errado</td>";
            }
          }
        }
        else{
          $RespostaAlunoQuestao=$this->getScoreExercicio($codExercicio,$codInstanciaGlobal,$nome->COD_PESSOA);
          while($AlunoErrou=mysql_fetch_object($RespostaAlunoQuestao))
          {
            if($AlunoErrou->codQuestao == $codQuestao[$i]) 
            {
                if(empty($AlunoErrou->codAlternativaEscolhida))
                 echo "<td style=\"background-color:#ffffff\" align=\"center\">SR</td>";
                else
                echo "<td style=\"background-color:#ffffff\" align=\"center\">Errado</td>";
            }
          }
          
          
         }

      }
     echo "<td style=\"background-color:#ffffff\"><sup>".$acertoAluno."</sup> /<sub> ".$totalQuestoes."</sub></td>";  
   }
  
   echo"</tr>";
   echo "</table></td></tr></table>";
}
 function exercicioAtivo($codExercicio,$codInstanciaGlobal){
  $sql= "SELECT * FROM exercicioinstancia WHERE codInstanciaGlobal=".$codInstanciaGlobal."  AND codExercicio=".$codExercicio."";
  $result= new RDCLQuery($sql);
  return $result;
 }

 /*
  * Exporta as respostas do aluno para um arquivo texto, a fim de ser lido
  * por um programa externo  
  *
  */    
 function exportarRespostas($codExercicio) {
   global $urlImagem;
   $linha=$this->getScoreQuestao($codExercicio,$codInstanciaGlobal);
   $linhaN= $this->getNomeAlunoScore($codExercicio,$codInstanciaGlobal);

   //coloquei os enunciados na tabela
   $totalQuestoes=0;
   foreach($linha->records as $questao){
      $enunciado =substr($questao->descricao,0,20);
      $enunciado.= "...";
   
      if( $linhaQuestao!=$questao->codQuestao) echo "<td style=\"background-color:#ffffff\">".$enunciado."</td>";
      $codQuestao[].=$questao->codQuestao;
      $linhaQuestao[$questao->codQuestao]=$questao->codAlternativaCorreta;
      $totalQuestoes= $totalQuestoes+1;
   }
   echo "<td style=\"background-color:#ffffff\">Acertos</td>";
   echo "</tr>";
   foreach($linhaN->records as $nome){
       echo "<tr><td style=\"background-color:#ffffff\">".$nome->NOME_PESSOA."</td>";
       $linhaNome=$nome->COD_PESSOA;
      $acertoAluno=0;
      for($i=0;$i<count($codQuestao);$i=$i+1){
        $linhaA= $this->getScoreAlternativa($codExercicio,$codInstanciaGlobal,$linhaNome,$codQuestao[$i]);
        if(!empty($linhaA->records)){
          foreach($linhaA->records as $alternativa){
            echo "<td style=\"background-color:#ffffff\" align=\"center\">";
            if($alternativa->codAlternativa==$linhaQuestao[$alternativa->codQuestao]){
               echo "<img src=\"".$urlImagem."/correto.gif\" border=\"no\"></td>"; 
              $acertoAluno= $acertoAluno+1;
            }
             else echo "<img src=\"".$urlImagem."/errado.gif\" border=\"no\"></td>";
          }
        }
        else echo "<td style=\"background-color:#ffffff\" align=\"center\">SR</td>";
      }
     echo "<td style=\"background-color:#ffffff\"><sup>".$acertoAluno."</sup> /<sub> ".$totalQuestoes."</sub></td>";  
   }
  
   echo"</tr>";
   echo "</table></td></tr></table>";
 
 }  
}?>
