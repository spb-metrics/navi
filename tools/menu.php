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

include_once("../config.php");
include_once($caminhoBiblioteca."/menu.inc.php");
include_once($caminhoBiblioteca."/menupersonalizado.inc.php");
include_once($caminhoBiblioteca."/defaultpage.inc.php");
include_once($caminhoBiblioteca."/imagens.inc.php");
session_name(SESSION_NAME); session_start(); security();


?>
<html>
<head>
  <title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  
  <link rel="stylesheet" href=".././cursos.css" type="text/css">
  <link rel="stylesheet" href="<?php echo $urlCss;?>/padraogeral.css" type="text/css">
  <link rel="stylesheet" href="<?php echo $urlCss;?>/configuracao.css" type="text/css">

  <script language="JavaScript" src="<?=$url?>/js/funcoes.js"></script>
  <script language="JavaScript" src="<?=$url?>/js/lista.js"></script>
  
</head>
<body class="bodybg">
<?

switch($_REQUEST['acao']) {
	//default: entra na tela dos formulários
	case "":
?>
  <table align="center" border=0 width="100%" height="100%">
  <tr valign="top">
    <td valign="top">
  <div class="nano" align="center"><b>Ger&ecirc;ncia do Menu</b> </div>
    <br>    
        <form name="formulario" action="<?echo $_SERVER["PHP_SELF"]."?acao=submit";?>" method="POST">
        <input type='hidden' name='continuar' value='0'>
        </td>
		<?
				//select hidden com todos os itens de menu
				$menus = getItensMenu();
				echo "<select id='itensMenu' style='visibility:hidden'>";
				while($itensMenuAtivos = mysql_fetch_array($menus))
				{
				echo "<option value=\"".$urlImagem."/".$itensMenuAtivos["imagem"]."|".$itensMenuAtivos["descricaoMenu"]."\">".
					$itensMenuAtivos["codMenu"]."</option>";

				}
				echo "</select>";
				
			?>
      </tr>
      <tr>
     <td align="center" valign="top" >
        <table border=0 width="550px" >
          <tr>	
          
            <!--  <td align="center" class="nano" width="45%">
              Itens Inativos do Usuário<br><br>
			   <div id='itensUsuario'>
			  <? /**
				
				$menus = getItensMenuUsuario($_SESSION["codInstanciaGlobal"]);
				while($itensMenuUsuario = mysql_fetch_array($menus))
				{
					echo "<img  style='width:20px; height: 20px;' src='".$urlImagem."/".$itensMenuInativos["imagem"]."' title='".$itensMenuInativos["descricaoMenu"]."' border='no' >";

				
                } */?> 
   			</div>
			 </td> -->
       
       	 
              <td align="center" class="nano" width="45%">
              Itens Inativos<br><br>
			   <div id='itensInativos'>
			  <? 
				
				$menus = getItensMenuInativos($_SESSION["codInstanciaGlobal"]);
				while($itensMenuInativos = mysql_fetch_array($menus))
				{
					echo "<img  style='width:20px; height: 20px;' src='".$urlImagem."/".$itensMenuInativos["imagem"]."' title='".$itensMenuInativos["descricaoMenu"]."' border='no' >";

				
                }?>
			 </div>
			 </td>
            <td align="center" width="10%">&nbsp;</td>
            <td align="center" class="nano" width="45%">
		
			Itens Ativos<br><br>
			<div id='itensAtivos'>
			<?
				$menus = getItensMenuAtivos($_SESSION["codInstanciaGlobal"]);
				while($itensMenuAtivos = mysql_fetch_array($menus))
				{
				echo "<img  style='width:20px; height: 20px;'  src='".$urlImagem."/".$itensMenuAtivos["imagem"]."' title='".$itensMenuAtivos["descricaoMenu"]."' border='no'>";

				}
				
			?>
			</div>
			</td>
			 
          </tr>
          <tr>
            <td align="center" valign="top">
			
			
				
                <select size=19 class="botao" multiple id="selectOrigem[]" name="selectOrigem[]" style="width: 200px; ">
			 
              <?
				
				$menus = getItensMenuInativos($_SESSION["codInstanciaGlobal"]);
				while($itensMenuInativos = mysql_fetch_array($menus)) {
					echo "<option value=\"" . $itensMenuInativos["codMenu"] . "\">".$itensMenuInativos["nomeMenu"]."</option>";
        }
              ?>
			  
                </select>
			 
            </td>
            <td align="center" width="10%" valign="center">
			    <input class="botao" type="button" onClick="move(this.form['selectOrigem[]'],this.form['selectDestino[]']);atualizaItens(this.form['selectOrigem[]'],this.form['selectDestino[]'],this.form['itensMenu'],'itensInativos','itensAtivos'); " value="   >>   "><br><br>
                <input class="botao" type="button" onClick="move(this.form['selectDestino[]'],this.form['selectOrigem[]']);atualizaItens(this.form['selectOrigem[]'],this.form['selectDestino[]'],this.form['itensMenu'],'itensInativos','itensAtivos');" value="   <<   ">
            </td>
            <td align="center" width="45%">
			
			    <select size=19 class="botao" multiple id="selectDestino[]" name="selectDestino[]" style="width: 200px;">
			    <?
				$menus = getItensMenuAtivos($_SESSION["codInstanciaGlobal"]);
				while($itensMenuAtivos = mysql_fetch_array($menus))
				{
					
                  echo "<option value=\"" . $itensMenuAtivos["codMenu"] . "\">".$itensMenuAtivos["nomeMenu"]."</option>";
				  
                }
				?>
				</select>
			    
			</td>
          </tr>
      <tr >
      <td align="center" valign="center" colspan="3">
            <div align="center">
            <input class="botao" type="submit" disabled="disabled" id="botaoSubmit" name="submit" value=" Salvar e continuar editando menus " onclick="addListSend(this.form['selectOrigem[]'],this.form['selectDestino[]']); document.formulario.continuar.value=1;">
            <BR>
            <input class="botao" type="submit" disabled="disabled" id="botaoSubmit2" name="submit" value=" Salvar, voltar e ver alterações " onclick="addListSend(this.form['selectOrigem[]'],this.form['selectDestino[]']); document.formulario.continuar.value=0;">

            </div>
      </form>
      </td>
    </tr>    
    </table>
  
	</td>
  </tr>
  </table>
  </body>
  </html>
  <?
	break;
		
  case "criar":

   $listaMenu = getMenuParticular($_SESSION["codInstanciaGlobal"]);
     
   echo "<span style=\"float: right\"><a href=\"recursos_fixos.php\"><img src='".voltar."'border =\"no\" alt=\"Voltar para recursos fixos\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></span>";
   echo "<p></p>";
   echo "<table class=\"tabelaFundo2\" align= \"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">";
   echo "<tr><td class=\"titulo\" align=\"center\" colspan=\"2\"><b>Menu Personalizado</b></td></tr>";
   echo "<tr><td class=\"CelulaEscura\" align=\"right\">excluir editar </td>";
   echo "<td class=\"CelulaEscura\" align=\"center\"><b>Criar Menu</b><br />(Você pode criar seu próprio ícone, basta preencher os campos abaixo)<br /></td></tr>";
   echo "<tr><td class=\"CelulaEscura\" align=\"right\">";
     
     
        foreach($listaMenu -> records as $p)
        {
         echo $p->nomeMenu."&nbsp&nbsp&nbsp<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir este arquivo ?')) {location.href='menupersonalizado.php?codMenu=".$p->codMenu."&acao=excluir". "' }\">\n".
				 "	 <img src='".remove."' border=0 alt=\"Remover\">\n".
				 "	 </a>\n&nbsp;&nbsp;&nbsp;\n".
				 "   <a href=\"#\" onClick=\"{location.href='menu.php?codMenu=".$p->codMenu."&acao=criar&opcao=mostrar' }\">\n".
				 "	 <img src='".edita."' border=0 alt=\"Editar\">\n".
				 "	 </a><br />";
				}
		
		$obj= mostraMenu($_REQUEST["codMenu"], $_FILES["imagem"]["name"]);
				
   echo "</td>";
   echo "<td class=\"branco\" >"; 
   echo "<table widht=\"60%\" align=\"center\" border=\"0\">";
   //echo "<tr><td class=\"CelulaEscura\" align=\"center\" colspan=\"4\"><b>Criar Menu</b><br />(Você pode criar seu próprio ícone, basta preencher os campos abaixo)<br /><br /></td></tr>";
 
	echo "<form name=\"frmCriarMenu\" action=\"menupersonalizado.php\" enctype=\"multipart/form-data\" method=\"POST\">";
	echo "<tr><td align=\"right\">* Nome do Menu: </td><td><input type=\"text\" name=\"nomeMenu\"value=\"".$obj["nomeMenu"]."\"></td></tr>";
	echo "<tr><td align=\"right\">* URL Menu: </td><td><input type=\"text\" name=\"urlMenu\" value=\"".$obj["urlMenu"]."\"></td></tr>";
	echo "<tr><td align=\"right\">  URL Editar: </td><td><input type=\"text\" name=\"urlToolsEditar\" value=\"".$obj["urlToolsEditar"]."\"></tr>";
	echo "<tr><td align=\"right\">  URL Criar: </td><td><input type=\"text\" name=\"urlToolsCriar\" value=\"".$obj["urlToolsCriar"]."\"></td></tr>";
	echo "<tr><td align=\"right\">* Imagem: </td>&nbsp&nbsp&nbsp<td><input type=\"file\" name=\"imagem\" size=\"55\" value=\"".$obj["imagem"]/*["name"]*/."\"></td></tr>";
	echo "<tr><td align=\"right\">* Descricao do Menu: </td><td><input type=\"text\" name=\"descricaoMenu\" size=\"58\" value=\"".$obj["descricaoMenu"]."\"></td></tr>";
	echo "<tr><td align=\"right\">* Ordem: </td><td><input type=\"text\" name=\"ordem\" size=\"3\" maxlength=\"2\" value=\"".$obj["ordem"]."\"></td></tr>";
	echo "<tr><td align=\"right\"> * Tipo de Acesso: </td><td align=\"left\" value=\"".$obj["tipoAcesso"]."\">
          <select name=\"tipoAcesso\">
            <option value=\"restrito\"> Restrito</option>
					  <option value=\"publico\">Público</option>
				  </select></td></tr>";
	echo "<tr><td></td><td align=\"left\"><input type=\"submit\" value=\"Enviar\" name=\"submeter\" >";
	echo "<input type=\"hidden\" name=\"codMenu\" value=\"".$_REQUEST["codMenu"]."\" ></td></tr>";
	echo "<tr><td></td><td align=\"left\"><input type=\"submit\" value=\"Enviar e continuar criando menus\" name=\"sub\" >";
	echo "<input type=\"hidden\" name=\"codMenu\" value=\"".$_REQUEST["codMenu"]."\" ></td></tr>";
   
	echo "</form>";
  echo "</table>";
  echo "</td></tr>";
  echo "</table>";
  break;
  
	case "submit":

  	$Menu=	$_REQUEST["selectOrigem"];
  	$MenuInstancia = $_REQUEST["selectDestino"];
  	$ordemItensMenu= $_REQUEST["SelectOrden"];
  	
  	$ok=editaMenuInstancia($Menu, $MenuInstancia, $_SESSION['codInstanciaGlobal'], "");
  	
  	if ($ok) {
      if ($_REQUEST['continuar']) {
       echo "<script>location.href='".$_SERVER['PHP_SELF']."';</script>";
       }
      else {
		   $nivel=getNivelAtual();
		   echo "<script>top.location.href='".$url."/index.php?&codNivel=".$nivel->codNivel."&codInstanciaNivel=".getCodInstanciaNivelAtual()."&userRole=".$_SESSION["userRole"]."';</script>";
       }
          
      //echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=".$_SERVER["PHP_SELF"]."\">";
      //colocar um reload
    }
    else {
      echo "<br><br><div class=\"strong\" align=\"center\">Erro na Inserção de menus!</div>";
      }
    break;

}

?>