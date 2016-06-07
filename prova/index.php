<?php 
ini_set("display_errors",1);
error_reporting(E_ALL);
include_once ("../config.php");
include_once ($caminhoBiblioteca."/prova2.inc.php");
session_start();
security();


function printHeader($params="") {
  echo	"<html>".
		"<head>".
		"<link rel=\"stylesheet\" href=\"./sca.css\" type=\"text/css\">".
		"<link rel=\"stylesheet\" href=\"./../cursos.css\" type=\"text/css\">".
		"<link rel=\"stylesheet\" href=\"./../css/prova.css\" type=\"text/css\">";
  if (!empty($params["titulo"]))
  echo "<title>{$params["titulo"]}</title>";
  echo "</head>";
  echo "<body class=\"bodybg\"{$params["body"]}>";
  echo"<table  width=\"700\" heigth =\"200\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
  echo "<tr align=\"center\"><td class=\"menu\">{$params["tituloPagina"]}</td></tr>";
  echo "<tr align=\"center\"><td valign='middle'>";
}

if(empty($_REQUEST["proximaQuestao"])){
$proximaQuestao=1;
}else{$proximaQuestao=$_REQUEST["proximaQuestao"];}

switch($_REQUEST["acao"]) {
	// aqui vai listar todas as provas que existem nessa turma
//=========================================================================================================
	case"":
		$params["tituloPagina"]="Prova";
		printHeader($params);
		echo"<table id='prova'>";
		echo"<tr align=\"center\"><td >";
		if($_SESSION["COD_AL"]=="")
		{
			echo "<p align=\"right\"><b><a href=\"indicadoresExerAluno.php\">| SCORE DOS ALUNOS |</a></b></p></td></tr>";
		}
    	echo"<tr align=\"center\"><td >";
		echo"<p><font color=\"red\">Abaixo estão listada(s)  a(s) Prova(s) Disponibiizada(s) pelos professores.</font></p></td></tr>";
		$provas=listaProvasInstancia();
		foreach($provas->records as $linhaP)
		{
			if($linhaP->aberto)
			{
				echo "<tr align=\"center\" ><td ><a href=\"".$_SERVER["PHP_SELF"]."?codProva=".$linhaP->codProva."&acao=paginaDeinicializacao\">".$linhaP->titulo."</a><td></tr>";
			}
			
		}
		echo "</td></table>";
		echo "</body>";
		echo "</html>";
	break;
//================================================================================================================
	case "paginaDeinicializacao":
	
	$params["tituloPagina"]="Abertura da Prova";
	printHeader($params);

		$ok=gethistorico($_REQUEST["codProva"]);
		if($ok)
		{
			echo "você já fez essa prova";
			die();
		}


	$prova=getProva($_REQUEST["codProva"]);

	$proximaQuestao=1;

		echo"<table id='prova' align='center'>";
		foreach($prova->records as $linhaP)
		{
			echo"<tr align=\"center\"><td  align=\"center\">";
			echo $linhaP->textoDeAbertura;
			echo"</td></tr>";
			
		}


echo "<tr align=\"center\"><td ><a href=\"".$_SERVER["PHP_SELF"]."?codProva=".$_REQUEST["codProva"]."&acao=sessionPerguntas\"><b>Iniciar o Exercício</b><br></a></td></tr>";
echo"</table>";
echo"</table>";
echo "</body>";
echo "</html>";




	break;
//================================================================================================================
	case "sessionPerguntas":
			
				$imprimiquestao=getTodasPerguntasPorProva($_REQUEST["codProva"]);
				
				
				//aqui nos vamos fazer um array colocando em SESSION os codigos das perguntas  
				$numeroDeQuestoesPorProva=0;
				foreach($imprimiquestao->records as $prova) {
					$numeroDeQuestoesPorProva=$numeroDeQuestoesPorProva+1;
					$_SESSION["PERGUNTA".$numeroDeQuestoesPorProva]=$prova->codPergunta;
					
					
				}


				
				
				$_SESSION["NumeroQuestoesPorProva"]=getNumeroPerguntasPorProva($_REQUEST["codProva"]);
					
		echo"<script>location.href='".$_SERVER["PHP_SELF"]."?codProva=".$_REQUEST["codProva"]."&acao=imprimiQuestoes';</script>";
	break;
//=================================================================================================================	
	// aqui vai listar o form da prova escolhida, */
	case"imprimiQuestoes":
		printHeader("");

		//vai vir a função que busca a pergunta 
				

				$pegaPergunta=getPergunta($_SESSION["PERGUNTA".$proximaQuestao]);
				$pegaResposta=getResposta($_SESSION["PERGUNTA".$proximaQuestao]);
				$PerguntaAtual=$proximaQuestao;
				

				//form da pergunta
			   foreach($pegaPergunta->records as $pegaPergunta){
          echo "<form name=\"form".$pegaPergunta->codPergunta."\" method=\"POST\" action=''>";
					
          
          echo "<table id='prova'><tr><td colspan='2' align=\"left\" class='titulo'><b>".$pegaPergunta->descPergunta."</b></tr></td>";           
         								
          
				foreach($pegaResposta->records as $resposta)
				{
  					echo "<tr><td  align=\"center\" valign='top'><input type=\"radio\" name=\"respostaAluno[]\" value=\"".$resposta->codResposta."\" ></td>";
  					echo "<td valign='top' style='text-align:left;'>".$resposta->descResposta."</td></tr>";
  				
  				}
					
				
					//vai primeiro para o verifica resultado da pergunta, depois volta para este case para a proxima pergunta.
				$proximaQuestao=$proximaQuestao+1;
				if(($_SESSION["NumeroQuestoesPorProva"])==($PerguntaAtual))
				{
			
				echo "<tr><td   colspan=\"2\" align=\"center\"><a href=\"#\"  onClick=\"if(confirm('deseja mesmo armazenar o resultado?')){ document.form".$pegaPergunta->codPergunta." .action='".$_SERVER["PHP_SELF"]."?codProva=".$_REQUEST["codProva"]."&perguntaAtual=".$PerguntaAtual."&acao=verificaResultado&codPergunta=".$_SESSION["PERGUNTA".$PerguntaAtual]."&proximaQuestao=".$proximaQuestao."';submit(); }\">Amazenar Resultado</a>";
				}else{	

					
					echo"<tr><td colspan='2'><br></td></tr>";

					echo"<tr><td colspan='2' align=\"center\"><div align='center'><a href='#' onclick=\"document.form".$pegaPergunta->codPergunta." .action='".$_SERVER["PHP_SELF"]."?codProva=".$_REQUEST["codProva"]."&perguntaAtual=".$PerguntaAtual."&acao=verificaResultado&codPergunta=".$_SESSION["PERGUNTA".$PerguntaAtual]."&proximaQuestao=".$proximaQuestao."'; submit();\" onMouseOver=\"window.status='Navi-Questoes'; return true;\">Próxima Questão</a></div></td></tr>";
				}
				
				 
				
				  echo "</table></form>";

			   }

			echo "</table>";  
	
	break;
//===============================================================================================================
	//guarda em sessão as respostas do aluno de cada questão da prova
	case "verificaResultado":

	if(!empty($_SESSION["COD_PESSOA"]))
	{		
			$_SESSION["COD_PROVA".$_REQUEST["codProva"]]["COD_PERGUNTA".$_REQUEST["codPergunta"]]=$_REQUEST["respostaAluno"][0];
		
			
	}


	if(($_SESSION["NumeroQuestoesPorProva"])==($_REQUEST["proximaQuestao"]-1))
	{
		echo"<script>location.href='".$_SERVER["PHP_SELF"]."?codProva=".$_REQUEST["codProva"]."&acao=armazenaResultado';</script>";
			
					
	}else{	
			echo"<script>location.href='".$_SERVER["PHP_SELF"]."?codProva=".$_REQUEST["codProva"]."&acao=imprimiQuestoes&proximaQuestao=".$_REQUEST["proximaQuestao"]."'; </script>";
		}

	break;


//================================================================================================================
	case "armazenaResultado":
		$params["tituloPagina"]="Resultado";
		printHeader($params);

	$ok=armazenaResultado($_REQUEST["codProva"]);

	if($ok)
	{
		echo "Você concluiu a prova, com sucesso";
	}

		
	break;
//================================================================================================================

	
}

?>
