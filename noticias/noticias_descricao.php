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
include_once("../config.php");
include_once ($caminhoBiblioteca."/noticia.inc.php");
include_once($caminhoBiblioteca."/rss/rss.inc.php");
include_once($caminhoBiblioteca."/pessoa.inc.php");
include_once($caminhoBiblioteca."/pesquisaavaliacaoinstanciapeloaluno.inc.php");
session_name(SESSION_NAME); session_start(); 
if (($_SESSION['userRole'] == PUBLICO) || (empty($_SESSION['userRole']))) {$sp = 1;} else {$sp = 0;}
security($sp);

//A partir de agora, apenas le da sessao
session_write_close();
//include_once("funcoes_bd.php");

?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href="../cursos.css" type="text/css">

		<script language="JavaScript" src="./funcoes_js.js"></script>
	</head>
	
<?php

if (! isset($_REQUEST["COD_NOTICIA"])){	exit(); }


?>
<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>

   <td width="10">&nbsp;  </td>
		<td width="200" valign="top"> 
			<table cellpadding="0" width="200" cellspacing="0" border="0">
				
				<?php
				
				if (($_SESSION["COD_ADM"] != "") or ($_SESSION["COD_PROF"] != "") or ($_SESSION["COD_AL"] != "")) {
					$rsConN = listaNoticias("",$_SESSION["codInstanciaGlobal"],1,2);
         
        }		
				else	 { 
    			$rsConN = listaNoticias("",$_SESSION['codInstanciaGlobal'],1,3);						
			    //print_r($rsConN);
        }
  			$var_classe = "CelulaEscura";
  			$hoje = date("Y-m-d");
        $avaliacao=getPesquisaAvaliacaoAluno($_SESSION["codInstanciaGlobal"],$hoje);         
        foreach($avaliacao->records as $ava){
          $instrucao=$ava->instrucoes;
         
        }
  
				// ADM GERAL altera noticias de disciplina.php, curso.php, principal.php;
				//ADM CURSO altera noticias de disciplina.php, curso.php;
				//Professor altera noticias de disciplina.php;
        if ($_REQUEST["OPCAO"]=="Apresentacao"){ $nomeFerramenta="Apresenta&ccedil;&atilde;o"; } else { $nomeFerramenta="Not&iacute;cia"; }

			//	if  (  ($_SESSION["NIVEL_ACESSO"] == 1) || ( ($_SESSION["NIVEL_ACESSO"] == 2) and ($_SESSION["PAGINA_ATUAL"] != "principal") ) || ( ($_SESSION["NIVEL_ACESSO"] == 3) && (getNivelAtual() == Nivel::getNivelRelacionamentoAlunosProfessores())  ) )			
			//	{	
		  if (Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage'])) {
		      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='".$url."/tools/noticias_operacao.php?OPCAO=Inserir'><font color='red'><b>Inserir nova ".$nomeFerramenta."</b></font></a> <br><br>";		
			}


   if ($_REQUEST["OPCAO"]=="Apresentacao"){
  
   
     //mantem links repassando a informacao de que o usuario esta na ferramenta "Apresentacao"
     $apresentacao="&OPCAO=Apresentacao";

    $var_classe = "CelulaEscura";
      $today = date("Y-m-d");
       
     
    if(!empty($instrucao)){
     echo  "<tr><td class='" . $var_classe . "'><b><span class='setas'> » ".
          "  Avalia&ccedil;&#259;o</span>".
          "</b>&nbsp;<br>";
     echo	"<a href='../pesquisaavaliacaoinstanciapeloaluno/index.php'>Clique aqui para fazer a avalia&ccedil;&#259;o da disciplina</a>"; 
     echo	"</td>".
          "</tr>".
          "<tr>".
          "<td height='10'> &nbsp; </td> ".
          "</tr>";	
				$var_classe = "CelulaClara";
		}
				if ($rsConN)
				{
     				   				
					while ($linha = mysql_fetch_array($rsConN))
					{
					 $noticia = sitesRSS($linha["COD_NOTICIA"]);
    
               if(!empty($noticia)){
                $feedAgregator = new RSSFeedAgregator(explode(';',$noticia));
                $itens = $feedAgregator->getItens();
              }
						               echo  "<tr>".
											"<td class='" . $var_classe . "'>".
												"<b>".
								
												"<span class='setas'>".
													" » ".
												"</span>".
													
													$linha["TITULO_NOTICIA"] .
													
												"</b>&nbsp;<br>";
						echo			"<a href='./noticias_descricao.php?COD_NOTICIA=" . $linha["COD_NOTICIA"] . "{$apresentacao}'>". 
													$linha["RESUMO_NOTICIA"] ."</a>";
																							
													"</a>";
						echo  "<BR>";
						  if(!empty($noticia)){
						 
						    if(!empty($itens)){
                  for ($i=0;$i<$linha["numeroNoticiasRSSResumo"];$i++) {
                      $item = $itens[$i]; 
                       $data = date('d/m/Y H:i',$item['date_timestamp']);
                       echo '<a href="'.$item['link'].'" target="_blank">['.$item['channelTitle'].'';
                       if(!empty($item["pubdate"])) echo ''.$data.'';
                       echo ']:<font color=\"bluE\"> '.$item['title'].'</font></a><br>';
                  }
                }
                else{ echo "<b>Notícias não diponíveis</b>";}
            }
	        echo "<br>";	
          	 echo	"</td>".
										"</tr>".
										"<tr>".
											"<td height='10'> &nbsp; </td> ".
										"</tr>";
			
						
						if ($var_classe == "CelulaClara")  
							$var_classe = "CelulaEscura";
						else 
							$var_classe = "CelulaClara";
						
					}
					
					echo "<tr> <td> &nbsp; </td> </tr>";
          echo "</table></td>"; //finaliza a tabela da coluna de apresentação

					
				}
    }
    else {
      echo "</table></td>"; //finaliza a tabela da coluna de apresentação
      echo "</td></tr><tr>"; //inicia outra linha para ocupar toda a tela quando nao for apresentacao
    }
				?>	

		
	    <td width="100%" valign="top"> 
              <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td>
              <br>
<?php

$rsConN = listaNoticias($_REQUEST["COD_NOTICIA"],"","","");
$linha  = mysql_fetch_array($rsConN);

if ($linha)
{    
     $noticias = sitesRSS($linha["COD_NOTICIA"]);
     
     if(!empty($noticias)){
        $feedAgregatorN = new RSSFeedAgregator(explode(';',$noticias));
        $itensN = $feedAgregatorN->getItens();
     // note($itensN);
    }
  
	echo "<div align='justify'><b>" . $linha["TITULO_NOTICIA"] . "</b> <br> <br>";
	echo $linha["TEXTO_NOTICIA"] . "</div>";
 
}
  if(!empty($noticias)){
  
      for ($i=$linha["numeroNoticiasRSSResumo"];$i<count($itensN);$i++) {
          $itemN = $itensN[$i]; 
          $dataN = date('d/m/Y H:i',$itemN['date_timestamp']);
          echo '<a href="'.$itemN['link'].'" target="_blank">['.$itemN['channelTitle'].'';
          if(!empty($itemN["pubdate"])) echo ' '.$dataN.'';
          echo ']:<font color=\"blue\"> '.$itemN['title'].'</font></a><br>';
      }
  }
 
if ($_REQUEST["OPCAO"]=="Apresentacao") {
  echo "<p><a href=\"#\" target=\"_self\" onClick='javascrip:history.back();'><b>< VOLTAR</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}
else{
  echo "<p><a href=\"./noticias.php\"  target=\"_self\"><b>< VOLTAR</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}

// ADM GERAL altera noticias de disciplina.php, curso.php, principal.php
// ADM CURSO altera noticias de disciplina.php, curso.php
// Professor altera noticias de disciplina.php

//if   ( ($_SESSION["NIVEL_ACESSO"] == 1) or ( ($_SESSION['userRole']==ADM_NIVEL) or ( ($_SESSION["NIVEL_ACESSO"] == 2) and ($_SESSION["PAGINA_ATUAL"] != "principal") ) or ( ($_SESSION["NIVEL_ACESSO"] == 3) && (getNivelAtual() == Nivel::getNivelRelacionamentoAlunosProfessores()) ) ) ) 
//{	
if (Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage'],$_SESSION['interage'])) {
		echo "<a href='".$url."/tools/noticias_operacao.php?OPCAO=Alterar&COD_NOTICIA=" .
			 $linha["COD_NOTICIA"] . "'' ><b>Editar esta ".$nomeFerramenta."</b></a></p>";
	
} 



 ?>
                    <br><br><br><br><br>
				  </td>
                </tr>
              </table>
		</td>
	</tr>
</table>
 
 </body>

</html>
