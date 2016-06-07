<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de d˙vidas e/ou sugestıes, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, port„o K. Porto Alegre - RS. CEP: 90035-003

Este programa È software livre; vocÍ pode redistribuÌ-lo e/ou modific·-lo sob os termos da LicenÁa P˙blica Geral GNU conforme publicada pela Free Software Foundation; tanto a vers„o 2 da LicenÁa, como (a seu critÈrio) qualquer vers„o posterior.

    Este programa È distribuÌdo na expectativa de que seja ˙til, porÈm, SEM NENHUMA GARANTIA;
    nem mesmo a garantia implÌcita de COMERCIABILIDADE OU ADEQUA«√O A UMA FINALIDADE ESPECÕFICA.
    Consulte a LicenÁa P˙blica Geral do GNU para mais detalhes.
    

    VocÍ deve ter recebido uma cÛpia da LicenÁa P˙blica Geral do GNU junto com este programa;
    se n„o, escreva para a Free Software Foundation, Inc., 
    no endereÁo 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} */

include_once("./../../config.php");
include_once($caminhoBiblioteca."/forum.inc.php");
include_once($caminhoBiblioteca."/perfil.inc.php");
//include_once($caminhoBiblioteca."/pessoa.inc.php");
session_name(SESSION_NAME); session_start(); security();

//le o arquivo de configuracao do forum
$ini = parse_ini_file("forum.ini",1);

//note($_SESSION);
$configMathml = new InstanciaGlobal($_SESSION["codInstanciaGlobal"]);

if($configMathml->getUsoMathml()===1 && empty($_REQUEST[unSetMathml]) && empty($_SESSION[unSetMathml])){
@$_SESSION['configMathml']['script']="<script type='text/javascript' src='".$url."/js/ASCIIMathML.js'></script>";
}else{
		unset($_SESSION['configMathml']);
		if(empty($_SESSION[unSetMathml])){
			$_SESSION[unSetMathml]=$_REQUEST[unSetMathml];
		}
	}


if( ($_SESSION["COD_PESSOA"] == "") OR ($_SESSION['codInstanciaGlobal'] == "")){
	?><link rel="stylesheet" href="./../../cursos.css" type="text/css"><?php
	$nomeFerramenta[1] = "F&oacute;rum";
	$nomeFerramenta[2] = "Suporte t&eacute;cnico";
	$nomeFerramenta[3] = "Caf&eacute virtual";
	msg($nomeFerramenta[$_REQUEST["tipoForum"]] . " dispon&iacute;vel apenas para alunos cadastrados.");
	die();
}
					  	
if(isset($_REQUEST["tipoForum"])) {
  //estamos entrando em um novo forum!
  //Guarda na variavel de sessao configForum as configuracoes
  //do tipo de forum atual
  switch($_REQUEST["tipoForum"]) {
  case "":
  case 0:
  case 1:
 
    //forum academico
    $_SESSION["configForum"] = $ini["Academico"];
    
    break;
  case 2:
    //forum tecnico
    $_SESSION["configForum"] = $ini["Tecnico"];
    break;
  case 3:
    //cafe virtual
    $_SESSION["configForum"] = $ini["CafeVirtual"];
    break;
  }
  //Abre sempre na primeira pagina, para evitar problemas quando trocamos de forum...
  $_SESSION["paginaForum"] = 1;
}
//Se √© administrador, entao ve todas as msgs. 
//se nao, ve apenas as msgs da turma.
if ( ($_SESSION["COD_ADM"] && $_SESSION["configForum"]["admVeTodasMensagens"]) || $_SESSION["configForum"]["userVeTodasMensagens"] ) {  
  $getTurma=0;  
   } 
else { 
  $getTurma=1; 
}

$threadsPerPage = 5; //numero de threads por pagina
$maxPaginas = 10;  //numero maximo de paginas

//trabalha a visualiza√ß√£o de todas as mensagens
if (isset($_REQUEST['showAll'])) { 
  $_SESSION['showAll']=$_REQUEST['showAll'];
}
$showAll = $_SESSION['showAll'];

if (empty($_SESSION["paginaForum"])) {
  $_SESSION["paginaForum"] = 1;
}
else if (isset($_REQUEST["paginaForum"])) {
     $_SESSION["paginaForum"] = $_REQUEST["paginaForum"];
}
//calcula a mensagem que deve ser retonada dado um determinado numero de pagina
function calcIniMsg($paginaForum) {
  global $threadsPerPage;
  return ($paginaForum-1)*$threadsPerPage; 
}





//imprime as mensagens
//$childMsgs esta indexado pelo codigo da mensagem pai
function imprimeMsgsForum(&$mainThreads,&$childMsgs,$param='') {
  $tamanho = count($mainThreads);
  $codInstanciaNivel=getTurma($_SESSION["codInstanciaGlobal"]);
  $instanciaGlobal = new InstanciaGlobal($_SESSION["codInstanciaGlobal"]);
  $codNivel = $instanciaGlobal->codNivel;
    
  for ( $i = 0; $i < $tamanho; $i++ ) {
    $tipoUser=isProfessor($mainThreads[$i]["COD_PESSOA"], $codInstanciaNivel, $codNivel);
    //$tipoUser = mysql_fetch_array($tipoUser);
    if (!$tipoUser) { $classeMsg="exibe_normal"; } else { $classeMsg="exibe_prof_normal";  }
    echo "<div class='".$classeMsg."'>";
    //Cria link interno
    echo "<a name=\"#".$mainThreads[$i]["COD_MENSAGEM"]."\"></a>";
    //echo "<hr>";
    
    //Mostra foto, se o aluno tiver. Se n√£o tiver, mostra foto gen√©rica
    echo "<img src='../../alunos/foto.php?COD_PESSOA=".$mainThreads[$i]["COD_PESSOA"]."&CASE=FOTO_REDUZIDA' height='34' width='30'>";
    
    //mostra [NOVO] ao lado da foto do usu√°rio se a mensagem for nova para ele
    $msgNova = BuscaMsgNova($_SESSION["codInstanciaGlobal"], $_REQUEST['COD_SALA'], $_SESSION["COD_PESSOA"], $_SESSION["configForum"]["tabelaMsgNova"], $_SESSION["configForum"]["tabelaSalas"], $mainThreads[$i]["COD_MENSAGEM"]);
    if ($msgNova) { $msgNova = mysql_fetch_array($msgNova); } else {$msgNova = "";}
    if ($msgNova AND $mainThreads[$i]["COD_PESSOA"]!=$_SESSION["COD_PESSOA"]) { echo "<font color='red'><b>[nova]</b></font>"; }



//---------------------------------alterar msg que ainda n√£o tenha filhos------------------------------

	if(($mainThreads[$i]["COD_PESSOA"]==$_SESSION["COD_PESSOA"]) AND empty($childMsgs[$mainThreads[$i]["COD_MENSAGEM"]]))
	{

	$locationHref="location.href='".$_SERVER['PHP_SELF']."?COD_SALA=".$mainThreads[$i]["COD_SALA"]."&acao=excluirMSG&codMSG=".$mainThreads[$i]["COD_MENSAGEM"]."'";

			   
	echo "<a href='#' onClick=\"if(confirm('Deseja mesmo excluir esta menssagem?')){".$locationHref."}\"  ><img  src=\"./imagens/".$_SESSION["configForum"]["imagemExcluir"]."\" border='no'></a>&nbsp;&nbsp;";
				
	echo "<a href=\"".$_SERVER['PHP_SELF']."?COD_SALA=".$mainThreads[$i]["COD_SALA"]."&acao=FormAlterar&codMSG=".$mainThreads[$i]["COD_MENSAGEM"]."\" ><img src=\"./imagens/".$_SESSION["configForum"]["imagemAlterar"] ."\" border='no'></a>";
				



	}

//-------------------------------------------------------------------------------------------------
	echo  "<span class=\"usuario\"><a href=\"../../consultar.php?&BUSCA_PESSOA=".$mainThreads[$i]["COD_PESSOA"]."\" target=\"_blank\">".
       $mainThreads[$i]["NOME_PESSOA"] .
      " </a></span>";
	$classificaMsg=classificaTipoMsg($mainThreads[$i]["codTipoMsg"]);
	echo"	&nbsp;<b>".$classificaMsg."</b>&nbsp;em <b>" . $mainThreads[$i]["DATA_MENSAGEM"] . "</b>&nbsp; - &nbsp" ;
  if ( podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) { 
		echo "	<a href='./escrever.php?topico=".$param["topico"]."&acao=".$param["acao"]."&dataFim=".$param["dataFim"]."&dataInicio=".$param["dataInicio"]."&COD_PESSOA=".$param["COD_PESSOA"]."&COD_SALA=".$mainThreads[$i]["COD_SALA"]."&RESPOSTA=" . $mainThreads[$i]["COD_MENSAGEM"] ."&codMainThread=".$mainThreads[$i]["COD_MENSAGEM"]."' class='escreve2'>Contribuir</a>";
	}

    if (!empty($childMsgs[$mainThreads[$i]["COD_MENSAGEM"]]))
      echo "&nbsp;&nbsp;<a href=\"#".$mainThreads[$i]["COD_MENSAGEM"]."\" onclick=\"toggle('div_msg_".$mainThreads[$i]["COD_MENSAGEM"]."')\" style=\"font-size: 12px\"> +/- </a>";
    
    
	
	
	if(($_REQUEST['acao']=='FormAlterar') AND ($mainThreads[$i]["COD_PESSOA"]==$_SESSION["COD_PESSOA"])AND ($mainThreads[$i]["COD_MENSAGEM"]==$_REQUEST["codMSG"])){
	$texto=formAlterarMSG($mainThreads[$i]["COD_MENSAGEM"],$mainThreads[$i]["TEXTO_MENSAGEM"],$_SESSION["COD_PESSOA"],$mainThreads[$i]["COD_SALA"]);
		}
	else
		$texto=preparaTexto($mainThreads[$i]["TEXTO_MENSAGEM"]);
	
	
	
	echo  " <br> <font size='2'>" . $texto . "</font><br><br>".         
      "</div>";
    
    //imprime as respostas ( mensagens filhas )
    if (!empty($childMsgs[$mainThreads[$i]["COD_MENSAGEM"]])) {
      imprimeMsgsFilhas($childMsgs,$mainThreads[$i]["COD_MENSAGEM"],$mainThreads[$i]["COD_MENSAGEM"],1,$param);
    }
  }
}

//imprime as mensagens filhas recursivamente
function imprimeMsgsFilhas(&$msgs,$codMsgPai,$codMainThread,$nivel=1,$param='') {
  $codInstanciaNivel=getTurma($_SESSION["codInstanciaGlobal"]);
  $instanciaGlobal = new InstanciaGlobal($_SESSION["codInstanciaGlobal"]);
  $codNivel = $instanciaGlobal->codNivel;
  
  echo "<div id=\"div_msg_".$codMsgPai."\">";
  foreach($msgs[$codMsgPai] as $msg) {
    $tipoUser=isProfessor($msg["COD_PESSOA"], $codInstanciaNivel, $codNivel);
    //$tipoUser = mysql_num_rows();
    if (!$tipoUser) { $classeMsg="exibe_normal"; } else { $classeMsg="exibe_prof_normal";}
    echo "<div class=\"".$classeMsg."\" style=\"padding-left: ".($nivel*10)."px;\">";
    //Cria link interno
    echo "<a name=\"".$codMsgPai.$nivel."\"></a>";
    
    //Mostra foto, se o aluno tiver. Se n√£o tiver, mostra foto gen√©rica
    echo "<img src='../../alunos/foto.php?COD_PESSOA=".$msg["COD_PESSOA"]."&CASE=FOTO_REDUZIDA' height='34' width='30'>";
    
    //mostra [NOVO] ao lado da foto do usu√°rio se a mensagem for nova para ele
    $msgNova = BuscaMsgNova($_SESSION["codInstanciaGlobal"], $_REQUEST['COD_SALA'], $_SESSION["COD_PESSOA"], $_SESSION["configForum"]["tabelaMsgNova"], $_SESSION["configForum"]["tabelaSalas"], $msg["COD_MENSAGEM"]);
    if ($msgNova) { $msgNova = mysql_fetch_array($msgNova); } else {$msgNova = "";}
    if ($msgNova AND $msg["COD_PESSOA"]!=$_SESSION["COD_PESSOA"]) { echo "<font color='red'><b>[nova]</b></font>"; }
    
    echo "<span class=\"usuario\"><a href=\"../../consultar.php?&BUSCA_PESSOA=".$msg['COD_PESSOA']."\" target=\"_blank\">".
       $msg["NOME_PESSOA"] . 
      "</a></span>";
	$classificaMsg=classificaTipoMsg($msg["codTipoMsg"]);
    echo " &nbsp;<b>".$classificaMsg."</b>&nbsp; em <b>" . $msg["DATA_MENSAGEM"] . "</b>&nbsp; - &nbsp;" ;
    if ( podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) {
      echo "<a href='./escrever.php?topico=".$param["topico"]."&acao=".$param["acao"]."&dataFim=".$param["dataFim"]."&dataInicio=".$param["dataInicio"]."&COD_PESSOA=".$param["COD_PESSOA"]."&COD_SALA=".$msg["COD_SALA"]."&RESPOSTA=" . $msg["COD_MENSAGEM"] ."&codMainThread=".$codMainThread."' class='escreve2'>Contribuir</a>";
    }
    if (!empty($msgs[$msg["COD_MENSAGEM"]])) {
      echo "&nbsp;&nbsp<a href=\"#".$codMsgPai.$nivel."\" onclick=\"toggle('div_msg_".$msg["COD_MENSAGEM"]."')\" style=\"font-size: 12px\"> +/- </a>";
    }
   
	
	if(($_REQUEST['acao']=='FormAlterar') AND ($msg["COD_PESSOA"]===$_SESSION["COD_PESSOA"]) AND ($msg["COD_MENSAGEM"]==$_REQUEST["codMSG"])){
		$texto=formAlterarMSG($msg["COD_MENSAGEM"],$msg["TEXTO_MENSAGEM"],$_SESSION["COD_PESSOA"], $msg["COD_SALA"]);
		}
	else
		$texto=preparaTexto($msg["TEXTO_MENSAGEM"]);
	
	
	echo "<br> <font size='2'>" .$texto. "</font><br><br>";
    

    if (!empty($msgs[$msg["COD_MENSAGEM"]]))
      imprimeMsgsFilhas($msgs,$msg["COD_MENSAGEM"],$codMainThread,$nivel+1,$param);
	else
	{
		//---------------------------------alterar msg que ainda n√£o tenha filhos------------------------------

	if($msg["COD_PESSOA"]===$_SESSION["COD_PESSOA"])
	{

	$locationHref="location.href='".$_SERVER['PHP_SELF']."?COD_SALA=".$msg["COD_SALA"]."&acao=excluirMSG&codMSG=".$msg["COD_MENSAGEM"]."'";

			   
	echo "<a href='#' onClick=\"if(confirm('Deseja mesmo excluir esta menssagem?')){".$locationHref."}\"  ><img  src=\"./imagens/".$_SESSION["configForum"]["imagemExcluir"]."\" border='no'></a>&nbsp;&nbsp;";
				
	echo "<a href=\"".$_SERVER['PHP_SELF']."?COD_SALA=".$msg["COD_SALA"]."&acao=FormAlterar&codMSG=".$msg["COD_MENSAGEM"]."\" ><img src=\"./imagens/".$_SESSION["configForum"]["imagemAlterar"] ."\" border='no'></a>";
				



	}

//-------------------------------------------------------------------------------------------------
    }
    echo "</div>";
    
  }
  echo "</div>";
}

//imprime a paginacao
function imprimePaginacao($paginaAtual,$codSala,$param='') {
  global $threadsPerPage,$maxPaginas;
  $numPaginas = ceil($_SESSION["NUM_MESSAGES"] / $threadsPerPage);

  echo "<div id=\"paginacao\" class=\"paginacao\">";
  //calcula e mostra anterior, atual e posterior
  echo "P&aacute;ginas Anterior, Atual e Pr&oacute;xima:&nbsp;"; 
  $paginaAnterior=$paginaAtual-1; $paginaProxima=$paginaAtual+1;
  if ($paginaAnterior==0) { $paginaAnterior=1; }   if ($paginaProxima>$numPaginas) { $paginaProxima=$numPaginas; }
  echo "<a href=\"".$_SERVER["PHP_SELF"]."?topico=".$param["topico"]."&acao=".$param["acao"]."&indicadores=".$param['indicadores']."&COD_PESSOA=".$param['COD_PESSOA']."&dataInicio=".$param['dataInicio']."&dataFim=".$param['dataFim']."&COD_SALA=".$codSala."&paginaForum=".$paginaAnterior."\" style=\"font-size: 14px;\">".$paginaAnterior."</a>&nbsp;";
  echo "<a href=\"".$_SERVER["PHP_SELF"]."?topico=".$param["topico"]."&acao=".$param["acao"]."&indicadores=".$param['indicadores']."&COD_PESSOA=".$param['COD_PESSOA']."&dataInicio=".$param['dataInicio']."&dataFim=".$param['dataFim']."&COD_SALA=".$codSala."&paginaForum=".$paginaAtual."\" style=\"font-size: 14px; font-weight: bold\"><big>".$paginaAtual."</big></a>&nbsp;";
  echo "<a href=\"".$_SERVER["PHP_SELF"]."?topico=".$param["topico"]."&acao=".$param["acao"]."&indicadores=".$param['indicadores']."&COD_PESSOA=".$param['COD_PESSOA']."&dataInicio=".$param['dataInicio']."&dataFim=".$param['dataFim']."&COD_SALA=".$codSala."&paginaForum=".$paginaProxima."\" style=\"font-size: 14px;\">".$paginaProxima."</a>&nbsp;";
  echo "<br>";
  //Mostra as paginas com um salto ajustavel ao numero total de p√°ginas
  echo "P&aacute;gina:&nbsp;";
  $passo=floor($numPaginas/$maxPaginas);
  if ($passo==0) { $passo=1; }
  for($i=1; $i<=$numPaginas; $i+=$passo) {
    $lastPage=$i;
    if ($paginaAtual == $i)
      echo "<a href=\"".$_SERVER["PHP_SELF"]."?topico=".$param["topico"]."&acao=".$param["acao"]."&indicadores=".$param['indicadores']."&COD_PESSOA=".$param['COD_PESSOA']."&dataInicio=".$param['dataInicio']."&dataFim=".$param['dataFim']."&COD_SALA=".$codSala."&paginaForum=".$i."\" style=\"font-size: 14px; font-weight: bold\"><big>".$i."</big></a>&nbsp;";
    else
      echo "<a href=\"".$_SERVER["PHP_SELF"]."?topico=".$param["topico"]."&acao=".$param["acao"]."&indicadores=".$param['indicadores']."&COD_PESSOA=".$param['COD_PESSOA']."&dataInicio=".$param['dataInicio']."&dataFim=".$param['dataFim']."&COD_SALA=".$codSala."&paginaForum=".$i."\" style=\"font-size: 14px\">".$i."</a>&nbsp;";
  }
  //Imprimimos a ultima p√°gina sempre, caso ela n√£o tenha sido impressa
  if ($lastPage<$numPaginas) { 
    echo "<a href=\"".$_SERVER["PHP_SELF"]."?topico=".$param["topico"]."&acao=".$param["acao"]."&indicadores=".$param['indicadores']."&COD_PESSOA=".$param['COD_PESSOA']."&dataInicio=".$param['dataInicio']."&dataFim=".$param['dataFim']."&COD_SALA=".$codSala."&paginaForum=".$numPaginas."\" style=\"font-size: 14px\">".$numPaginas."</a>&nbsp;";
  }

  echo "</div>";
}
?>

<?php	
//body estava aqui
/***** delStatusNovasMsg***********/
if ($_REQUEST['acao']=='delStatus'){
  DelStatusMsgNova($_REQUEST['COD_SALA'],$_SESSION['COD_PESSOA'],$_SESSION["configForum"]["tabelaMsgNova"]);
}

/***********end********************/
/*********delMSG******************/

if($_REQUEST['acao']=='excluirMSG')
{
	$DelMSG=DelMSG($_REQUEST['codMSG']);
	echo "<script>location.href='".$_SERVER['PHP_SELF']."?COD_SALA=".$_REQUEST["COD_SALA"]."';</script>";
}

/********************************/

if($_REQUEST["acao"]=='alterarMSG')
{
	$alterarMSG=AlterarMSG($_REQUEST["codMSG"],$_REQUEST["MSG"],$_REQUEST["codPessoa"]);
	echo "<script>location.href='".$_SERVER['PHP_SELF']."?COD_SALA=".$_REQUEST["COD_SALA"]."';</script>";

}

  if ($_REQUEST["semSomFundo"]===1) { $_SESSION["configForum"]["semSomFundo"]=1; } 
  else if ($_REQUEST["semSomFundo"]===2) { $_SESSION["configForum"]["semSomFundo"]=0; } 

  if (!empty($_SESSION["configForum"]["somFundo"]) && (!$_SESSION["configForum"]["semSomFundo"])) {
     //coloca o som de fundo se houver
     echo "<embed src=\"".$_SESSION["configForum"]["somFundo"]."\" class=\"somFundo\" hidden=\"true\" autostart=\"true\" loop=\"true\" MASTERSOUND></embed>";
  }
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 

		<td colspan="2" valign="top"> 
		<html>
			<head>
				<title>F&Oacute;RUM</title>
				<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
				<link rel="stylesheet" href="./../../cursos.css" type="text/css"> 
				<link rel="stylesheet" href="<?php echo $_SESSION["configForum"]["arquivoCSS"]?>" type="text/css">
			
				<?php
				echo $_SESSION['configMathml']['script'];
				?>
				

				<script language="JavasScript" type="text/javascript" src="../../js/divs.js"></script>		  
			</head>

			<body <?php echo $_SESSION['configMathml']['onLoad'];?> class="forum">

			<table width="100%" height='100%' border="0" cellpadding="0" cellspacing="0" >
				<tr>
				 <td class="cabecalho" align="center" colspan="5"> 
				 <?php
				   $nomeTopico= @getNomeTopico($_REQUEST["COD_SALA"]);  
		           echo "<p align=\"center\" class=\"menu\"><b>".$nomeTopico."</b></p>";
				 ?>
				 </td>
				</tr>
				<tr> 
				
				<td class="cabecalho" height="30" width="20%" align="left"> 
				<?php if($_REQUEST["indicadores"]){?>
						<a href="<?php echo $url?>/indicadoresaluno/index.php?acao=buscaPorTempo&dataInicio=<?php echo $_REQUEST["dataInicio"]?>&dataFim=<?php echo $_REQUEST["dataFim"]?>&dataInicioValue=<?php echo $_REQUEST["dataInicioValue"]?>&dataFimValue=<?php echo $_REQUEST["dataFimValue"]?>" class="menu"><img src="./imagens/<?php echo $_SESSION["configForum"]["voltar"] ?>" border="no"><br>Voltar para Indicadores</a>
				 </td>
					<?php } else{?>
				    	<a href="./index.php" class="menu"><img src="./imagens/<?php echo $_SESSION["configForum"]["voltar"] ?>" border="no"><br>Voltar para TÛpicos</a>
					
                 </td>
				
					<td class="cabecalho" height="30" width="20%" align="center">
					  <?php
            if ( podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) { 
				    	echo '<a href="./escrever.php?COD_SALA='.$_REQUEST['COD_SALA'].'" class="menu"><img src="./imagens/'.$_SESSION['configForum']['imagemEscrever'].'" border="no"><br>Escrever uma nova mensagem</a>';
				    }
				    else {
              echo '&nbsp;';
            }
				    ?>
			    </td>
			    <td class="cabecalho" height="30" width="20%" align="center"> 
			    	<a href="./forum.php?COD_SALA=<?php echo $_REQUEST['COD_SALA']?>" class="menu"><img src="./imagens/<?php echo $_SESSION["configForum"]["imagemAtualizar"] ?>" border="no"><br>Atualizar mensagens</a>
          </td>
          <td class="cabecalho" height="30" width="20%" align="center"> 
			    	<a href="./forum.php?acao=delStatus&COD_SALA=<?php echo $_REQUEST['COD_SALA']?>" class="menu"><img src="./imagens/lidas.jpg" border="no"><br>Desmarcar <font color='red'><b>[novas]</b></font> mensagens</a>
          </td>
   	      <td class="cabecalho" height="30" width="20%" align="center"> 
				   	<a href="./filtro.php?COD_SALA=<?php echo $_REQUEST['COD_SALA']?>" class="menu"><img src="./imagens/<?php echo $_SESSION["configForum"]["imagemSearch"] ?>" border="no"><br>Filtrar</a>
          </td>
				 <?php } ?>
				    <td class="cabecalho" height="30" width="20%" align="center"> 
              <?php 
			@$param["COD_PESSOA"]=$_REQUEST["COD_PESSOA"];
			@$param["dataInicio"]=$_REQUEST["dataInicio"];
			@$param["dataFim"]=$_REQUEST["dataFim"];
			@$param["indicadores"]=$_REQUEST["indicadores"];
			@$param["acao"]=$_REQUEST["acao"];
			@$param["topico"]=$_REQUEST["topico"];

			

			if($_REQUEST["acao"]=="filtro"){
			  $codSala=$_REQUEST["topico"];
			}else{
				$codSala=$_REQUEST["COD_SALA"];
			}
			
				
              if (!empty($_SESSION["configForum"]["somFundo"])) {
                if ($_SESSION["configForum"]["semSomFundo"]===1 ) {
                  echo "<a href=\"./forum.php?indicadores=".$param['indicadores']."&COD_PESSOA=".$param['COD_PESSOA']."&dataInicio=".$param['dataInicio']."&dataFim=".$param['dataFim']."&COD_SALA=".$codSala."&semSomFundo=2\" class=\"menu\">COM som de Fundo</a>";
                }
                else  {
                  echo "<a href=\"./forum.php?indicadores=".$param['indicadores']."&COD_PESSOA=".$param['COD_PESSOA']."&dataInicio=".$param['dataInicio']."&dataFim=".$param['dataFim']."&COD_SALA=".$codSala."&semSomFundo=1\" class=\"menu\">SEM som de Fundo</a>";
               }
              }
              else { echo "&nbsp;"; }
              
              //permite ao usuario ver todas as mensagens ou de modo paginado
              if ($showAll) {
                echo "<br><a href=\"./forum.php?showAll=0&indicadores=".$param['indicadores']."&COD_PESSOA=".$param['COD_PESSOA']."&dataInicio=".$param['dataInicio']."&dataFim=".$param['dataFim']."&COD_SALA=".$codSala."\" class=\"menu\">Ver Mensagens por p√°gina</a>";
              }
              else  {
                echo "<br><a href=\"./forum.php?showAll=1&indicadores=".$param['indicadores']."&COD_PESSOA=".$param['COD_PESSOA']."&dataInicio=".$param['dataInicio']."&dataFim=".$param['dataFim']."&COD_SALA=".$codSala."\" class=\"menu\">Ver TODAS as mensagens</a>";
             }
              ?>	
				    </td>
				</tr>
				<tr>
					<td height="10">
            <?php 
			
			
						//le as mensagens principais
            $iniMsg = calcIniMsg($_SESSION["paginaForum"]);
            $mainMsgs = getMainThreads($_SESSION["NUM_MESSAGES"],$iniMsg,$threadsPerPage,$getTurma,$showAll, $codSala, $param);
            //imprime em cima a paginacao
            if ($_SESSION["NUM_MESSAGES"] > $threadsPerPage) {  imprimePaginacao($_SESSION["paginaForum"],$_REQUEST['COD_SALA'],$param); }
            ?>

          </td>
					<td height="10">
					</td>
				</tr>
			     </table>
					<div>
						<?php				
						//le as mensagens filhas das mensagens principais
						$childMsgs = getChildMessages($mainMsgs,$getTurma, $codSala,$param);
            //Imprime as msgs
            imprimeMsgsForum($mainMsgs,$childMsgs,$param);   
						?>		
					</div>
		</td>
	</tr>
</table>
<?php 
 //echo"<a href=\"teste.php\">Ver sem quebra de linha</a>";
//imprime embaixo
if ($_SESSION["NUM_MESSAGES"] > $threadsPerPage) {  imprimePaginacao($_SESSION["paginaForum"],$_REQUEST['COD_SALA'],$param); }
?>
</body>
</html>