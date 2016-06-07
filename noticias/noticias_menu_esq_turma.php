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
include_once("../config.php");
include_once($caminhoBiblioteca."/noticia.inc.php");
include_once($caminhoBiblioteca."/pesquisaavaliacaoinstanciapeloaluno.inc.php");
include_once($caminhoBiblioteca."/rss/rss.inc.php");
session_name(SESSION_NAME); session_start(); security();
//A partir de agora, apenas le da sessao
session_write_close();

?>

<html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href="./cursos.css" type="text/css">

		<script language="JavaScript" src="./funcoes_js.js"></script>
	</head>
	<body>
			<table align="left" cellpadding="0"  cellspacing="0" border="0" width="200">
							
				<?php
				
				if (($_SESSION["COD_PESSOA"] != "") AND ($_SESSION['codInstanciaGlobal'] != "") ) 							
					$rsConN = listaNoticias("",$_SESSION['codInstanciaGlobal'],1,2);
				else 
				$rsConN = listaNoticias("",$_SESSION['codInstanciaGlobal'],1,3);
        $hoje = date("Y-m-d");
        $avaliacao=getPesquisaAvaliacaoAluno($_SESSION["codInstanciaGlobal"],$hoje);         
         foreach($avaliacao->records as $ava){
         $instrucao=$ava->instrucoes;
        
        }
         	
				// ADM GERAL altera noticias de disciplina.php, curso.php, principal.php
				// ADM CURSO altera noticias de disciplina.php, curso.php
				// Professor altera noticias de disciplina.php
				
			/*	if  ( ($_SESSION["NIVEL_ACESSO"] == 1) or 
					  ( ($_SESSION["NIVEL_ACESSO"] == 2) and ($_SESSION["PAGINA_ATUAL"] != "principal" ) ) or 
					  ( ($_SESSION["NIVEL_ACESSO"] == 3) && (getNivelAtual() == Nivel::getNivelRelacionamentoAlunosProfessores()) ) )
					 
				{	*/
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				if (Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage'],$_SESSION['interage'])) {
				echo "<a href='".$url."/tools/noticias_operacao.php?OPCAO=Inserir&recurso=lembretes'><font color=\"red\">
          <b>Inserir novo Lembrete.</b></font></a> <br><br>";
        }
			 /*	$var_classe = "CelulaEscura";
        
       echo  "<tr><td class='" . $var_classe . "'><b><span class='setas'> » </span>".
	          "  Participantes da Turma".
	          "</b>&nbsp;<br>";
			  
        echo	"<a href=\"".$url."/alunos/index.php\">". 
            "Alunos Matriculados" .
	          "</a>";
        echo	"</td>".
	          "</tr>".
	          "<tr>".
	          "<td width=\"10\"> &nbsp; </td> ".
	          "</tr>";	*/
				$var_classe = "CelulaClara";
				
     
          if(!empty($instrucao)){
            echo  "<tr>".
									"<td class='" . $var_classe . "'>".
									"<b>".
								  "<span class='setas'> ".
									"» " .
									"Avalia&ccedil;&#259;o".
									"</b></span>&nbsp;<br>";
				   echo	"<a href='../pesquisaavaliacaoinstanciapeloaluno/index.php'>Clique aqui para fazer a avalia&ccedil;&#259;o da disciplina</a>"; 
 					 echo	"</td></tr>";
           echo "<tr><td>&nbsp;</td></tr>";
           $var_classe = "CelulaEscura"; 
       } 
        
        if ($rsConN)
				
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
													" » " .
												"</span>".
													
													$linha["TITULO_NOTICIA"].
													
												"</b>&nbsp;<br>";
							echo			"<a href='".$url."/noticias/noticias_descricao.php?OPCAO=Apresentacao&COD_NOTICIA=" . $linha["COD_NOTICIA"] . "&codInstanciaGlobal=".$_SESSION["codInstanciaGlobal"]."'>". 
														$linha["RESUMO_NOTICIA"] .
													"</a>";
							echo  "<BR>";
            if(!empty($noticia)){
               if(!empty($itens)){
                  for ($i=0;$i<$linha["numeroNoticiasRSSResumo"];$i++) {
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
																							
						echo	"</td>".
										"</tr>" .
										"<tr>".
											"<td width=\"10\" align='left'> &nbsp; </td> ".
										"</tr>";
						
						
						if ($var_classe == "CelulaClara" ) 
							$var_classe = "CelulaEscura";
						else 
							$var_classe = "CelulaClara";
						
					}	
					echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
				?>
					
			</table>
      </body>
      </html>
