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

//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once ($caminhoBiblioteca."/portfolio.inc.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
include_once ($caminhoBiblioteca."/arquivo.inc.php");
session_name(SESSION_NAME); session_start(); security();

// INCLUIR
// REMOVER
// ALTERAR
// LISTAGEM

if ( !isset($_REQUEST["OPCAO"]) )
	$_REQUEST["OPCAO"] = "";

if ( !isset($_REQUEST["LOCAL"]) )
	$_REQUEST["LOCAL"] = "";

if ( !isset($_REQUEST["COD_ARQUIVO"]) )
	$_REQUEST["COD_ARQUIVO"] = "";

if ( !isset($_REQUEST["SENT"]) )
	$_REQUEST["SENT"] = "";
if ( !isset($_REQUEST["TIPO_CASO"]))
	$_REQUEST["TIPO_CASO"] = "";
	
?>

<html>
	<head>
		<title>Ferramentas de Administrador - Portfólio</title>		
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		<script language="JavaScript">
			function open1(src) 
			{
			    props = "top=106,left=120,width=756px,height=450px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes";
				page = window.open(src,"Wlocal",props);
				page.focus();
			}
		</script>
	</head>
	
<body bgcolor="#FFFFFF" text="#000000" class="bodybg" style="background-image: none">

<?php
//$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];

if (($_SESSION['userRole']!=ALUNO) AND ($_SESSION['userRole']!=ADMINISTRADOR_GERAL) )
{ 
	echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../index.php' target='_parent'>Página Principal</a></p>";
	exit();
 } 
$nivelAtual = getNivelAtual();
$instanciaAtual = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());

switch ($_REQUEST["OPCAO"])
{ 


//#################################################################################################
//									INCLUIR
//#################################################################################################


	case "Incluir":
			if ( $_REQUEST["SENT"] == "" )
			{
				echo "<br> <p align='center'>";
		 
				$nivel = getNivelAtual();

				echo "</p>".
					 "<form name=\"form\" method=\"post\" action=\"\"><table align=\"center\">\n".
					 "<tr><td><b>Descrição do portfólio para ".$nivel->nome." : </b>&nbsp;&nbsp;</td></tr><tr><td> <input type=\"text\" name=\"DESC_ARQUIVO_INSTANCIA\" size=\"50\" value=\"".getDescricaoArquivo($_REQUEST["COD_ARQUIVO"])."\" ></td></tr>\n".
					 "<tr><td><b>Tipo Caso:</b>&nbsp;&nbsp;&nbsp; <select name=\"TIPO_CASO\">\n";
			  if (permiteArquivoGeral($_SESSION['codInstanciaGlobal'])) {
					 echo "<option value=1>GERAL</option>\n";
				}
        if(permiteArquivoParticular($_SESSION['codInstanciaGlobal'])){
          echo "<option value=2>PARTICULAR</option>\n";
        }
        echo "</select></td></tr>\n".
					  "<input type=\"hidden\" value=\"ok\" name=\"SENT\">".
					 //"<tr><td colspan=2 align=center><br><br><input type=\"submit\" name=\"SENT\" value=\"Enviar\">&nbsp;<input type=\"reset\" value=\"Cancelar\" onclick=\"history.back()\"></td></tr>\n".
					 "<tr><td colspan=2 align=left><br><br><input type=\"button\" value=\"Enviar\"  onclick=\"javascript:if (document.form.DESC_ARQUIVO_INSTANCIA.value=='') { alert('Preencha a descrição do portfolio!'); document.form.DESC_ARQUIVO_INSTANCIA.focus(); } else { document.form.submit(); }  \">&nbsp;<input type=\"reset\" value=\"Cancelar\" onclick=\"window.location.href = 'portifolio_local.php?COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\"></td></tr>\n".
					 "</table></form>\n";
			 }
			else
			{
				

					$sucesso = PortLocalInsere($_REQUEST["COD_ARQUIVO"], $_SESSION["codInstanciaGlobal"], $_REQUEST["TIPO_CASO"], $_REQUEST["DESC_ARQUIVO_INSTANCIA"]);
				
					if ( $sucesso )
					{
					  
            $professoresTurma = listaProfessores();
        
            while($linha = mysql_fetch_array($professoresTurma))
            {
              $retNovo = PortNovoLeitura($_REQUEST["COD_ARQUIVO"], $linha["COD_PROF"],true);
              if(!$retNovo){
                print "erro ao atualizar portfolio! ".mysql_error();
                die();
              }
            }  
						
            echo "<br><br><p align=center>Portfolio publicado<br>\n";
					}
					else
					{
						echo "<br><br><p align=center>ERRO ao publicar portfolio<br>";
					}

  				echo "<a href=\"#\" onclick=\"window.location.href ='portifolio_local.php?COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\">Voltar</a>\n";

			 }
		 
		 break;

//#################################################################################################
//								REMOVER
//#################################################################################################


	case "Remover":
		if ( PortVerificaAcesso($_REQUEST["COD_ARQUIVO"]) )
		{
			if ( $_REQUEST["SENT"] == "" )
			{
				$rsConL = listaPort($_REQUEST["COD_ARQUIVO"], $_SESSION["codInstanciaGlobal"], $_REQUEST["TIPO_CASO"]);

				if ( $rsConL )
					$linhaL = mysql_fetch_array($rsConL);
				else
				{
					echo "Erro ao acessar portfolio.";
					exit();
				}
					
				echo "<br>";

			  $nivel = getNivelAtual();
				$acao = $_SERVER["PHP_SELF"]."?OPCAO=Remover&codInstanciaGlobal=".$_REQUEST["codInstanciaGlobal"];
				$acao.= "&COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."&TIPO_CASO=".$linhaL["COD_TIPO_CASO"];

				?>
	
				<form name="form" method="post" action="<?=$acao?>">				
					
  <table align="center">
    <tr> 
      <td><b>Descrição do portfolio para <?=$nivel->nome?></b>&nbsp;&nbsp;</td></tr>
      <tr><td> 
        <?=$linhaL["DESC_ARQUIVO_INSTANCIA"]?>
      </td>
    </tr>
    
    <tr> 
      <td><b>Tipo de caso:&nbsp;&nbsp;&nbsp;</b>
          <?	if ($linhaL["COD_TIPO_CASO"] == 1 ) 
							{
								echo "Geral"; 
							}
							else 
							{	if ($linhaL["COD_TIPO_CASO"] == 2)
								{
									echo "Particular";
								}
								
							}
						?>
      </td>
    </tr>
    <tr> 
      <td colspan=2 align=center><br>
        <input type="submit" name="SENT" value="REMOVER">
        &nbsp; 
        <input type="reset" value="Cancelar" onclick="window.location.href = 'portifolio_local.php?COD_ARQUIVO=<?=$_REQUEST["COD_ARQUIVO"]?>';">
      </td>
    </tr>
  </table>
				</form>	
			<?php
			 }
			else
			{
				$sucesso = PortLocalRemove($_REQUEST["COD_ARQUIVO"], $_SESSION["codInstanciaGlobal"], $_REQUEST["TIPO_CASO"]);
	
				if ( $sucesso )
				{ 
					echo "<br><br><p align=center>Local Removido<br>\n";
				 }
				else
				{
					echo "<br><br><p align=center>ERRO na Remoção<br>";
				 }

				echo "<a href=\"#\" onclick=\"window.location.href = 'portifolio_local.php?COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\">Voltar</a>\n";


			 }
		 }
		else 
		{
			echo "<br><br><p align=center>Você não possui o privilégio necessário.<br>".
				 "<a href=\"javascript:history.back()\">Voltar</a>";			
		 }
		 
		 break;
		 
			
//#################################################################################################
//								ALTERAR
//#################################################################################################


	case ( "Alterar" ):
	{ 
		if ( PortVerificaAcesso($_REQUEST["COD_ARQUIVO"]) )
		{
			if ( $_REQUEST["SENT"] == "" )
			{ 
				$rsConL = listaPort($_REQUEST["COD_ARQUIVO"], $_SESSION["codInstanciaGlobal"], $_REQUEST["TIPO_CASO"]);
				echo "<br> <p align='center'>";
				
				if ( $rsConL )				
				{
					if (! $linhaL = mysql_fetch_array($rsConL))
						exit();
				 }
				else
					exit();

				$nivel = getNivelAtual();
				$acao = $_SERVER["PHP_SELF"]."?OPCAO=Alterar&codInstanciaGlobal=".$_REQUEST["codInstanciaGlobal"];
				$acao.= "&COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."&TIPO_CASO=".$linhaL["COD_TIPO_CASO"];	
	
				echo "</p>";
				?>
	
				<form name="form" method="post" action="<?=$acao?>">				
					
  <table align="center">
    <tr> 
      <td><b>Descrição do portfolio para <?=$nivel->nome?> :</b>&nbsp;&nbsp;</td></tr>
      <tr><td> 
        <input type="text" name="DESC_ARQUIVO_INSTANCIA" size="50" value="<?=$linhaL["DESC_ARQUIVO_INSTANCIA"]?>" >
      </td>
    </tr>
    <tr> 
      <td></td>
      <td></td>
    </tr>
    <tr> 
      <td><b>Tipo Caso :</b>&nbsp;&nbsp;&nbsp;
     
        <select name="TIPO_CASO_NOVO">
          <? if (permiteArquivoGeral($_SESSION['codInstanciaGlobal'])) 
            {  ?> <option value=1<? if ($linhaL["COD_TIPO_CASO"] == 1) echo " selected";  ?> >Geral </option>
          <?} ?>
         
          <? if (permiteArquivoParticular($_SESSION['codInstanciaGlobal'])) 
            {?>
            <option value=2<? if ($linhaL["COD_TIPO_CASO"] == 2 ) echo " selected";?>>Particular</option>
        <?  }?>
        </select>
      </td>
    </tr>
    <tr> 
      <td colspan=2 align=center height="36"> 
        <input type="submit" name="SENT" value="Enviar">
        &nbsp; 
        <input type="reset" value="Cancelar" onclick="window.location.href = 'portifolio_local.php?COD_ARQUIVO=<?=$_REQUEST["COD_ARQUIVO"]?>';"">
      </td>
    </tr>
  </table>
				</form>
				
<table align="center" >
  <tr>
    <td><p align="center"><b>Escolha um tipo caso</b></p></td> 
  </tr>
  <tr>
  	<td >
      <p align="center"><font color="red"><b>*Geral: acesso permitido à todos 
       </b></font></p>
    </td>
  </tr>
  <tr>
  	<td >
      <p align="center"><font color="red"><b>*Particular: acesso restrito ao professor.</b></font></p>
    </td>
  </tr>

</table>
	
			

<?php
			   
			 }
			else
			{
				$sucesso = PortLocalAltera($_REQUEST["COD_ARQUIVO"], $_SESSION["codInstanciaGlobal"], $_REQUEST["DESC_ARQUIVO_INSTANCIA"],$_REQUEST["TIPO_CASO"],$_REQUEST["TIPO_CASO_NOVO"]);
		    //echo mysql_error();
				if ($sucesso)
					echo "<br><br><p align=center>Local Alterado<br>";
				else
				{
					echo "<br><br><p align=center>ERRO na Alteração<br>";
				 }

				echo "<a href=\"#\" onclick=\"window.location.href = 'portifolio_local.php?COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."'\">Voltar</a>\n";

			 }
		 } 
		else 
		{ 
			echo "<br><br><p align=center>Você não possui o privilégio necessário.<br>".
				 "<a href=\"javascript:history.back()\">Voltar</a>";
		 }
	} 
	break;
	

//#################################################################################################
//								LISTAGEM
//#################################################################################################


	default:
		?>
<table width="100%" border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td>&nbsp;</td>
				<td align="left" width="120px"><U>Local</u></td>
				<td align="center"><u>Caso</u></td>
			</tr>
			<tr> <td colspan="3">&nbsp;  </td> </tr>
			<?php
	
			if ( $_REQUEST["COD_ARQUIVO"] != "" )			{	
				
				if ($_SESSION["userRole"] == PROFESSOR || $_SESSION["userRole"] == ALUNO) {
					$numNiveisImprime = NUM_NIVEIS_PROFESSOR;
				}
				else {
					$numNiveisImprime = 100;
        }
				$numNiveisImprime = 2;

				$rsConL = listaPortLocal($_REQUEST["COD_ARQUIVO"]); echo mysql_error();
				$publicadoNestaInstancia = 0;
				echo imprimeLocais($rsConL,$_SERVER["PHP_SELF"],"COD_ARQUIVO",$_REQUEST["COD_ARQUIVO"],$numNiveisImprime,$_SESSION['codInstanciaGlobal'],$publicadoNestaInstancia);
      }
	
			?>
			<tr>
				<td align="left" colspan=3 height="33px">
				<?
				if (!$publicadoNestaInstancia) {

					echo "<a href='portifolio_local.php?OPCAO=Incluir&COD_ARQUIVO=".$_REQUEST["COD_ARQUIVO"]."';\">".
						"Incluir em ".$instanciaAtual->getAbreviaturaOuNomeComPai()."</a>";
				}
        ?>
					</a>
				</td>
			</tr>
		</table>

	<?php
 } 

 if ($_REQUEST["OPCAO"] == "Incluir" || $_REQUEST["OPCAO"] == "Alterar")
	 echo "<script type=\"text/javascript\">document.form.DESC_ARQUIVO_INSTANCIA.focus(); </script>";
?>

</body>

</html>
