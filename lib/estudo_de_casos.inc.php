<?

/** FUNCOES PARA MANIPULACAO DE CASOS
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA
    
    Funcoes : 
    - caso
    - casoAcesso
    - casoAutores
    - casoAltera
    - casoApaga
    - casoComentario
    - casoEnvia
    - casoEnviaCom
    - casoLista
    - listaCaso

    Duvidas : pq casoLita e listaCaso???

**/
/*defines*/
$campo[1]="Título";
$campo[2]="Diagnóstico";
$campo[3]="Objetivos";
$campo[4]="Metas e Indicadores";
$campo[5]="Equipe";
$campo[6]="Orçamento";
$campo[7]="Cronograma";
$campo[8]="Metodologia e Estratégia de Implementação";

$name[1]="TITULO";
$name[2]="DIAGNOSTICO";
$name[3]="OBJETIVOS";
$name[4]="METAS";
$name[5]="EQUIPE";
$name[6]="ORCAMENTO";
$name[7]="CRONOGRAMA";
$name[8]="METODOLOGIA";



//======================================================================================================
// casos/lista.asp; casos/casos_mostrar.asp
// Estudo de casos

function caso($cod)
{
  $strSQL = "SELECT * FROM estudo_de_caso WHERE COD_CASO = ". $cod; 
  return mysql_query($strSQL);	
}

//======================================================================================================
// casos/casos_mostrar.asp
// Estudo de casos

function casoAcesso($verifica_autor, $cod_caso)
{
  // Verifica se o caso que se quer esta acessando é da mesma turma onde a pessoa esta no momento
  if ($verifica_autor == "") 
    {
      $strSQL = "SELECT COD_CASO FROM estudo_de_caso WHERE COD_CASO=". $cod_caso ." AND codInstanciaGlobal=". $_SESSION['codInstanciaGlobal'];
      $rsCon  = mysql_query($strSQL);
			
      if ($rsCon and (mysql_num_rows($rsCon) > 0) )
	return true;
      else
	return false;
    }
  else
    {
      // Verifica se a pessoa que quer esta acessando é um dos autores da noticia
      if ($_SESSION["COD_PESSOA"] == "")
	return false;
      else
	{
	  $strSQL = "SELECT COD_CASO FROM estudo_de_caso_autor WHERE COD_CASO=". $cod_caso ." AND COD_PESSOA=". $_SESSION["COD_PESSOA"];
	  $rsCon  = mysql_query($strSQL);
				
	  if ($rsCon and (mysql_num_rows($rsCon) > 0) )
	    return true;
	  else
	    return false;
	}
    }
}

//======================================================================================================
// casos/casos_mostrar.asp
// Estudo de casos

function casoAutores($cod)
{
  $strSQL = "SELECT P.NOME_PESSOA, P.COD_PESSOA".
    " FROM estudo_de_caso C, estudo_de_caso_autor CA, pessoa P".
    " WHERE P.COD_PESSOA = CA.COD_PESSOA AND C.COD_CASO = CA.COD_CASO".
    " AND C.COD_CASO = ". $cod;
			 
  return mysql_query($strSQL);	
}

//======================================================================================================
// casos/alterar.asp
// Estudo de casos

function casoAltera($cod_caso, $autor, $titulo, $diagnostico, $objetivos, $metas, $equipe, $orcamento, $cronograma , $metodologia,$emConstrucao)
{
  $strSQL = "DELETE FROM estudo_de_caso_autor WHERE cod_caso =" . $cod_caso;
	
  $rsCon  = mysql_query($strSQL);
				
  if (! mysql_errno())
    {
      $strSQL = "UPDATE estudo_de_caso SET codInstanciaGlobal=". $_SESSION['codInstanciaGlobal'].", ";

	  if(!empty($titulo)){
		  $strSQL .= "titulo='". $titulo ."' , ";
	  }
	  if(!empty($diagnostico)){
		   $strSQL .="diagnostico='". $diagnostico ."', ";
	  }
	  if(!empty($objetivos)){
		   $strSQL .="objetivos='". $objetivos ."' , ";
	  }
	  if(!empty($metas)){
		   $strSQL .="metas='". $metas ."', ";
	  }
	  if(!empty($equipe)){
		   $strSQL .="equipe='". $equipe ."', ";
	  }
	  if(!empty($orcamento)){
		   $strSQL .="orcamento='". $orcamento ."', ";
	  }
	  if(!empty($cronograma)){
		   $strSQL .="cronograma='". $cronograma ."', ";
	  }
	  if(!empty($metodologia)){
		   $strSQL .="metodologia='". $metodologia ."', ";
	  }

	 $strSQL .="emConstrucao='".$emConstrucao."' WHERE cod_caso=" . $cod_caso;	

      $rsCon  = mysql_query($strSQL);
		
      if (! mysql_errno())
	{
	  $erro  = false;
				
	  for ($i=0; $i < count($autor); $i++)
	    {
	      $strSQL = "INSERT INTO estudo_de_caso_autor (COD_CASO, COD_PESSOA) VALUES (". $cod_caso .",". $autor[$i] . ")";
	      $rsCon  = mysql_query($strSQL);
		
	      if (mysql_errno())
		{
		  $erro = true;
		  break;
		}
	    }
				
	  if ($erro)
	    return -1;
	  else
	    return $cod_caso;
	}
      else 
	return -1;
    }
  else 
    return -1;
}

//======================================================================================================
// casos/casos_apagar.asp
// Estudo de casos

function casoApaga($cod)	
{
  if ($cod != "")
    {
      $strSQL = "SELECT C.COD_CASO FROM estudo_de_caso C, estudo_de_caso_autor CA WHERE C.COD_CASO = CA.COD_CASO AND C.COD_CASO=". $cod ." AND CA.COD_PESSOA='". $_SESSION["COD_PESSOA"] . "'";
      $rsCon  = mysql_query($strSQL);
		
      if (! $rsCon)
	return false;
      else
	{
	  $strSQL = "DELETE FROM estudo_de_caso WHERE COD_CASO=". $cod; 
	  return mysql_query($strSQL);
	}
    }
  else
    return false;
}
 
//======================================================================================================
// casos/casos_mostrar.asp
// Estudo de casos

function casoComentario($cod) {
  $strSQL = "SELECT CM.COD_CASO , CM.COD_COM, CM.TEXTO, DATE_FORMAT(DATA, '%d/%m/%Y') AS DATA_MODIFICADA, P.NOME_PESSOA, P.COD_PESSOA".
    " FROM estudo_de_caso_comentario CM, pessoa P".
    " WHERE CM.COD_PESSOA = P.COD_PESSOA AND CM.COD_CASO = ". $cod; 
 
  return mysql_query($strSQL);
}

//======================================================================================================
// casos/casos_enviar.asp
// Estudo de casos

function casoEnvia($autor, $titulo, $diagnostico, $objetivos, $metas, $equipe, $orcamento, $cronograma , $metodologia, $emConstrucao)
{
	$strSQL = "INSERT INTO estudo_de_caso (codInstanciaGlobal, titulo, diagnostico, objetivos, metas, equipe, orcamento, cronograma , metodologia, emConstrucao)" .
    " VALUES (" . $_SESSION['codInstanciaGlobal'] . ", '". $titulo ."','". $diagnostico ."','". $objetivos ."','". $metas ."','". $equipe ."','". $orcamento ."','". $cronograma ."','". $metodologia ."','". $emConstrucao."')";

	mysql_query($strSQL);
	$last_id=mysql_insert_id();
	if ($last_id==0)
		return -2;
	else
	{
		$erro  = false;
		for ($i=0; $i < count($autor); $i++)
	    {
			$strSQL = "INSERT INTO estudo_de_caso_autor (COD_CASO, COD_PESSOA) VALUES (". $last_id .",". $autor[$i] . ")";
			mysql_query($strSQL);
				
			if (mysql_errno())
			{
				echo "ERRO na Inserção dos Autores<br> <a href=\"javascript:history.back()\">Voltar</a>";
				$erro = true;
				break;
			}
	    }
			
	  if ($erro)
	    return -3;
	  else
	    return $last_id;
	}
 
}

//======================================================================================================
// casos/envia_com.asp
// Estudo de casos

function casoEnviaCom($cod, $text)
{
  $strSQL = "INSERT INTO estudo_de_caso_comentario (COD_CASO, COD_PESSOA, TEXTO)" .
    " VALUES (". $cod ."," . $_SESSION["COD_PESSOA"] . ", '" . $text . "')";
	
  mysql_query($strSQL);		
	
  return (! mysql_errno());
}

//======================================================================================================
// casos/casos_listar.asp;
// Estudo de casos

function casoLista($order)
{

  // Lista os estudos de caso pelo nome das pessoas
  if ($order == "nome")
    {
      $strSQL = "SELECT CA.COD_PESSOA, C.emConstrucao, C.COD_CASO, C.TITULO, P.NOME_PESSOA".
	" FROM  pessoa P, estudo_de_caso C".
	" LEFT JOIN".
	" estudo_de_caso_autor CA".
	" ON (C.COD_CASO = CA.COD_CASO)".
	" WHERE C.codInstanciaGlobal = '" . $_SESSION['codInstanciaGlobal'] . "' AND CA.COD_PESSOA = P.COD_PESSOA AND C.COD_CASO IS NOT NULL ORDER BY P.NOME_PESSOA";
    }				 
  else
    {
      // Lista os estudos de caso pelo seu titulo
      //		$strSQL = "SELECT CA2.COD_PESSOA AS COD_MEU_CASO, CA.COD_PESSOA, C.COD_CASO, C.TITULO, P.NOME_PESSOA".
      //				  " FROM CASO C".
      //				  " LEFT JOIN".
      //						" PESSOA P, CASO_AUTOR CA".
      //				  " ON C.COD_CASO = CA.COD_CASO".
      //				  " LEFT JOIN CASO_AUTOR CA2".
      //				  " ON CA2.COD_CASO = CA.COD_CASO AND CA2.COD_PESSOA =". $_SESSION["COD_PESSOA"].
      //				  " WHERE C.codInstanciaGlobal = '" . $_SESSION['codInstanciaGlobal'] . "' AND CA.COD_PESSOA = P.COD_PESSOA AND C.COD_CASO IS NOT NULL ORDER BY C.COD_CASO DESC";
      $strSQL = "SELECT CA.COD_PESSOA, C.emConstrucao, C.COD_CASO, C.TITULO, P.NOME_PESSOA".
	" FROM pessoa P,estudo_de_caso C".
	" LEFT JOIN".
	" estudo_de_caso_autor CA".
	" ON (C.COD_CASO = CA.COD_CASO)".
	" WHERE C.codInstanciaGlobal = '" . $_SESSION['codInstanciaGlobal'] . "' AND CA.COD_PESSOA = P.COD_PESSOA AND C.COD_CASO IS NOT NULL ORDER BY C.COD_CASO DESC";

    }

  return mysql_query($strSQL);		
}


function listaCaso($order)
{
  if ($order == "nome")
    $strSQL = "SELECT C.COD_CASO, C.TITULO, P.NOME_PESSOA FROM estudo_de_caso C, estudo_de_caso_autor CA, aluno A, aluno_turma AT, pessoa P WHERE A.COD_PESSOA = P.COD_PESSOA AND A.COD_AL = AT.COD_AL AND A.COD_AL = CA.COD_AL AND C.COD_CASO = CA.COD_CASO AND AT.codInstanciaGlobal = '". $_SESSION['codInstanciaGlobal'] ."' ORDER BY P.NOME_PESSOA";
  else
    {
      $strSQL = "SELECT C.COD_CASO  FROM estudo_de_caso C, estudo_de_caso_autor CA, ALUNO A, aluno_turma AT, pessoa P WHERE A.COD_PESSOA = P.COD_PESSOA AND A.COD_AL = AT.COD_AL AND A.COD_AL = CA.COD_AL AND AT.codInstanciaGlobal = '". $_SESSION['codInstanciaGlobal'] ."' group BY C.COD_CASO";
      $rsCon  = mysql_query($strSQL);
			
      $casos = "(";
			
      while ($linha = mysql_fetch_array($rsCon))
	$casos = $casos . $linha["COD_CASO"] . ",";
			
      $strSQL = "SELECT C.COD_CASO, C.TITULO, P.NOME_PESSOA  FROM estudo_de_caso C, estudo_de_caso_autor CA, aluno A, pessoa P WHERE A.COD_PESSOA = P.COD_PESSOA AND C.COD_CASO IN ". $casos ."0) AND A.COD_AL = CA.COD_AL AND C.COD_CASO = CA.COD_CASO ".
	" ORDER BY C.TITULO";
    }

  return mysql_query($strSQL);
}
 //==================================================================================================
 
/*funcoes para configuração dos  campos do estudo de caso
	As funções getConfiguracoesCampo, insereConfiguracaoCampo, atualizaConfiguracaoCampo,modificaConfiguracaoCampo são utilizada na configuração dos campos do estudo de caso, página configuracao_campos_casos.php, em que o professor pode escolher o nome dos campos do estudo de caso. Caso haja alguma configuração prévia por parte do professor os nomes padrão são trocados por esta configuração, caso contrário são mostrado os campos habituais, além disso o professor também pode escolher se o campo pode ser mostrado ou não*/

function getConfiguracoesCampo($codInstanciaGlobal) {

  $result = mysql_query("SELECT * FROM configuracaocampoestudocaso C where C.codInstanciaGlobal={$codInstanciaGlobal} ORDER BY C.codCampo");

  while ($linha = mysql_fetch_object($result) ){
    $config[$linha->codCampo] = $linha;
  }
  return $config;
}

//===================================================================================================
function insereConfiguracaoCampo($codInstanciaGlobal,$codCampo,$texto,$aparece="") {
  if((empty($aparece))AND($codCampo!=1)){ $aparece=0;}else{$aparece=1;}
	
  
  $strSQL = "INSERT INTO configuracaocampoestudocaso (codInstanciaGlobal,codCampo,titulo,aparece) VALUES (".$codInstanciaGlobal.",".$codCampo.",".quote_smart($texto).",".$aparece.") ";

   mysql_query($strSQL);

  return (! mysql_errno());
}
//===================================================================================================
function atualizaConfiguracaoCampo($codInstanciaGlobal,$codCampo,$texto,$aparece) {
	if($codCampo==1){$aparece=1;}
   $strSQL = "UPDATE  configuracaocampoestudocaso set titulo=".quote_smart($texto).",aparece=".quote_smart($aparece);
  $strSQL .= " WHERE codInstanciaGlobal=".quote_smart($codInstanciaGlobal). " AND codCampo=".$codCampo;

  mysql_query($strSQL);

  return (! mysql_errno());
}
//===================================================================================================
function modificaConfiguracaoCampo($codInstanciaGlobal,$codCampo,$texto,$aparece){

$result = mysql_query("SELECT * FROM configuracaocampoestudocaso C where C.codInstanciaGlobal=".$codInstanciaGlobal." AND C.codCampo=".$codCampo." ORDER BY C.codCampo");

	
if(mysql_num_rows($result)>0){if(atualizaConfiguracaoCampo($codInstanciaGlobal,$codCampo,$texto,$aparece))return 1; }
else{if(insereConfiguracaoCampo($codInstanciaGlobal,$codCampo,$texto,$aparece))return 1;}
}
//===================================================================================================
function exclueConfiguracaoCampo($codInstanciaGlobal,$codCampo){

$result = mysql_query("DELETE FROM configuracaocampoestudocaso WHERE codCampo={$codCampo} AND codInstanciaGlobal={$codInstanciaGlobal}");

return  (! mysql_errno());
}
//===================================================================================================
function getEstudodeCasosVistoPeloProfessor($codCaso){

	$strSQL="SELECT * FROM estudo_de_caso_visto_professor WHERE codCaso=".$codCaso;
	$rsCon= mysql_query($strSQL);
	
	while ($linha = mysql_fetch_object($rsCon) ){
		$getVisto[$linha->codCampo] = $linha;
		$getVisto[$linha->codCampo]->visto='disabled';
		$getVisto[$linha->codCampo]->style='border: 1px solid #e0e0e0;';
		$getVisto[$linha->codCampo]->frase='Este campo já foi visto pelo professor e você não poderá alterá-lo.';
	}

	return $getVisto;
}
//===================================================================================================

function insereVistoDadoPeloProfessor($codCaso,$codCampo){
	

		$sql="INSERT INTO estudo_de_caso_visto_professor (codCaso,codCampo) VALUES(".$codCaso.",".$codCampo.")";

	
	mysql_query($sql);
		
	return  (! mysql_errno());
}
//===============================================================================================================
function retiraVistoDadoPeloProfessor ($codCaso,$codCampo){
	
$sql="DELETE FROM estudo_de_caso_visto_professor WHERE codCampo=".$codCampo." AND codCaso=".$codCaso;
	
	 mysql_query($sql);

	 return  (! mysql_errno());
}
?>
