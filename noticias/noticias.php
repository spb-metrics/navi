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

//include_once("funcoes_bd.php");
include("../config.php");
include($caminhoBiblioteca."/noticia.inc.php");
include($caminhoBiblioteca."/curso.inc.php");
include($caminhoBiblioteca."/rss/rss.inc.php");
session_name(SESSION_NAME); session_start(); 
if (($_SESSION['userRole'] == PUBLICO) || (empty($_SESSION['userRole']))) {$sp = 1;} else {$sp = 0;}
security($sp);
//A partir de agora, apenas le da sessao
//session_write_close();
$instanciaAtual = new InstanciaNivel(getNivelAtual(),getCodInstanciaNivelAtual());
$showColunaEsquerda = $instanciaAtual->relacionaPessoas();
?>
<link rel="stylesheet" href="./../cursos.css" type="text/css">
<? if($showColunaEsquerda){
echo "<div style=\"position:relative; width:40%; float:left;\">";
echo "<div style='font-family: Times New Roman, Times, serif;font-style: italic;font-weight:bolder;font-size: medium; color:#666699;text-align:center;'>Mural de Notícias, avisos e novidades</div>";
}?>
<table width='100%' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width="10">
      &nbsp;  
    </td>
<?php
			  
if (!empty($_SESSION["COD_ADM"]) || !empty($_SESSION["COD_PROF"]) || !empty($_SESSION["COD_AL"])) {
  $acesso = 2;
}
else {

  $acesso = 3;
}


$codInstanciaGlobal = $_SESSION['codInstanciaGlobal'];  
$noticiasRS = listaNoticias("",$codInstanciaGlobal,"",$acesso);


//if   ( ($_SESSION["NIVEL_ACESSO"] == 1) or ($_SESSION["NIVEL_ACESSO"] == 2)  ){
if (Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage'])) {		
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='".$url."/tools/noticias_operacao.php?OPCAO=Inserir&recurso=noticias'><font color=\"red\"><b>Inserir nova Not&iacute;cia.</b></font></a> <br><br>";
}
					
if ($noticiasRS) { 	
    
    $var_classe = "CelulaEscura";
    //forma um array com as noticias indexado pelo numero da coluna
    $noticias = array();
    $noticias[1] = array(); $noticias[2] = array(); $noticias[3] = array();
  
    while($noticia = mysql_fetch_array($noticiasRS)) {
      $noticias[$noticia["NRO_COLUNA_NOTICIA"]][] = $noticia;
    }
    /* retirado para criar uma pgna só de noticias e outra p/ apresentação*/
    //A Coluna mais a esquerda eh de apresentacao, e as duas de dentro sao noticias   
    //Se o nivel nao relacionar alunos e professores ou entao for 'comunidade', 
    //entao mostra a coluna esquerda
   /*$nivelAtual = getNivelAtual();
    $showColunaEsquerda = $nivelAtual->relacionaAlunosProfessores + $nivelAtual->nivelComunidade;*/
    if ((isset($_REQUEST["OPCAO"]) && $_REQUEST["OPCAO"]=="Apresentacao") || !$showColunaEsquerda)   {
      //imprime a primeira coluna
      echo "<td width=\"200\" valign=\"top\">"; 
      echo "<table cellpadding=\"0\" width=\"200\" cellspacing=\"0\" border=\"0\">";
      if ($showColunaEsquerda) {
        echo  "<tr><td class='" . $var_classe . "'><b><span class='setas'> » </span>".
          " Participantes da Turma".
          "</b>&nbsp;<br>";
        echo	"<a href='../alunos/index.php'>Alunos Matriculados</a>";
      		
        echo	"</td></tr><tr><td height='10'> &nbsp; </td></tr>";				
      }
      $var_classe = "CelulaClara";
      foreach($noticias[1] as $linhaN) {         
        //noticia da primeira coluna
     
        echo  "<tr><td class='" . $var_classe . "'><b><span class='setas'> » </span>".
         $linhaN["TITULO_NOTICIA"] . "</b>&nbsp;<br>";
        
        echo	 "<a href='./noticias_descricao.php?COD_NOTICIA=" . $linhaN["COD_NOTICIA"] . "'>". 
          $linhaN["RESUMO_NOTICIA"] .    "</a>";
         echo "<BR>";
        $noticia = sitesRSS($linhaN["COD_NOTICIA"]);
        
        if(!empty($noticia)){
          $noticia = explode(';',$noticia);
          $feedAgregator = new RSSFeedAgregator($noticia);
          $itens = $feedAgregator->getItens();
          if(!empty($itens)) {
              for ($i=0;$i<$linhaN["numeroNoticiasRSSResumo"];$i++) {
                  $item = $itens[$i]; 
                  $data = date('d/m/Y H:i',$item['date_timestamp']);
                  echo '<a href="'.$item['link'].'" target="_blank">['.$item['channelTitle'].' ';
                  if(!empty($item["pubdate"])) echo ' '.$data.'';
                  echo ']:<font color=\"bluE\"> '.$item['title'].'</font></a><br>';
              }
          }
         else { echo "<b>Notícias não disponíveis</b>";}
        }
      
	      echo "<br>";	
	     
        echo	"</td></tr><tr><td height='10'> &nbsp; </td></tr>";									
        
        if ($var_classe == "CelulaClara")
          $var_classe = "CelulaEscura";
        else 
          $var_classe = "CelulaClara";
      }
    
        echo "<tr> <td> &nbsp; </td> </tr>";
        
        echo "</table></td>";
    }
    ?>        
    <td width="10">
      &nbsp;  
    </td>
    <td width="530" valign="top">      
      <table  width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr width="100%" valign="top">
          <td width="49%" align="top">
            <table  border="0" cellspacing="0" cellpadding="0" align="center">
<?php
  foreach($noticias[2] as $linhaN) {
       
    echo  "<tr width='100%'>";
										
    echo  "<td width='100%' class='CelulaTituloDisc'>";
										
    echo  $linhaN["TITULO_NOTICIA"] .	  "</td>".	  "</tr>".
														
	  "<tr width='100%'>".
	  "<td class='CelulaTexto'>".
	  "<p align='justify'>".
	  $linhaN["RESUMO_NOTICIA"] .
	   "<BR>";
    $noticia = sitesRSS($linhaN["COD_NOTICIA"]);
    
    if(!empty($noticia)){
      $noticia = explode(';',$noticia);
      $feedAgregator = new RSSFeedAgregator($noticia);
      $itens = $feedAgregator->getItens();
      if(!empty($itens)){
          for ($i=0;$i<$linhaN["numeroNoticiasRSSResumo"];$i++) {
              $item = $itens[$i]; 
              $data = date('d/m/Y H:i',$item['date_timestamp']);
              echo '<a href="'.$item['link'].'" target="_blank">['.$item['channelTitle'].'';
              if(!empty($item["pubdate"])) echo ' '.$data.'';
              echo ']:<font color=\"bluE\"> '.$item['title'].'</font></a><br>';
          }
      }
      else { echo "<b>Notícias não diponiveis</b>";} 
    }
	echo "<br>";	
										
 if(!empty($linhaN['TEXTO_NOTICIA'])){                  							
	echo				"<a href='./noticias_descricao.php?COD_NOTICIA=" . $linhaN["COD_NOTICIA"] . "'>" .
	  "<b> Ler Mais </b>".
	  "</a>";
 }
										
	echo			"</p>".
	  "</td>".
	  "</tr>";																										
  }
		
  echo "<tr> <td> &nbsp; </td> </tr>";  
              ?>              
            </table>
          </td>          
         <td width="2%">
            &nbsp;  
            <td>            
              <td width="49%">
                <table border="0" cellspacing="0" cellpadding="0" align="center">
<?php
  foreach($noticias[3] as $linhaN) {
   
    echo  "<tr width='100%'>";
                  
    echo  "<td width='100%' class='CelulaTituloDisc'>";
    echo $linhaN["TITULO_NOTICIA"] .
      "</td>".
      "</tr>".													
      "<tr width='100%'>".
      "<td class='CelulaTexto'>".
      "<p align='justify'>".
      $linhaN["RESUMO_NOTICIA"];
     echo "<BR>";
    
    $noticia = sitesRSS($linhaN["COD_NOTICIA"]);
    
    
    if(!empty($noticia)){
      $noticia = explode(';',$noticia);
      $feedAgregator = new RSSFeedAgregator($noticia);
      $itens = $feedAgregator->getItens();
      if(!empty($itens)) {
          for ($i=0;$i<$linhaN["numeroNoticiasRSSResumo"];$i++) {
            $item = $itens[$i]; 
            $data = date('d/m/Y H:i',$item['date_timestamp']);
            echo '<a href="'.$item['link'].'" target="_blank">['.$item['channelTitle'].'';
            if(!empty($item["pubdate"])) echo ' '.$data.'';
            echo ']:<font color=\"bluE\"> '.$item['title'].'</font></a><br>';
          }
      }
      else { echo "<b>Notícias não disponíveis</b>";}
   }
	echo "<br>";	
	
	if(!empty($linhaN['TEXTO_NOTICIA'])){
    echo  "<a href='./noticias_descricao.php?COD_NOTICIA=" . $linhaN["COD_NOTICIA"] . "'>" .
      "<b> Ler Mais </b>".
      "</a>";
  }
                      
    echo			"</p>".
      "</td>".
      "</tr>";																										
  }
                
  echo "<tr> <td> &nbsp; </td> </tr>";
echo "</table>".
     "</td>".
    "</tr>".
  "</table>";
    
//}
                  ?>
          </td>
        </tr>
      </table>
      <? if($showColunaEsquerda){?>
      </div>
      <div style="position:relative; width:40%; float:right;">
<?

  
  $_REQUEST['codInstanciaGlobal']=$_SESSION['codInstanciaGlobal'];
  $_REQUEST['opcao']="muraldeRecados";
  
  if ($_SESSION['userRole']!=PUBLICO) {
    include($caminhoRoot.'/blog/index.php');
  }
  
  }
  
  
  
        ?>
      </div>
     <? } ?>    
