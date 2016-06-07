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
//session_name('multinavi_cpmd'); 

//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once ($caminhoBiblioteca."/noticia.inc.php");
session_name(SESSION_NAME); session_start(); security();
/*colocar array para trocar layout entre lembretes e noticias*/
if(empty($_SESSION['recurso'])|| $_SESSION['recurso']!=$_REQUEST['recurso']  && !empty($_REQUEST['recurso'])){
$_SESSION['recurso']=$_REQUEST['recurso'];
}

if($_SESSION['recurso']=="noticias"){
  $configRecurso['nome']="Notícias";
  $configRecurso['todas']="Todas";
  $configRecurso['ativas']="Ativas";
  $configRecurso['nao_ativas']="Não Ativas";
  $configRecurso['sua']="Suas Notícias";
  $configRecurso['aviso_a']="AVISO: Ao retirar as notícias neste local, você estará removendo-as de todos locais inseridos.";
  $configRecurso['aviso_b']=" Não há noticias cadastradas."; 
}else{
  $configRecurso['nome']="Lembretes";
  $configRecurso['todas']="Todos";
  $configRecurso['ativas']="Ativos";
  $configRecurso['nao_ativas']="Não Ativos";
  $configRecurso['sua']="Seus Lembretes";
  $configRecurso['aviso_a']="AVISO: Ao retirar os lembretes neste local, você estará removendo-os de todos locais inseridos.";
  $configRecurso['aviso_b']=" Não há lembretes cadastrados.";
  
}


?>
	
<html>
	<head>
		<title>Noticias/lembretes</title>
		<link rel="stylesheet" href="./../css/padraogeral.css" type="text/css"">
	  <script language="JavaScript" src="".$url."/js/editor.js"></script>
	  <script language="javascript" type="text/javascript" src="".$url."/js/tiny_mce/tiny_mce.js"></script>
  </head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg" style="overflow:scroll">

<?php

	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
			
	if ( ($acesso != 1) AND ($acesso != 2) AND ($acesso != 3) )	{
		echo "<p align='center'>Acesso Restrito. </p>";
		exit();
	}	  	
?>

<table cellpadding="10" cellspacing="0" border="0" width="85%"  align="center">
	<tr>
		<td colspan="6" align="center"><font size="4"><b>
		<? echo  $$configRecurso['nome'];?>
		</b></font></td>
	</tr>

	<tr>
		<?
    //nivel atual
    $nivel = getNivelAtual();

		if ($acesso == 1)
		{
		?>	
			<td colspan="4" align="left">
				<a href="noticias_operacao.php?OPCAO=Inserir&FILTRO=<?=$_REQUEST["FILTRO"]?>&CURSO=<?=$_REQUEST["CURSO"]?>&TURMA<?=$_REQUEST["CURSO"]?>=<?=$_REQUEST["TURMA" . $_REQUEST["CURSO"]]?>">Inserir <? echo  $configRecurso['nome'] ;?></a>				
			</td>
			<td colspan="2" align="right">
				<a href="index.php">Ferramentas de Gerência</a>
				- <a href="javascript:history.back()">Voltar</a>
			</td>	
		<?
		}
		else
		{
		?>
			<td colspan="1" align="left">
				<a href="noticias_operacao.php?OPCAO=Inserir&FILTRO=<?=$_REQUEST["FILTRO"]?>&CURSO=<?=$_REQUEST["CURSO"]?>&TURMA<?=$_REQUEST["CURSO"]?>=<?=$_REQUEST["TURMA" . $_REQUEST["CURSO"]]?>">Inserir <? echo  $configRecurso['nome'] ;?></a>				
			</td>
			<td colspan="1" align="right">
				<a href="./../tools/index.php" >Ferramentas de Gerência</a>
				- <a href="javascript:history.back()">Voltar</a>
			</td>			
		<?
		}
		?>
	</tr>

	<tr>
		<?php
		if ($acesso == 1)
		{
			?>
			<td align="left"><a href="?FILTRO=todas"><? echo $configRecurso['todas'];?></a></td>
			<td align="center"><a href="?FILTRO=algo"><? echo $configRecurso['ativas'];?></a></td>
			<td align="center"><a href="?FILTRO=nenhum"><? echo $configRecurso['nao_ativas'];?></a></td>
			<!-- <td align="center"><a href="?FILTRO=principal">Página Principal</a></td> -->
			<td align="center"><a href="?FILTRO=suas"><? echo $configRecurso['sua'];?></a></td>	
			<td align="center"><a href="?FILTRO=instancia"><? echo $configRecurso['nome'];?> <?=$nivel->nome?> Atual</a></td>
			<?php
		 }
		else
		if ($acesso == 2 or $acesso == 3)
		{
			?>
			<td align="center"><a href="?FILTRO=suas"><? echo $configRecurso['sua'];?></a></td>	
			<td align="center"><a href="?FILTRO=instancia"><? echo $configRecurso['nome'];?> <?=$nivel->nome?> Atual</a></td>
			<?php
		 }
		?>
		
	</tr>
</table>	
	
<?php

if ( !isset($_REQUEST["FILTRO"]) )
	exit();
	
// ////////////////////////////////////////////
// 			Página de Curso / Turma			//			
// //////////////////////////////////////////

if ( $_REQUEST["FILTRO"] == "instancia" ) {

  if ($acesso == 1 )
    $rsConC = listaAcesso(15, "", "", "");
  else {
    if ($acesso == 2 )
      $rsConC = listaAcesso(4, "", "", "");
    else  {
      if ($acesso == 3)
        $rsConC = listaAcesso(7, "", "", "");
    }
  }			
}


$filtro = $_REQUEST["FILTRO"];
$turma  = "";
$quem   = "";

			
if  ( ( ($filtro == "todas" or $filtro == "algo" or $filtro == "nenhum" or $filtro == "principal") and ($acesso == 1) ) or
	  ( ($filtro == "suas" or $filtro == "instancia") and ($acesso == 1 or $acesso == 2 or $acesso == 3) ) or
	  ( ($filtro == "instancia" ) and ($acesso == 1 or $acesso == 2 ) ) 	  
	)
{		
		if ($filtro == "todas")
			$filtro = "";
		
		if ($filtro == "suas")
			$quem = $_SESSION["COD_PESSOA"];
		
		$rsConN = listaNoticiasAdm($_SESSION["codInstanciaGlobal"], $filtro, $quem);		

		if ($rsConN)
		{
			if ($linhaN = mysql_fetch_array($rsConN))
			{
				if ($filtro == "instancia")
					echo " &nbsp;&nbsp;&nbsp; <b> Noticias Publicadas aqui: </b> <br><br>";

				?>
				<table cellpadding="0" cellspacing="0" border="0" width="85%"  align="center">
				<tr>
				   
					<td width="80" align="center">
						<b> Excluir - Alterar </b>				
					</td>
					
					<td width="40" align="right">&nbsp;   
					</td>
					<td> <font size="2"><b>Titulo </b></font></td>
				</tr>
				<tr> <td colspan="3"><p><font color=red><? echo $configRecurso['aviso_a'];?></font></p>  </td> </tr>
				<tr> <td colspan="4">&nbsp;</td> </tr>
				<?php
				
				while ($linhaN) 	{
          echo "<tr>\n".
             "	<td align=\"center\">\n".
             "		<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir esta noticia ?')) { window.open('noticias_envio.php?OPCAO=Remover&COD_NOTICIA=" . $linhaN["COD_NOTICIA"] . "','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">\n".
             "			<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">\n".
             "		</a>\n&nbsp;&nbsp;&nbsp;\n".
             "		<a href=\"noticias_operacao.php?OPCAO=Alterar&COD_NOTICIA=".$linhaN["COD_NOTICIA"] ."&FILTRO=".$_REQUEST["FILTRO"] ."\">\n".
             "			<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">\n".
             "		</a>".
             "	</td>\n".
             "	<td align=\"right\">" . $linhaN["COD_NOTICIA"] . "&nbsp;&nbsp;</td>\n".
             "	<td align=\"left\">" . $linhaN["TITULO_NOTICIA"] . "</td>\n".
             "</tr>\n";
    
          $linhaN = mysql_fetch_array($rsConN);
			  }		
		  }
			else{	
        echo "  &nbsp;&nbsp;&nbsp; <b>".$_SESSION['configRecurso']['avido_b']." </b>";
			}
		}
		 
		echo "</table>";
	}


//    Se nivel_acesso=1 then 1, 2, 3, 4, 5, 6		acesso = 1
//    senao
//        Fazer consulta
//            Se é ADM_Curso 4, 6					acesso = 2
//            senao
//                Se Prof 4, 6						acesso = 3

//1. Todas
//2. Ativas
//3. Não Ativas
//4. Suas Noticias
//5. Página Principal
//6. Página de Curso / Turma



// Administrador pode excluir qualquer noticia e qualquer referencia a uma noticia.
// Administrador Curso pode excluir qualquer referencia de uma noticia nos cursos e turmas os quais ele é administrador. 
// Professor pode excluir qualquer referencia de uma noticia em suas turmas.


?>

</body>
</html>
