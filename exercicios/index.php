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

include('../config.php');
//set_include_path(get_include_path() . ';'. $caminhoBiblioteca.'/pear/');
set_include_path(get_include_path() . PATH_SEPARATOR. $caminhoBiblioteca.'/pear/');
//set_include_path($caminhoBiblioteca.'/pear');

ini_set('display_errors',0);
error_reporting(E_ALL ^ E_NOTICE);

include($caminhoBiblioteca.'/questao.inc.php');
include($caminhoBiblioteca.'/functionsDeEdicao.inc.php');
include($caminhoBiblioteca.'/funcoesftp.inc.php');
include($caminhoBiblioteca.'/notas.inc.php');
include($caminhoBiblioteca.'/exercicios.inc.php');
include($caminhoBiblioteca.'/pear/Spreadsheet/Excel/Writer.php');
session_name(SESSION_NAME);  
session_start(); 
if ($_REQUEST['acao']=="exportarScore") {
  security(0,1); //nao executar js de verificao de seguranca na instancia, pois impede cabecalho via header
}
else {
  security();
}

$nivel=getNivelAtual();

function printHeader($params="") {
  global $url,$urlCss;
  echo "<html><head>";
  echo "<link rel='stylesheet' href='".$urlCss."/exercicio.css' type='text/css'>";
///////
  $configMathml = new InstanciaGlobal($_SESSION["codInstanciaGlobal"]);

  if($configMathml->getUsoMathml()===1 && empty($_REQUEST[unSetMathml]) && empty($_SESSION[unSetMathml])){
  @$_SESSION['configMathml']['script']="<script type='text/javascript' src='".$url."/js/ASCIIMathML.js'></script>";
  }else{
		unset($_SESSION['configMathml']);
		if(empty($_SESSION[unSetMathml])){
			$_SESSION[unSetMathml]=$_REQUEST[unSetMathml];
		}
	}
////	
  echo $_SESSION['configMathml']['script'];
  
  if (!empty($params["titulo"])) {
    echo "<title>".$params["titulo"]."</title>";
  }

  //script para contagem de segundos restantes
  if ($params['contarTempo']) {
    echo "<script>
    tempoRestante=0;
    function tempo(totalSegundos,tempoAtual) {
      tempoRestante = totalSegundos - tempoAtual;
      atualizaTempoRestante();
      window.setInterval('atualizaTempoRestante(tempoRestante)',1000);
    }    
    
    function atualizaTempoRestante() {
      tempoRestante = tempoRestante-1;
      minutos = Math.floor(tempoRestante/60);
      segundos = tempoRestante % 60;
      document.getElementById('tempoRestante').innerHTML=minutos+':'+segundos;
      
      //for¦a o submit do form se o tempo tiver sido esgotado
      if (tempoRestante<=0) {
        alert('Tempo limite atingido');
        location.href='index.php';
      }
    }
    </script>";    
  }

  echo "</head>";
  echo "<body class='bodybg' ".$params["body"].">";
  
  //coloca um inicio de tabela para o conteudo ficar centralizado  
  echo"<table  width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
  echo "<tr align=\"center\"><td class='titulos'>".$params["tituloPagina"]."<br></td></tr>";
  if (!$params['naoMostrarTabela']) {
    echo "<tr align=\"center\"><td valign='middle'>";
  }
  else {
    echo '</table>';
  }
  
}

function printFooter() {
  echo '</td></tr></table></body></html>';
}

//funcao para download do arquivo com o score exportado no formato CSV
function headerDownload($nome,$mimeType="",$tamanho="") {
  
  if (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE"))
    header("Content-Disposition: filename=".$nome);
  else
    header("Content-Disposition: attachment; filename=".$nome);
  header("Content-type: ".$mimeType);
	  
  if (!empty($tamanho))
    header("Content-Length: ".$tamanho);
}

switch($_REQUEST["acao"]) {
/** 
*LISTA TODOS OS EXERCICIOS DA INSTANCIA
**/
	case "":
		$params["tituloPagina"]="Exercicios on-line";
		$_SESSION['tempoMaximo']=0;
		printHeader($params);
		
    if(Pessoa::podeAdministrar($_SESSION['userRole'],$nivel,$_SESSION['interage'])){ 
    echo "<table width=\"80%\"><tr>". 
         "<td align=\"left\"><a href=\"exercicio.php?FILTRO=meus&classe=Questao\">Banco de Quest&otilde;es</a></td>".
         "<td align =\"right\"><a href=\"exercicio.php\">Banco de Exerc&iacute;cios</a>". 
		     //"\n|<a href=\"exercicio.php?acao=criar\">CRIAR</a></td>". 
         "</tr></table>";
    }
    else  echo "<br><br>";
    $remover= $_SERVER[PHP_SELF]."?acao=remover";
    $alterar = $_SERVER[PHP_SELF]."?acao=alterar&voltar=ok";
    $ver =  $_SERVER[PHP_SELF]."?acao=verExercicio";
    $verScore= $_SERVER[PHP_SELF]."?acao=verScore";
    $verSocreAluno=$_SERVER[PHP_SELF]."?acao=verResposta";
    $exercicio= new Exercicio();

    $exercicio->layoutListaExerciciosInstancia($nivel, $remover,$alterar,$ver,$verScore,$verSocreAluno,$_SESSION['codInstanciaGlobal']);
    
    printFooter();
    break;
  /**
  * VISUALIZACAO DO EXERCICIO
  */
  case "verExercicio":
   $params["tituloPagina"]="Exerc&iacute;cios on-line";
    
   $exe=new Exercicio();
   $tempo = $exe->getRelogio($_REQUEST["codExercicio"]);
   
   //relogio de tempo menor de execucao, 
   //dentro do periodo em que a prova est¯ disponivel 
   if ($tempo->tempoMinutosExecucaoDurantePeriodo) {
     if (empty($_SESSION['tempoMaximo'])) {
       $_SESSION['tempoMaximo']=time() + ($tempo->tempoMinutosExecucaoDurantePeriodo * 60);
     }
     $relogioAtivo=1;
   }
   //relogio de tempo menor de execucao, 
   //dentro do periodo em que a prova est¯ disponivel 
   if ($tempo->mostrarRelogioExpiracao && date('Ymd')==$tempo->diaFinal ) {
     if (empty($_SESSION['tempoMaximo'])) {
       $_SESSION['tempoMaximo']=strtotime($tempo->dataExpiracao);
     }
     $relogioAtivo=1;     
   }

   //Nos dois tipos de relogio usa a funþÒo do relogio
   if ($relogioAtivo) {
     if (($_SESSION['tempoMaximo']-time())<=0) {
       echo "<center><b>Tempo m&aacute;ximo atingido.</b></center>"; die;
     }          
     $params['body']=" onLoad='tempo(".$_SESSION['tempoMaximo'].",".time().");'";
     $params['contarTempo']=1;   
   }
   printHeader($params);
   if ($relogioAtivo) {
     echo "<span style='font-weight:bold; color:red;'>";
     if ($tempo->tempoMinutosExecucaoDurantePeriodo) {
       echo "Tempo maximo: ".$tempo->tempoMinutosExecucaoDurantePeriodo." minuto";
       if ($tempo->tempoMinutosExecucaoDurantePeriodo>1) { echo "s"; } //trata o plural       
     } 
     else { 
       echo "&Uacute;ltimo dia! Hora final: ".$tempo->horaFim.":".$tempo->minutoFim; 
     }
     echo "</span>";
     echo "  | Tempo restante: <span id='tempoRestante'></span>";
   }
   $dadosExercicio=$exe->imprimiExercicio($_REQUEST["codExercicio"]);   
   
   if (empty($_REQUEST["arrayDeQuestoes"])) { 
     /*print ("array vazio<br>");*/
     $exe->adicionaTentativaExercicio($_SESSION["COD_PESSOA"],$_REQUEST["codExercicio"]);
   }
   
   $numeroTentativasAluno= $exe->getNumeroTentativas($_SESSION["COD_PESSOA"],$_REQUEST["codExercicio"]);
   $questao= new Questao($codQuestao);
   $acao=$_SERVER[PHP_SELF]."?acao=insereBD";
   
   if($numeroTentativasAluno>0){
    $respostaAlunoExercicio=$exe->getRespostasExercicio($_SESSION["COD_PESSOA"],$_REQUEST["codExercicio"],$_SESSION["codInstanciaGlobal"]);
   }
   $questao->layoutDaResolucaoDoExercicio($dadosExercicio,$_REQUEST["codExercicio"],$acao,$_REQUEST["arrayDeQuestoes"],$numeroTentativasAluno,$respostaAlunoExercicio);
   break;
  /**
  *REMO++O DO EXERCICIO DA INST-NCIA
  */
  case "remover":
      $exercicio= new Exercicio();
      $exercicio-> ExercicioLocalRemove($_REQUEST["codExercicio"],$_SESSION["codInstanciaGlobal"]);
      echo "<script>location.href=\"".$_SERVER[PHP_SELF]."?\";</script>";
	 break;
  /**
  *REDIRECIONA PARA EXERCICIO.PHP P/ ALTERAR EXERCICIO
  */
  case "alterar":
     echo "<script>location.href=\"exercicio.php?acao=criar_exercicio&codExercicio=".$_REQUEST["codExercicio"]."\";</script>";
    break;
 /**
 *VER AS REPOSTAS
 */
  case "verResposta":
      $params["tituloPagina"]="Exerc&iacute;cios - Resposta";
	    printHeader($params); 
      if($_REQUEST['concluido']=='ok'){ msg("<br><br>Resposta(s) recebida(s) com sucesso!<br><br>");}
      $obj=new Exercicio();
      $voltar=$_SERVER[PHP_SELF];
      if ($_REQUEST["voltar"]!="naoAtivo") echo "<tr><td colspan=\"2\" aling=\"left\"><a href=\"javascript:history.back()\"><img src=\"".$urlImagem."/voltar.gif\" border=\"no\"><br><b>Voltar</b></a></td></tr>";
      if(!empty($_SESSION["COD_AL"])){
        echo "<tr><td colspan=\"2\" align=\"center\">";
        $obj->layoutVerResposta($_SESSION["COD_PESSOA"],$_REQUEST["codExercicio"],$voltar);
        echo "</td></tr>";
      }
      else{echo "<tr><td colspan=\"2\" align=\"center\"><font color=\"red\"><b>Aten&ccedil;&atilde;o! Voc&ecirc; n&atilde;o tem permiss&atilde;o para ver o resultado.</b></font></td></tr>";}
   break;
   /*
    * VER SCORE DAS PROVAS
    */
  case "verScore":  
      $params["tituloPagina"]="Exercicio - Score"; $params['naoMostrarTabela']=1;
      printHeader($params);
      $obj=new Exercicio();
      $exercicio=$obj->listaExercicioInstancia($_SESSION['codInstanciaGlobal']);
      echo '<div align="left"><a href="'.$_SERVER[PHP_SELF].'"><img src="'.$urlImagem.'/voltar.gif" border="no"><b>Voltar</b></a></div>';

      
      if(empty($_REQUEST["codExercicio"])){
        echo "<table width='80%'><tr><td>";
        echo "<table class='questao' cellspacing='3' cellpadding='5' style=\"background-color:#efffff\" width='200px' align='left'>";
        if(Pessoa::podeAdministrar($_SESSION['userRole'],$nivel,$_SESSION['interage'])){
          foreach($exercicio->records as $exe){ echo "<tr><td  align=\"left\" style=\"background-color:#ffffff\">=><a href=\"".$_SERVER[PHP_SELF]."?acao=verScore&codExercicio=".$exe->codExercicio."\">".$exe->descricaoExercicio."</a></td></tr>";}
        }
        else{
          foreach($exercicio->records as $exe){ echo "<tr><td  align=\"left\" style=\"background-color:#ffffff\">=><a href=\"".$_SERVER[PHP_SELF]."?acao=verResposta&codExercicio=".$exe->codExercicio."\">".$exe->descricaoExercicio."</a></td></tr>";} 
        }
        echo '</table></td></tr></table>';
        echo '</td></tr></table>';     
      }
      //escolha do exercicio
      else{
        echo '<table width="50%"><tr>';
        if(Pessoa::podeAdministrar($_SESSION['userRole'],$nivel,$_SESSION['interage'])) {
          echo "<td align=\"right\"><a href=\"./imprimirScore.php?codExercicio=".$_REQUEST["codExercicio"]."\"><img src=\"".$urlImagem."/impressaochamada.gif\" border=\"no\"><br><b>Imprimir Score</b></a></td>";
          echo "<td align=\"right\"><a href=\"".$_SERVER[PHP_SELF]."?acao=exportarScore&codExercicio=".$_REQUEST["codExercicio"]."\"><img src=\"".$urlImagem."/exportar.gif\" border=\"no\"><br><b>Exportar Score</b></a></td>";
          echo "<td align=\"right\"><a href=\"".$_SERVER[PHP_SELF]."?acao=alunosFaltantes&codExercicio=".$_REQUEST["codExercicio"]."\"><img src=\"".$urlImagem."/exportar.gif\" border=\"no\"><br><b>Alunos que N&Atilde;O fizeram a prova</b></a></td>";
        }
        echo "</tr></table>";

        $obj->verScore($_REQUEST["codExercicio"],$_SESSION["codInstanciaGlobal"]);
        echo "</td></tr></table>";
      }      
      
      printFooter();
      break;  

 /**
 * INSERIR BD 
 */
   case "insereBD":
     $obj=new Exercicio();
   
     /*
      * COMPARAR A INSTANCIA GLOBAL COM UM COOKIE NO CLIENTE
      *
      */            
     $obj->gravarResposta($_SESSION["COD_PESSOA"],$_SESSION["codInstanciaGlobal"],$_REQUEST);
     // print($gravar);
     //fazer teste do array de questoes para saber para onde voltar;  
     $arrayDeQuestoes=$_REQUEST["arrayDeQuestoes"];
     
     if(!empty($_REQUEST["arrayDeQuestoes"])){
          echo "<script>location.href=\"".$_SERVER[PHP_SELF]."?acao=verExercicio&codExercicio=".$_REQUEST["codExercicio"]."";
          for($i=0;$i<count($_REQUEST["arrayDeQuestoes"]);$i++) echo "&arrayDeQuestoes[].=".$arrayDeQuestoes[$i]."";
          echo "\";</script>";
     }
     else{
      echo "<script>location.href=\"".$_SERVER[PHP_SELF]."?acao=verResposta&codExercicio=".$_REQUEST["codExercicio"]."&voltar=naoAtivo&concluido=ok\";</script>";
     }
     break;  

  case "exportarScore":
    $obj = new Exercicio();
    $conteudo = array();
    $conteudo = $obj->exportScoreExercicio($_REQUEST['codExercicio'],$_SESSION['codInstanciaGlobal']);  
    
    // Creating a workbook
    $workbook = new Spreadsheet_Excel_Writer();

    // sending HTTP headers
    $nome = "score_".strtr($conteudo['descricaoExercicio'][0]," ","_").".xls";

    $workbook->send($nome);  
    
    //Creating a worksheet
    $worksheet =& $workbook->addWorksheet("Planilha1");
    
    //coloca o conteudo na planilha
    for ($i=0; $i<=$conteudo['linhas'][0]; $i++) {
      for ($j=0; $j<=$conteudo['colunas'][0]; $j++) {
        $worksheet->writeString($i, $j, $conteudo[$i][$j]);
      }
    }
    // Let's send the file
    $workbook->close();
    $nome = "score_".strtr($conteudo['descricaoExercicio'][0]," ","_").".xls"; 
    $mimeType = "application/force-download";
    headerDownload($nome,$mimeType);
    echo $return[1];
  break;

  case "alunosFaltantes":
    //verificar
    include_once($caminhoBiblioteca.'/curso.inc.php');
    $params["tituloPagina"]="Alunos que n&atilde;o fizeram o exerc&iacute;cio"; $params['naoMostrarTabela']=1;

    printHeader($params);
    $obj=new Exercicio();
    $codExercicio = (int)$_REQUEST['codExercicio'];
    $codInstanciaGlobal=(int)$_SESSION['codInstanciaGlobal'];
    $codInstanciaNivel=getCodInstanciaNivelAtual();
    
    //$alunosFaltantes = $obj->getAlunosFaltaramScore($codExercicio, $codInstanciaGlobal, $codInstanciaNivel, $nivel);
    //die();

    $todosAlunos= listaTodosIntegrantes(new Aluno(),0,0,0,0,$codInstanciaNivel,$nivel);

    //coloca os nomes de todos os alunos da instância em um array
    $i=0;
    while ( $linha = mysql_fetch_array($todosAlunos) )
    {
      $alunosDaTurma[$i]= $linha["NOME_PESSOA"];
      $i++;
    }
    //
    
    echo '<div align="left"><a href="'.$_SERVER[PHP_SELF].'"><img src="'.$urlImagem.'/voltar.gif" border="no"><b>Voltar</b></a></div>';
    echo '<div style="padding-left:15px;padding-right:15px;padding-top:15px;">';
    echo '<table cellpadding="3" cellspacing="1" style="background-color:#606060;width:100%;">';
    echo '<tr><th style="font-size:14px;font-weight:bold;background-color:#DFDFCF;color:navy;">Nome</th></tr>';    
    
    $presentes = $obj->getNomeAlunoScore ($codExercicio,$codInstanciaGlobal);

    //coloca os nomes de todos os alunos que fizeram o exercício em um array
    $i=0;
    foreach ($presentes->records as $p)
    {
      $alunosFizeramExercicio[$i]= $p->NOME_PESSOA;
      $i++;
    }
    //

    //compara o array com todos os alunos da turma com o array que contém apenas os alunos que fizeram o exercício
    if (count($alunosFizeramExercicio) == 0)
    {
      $alunosNaoFizeramExercicio = $alunosDaTurma;
    }
    else
    {
      $alunosNaoFizeramExercicio = array_diff($alunosDaTurma, $alunosFizeramExercicio);
    }
    //
    
    if (count($alunosDaTurma) == count($alunosFizeramExercicio))
    {
      $color='#C0C0C0';
      echo '<tr style="background-color:'.$color.'"><td>Todos os alunos realizaram este exerc&iacute;cio.</td></tr>';
    }
    else
    {
      foreach ($alunosNaoFizeramExercicio as $p)
      {
        $color='#C0C0C0';
        echo '<tr style="background-color:'.$color.'"><td>'.$p.'</td></tr>';
        if ($color=='#C0C0C0') { $color='#DEDEDE';} else { $color='#C0C0C0';}
      }
    }
 
    echo '</table></div>';     
    break;
}
?>
