<?
session_start();
//retorna a lista de provas para uma instancia
function listaProvasInstancia()
{

 $result = new RDCLQuery("SELECT P.codProva, P.titulo, P.aberto, P.textoDeAbertura  FROM prova_instancia P WHERE  P.codInstanciaGlobal=".$_SESSION["codInstanciaGlobal"]."  ORDER BY P.codProva");

 return $result;
}
//==================================================================================================
//retorna uma pergunta em especifico
function getPergunta($pegaPergunta)
{
	
 $result = new RDCLQuery("SELECT codPergunta, descPergunta FROM prova_pergunta  WHERE  codPergunta=".$pegaPergunta."");

 
return $result;
}
//==================================================================================================
//retorna as respostas para uma pergunta em especifico
function getResposta($pegaPergunta)
{
	
	 $result = new RDCLQuery("SELECT codResposta, descResposta, respostaCerta FROM prova_resposta WHERE  codPergunta=".$pegaPergunta."");

	 
return $result;
}
//==================================================================================================
//retorna todos os codPerguntas de uma prova 
function getTodasPerguntasPorProva($codProva)
{
	$result = new RDCLQuery("SELECT  codPergunta FROM prova WHERE codProva=".$codProva."");
	
	return $result;
}


//==================================================================================================
//armazena os resultados dos alunos para uma determinada prova
function armazenaResultado($codProva)
{
	$qtdAcertos=0;

	
	
	for($i=1;$i<=$_SESSION["NumeroQuestoesPorProva"];$i++)
	{
		
		$respostaCerta=getRespostaCerta($_SESSION["PERGUNTA".$i]);
		
		$respostaMarcada=$_SESSION["COD_PROVA".$codProva]["COD_PERGUNTA".$_SESSION["PERGUNTA".$i]];
	
		$respostasPessoa.="codPergunta=".$_SESSION["PERGUNTA".$i]."-codRespostaMarcada=".$respostaMarcada."-codRespostaCerta=".$respostaCerta.";";

		if($respostaCerta==$respostaMarcada)
		{
			$qtdAcertos=$qtdAcertos+1;
		}
	}


	$strSQL="INSERT INTO prova_pessoa_gabarito (respostasPessoa, codProva, codPessoa, qtdAcertos)".
			"VALUES ('".$respostasPessoa."',".$codProva.",".$_SESSION["COD_PESSOA"].",".$qtdAcertos.")";


mysql_query($strSQL);

return(!mysql_errno());
	
}
//==================================================================================================
//retorna os dados de uma prova em específico (dados reduntantes com listaProvasInstancia)
function getProva($codProva)
{
	$result = new RDCLQuery("SELECT P.codProva, P.titulo, P.aberto, P.textoDeAbertura  FROM prova_instancia P WHERE  P.codProva=".$codProva."  ORDER BY P.codProva");
 return $result;
}
//==================================================================================================
//retorna o numero(inteiro) de Perguntas em uma prova (não confundir com a função getTodasPerguntasPorProva que retorna os codPerguntas)
function getNumeroPerguntasPorProva($codProva)
{
	$result = new RDCLQuery("SELECT count(codPergunta) as NumeroQuestoesPorProva FROM prova WHERE codProva=".$codProva."");

foreach( $result->records as $Numero)
		{
			$NumeroPerguntasPorProva=$Numero->NumeroQuestoesPorProva;	
		}



 return $NumeroPerguntasPorProva;
}
//==================================================================================================
//rever se essa função é necessaria???? reduntante com getResposta
function getRespostaCerta($pegaPergunta)
{
	
	 $result = new RDCLQuery("SELECT codResposta, descResposta, respostaCerta FROM prova_resposta WHERE  codPergunta=".$pegaPergunta."");


		foreach( $result->records as $respostaCerta)
		{
				 if(!empty($respostaCerta->respostaCerta))
				{
					 $codResposta=$respostaCerta->codResposta;
				 }
		}

	 
return  $codResposta;
}
//==================================================================================================
//baaa retorna apenas se existe um histórioco da pessoa. poderia se chamar existehistorico, 
function gethistorico($codProva){
	 $strSQL="SELECT * FROM prova_pessoa_gabarito WHERE codProva=".$codProva." AND codPessoa=".$_SESSION["COD_PESSOA"];

$rsCon=mysql_query($strSQL);
$linha = mysql_fetch_array($rsCon);
if(!empty($linha["codPessoa"]))
{
	
	return 1;
}else return 0;
 
}
//===========================================================================================================
class questaoProva{

	var $codPergunta;
	var $codRespostaMarcada;
	var $codRespostaCerta;
	var $qtdAcertos;


	function QuestaoProva($codPergunta, $codRespostaMarcada, $codRespostaCerta, $aqtdAcertos){
		$this->codPergunta=$codPergunta;
		$this->codRespostaMarcada=$codRespostaMarcada;
		$this->codRespostaCerta=$codRespostaCerta;
		$this->qtdAcertos=$aqtdAcertos;
	}

	function getQuestaoProva(){
		return $this->codPergunta;
	}

	function getRespostaMarcada(){
		return $this->codRespostaMarcada;
	}
	
	function getRespostaCerta(){
		return $this->codRespostaCerta;
	}

	function getqtdAcertos(){
		return $this->qtdAcertos;
	}
}
/***********************************************************************************************/


//como criar uma classe prova que cria dinamicamente as questoes


function listaIndicadoresProvaAluno(){

$alunos= listaAlunos();
$numAcertosProva=getNumAcertosAlunoProva($_SESSION["codInstanciaGlobal"]);
$ListaProvaInstancia=getProvaInstancia($_SESSION["codInstanciaGlobal"]);

echo "<h4 class='menu'><center> Total de Acertos, de cada aluno, por Questões</center></h4>";
	
while( $ListaProva= mysql_fetch_array($ListaProvaInstancia))
{//listaprovas 

	$colspan=getNumeroPerguntasPorProva($ListaProva["codProva"]);
	$perguntasProva=getTodasPerguntasPorProvateste($ListaProva["codProva"]);

	echo "<table onclick=\"MenuAparecer('".$ListaProva["codProva"]."');\" align=\"center\" id='prova' >";
	echo "<TBODY><tr align=\"center\" class='titulo'><td  style='text-align:center;' colspan=".$colspan."><a href=\"#".$ListaProva["codProva"]."\">".$ListaProva["titulo"]."</a></td></tr></TBODY></table>";	
	
	echo "<div id='".$ListaProva["codProva"]."' style='DISPLAY: none'>";


	echo "<table id='prova'  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style='text-align:center;'>";
	echo "<tr class='titulo' ><td width=\"300\">Alunos</td>";
	$cont=1;
	while($pergunta= mysql_fetch_array($perguntasProva))
	{		
		echo "<td>Pergunta".$cont."</td>";
		$cont=$cont+1;
		
	}
	echo "<td>Acertos</td>";
	echo"</tr>";
	mysql_data_seek($perguntasProva,0);

			while($linhas = mysql_fetch_array($alunos))//corre alunos
			{
				
					echo "<tr ><td style='text-align:left;' width=\"300\">".$linhas["NOME_PESSOA"]."</td>";

						
						while($pergunta= mysql_fetch_array($perguntasProva))//corre perguntas
						{	
							$tmp=$pergunta["codPergunta"];
												
							if((isset($numAcertosProva[$linhas["COD_PESSOA"]][$ListaProva["codProva"]][$pergunta["codPergunta"]]->codRespostaMarcada))AND($numAcertosProva[$linhas["COD_PESSOA"]][$ListaProva["codProva"]][$pergunta["codPergunta"]]->codRespostaMarcada!==""))
							{	
								
									if($numAcertosProva[$linhas["COD_PESSOA"]][$ListaProva["codProva"]][$pergunta["codPergunta"]]->codRespostaMarcada===$numAcertosProva[$linhas["COD_PESSOA"]][$ListaProva["codProva"]][$pergunta["codPergunta"]]->codRespostaCerta)
									{
										//echo "<td><img src=\"certo.gif\"></td>";
										echo "<td>1</td>";
									}
									else
									{
										//echo "<td><img src=\"errado.gif\"></td>";
										echo "<td>0</td>";
									}
								
							}
							else
							{
								echo "<td>0</td>";
							}

						}
						mysql_data_seek($perguntasProva,0);
						
						
						echo"<td class='menu'>".$numAcertosProva[$linhas["COD_PESSOA"]][$ListaProva["codProva"]][$tmp]->qtdAcertos."</td></tr>";
						//&nbsp;
			}
							
			mysql_data_seek($alunos,0);
			echo"</table>";
			echo "</div>";
	}
}
//todas as provas por instancia
function getProvaInstancia($codIsntanciaGlobal){
	$sql="SELECT codInstanciaGlobal, codProva, titulo ".
	     " FROM prova_instancia ".
		" WHERE codInstanciaGlobal=".$codIsntanciaGlobal;


return mysql_query($sql);
}
//resultados dos alunos das provas por instancia
function getNumAcertosAlunoProva($codInstanciaGlobal){
$sql="SELECT PPG.codPessoa, PPG.qtdAcertos, PPG.codProva, PPG.respostasPessoa".
	 " FROM prova_pessoa_gabarito PPG, prova_instancia PT".
	 " WHERE PPG.codProva=PT.codProva  AND PT.codInstanciaGlobal=".$codInstanciaGlobal;

$result = mysql_query($sql);

while ($numExer = mysql_fetch_object($result)){
		
		$perguntas=explode(';',$numExer->respostasPessoa);
	
		
		for($i=0; $i<(count($perguntas)-1); $i++)
		{			
				
				$tmp=explode('-',$perguntas[$i]);
		
				for($j=0; $j<count($tmp);$j++)
				{
					$saida=explode('=',$tmp[$j]);
				
					$perguntas2[$saida[0]]=$saida[1];

			
				}
			
		$numAcertosProva[$numExer->codPessoa][$numExer->codProva][$perguntas2["codPergunta"]]= new questaoProva($perguntas2["codPergunta"],$perguntas2["codRespostaMarcada"],$perguntas2["codRespostaCerta"],$numExer->qtdAcertos);
		
		}
	}

	return  $numAcertosProva;
}

//teste
function getTodasPerguntasPorProvateste($codProva)
{
	$result = "SELECT  codPergunta FROM prova WHERE codProva=".$codProva;
	
	return mysql_query($result);
}
?>