<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de d�vidas e/ou sugest�es, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, port�o K. Porto Alegre - RS. CEP: 90035-003

Este programa � software livre; voc� pode redistribu�-lo e/ou modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme publicada pela Free Software Foundation; tanto a vers�o 2 da Licen�a, como (a seu crit�rio) qualquer vers�o posterior.

    Este programa � distribu�do na expectativa de que seja �til, por�m, SEM NENHUMA GARANTIA;
    nem mesmo a garantia impl�cita de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.
    Consulte a Licen�a P�blica Geral do GNU para mais detalhes.
    

    Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU junto com este programa;
    se n�o, escreva para a Free Software Foundation, Inc., 
    no endere�o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} */
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once ($caminhoBiblioteca."/noticia.inc.php");
include_once ($caminhoBiblioteca."/curso.inc.php");
session_name(SESSION_NAME); session_start(); security();
$nivelAtual = getNivelAtual();
// INCLUIR
// REMOVER
// ALTERAR
// LISTAGEM

if ( !isset($_REQUEST["OPCAO"]) )
	$_REQUEST["OPCAO"] = "";

if ( !isset($_REQUEST["COD_NOTICIA"]) )
	$_REQUEST["COD_NOTICIA"] = "";
	
if ( !isset($_REQUEST["codInstanciaGlobal"]) )
	$_REQUEST["codInstanciaGlobal"] = "";
	
if ( !isset($_REQUEST["SENT"]) )
	$_REQUEST["SENT"] = "";
	
?>

<html>
	<head>
		<title>Ferramentas de Administrador - Noticias</title>		
  	<link rel="stylesheet" href="./../css/padraogeral.css" type="text/css"">
	  <script language="JavaScript" src="".$url."/js/editor.js"></script>
	  <script language="javascript" type="text/javascript" src="".$url."/js/tiny_mce/tiny_mce.js"></script>
    
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
$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];

if ( ( $acesso != 1 ) AND ( $acesso != 2 ) AND ( $acesso != 3 ) )
{ 
	echo "<p align='center'>Acesso Restrito. </p>";
	exit();
 } 

$nivelAtual = getNivelAtual();
$instanciaAtual = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());

switch ($_REQUEST["OPCAO"]) { 

//#################################################################################################
//									INCLUIR
//#################################################################################################

	case "Incluir":

    if ( $_REQUEST["SENT"] == "" )    {
      echo "<br> <p align='center'>";

      echo "</p>".
//					 "<form name=\"form\" method=\"post\" action=\"?". request.querystring ."\"><table align=\"center\">\n".
         "<form name=\"form\" method=\"post\" action=\"\"><table align=\"center\">\n";
    if ($_SESSION['recurso']=='lembretes'){
          echo "<input type='hidden' name='COL' value='1'>";
    }else{
    echo "<tr><td>Coluna :&nbsp;&nbsp;&nbsp;</td><td>".
         " <select name=\"COL\">\n";
         if($instanciaAtual->relacionaPessoas()) {
            echo "<option value=2>1</option>\n".
                 "<option value=3>2</option>\n";
         }else{
            echo "<option value=1>1</option>\n".
                 "<option value=2>2</option>\n".
                 "<option value=3>3</option>\n";
         }
         echo "</select></td></tr>\n";
    }
      echo"<tr><td>Linha :&nbsp;&nbsp;</td><td> <input type=\"text\" name=\"LIN\" size=\"2\" maxlength=\"2\"></td></tr>\n".
         "<tr><td>Tipo de Acesso :&nbsp;&nbsp;&nbsp;</td><td> <select name=\"TIPO_ACESSO\">\n".
         "<option value=3>Publico</option>\n".
         "<option value=2 SELECTED>Restrito</option>\n".
        // "<option value=3>Publico e Restrito</option>\n".
         "</select></td></tr>\n".
         "<tr><td colspan=2 align=center><br><br><input type=\"submit\" name=\"SENT\" value=\"Enviar\">&nbsp;<input type=\"reset\" value=\"Cancelar\" onclick=\"history.back()\"></td></tr>\n".
           "<br><br><br>".
         "</table></form>\n";
        /* "<table aling='center'>".
         "<tr><td><p aling ='center'><font color ='red'><b>*Coluna 1 : insere not�cia na Apresenta��o.</font></p></td></tr>".
           "<tr><td><p aling='center'><font color ='red'><b>*As demais colunas inserem a not�cia na  Not�cia. </font></p></td></tr></table>";*/
      
    }
		else	{

			//echo $_REQUEST["COD_NOTICIA"]."ola";
  		$sucesso = NoticiaLocalInsere($_REQUEST["COD_NOTICIA"], $_SESSION["codInstanciaGlobal"], $_REQUEST["LIN"], $_REQUEST["COL"], $_REQUEST["TIPO_ACESSO"]);
      
      if ( $sucesso )	{
           echo "<br><br><p align=center>Noticia inserida com sucesso.<br>";
				   //echo "<a href=\"javascript:window.close()\">Fechar</a>";
					echo "<a href=\"javascript:window.location.href = 'noticias_local.php?COD_NOTICIA=".$_REQUEST['COD_NOTICIA']."'\">Voltar</a>\n";
       }
       else    {
         echo "<br><br><p align=center>ERRO na Inser��o<br>".
         "<p align=center>Verifique se a not�cia j� n�o est� inserida neste local.<br><br>".
           "<a href=\"javascript:history.back()\">Voltar</a>";
       }
     }
		 break;

//#################################################################################################
//								REMOVER
//#################################################################################################


	case "Remover":
		if ( NoticiaVerificaAcesso($_REQUEST["COD_NOTICIA"]) )
		{
			if ( $_REQUEST["SENT"] == "" )
			{
//				echo $_REQUEST["COD_NOTICIA"]."-".$_REQUEST["CURSO"]."-".$_REQUEST["TURMA"]."-".$_REQUEST["TIPO_ACESSO"];

				$rsConL = listaNoticias($_REQUEST["COD_NOTICIA"], $_REQUEST['codInstanciaGlobal'], "", $_REQUEST["TIPO_ACESSO"]);

				if ( $rsConL )
					$linhaL = mysql_fetch_array($rsConL);
				else
					echo "erro";
					
				?>
	
<!--				<form name="form" method="post" action="?<?=request.querystring?>"> -->
				<form name="form" method="post" action="">				
					<table align="center">
						<tr><td>Coluna :&nbsp;&nbsp;&nbsp;</td><td> <?=$linhaL["NRO_COLUNA_NOTICIA"]?></td></tr>
						<tr><td>Linha :&nbsp;&nbsp;</td><td> <?=$linhaL["NRO_LINHA_NOTICIA"]?></td></tr>
						<tr><td>Tipo de Acesso :&nbsp;&nbsp;&nbsp;</td><td> 
						<?	if ($linhaL["COD_TIPO_ACESSO"] == 1 ) 
							{
								echo "Publico"; 
							}
							else 
							{	if ($linhaL["COD_TIPO_ACESSO"] == 2)
								{
									echo "Restrito";
								}
								else
								{	if ($linhaL["COD_TIPO_ACESSO"] == 3 )
										echo "Publico";
								}
							}
						?>
						</td></tr>
						<tr><td colspan=2 align=center><input type="submit" name="SENT" value="REMOVER">&nbsp;<input type="reset" value="Cancelar" onclick="window.location.href ='noticias_local.php?COD_NOTICIA=<?=$_REQUEST["COD_NOTICIA"];?>'"></td></tr>
					</table>
				</form>	
			<?php
			 }
			else	{
				$sucesso = NoticiaLocalRemove($_REQUEST["COD_NOTICIA"], $_REQUEST['codInstanciaGlobal'], $_REQUEST["TIPO_ACESSO"]);
	
				if ( !$sucesso ) {
					echo "<br><br><p align=center>ERRO na Remo��o<br>".
						 "<a href=\"javascript:history.back()\">Voltar</a>";
				}
        else {
         // echo "<script> window.opener.location.reload(true); </script>";
			    echo "<br><br><p align=center>Remo��o efetuada com sucesso.<br>";
				    //"<a href=\"javascript:window.close()\">Fechar</a>";
					echo "<a href=\"javascript:window.location.href = 'noticias_local.php?COD_NOTICIA=".$_REQUEST['COD_NOTICIA']."'\">Voltar</a>\n";
        }
			}
		}
		else 
		{
			echo "<br><br><p align=center>Voc� n�o possui o privil�gio necess�rio.<br>".
				 "<a href=\"javascript:history.back()\">Voltar</a>";			
		 }
		 
		 break;
		 
			
//#################################################################################################
//								ALTERAR
//#################################################################################################


	case ( "Alterar" ):

		if ( NoticiaVerificaAcesso($_REQUEST["COD_NOTICIA"]) )
		{
			if ( $_REQUEST["SENT"] == "" )
			{ 
				$rsConL = listaNoticias($_REQUEST["COD_NOTICIA"], $_REQUEST['codInstanciaGlobal'], "", $_REQUEST["TIPO_ACESSO"]);
				echo "<br> <p align='center'>";
				
				if ( $rsConL )				
				{
					if (! $linhaL = mysql_fetch_array($rsConL))
						exit();
				 }
				else
					exit();
				
		//		echo "<br> <p align='center'>";
	
				
				echo "</p>";
				?>
	
<!--				<form name="form" method="post" action="?<?=request.querystring?>"> -->
				<form name="form" method="post" action="">				
					<table align="center">
							<? if ($_SESSION['recurso']=='lembretes'){
                  echo "<input type='hidden' name='COL' value='1'>";
                  }else{
                ?>
            <tr>
							<td>Coluna :&nbsp;&nbsp;&nbsp;</td>
							<td>
							  	<select name="COL">
							  	<? if ($instanciaAtual->relacionaPessoas()) {?>
               		<!--<option value=1<? if ($linhaL["NRO_COLUNA_NOTICIA"] == 1) echo " selected";?>>1</option>-->
							  	<option value=2<? if ($linhaL["NRO_COLUNA_NOTICIA"] == 2) echo " selected";?>>1</option>
									<option value=3<? if ($linhaL["NRO_COLUNA_NOTICIA"] == 3) echo " selected";?>>2</option>
									<?}else{?>
									<option value=1<? if ($linhaL["NRO_COLUNA_NOTICIA"] == 1) echo " selected";?>>1</option>
							  	<option value=2<? if ($linhaL["NRO_COLUNA_NOTICIA"] == 2) echo " selected";?>>2</option>
									<option value=3<? if ($linhaL["NRO_COLUNA_NOTICIA"] == 3) echo " selected";?>>3</option>
									<?}?>
								</select>
							</td>
						</tr>
						<?}?>
						<tr><td>Linha :&nbsp;&nbsp;</td><td> <input type="text" name="LIN" size="2" maxlength="2" value="<?= $linhaL["NRO_LINHA_NOTICIA"]?>"></td></tr>
						<tr><td>Tipo de Acesso :&nbsp;&nbsp;&nbsp;</td>
						<td>
						    <select name="TIPO_ACESSO_NOVO">
								<!--<option value=1<? if ($linhaL["COD_TIPO_ACESSO"] == 1) echo " selected";?>>Publico</option>-->
								<option value=2<? if ($linhaL["COD_TIPO_ACESSO"] == 2 ) echo " selected";?>>Restrito</option> 
								<option value=3<? if ($linhaL["COD_TIPO_ACESSO"] == 3 ) echo " selected";?>>Publico</option>
						    </select>
						    </td>
						</tr>
					
						<tr><td colspan=2 align=center><input type="submit" name="SENT" value="Enviar">&nbsp;<input type="reset" value="Cancelar" onclick="window.location.href ='noticias_local.php?COD_NOTICIA=<?=$_REQUEST["COD_NOTICIA"];?>'"></td></tr>
					</table>
				</form>
				<br><br><br>
				<table align="center" >
				   <tr>
					<td >
					 <!-- <p align="center"><font color="red"><b>*Coluna 1 : insere not�cia na Apresenta��o.</b></font></p>-->
					</td>
				  </tr>
				  <tr>
					<td >
					 <!-- <p align="center"><font color="red"><b>*As demais colunas inserem a not�cia na  Not�cia.</b></font></p>-->
					</td>
				  </tr>

				</table>
	
			<?php
			   
			}
			else {
			
				$sucesso = NoticiaLocalAltera($_REQUEST["COD_NOTICIA"], 
				$_REQUEST["codInstanciaGlobal"], $_REQUEST["LIN"], $_REQUEST["COL"], $_REQUEST["TIPO_ACESSO"],$_REQUEST["TIPO_ACESSO_NOVO"]);
				
				if ($sucesso)
					echo "<br><br><p align=center>Local Alterado<br>".
						 "<a href=\"javascript:window.close()\">Fechar</a>".
						 "<script>".
						 "window.opener.location.reload(true)".
						 "</script>";
				else{
					echo "<br><br><p align=center>ERRO na Altera��o<br>".					       
						 "<a href=\"javascript:history.back()\">Voltar</a>";
				}
			}
		} 
		else	{ 
			echo "<br><br><p align=center>Voc� n�o possui o privil�gio necess�rio.<br>".
				 "<a href=\"javascript:history.back()\">Voltar</a>";
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
				<td align="center"><u>Tipo</u></td>
			</tr>
			<tr> <td colspan="3">&nbsp;  </td> </tr>
			<?php
	
			if ( $_REQUEST["COD_NOTICIA"] != "" )		{
        $instanciasPublicadas = listaLocal('noticia_instancia','COD_NOTICIA',$_REQUEST["COD_NOTICIA"]);

        $publicadoNestaInstancia = 0; //passado como parametro de saida para imprimeLocais       
        echo imprimeLocais($instanciasPublicadas,'noticias_local.php','COD_NOTICIA',$_REQUEST["COD_NOTICIA"],2,$_SESSION['codInstanciaGlobal'],$publicadoNestaInstancia);

				echo "</td></tr>\n";
					 
			 }
	
			?>
			<tr>
				<td align="left" colspan=3 height="33px">
          <?
        //  echo "<h2>exerLoca".$publicadoNestaInstancia."</h2>";
           if (!$publicadoNestaInstancia) {
             echo "<a href='noticias_local.php?OPCAO=Incluir&COD_NOTICIA=".$_REQUEST['COD_NOTICIA']."'>".
						      "Incluir aqui em ".$instanciaAtual->getAbreviaturaOuNomeComPai(); 
           }
           else {
             echo "<br>Esta not&iacute;cia j&aacute; est&aacute; publicada aqui.";
           }
          ?>
					</a>
				</td>
			</tr>
		</table>

	<?php
 } 
?>

</body>

</html>
