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

ini_set('display_errors',0);
//include_once("../funcoes_bd.php");
include("../config.php");
include($caminhoBiblioteca."/portfolio.inc.php");
include($caminhoBiblioteca."/curso.inc.php");
include($caminhoBiblioteca."/funcoesftp.inc.php");
session_name(SESSION_NAME); session_start(); security();



/**********************************************

�ltima altera��o: Leandro Ferreira de Souza
data:             19/10/2006
motivo:           pemitir colocar portifolios como nao lidos
descri��o:
          inclui a funcao removeSelected() e o a opcao de marcar
          o portifolio como nao lido
***********************************************/
/**********************************************

�ltima altera��o: Gisele Bonapaz da Silva
data:             30/10/2006
motivo:           mostrar numero de comentarios;
descri��o:
          inclui a funcao numeroComentario mostra 
		  o numero de comentarios ao lado do "Ver comentario" ;
***********************************************/
/**********************************************

�ltima altera��o: Gisele Bonapaz da Silva
data:            14/11/2006
motivo:			  acrescentar links nos arquivos apontando
				  para ferramentas de ger�ncia, para o aluno
				  alterar e excluir os portf�lios
descri��o:
                  -incluida a fun��o ExAltPortDentroInst(),
				   essa fun��o cont�m a op��o de alterar e excluir o arquivo;
***********************************************/
$nivel=getNivelAtual();
?>

<html>
	<head>
	<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	  <link rel="stylesheet" href="<?php echo $url;?>/css/padraogeral.css" type="text/css">

  <script language="javascript">
 
    function removeSelected(){
      var forme = document.getElementById("form");
      var checado = false;
      var sel="";
      
      for(var i=0; i< forme.length; i++){
        if((forme.elements[i].type == "checkbox") && (forme.elements[i].checked)){
            
            if(sel == "") sel=forme.elements[i].value;
            else          sel+=","+forme.elements[i].value;
        }
      }
      
      if(sel == "")alert("pelo menos um portif�lio deve ser selecionado!");
      else{
        forme.selecionados.value = sel;
        forme.action="proc_nao_lido.php"
        forme.submit();
      }
    }

function enviaForm(){
	if(document.form1.DESC_ARQUIVO.value == ""){	alert('Por favor preencha a descri��o do arquivo');}
	else {document.form1.submit();}
}
function enviaFormParticularGeral(){
    document.particularGeral.submit();
}

function mostraForm(a){
  	aa = document.getElementById(a).style.display;
		if (aa == "inline"){document.getElementById(a).style.display = "none";}
    else{	document.getElementById(a).style.display = "inline";}
 } 
 var status = 0;
 function mudaFigura(obj,url){
  if (status == 0) {
    document.getElementById(obj.id).src = ''+url+'/imagens/aumenta.gif';
    //alert(''+url+'/imagens/aumenta.gif');
    status = 1;
  } 
  else {
    document.getElementById(obj.id).src = ''+url+'/imagens/diminui.gif';
   // alert(''+url+'/imagens/diminui.gif');
    status = 0;
  }
   
 }
   </script>

   
  </head>
<body>

<?php

if (!function_exists('confNum')) {
  //define a funcao confNum se ela nao existe 
  //meio tosco... mas eh q a definicao dela esta em agenda.inc.php
  //e nao queria incluir este arquivo apenas para esta funcao, nao faz sentido
  function confNum($Num)  {
  		if ($Num < 10) 
  			return "0" . strval($Num);
  		else		
  			return strval($Num);
  }
}

//salva arquivo no portfolio
if ($_REQUEST['acao'] == 'A_publica_arquivo') {
  //salva arquivo no portfolio
  $sucesso = salvaArquivoPortifolio($_REQUEST['COD_ARQUIVO']);
  if ($sucesso) {
    echo '<br><b>Arquivo '.strip_tags($_FILES['ARQUIVO']['name']).' salvo com sucesso!</b>';
  }
  
  //else {   echo "<!-- ".mysql_error();    echo " -->";  }
}

//altera modifica��es de publica��o do portfolio
if ($_REQUEST['acao'] == 'A_particularGeral') {
  $ok= alteraParticularGeralPortfolio($_REQUEST["permiteArquivoGeral"],$_REQUEST["permiteArquivoParticular"],$_SESSION["codInstanciaGlobal"]);  
}

if(!isset($_REQUEST["COD_ALUNO_ARQUIVO"])) {
	$_REQUEST["COD_ALUNO_ARQUIVO"]="";
}		
if ( isset($_REQUEST["COD_AL"]) ) {
	$COD_ALUNO =$_REQUEST["COD_AL"];
}
else {
	$COD_ALUNO ="";
}	

if ( $COD_ALUNO  == "" )	{
	
  $rsCon = portifolio("");
	
	if ($rsCon) { 
		if ( $linha = mysql_fetch_array ($rsCon) ) 	{
			echo  "<p align='center'>".
				  "		<b><font size='1'>Portf�lio</font></b> <br> <br>".
				  "		Selecione � esquerda qual portf�lio deseja visitar<br> ".
				  "</p>";
		 }
		else {
			echo  "<p align='center'>".
				  "		<b><font size='1'>Portf�lio</font></b> <br> <br>".
				  "		n�o h� nenhum material dispon�vel no momento.<br>".
				  "</p>";
		 }
	 }
  
  //se o portifolio q esta sendo visualizado eh o proprio
  //portifolio do aluno q esta logado, entao exibe a tela de publicacao
  if(Pessoa::podeAdministrar($_SESSION['userRole'],$nivel,$_SESSION['interage'])){
   // echo "administrador/professor";
    mostraFormParticularGeralPortifolio("aberta");
  }
}
else	{  

  if ($_SESSION['COD_AL'] == $COD_ALUNO && podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) {
    mostraFormPublicaPortifolio();
  } 
   
	 $rsCon = portifolio($COD_ALUNO);
   $rsCon2 =listaProfessores();
	 
	while($linha = mysql_fetch_array($rsCon2))    {
    $professoresDestaTurma[$linha["COD_PROF"]]=1;
  }
	
	if ( $rsCon )	{
   
    if($linha = mysql_fetch_array($rsCon))	{
				
		?>
       <table width="100%" border="0">
  		  <tr>
				  <td  width="100%" align="center"><b><font size="1"><?=$linha["NOME_PESSOA"]?></font></b>
          </td>
				</tr>
				</table>
				<table width="100%" border="0" align="center"  >
				<tr align="center" style="background-color: #ededed">
			<? if ($_SESSION['COD_AL']==$COD_ALUNO){?>
			  <td ><b>Excluir/Alterar</b></td>
			<?}?>
				  <td ><b>Arquivo</b></td>
				  <td><b>Coment�rios Enviados</b></td>
				  <td><b>Comentar</b></td>
				  <td><strong>Data da Publica&ccedil;&atilde;o</strong></td>
				</tr>  
	 <?
	    print "<form name=\"form\" id=\"form\" method=\"post\" >\n";
      while ($linha)
			{		
        if($_SESSION['userRole']==PROFESSOR)
        $retPortStatusQtde= PortStatus($_SESSION["COD_PROF"],$linha["COD_ARQUIVO"],$linha["COD_AL"],"qtde");
         
        if(
            ($linha["COD_TIPO_CASO"]=="2") AND
            (
              (!empty($_SESSION["COD_ADM"]))  OR
              ($_SESSION["COD_AL"]== $linha["COD_ALUNO"]) OR
              ($professoresDestaTurma[$_SESSION["COD_PROF"]]))
          )
				{
								
					?>
            <tr style="background-color: #ededed">

		  <?
			  if( $_SESSION['COD_AL']==$COD_ALUNO)
			  {
				ExAltPortDentroInst($linha["COD_ARQUIVO"]);
			   }
			
		  ?>
				      <td  align="left" nowrap>
               <?
                if($_SESSION['userRole']==PROFESSOR)
                  print "<input type='checkbox' name='remarcar' id='remarcar".$linha["COD_ARQUIVO"]."' value='".$linha["COD_ARQUIVO"]."'>"; 

               echo"<a href=\"#\" onClick=\"javascript:window.open('./mostrar.php?COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "&COD_AL=".$_REQUEST["COD_AL"]."','', 'fullscreen=yes, scrollbars=none');\"";

                    $tamLinhaDesc = 15;
                    if(strlen($linha["DESC_ARQUIVO_INSTANCIA"]) > $tamLinhaDesc){
                      print "title='".$linha["DESC_ARQUIVO_INSTANCIA"]."'";
                      $linha["DESC_ARQUIVO_INSTANCIA"] = substr($linha["DESC_ARQUIVO_INSTANCIA"],0,$tamLinhaDesc);
                      $linha["DESC_ARQUIVO_INSTANCIA"] .= "...";
                      
                    }
                 echo " >".$linha["DESC_ARQUIVO_INSTANCIA"]." </a>";
               
           
             
          
                if($_SESSION['userRole']==PROFESSOR){
                   if($retPortStatusQtde != 0)
                   print "[novo]";
      	        } 
              ?>
              </td>
							<td  align="center" nowrap>
						<?$numCom=numeroComentario($linha["COD_ALUNO_ARQUIVO"]);
						 	if($numCom!=0)
							{
								echo"<a  href=\"./ver_comentario.php?COD_AL=".$_REQUEST["COD_AL"]."&COD_ALUNO_ARQUIVO=".$linha["COD_ALUNO_ARQUIVO"]."\"> ver Coment�rio [$numCom]</a>";
							}
							else
							{
								echo "<p align='center' class='perfil'>N�o h� coment�rios</p>";
							}
						?>
						</td>
						<td  align="center" nowrap><a  href="./portifolio_comentario_enviar.php?COD_AL=<?=$_REQUEST["COD_AL"]?>&COD_ALUNO_ARQUIVO=<?=$linha["COD_ALUNO_ARQUIVO"]?>"> Enviar seu comentario</a></td>
							<td  align="center" nowrap><?=$linha["DT_CADASTRO"]?></td>
						</tr>
					<?	 
				}
				else
        {
            if(($linha["COD_TIPO_CASO"]=="1"))
            {?>		 
					  
            <tr style="background-color: #ededed">
		   <?
				  if( $_SESSION['COD_AL']==$COD_ALUNO)
				  {
					ExAltPortDentroInst($linha["COD_ARQUIVO"]);

				   }
			?>
              <td  align="left" nowrap>
                <?
                if($_SESSION['userRole']==PROFESSOR)
                  print "<input type='checkbox' name='remarcar' id='remarcar".$linha["COD_ARQUIVO"]."' value='".$linha["COD_ARQUIVO"]."'>"; 

              echo"<a href=\"#\" onClick=\"javascript:window.open('./mostrar.php?COD_ARQUIVO=" . $linha["COD_ARQUIVO"] . "&COD_AL=".$_REQUEST["COD_AL"]."','', 'fullscreen=yes, scrollbars=none');\"";

                    $tamLinhaDesc = 15;
                    if(strlen($linha["DESC_ARQUIVO_INSTANCIA"]) > $tamLinhaDesc){
                      print "title='".$linha["DESC_ARQUIVO_INSTANCIA"]."'";
                      $linha["DESC_ARQUIVO_INSTANCIA"] = substr($linha["DESC_ARQUIVO_INSTANCIA"],0,$tamLinhaDesc);
                      $linha["DESC_ARQUIVO_INSTANCIA"] .= "...";
                      
                    }
             echo " >".$linha["DESC_ARQUIVO_INSTANCIA"]."</a>";
             
               
                if($_SESSION['userRole']==PROFESSOR){
                   if($retPortStatusQtde != 0)
                   print "[novo]";
      	        } 
              ?>
              </td>
              <td  align="center" nowrap>
			
			<?$numCom=numeroComentario($linha["COD_ALUNO_ARQUIVO"]);
						 
							if($numCom!=0)
							{
								echo"<a  href=\"./ver_comentario.php?COD_AL=".$_REQUEST["COD_AL"]."&COD_ALUNO_ARQUIVO=".$linha["COD_ALUNO_ARQUIVO"]."\"> ver Coment�rio [$numCom]</a>";
							}
							else
							{
								echo "<p align='center' class='perfil'>N�o h� coment�rios</p>";
							}
			?>
			</td>						
				<td  align="center" nowrap><a  href="./portifolio_comentario_enviar.php?COD_AL=<?=$_REQUEST["COD_AL"]?>&COD_ALUNO_ARQUIVO=<?=$linha["COD_ALUNO_ARQUIVO"]?>"> Enviar seu comentario</a></td>
				<td  align="center" nowrap><?=$linha["DT_CADASTRO"]?></td>
				</tr>
						
					<?
            }
				}
          $linha = mysql_fetch_array($rsCon);
			}
			
			if(mysql_num_rows($rsCon) >0){
        if($_SESSION['userRole']==PROFESSOR){ ?>
         
          <tr>
            <td colspan="3" align="cwenter">
                <input type="button" name="remover" id="remover" value="marcar como n&atilde;o lido" onClick="removeSelected()"/>
            </td>
          </tr>
          <input type="hidden" name="selecionados" id="selecionados" value=""/>
          <input type="hidden" name="cd_aluno" id="cd_aluno" value="<?=$_REQUEST["COD_AL"]?>"/>
        <?
        }
      }
      print "</form>";
		 			
		}
		else
		{
					echo  "<p align='left' style=\"padding-left:5px;\">".
						  "		<b><font size='1'>Portf�lio</font></b> <br> <br>".
              "  Prezados,<br><br>".
              "  Para postar um arquivo no portf�lio, proceda da seguinte forma:<br>".
              "  1) Preencha a descri��o do arquivo e localize o mesmo com o bot�o de \"procurar\" <br>".
              "  2) Escolha \"Geral\" (permite que todos os alunos e professores vejam o trabalho) ou \"Particular\" (somente os professores podem ver) e, finalmente, clique em \"Enviar\"<br>".
						  "</p>";
		}
		?>
		</table>
					

		<?			
	} 
			 	
}
?>
</body>
</html>
