<?
ini_set('display_errors',1);
error_reporting(E_ALL);

include_once ("./../../config.php");
include_once($caminhoBiblioteca."/forum.inc.php");
include_once ($caminhoBiblioteca."/perfil.inc.php");
include_once ($caminhoBiblioteca."/pessoa.inc.php");
session_name(SESSION_NAME); session_start(); security();

//le o arquivo de configuracao do forum
$ini = parse_ini_file("forum.ini",1);


$configMathml = new InstanciaGlobal($_SESSION["codInstanciaGlobal"]);

function topo()
{
	echo"<html>".
		"	 <head>".
		"		<title>F&Oacute;RUM</title>".
		"		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">".
		"		<link rel=\"stylesheet\" href=\"./../../cursos.css\" type=\"text/css\">". 
		"		<link rel=\"stylesheet\" href=\"".$_SESSION["configForum"]["arquivoCSS"]."\" type=\"text/css\">".
		"		<script language=\"JavasScript\" type=\"text/javascript\" src=\"../../js/divs.js\"></script>";
  ?>
	
		<SCRIPT>
	  
    function mostraForm(a){
  	   aa = document.getElementById(a).style.display;
		   if (aa == "inline"){document.getElementById(a).style.display = "none";}
       else{	document.getElementById(a).style.display = "inline";}
   } 
   

		function alterarTopico(a){

		}
	 var status = 1;
	function mudaFigura(obj,url){
    if (status == 0) {
      document.getElementById(obj.id).src = ''+url+'/navi/imagens/aumenta.gif';
      //alert(''+url+'/navi/imagens/aumenta.gif');
      status = 1;
    } 
    else {
      document.getElementById(obj.id).src = ''+url+'/navi/imagens/diminui.gif';
    // alert(''+url+'/navi/imagens/diminui.gif');
      status = 0;
    }
   
 }
		</SCRIPT>
	<?
	echo"	</head>".
		" <body class=\"forum\">".
	    "  <table  width=\"700\" heigth =\"200\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">".
	    "   <tr align=\"center\"><td valign='middle'>";




}
function rodape()
{
	echo"      </td></tr>".
		"	 </table>". 
		"	</body>".
		"<html>";
}
function classTopico($class)
{
	if($class=='tdNovoTopico1')
		$class='tbNovoTopico2';
	else
		$class='tdNovoTopico1';

	return $class;
}
$nomeFerramenta[1] = "F&oacute;rum";
$nomeFerramenta[2] = "Suporte t&eacute;cnico";
$nomeFerramenta[3] = "Caf&eacute virtual";

if( ($_SESSION["COD_PESSOA"] == "") OR ($_SESSION['codInstanciaGlobal'] == "")){
	?><link rel="stylesheet" href="./../../cursos.css" type="text/css"><?
	
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
  $_SESSION["tipoForum"]=$_REQUEST["tipoForum"];
}


// insert Novo Tópico 
//alterar tópico
//excluir tópico
//tópico default create tópico whem nothing(??????).

switch($_REQUEST["acao"]) {
	case "":
	topo();
//table insert tópico, avisos , etc;

	
		echo"<tr><td  align='center'><h4>Tópicos do ".$nomeFerramenta[$_SESSION["tipoForum"]] ."</h4><br></td></tr>";

		echo "<tr>"; 
    //para inserir topico, precisa poder administrar ou ser aluno que interage, em forum que permite alunos postarem topicos 
		if ( (Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage'])) || 
        ($_SESSION['userRole']==ALUNO && $_SESSION["configForum"]["PermitirAlunoInserirTopico"] && podeInteragir($_SESSION['userRole'],$_SESSION['interage']) )
        )    		{
			echo "<td><table align='left'><tr><td align='center' width='200'>";
				
				formInsertTopico($_SESSION['codInstanciaGlobal'], $_SESSION["COD_PESSOA"]);
			
			echo "</tr></td></table></td>";
		}

		if($_SESSION['userRole']!=ALUNO){
				echo"<td  align='left' class=\"menu\">";
			echo "<form name=\"form\" method=\"post\"><input type=\"checkbox\" name=\"mathml\" value=\"1\"";
		if($configMathml->getUsoMathml()==1){
			echo "checked ";
		}
		echo" onClick=\"if(this.checked){document.form.action='".$_SERVER['PHP_SELF']."?acao=setUsoMathml';submit();}else{document.form.action='".$_SERVER['PHP_SELF']."?acao=unsetUsoMathml';submit();}\" >Usar Mathml <a target=\"_blanck\" href='instrucaoUsoPlugginMathml.php'><img src=\"".$urlImagem."/help.jpg\" border='no' title='ajuda'></a></form>";
		echo "</td>";
		}




		echo "</tr>";
		

//table lista tópicos , com possibilidade de alterar excluir etc.

$listaDeTopicos=listaTopicos($_SESSION["codInstanciaGlobal"]);

/****cria topico default caso não exista nenhum *********/
if(mysql_num_rows($listaDeTopicos)==0){
	$topico='Geral';
	insertTopico($_SESSION['codInstanciaGlobal'],$topico,'');
	echo"<script>location.href='".$_SERVER['PHP_SELF']."'</script>";

}

/***** end ***********/


		echo "<tr><td colspan='2'><table id='topico' width='70%'>";
		$class='tbNovoTopico2';
		while($linha = mysql_fetch_array($listaDeTopicos))
		{
			$colspan='2';
			if(!empty($_SESSION["configForum"]["tabelaMsgNova"]))
			{
				$numMsgsTopico=numMsgsForum($linha['COD_SALA']);
				$numMsgNovas=VerificaMsgNova($_SESSION['codInstanciaGlobal'], $linha['COD_SALA'], $_SESSION["COD_PESSOA"],$_SESSION["configForum"]["tabelaMsgNova"],$_SESSION["configForum"]["tabelaSalas"]);
				$msg="<br><span class='msgNova'> [ ".$numMsgNovas." novas mensagens de um total de ".$numMsgsTopico." ] </span>";

			}
			echo "<tr align='left'>";
			if($linha['criadoPorCodPessoa']==$_SESSION["COD_PESSOA"])
			{
			
					$colspan='';
					$locationHref="location.href='".$_SERVER['PHP_SELF']."?acao=excluirTopico&COD_SALA=".$linha['COD_SALA']."'";

			   echo "<td class=".$class." width='4%' >";

				echo "<a href='#' onClick=\"if(confirm('Deseja mesmo excluir este tópico e todas as mensagens relacionadas?')){".$locationHref."}\"  ><img  src=\"./imagens/".$_SESSION["configForum"]["imagemExcluir"]."\" border='no'></a>&nbsp;&nbsp;";
				
				echo "<a href=\"".$_SERVER['PHP_SELF']."?codSala=".$linha["COD_SALA"]."&change=yes\" ><img src=\"./imagens/".$_SESSION["configForum"]["imagemAlterar"] ."\" border='no'></a>".			"</td>";
				


			}
				$link= "<a href=\"forum.php?tipoForum=".$_SESSION["tipoForum"]."&COD_SALA=".$linha["COD_SALA"]."\" class='menu'>".$linha["topico"]."</a>".$msg;

				echo "<td class=".$class."  colspan='".$colspan."' ><br>";
						if(($_REQUEST['change']=='yes')&& ($_REQUEST['codSala']==$linha["COD_SALA"]) )
						{
							formAlterarTopico($linha["COD_SALA"], $linha["topico"],$linha['criadoPorCodPessoa']);
						}else
							echo $link;

				echo "</td></tr>";

			
			
			$class=classTopico($class);
		}
		echo "</table></td></tr>";
	rodape();
	break;

	case "insertTopico":
    //para inserir topico, precisa poder administrar ou ser aluno que interage, em forum que permite alunos postarem topicos 
		if ( (Pessoa::podeAdministrar($_SESSION['userRole'],getNivelAtual(),$_SESSION['interage'])) || 
         ($_SESSION['userRole']==ALUNO && $_SESSION["configForum"]["PermitirAlunoInserirTopico"] && podeInteragir($_SESSION['userRole'],$_SESSION['interage']) ) 
       ) {
  		insertTopico($_SESSION['codInstanciaGlobal'],$_REQUEST['topico'],$_REQUEST['codPessoa']);
      echo"<script>location.href='".$_SERVER['PHP_SELF']."'</script>";
    }
    break;
    
	case "setUsoMathml":
		$configMathml->setUsoMathml();
		echo"<script>location.href='".$_SERVER['PHP_SELF']."'</script>";
	break;
	case"unsetUsoMathml":
		$configMathml->unsetUsoMathml();
		echo"<script>location.href='".$_SERVER['PHP_SELF']."'</script>";
	break;
	case "alterarTopico":
		alterarTopico($_SESSION['codInstanciaGlobal'],$_REQUEST['codSala'],$_REQUEST['topico'],$_REQUEST['codPessoa']);
		echo"<script>location.href='".$_SERVER['PHP_SELF']."?change=no'</script>";

	break;

	case "excluirTopico":
		topo();
		$ok=excluirTopico($_SESSION['codInstanciaGlobal'],$_REQUEST['COD_SALA']);
		if($ok)
			echo"<script>location.href='".$_SERVER['PHP_SELF']."'</script>";
		else
			msg("Não foi possível deletar o tópico");
		rodape();
	break;

}




?>
