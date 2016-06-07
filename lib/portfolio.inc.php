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

ini_set("display_errors",0);
error_reporting(E_ALL ^ E_NOTICE);

/**********************************************
última alteração: Gisele Bonapaz da Silva
data:            14/11/2006
motivo:			  -acrescentar links nos arquivos apontando
				  para ferramentas de gerência, para o aluno
				  alterar e excluir os portfólios
				  -mostrar o numero total de portfólios enviados pelo aluno
descrição:
                  -incluida a função ExAltPortDentroInst(),
				   essa função contém a opção de alterar e excluir o arquivo;
				  -pequena alteração na função salvaArquivoPortifolio();
				   acrescentando nela a função PortNovoLeitura()
				  -incluida função numeroDePortfolios()
				   retorna um inteiro contendo a quantidade de portfólios 
				   enviados pelo aluno.
***********************************************/
/** SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA **/
//==========================================**===================**=======================================
function portifolio($cod_aluno)// ****** EM CONSTRUÇ+O ******
//											**					 **												
{	 
	$nivel = getNivelAtual();
	
	//tabela de relacionamento aluno
	$tblRel = Aluno::getTabelaRelacionamento($nivel);
	//campo chave de relacionamento aluno
	$pkRel = Aluno::getPKRelacionamento($nivel);
	$nivelPK = $nivel->nomeFisicoPK;
	$codInstanciaNivel = getCodInstanciaNivelAtual();
	if ($cod_aluno == "")
	  {
			 $strSQL = "SELECT A.".$pkRel.", A.COD_PESSOA, P.NOME_PESSOA".
			  " FROM aluno A, pessoa P, {$tblRel} AT".
			  " WHERE AT.".$pkRel."=A.".$pkRel." AND AT.".$nivelPK."= '" . $codInstanciaNivel . "'".
			  " AND A.COD_PESSOA= P.COD_PESSOA GROUP BY AT.{$pkRel} ORDER BY P.NOME_PESSOA";
			 
	  }
  else{
    $strSQL = "SELECT  AAT.COD_ARQUIVO, AAT.COD_ALUNO, AAT.COD_TIPO_CASO, AAT.COD_ALUNO_ARQUIVO,  AAT.DESC_ARQUIVO_INSTANCIA, P.NOME_PESSOA, DATE_FORMAT(AAT.DT_CADASTRO, '%d/%m/%Y %H:%i:%S') AS DT_CADASTRO".
			  " FROM arquivo_aluno_instancia AAT, pessoa P, aluno A".
			  " WHERE AAT.COD_ALUNO= '" . $cod_aluno . "' AND AAT.COD_INSTANCIA_GLOBAL= '" . $_SESSION["codInstanciaGlobal"] . "'".
			  " AND A.COD_PESSOA=P.COD_PESSOA AND A.COD_AL= '" . $cod_aluno . "'".
			  " ORDER BY COD_ARQUIVO";
    
			  
	}
  return mysql_query($strSQL);			
}
//======================================================================================================
// biblioteca/pdf.asp; biblioteca/topo.asp
// tools/acervo_operacao.asp
function portifoliolista($cod_arquivo)
{
  if ($cod_arquivo == "")
    $strSQL = "SELECT COD_ARQUIVO, DESC_ARQUIVO_INSTANCIA FROM arquivo_aluno_instancia WHERE COD_INSTANCIA_GLOBAL=" . $_SESSION["codInstanciaGlobal"] . " ORDER BY DESC_ARQUIVO_INSTANCIA";
  else
    $strSQL = "SELECT AAT.COD_ARQUIVO, AAT.DESC_ARQUIVO_INSTANCIA, A.DESC_ARQUIVO,AAT.COD_AL,".
      " A.CAMINHO_LOCAL_ARQUIVO, A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO ".
      " FROM arquivo A, arquivo_aluno_instancia AAT WHERE AAT.COD_INSTANCIA_GLOBAL = ".$_SESSION["codInstanciaGlobal"].
      " AND A.COD_ARQUIVO = AAT.COD_ARQUIVO ".
      " AND A.COD_ARQUIVO =". $cod_arquivo;
  return mysql_query($strSQL);
}
//======================================================================================================
// portifolio/portifolio_mostrar.asp
// portifolio alunos
function portifolioComentario($cod)
{
  $strSQL = "SELECT PC.codArquivoComentario, PC.COD_ALUNO_ARQUIVO , PC.COD_COMENTARIO, PC.TEXTO, DATE_FORMAT(DATA, '%d/%m/%Y %T') AS DATA_MODIFICADA, P.NOME_PESSOA, P.COD_PESSOA".
    " FROM portifolio_comentario PC, pessoa P".
    " WHERE PC.COD_PESSOA = P.COD_PESSOA AND  PC.COD_ALUNO_ARQUIVO ='". $cod."'";
			   
  return mysql_query($strSQL);
}
//*************************************************************************************************
function getArquivoComentario($codAlunoArquivo){
	$sql="SELECT A.COD_ARQUIVO, A.DESC_ARQUIVO, PC.COD_COMENTARIO  FROM arquivo A ".
		 " INNER JOIN portifolio_comentario PC ON (PC.codArquivoComentario=A.COD_ARQUIVO)".
		" WHERE  PC.COD_ALUNO_ARQUIVO ='". $codAlunoArquivo."'";
$rsCon=mysql_query($sql);
while($linha=mysql_fetch_array($rsCon)){
	$array[$linha["COD_COMENTARIO"]]["COD_ARQUIVO"]=$linha["COD_ARQUIVO"];
	$array[$linha["COD_COMENTARIO"]]["DESC_ARQUIVO"]=$linha["DESC_ARQUIVO"];
}
return $array;
}
//**************************************************************************************************
function portComUsuario($cod_aluno_arquivo)
{
	$strSQL="SELECT AAI.DESC_ARQUIVO_INSTANCIA, P.NOME_PESSOA ".
			" FROM arquivo_aluno_instancia AAI, pessoa P, aluno A".
			" WHERE AAI.COD_ALUNO=A.COD_AL AND A.COD_PESSOA=P.COD_PESSOA AND AAI.COD_ALUNO_ARQUIVO=".$cod_aluno_arquivo;
	
	return mysql_query($strSQL);
}
//======================================================================================================
//PORTIFOLIO/portifolio_comentario_envia.php
// portifolio Alunos
function portEnviaCom($cod, $text,$codAl)
{
global $caminhoUpload;
  $codArquivo="";
  $desc_arquivo = str_replace("\n", "<br>", $_REQUEST["DESC_ARQUIVO"]);
  $sql="SELECT COD_PESSOA FROM aluno WHERE COD_AL= ".$codAl;
  $rsCon=mysql_query($sql);
  $codPessoaAluno= mysql_fetch_array($rsCon);
 
   
  
    
    if (verificaArquivoValido()) {
  
      $CAMINHO = $caminhoUpload."/portfolio/". confNum($codPessoaAluno["COD_PESSOA"]) . "/comentarios/"; 
      $erro = false;
     
      //cria o diretorio se necessario
	  if (! file_exists($CAMINHO))
			$erro = !mkdir($CAMINHO,0777);	
	  
           //comentario concatenado com o codAlunoArquivo 
		   $nome=substr($_FILES["ARQUIVO"]["name"],0,-4);
		   $nome=$nome."0000";
  		   $ext=substr($_FILES["ARQUIVO"]["name"],-4);
           $nomeArquivo="COMENTARIO_COD_ALUNO_ARQUIVO_".confNum($cod)."_".$nome.$ext;
	  if (file_exists($CAMINHO .$nomeArquivo))
	  {
		  $nome=substr($nomeArquivo,0,-4);
  		  $ext=substr($nomeArquivo,-4);
		  $nome++;
          $nomeArquivo=$nome.$ext;
			
	  }
      if (!$erro) {
        //faz o upload do arquivo
        $erro = !move_uploaded_file($_FILES["ARQUIVO"]["tmp_name"], $CAMINHO .$nomeArquivo); 
        if ($erro) {
					echo "<br><br> Erro de upload. ";
				}
				else {
				  //insere no banco de dados
				//******** FTP**************
					$CAMINHO = $caminhoUpload."/portfolio/".  confNum($codPessoaAluno["COD_PESSOA"]) . "/comentarios/";
					duplica($CAMINHO .$nomeArquivo, $nomeArquivo, "portfolio/". confNum($codPessoaAluno["COD_PESSOA"])."/comentarios" );
					//******* END FTP***********
  				$CAMINHO = "/portfolio/". confNum($codPessoaAluno["COD_PESSOA"]) . "/comentarios/";
  				//$CAMINHO = str_replace("\\", "\\\\", $CAMINHO);
  				$erro = !PortInsere($desc_arquivo, $CAMINHO .$nomeArquivo, $_FILES["ARQUIVO"]["size"], $_FILES["ARQUIVO"]["type"]);
				
				 if ($erro) {
				echo " ERRO na Inserção";
				 }		
				else {
				 //agora publica na turma atual            
				$codArquivo = mysql_insert_id();
				}  
		}
	  }
	}
  $strSQL = "INSERT INTO portifolio_comentario(COD_ALUNO_ARQUIVO, COD_PESSOA, TEXTO,codArquivoComentario)" .
    " VALUES ('". $cod ."' , '" . $_SESSION["COD_PESSOA"] . "' , " .quote_smart($text). ",'".$codArquivo."' )";


  mysql_query($strSQL);		
	
  return (! mysql_errno());
}
//======================================================================================================
//usada no portifolio -ferramentas
function listaPortAdm($codInstanciaGlobal, $local, $quem)
{
  $strSQL = "SELECT DISTINCT A.DESC_ARQUIVO, A.COD_ARQUIVO";
  if ($local == "instanciaAtual")
    {
      $strSQL .= " FROM arquivo A, arquivo_aluno_instancia AAT WHERE A.COD_ARQUIVO = AAT.COD_ARQUIVO";
		
      if ($codInstanciaGlobal != "")
	$strSQL .= " AND AAT.COD_INSTANCIA_GLOBAL = '" . $codInstanciaGlobal . "'";
    }
  if ($local == "nenhum")
    {
      $rsCon = mysql_query("SELECT AAT.COD_ARQUIVO FROM arquivo_aluno_instancia AAT");
		
      $strSQL .= " FROM arquivo A WHERE A.COD_ARQUIVO NOT IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_ARQUIVO"] . ",";
		
      $strSQL .= "0)";
    }
  if ($local == "algo")
    {
      $rsCon = mysql_query("SELECT AAT.COD_ARQUIVO FROM arquivo_aluno_instancia AAT");
		
      $strSQL .= " FROM arquivo A WHERE A.COD_ARQUIVO IN (";
		
      while ($linha = mysql_fetch_array($rsCon))
	$strSQL .= $linha["COD_ARQUIVO"] . ",";
		
      $strSQL .= "0)";	
    }
  if ($local == ""){
    //echo $local."portaluno";
    $strSQL .= " FROM arquivo A, arquivo_aluno_instancia AAT WHERE A.COD_ARQUIVO=AAT.COD_ARQUIVO";}
  if ($quem != "")
	 
    $strSQL = "Select * FROM arquivo A  WHERE  A.COD_PESSOA = '". $quem ."'";
//from arqivo-aluno-turma	A.COD_ARQUIVO=AAT.COD_ARQUIVO condição retirada para listar todos os arquivos daquela pessao independente de estar em uma turma ou não	
  $strSQL .= " ORDER BY A.COD_ARQUIVO";
  return mysql_query($strSQL);					
}
//============================================================================================================================
// usada  em portifolio tools//poertifolio_operacao
//falta fazer...
function PortVerificaAcesso($cod_arquivo)
{
	//($_SESSION['userRole']!=ALUNO) AND ($_SESSION['userRole']!=ADMINISTRADOR_GERAL)
  $permite = false;
		
  if ($_SESSION['userRole']==ADMINISTRADOR_GERAL)
    $permite = true;
	
  if ( ( $_SESSION['userRole']==ALUNO ) and (! $permite) )
    {
      // Verifica se o arquivo é dele
		
      $strSQL = "SELECT COD_ARQUIVO FROM arquivo".
	" WHERE COD_ARQUIVO = '". $cod_arquivo ."'".
	" AND COD_PESSOA = '". $_SESSION["COD_PESSOA"] ."'";
				  
      $rsCon = mysql_query($strSQL);
		
      if ($rsCon)
	{
	  if ($linha = mysql_fetch_array($rsCon))
	    $permite = true;
	}
    }
	
  if ( ( $_SESSION['userRole']==ALUNO) and (! $permite) )
    {
      // Verifica se o arquivo é de alguma turma que ele é aluno
			$strSQL = "SELECT AAI.COD_ARQUIVO FROM arquivo_aluno_instancia AAI WHERE AAI.COD_ARQUIVO=".quote_smart($cod_arquivo).
					" AND AAI.COD_INSTANCIA_GLOBAL=".$_SESSION["codInstanciaGlobal"];
      $rsCon = mysql_query($strSQL);
      if ($rsCon)
	{
	  if ($linha = mysql_fetch_array($rsCon))
	    $permite = true;
	}
    }
	
	
  return $permite;
}
//======================================================================================================================
//portifolio tools // portifolio_envio
function PortAltera($cod_arquivo, $desc_arquivo, $caminho, $tamanho, $tipo)
{
  $strSQL = "UPDATE arquivo SET " .
    "CAMINHO_LOCAL_ARQUIVO = '" . $caminho . "', " .
    "DESC_ARQUIVO = '" . $desc_arquivo . "', " .
    "TAMANHO_ARQUIVO = '" . $tamanho . "', " .
    "TIPO_ARQUIVO = '" . $tipo . "', " .
    "COD_PESSOA = " . $_SESSION["COD_PESSOA"] .
    " WHERE COD_ARQUIVO = " . $cod_arquivo; 
	
  mysql_query($strSQL);
				 
  return (! mysql_errno());
}
//============================================================================================================================
//portifolio tools /portifolio_envio
function PortInsere($desc_arquivo, $caminho, $tamanho, $tipo)
{
  $strSQL = "INSERT INTO arquivo (COD_PESSOA, CAMINHO_LOCAL_ARQUIVO, TAMANHO_ARQUIVO, TIPO_ARQUIVO, DESC_ARQUIVO) " .
    " VALUES (". $_SESSION["COD_PESSOA"] .",'". $caminho . "','". $tamanho ."','". $tipo ."','". $desc_arquivo ."')";
  mysql_query($strSQL);
	
  return (! mysql_errno());
}
//=====================================================================================================================
function listaPort($cod_arquivo, $codInstanciaGlobal,$tipo_caso,$codPessoa='')
{
  if ($codInstanciaGlobal != "")
    {	
      $strSQL = "SELECT A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO, A.DESC_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.COD_ARQUIVO, AAT.COD_TIPO_CASO, AAT.DESC_ARQUIVO_INSTANCIA" . 		
	" FROM arquivo A, arquivo_aluno_instancia AAT" .
	" WHERE A.COD_ARQUIVO = AAT.COD_ARQUIVO" .
	" AND AAT.COD_ARQUIVO = " . quote_smart($cod_arquivo);
      if ($cod_arquivo != "")
	$strSQL .= " AND A.COD_ARQUIVO =" . quote_smart($cod_arquivo);
      else
	{		
	  if($tipo_caso!= "")
	    $strSQL .= " AND (AAT.COD_TIPO_CASO =" . quote_smart($tipo_caso) . "  )";
				
	}
				
      $strSQL .= " ORDER BY A.COD_ARQUIVO DESC";
    }
  else
    {   
      $strSQL = "SELECT A.TIPO_ARQUIVO, A.TAMANHO_ARQUIVO, A.DESC_ARQUIVO, A.CAMINHO_LOCAL_ARQUIVO, A.COD_ARQUIVO".
	" FROM arquivo A" .
	" WHERE A.COD_ARQUIVO = " . quote_smart($cod_arquivo);
    if (!empty($codPessoa)) { $strSQL.="  AND A.COD_PESSOA=".quote_smart($codPessoa); }	
    }
  //echo $strSQL; die;
  return mysql_query($strSQL);					
}
//=========================================================================================================================
function PortLocalInsere($cod_arquivo, $inst, $tipo_caso, $desc_arquivo)
{    
  $strSQL= "SELECT AAT.COD_ARQUIVO FROM arquivo_aluno_instancia AAT WHERE AAT.COD_ARQUIVO=".$cod_arquivo;
  mysql_query($strSQL);
	
  if((mysql_errno())=="0"){
		
    $strSQL= "INSERT INTO arquivo_aluno_instancia (COD_ARQUIVO, COD_INSTANCIA_GLOBAL, COD_ALUNO, COD_TIPO_CASO, DESC_ARQUIVO_INSTANCIA) " .
      " VALUES (". $cod_arquivo .",". $inst .", '".$_SESSION["COD_AL"]."', ".$tipo_caso.", '". $desc_arquivo . "')";
    mysql_query($strSQL);
    return (!mysql_errno());
  }
	
}
// ========================================================================================================================
/*function listaPortLocal($cod_arquivo)
{
  $strSQL = "SELECT DISCIPLINA.COD_CURSO, ARQUIVO_ALUNO_TURMA.COD_TURMA, TURMA.COD_DIS, ARQUIVO_ALUNO_TURMA.COD_TIPO_CASO AS TIPO_CASO, ARQUIVO_ALUNO_TURMA.DESC_ARQUIVO_TURMA FROM ARQUIVO_ALUNO_TURMA , TURMA, DISCIPLINA " .
    " WHERE DISCIPLINA.COD_DIS = TURMA.COD_DIS AND TURMA.COD_TURMA = ARQUIVO_ALUNO_TURMA.COD_TURMA AND COD_ARQUIVO = ". $cod_arquivo .
    " ORDER BY ARQUIVO_ALUNO_TURMA.COD_TURMA";
	
  return mysql_query($strSQL);					
}*/
//lista os locais em q determinado arquivo do portifolio esta publicado
function listaPortLocal($codArquivo) {
	$sql = "SELECT COD_ARQUIVO, ig.codInstanciaGlobal, ig.codNivel, ig.codInstanciaNivel,DESC_ARQUIVO_INSTANCIA ".
	       " FROM arquivo_aluno_instancia as f".
				 " INNER JOIN instanciaglobal AS ig ON (f.COD_INSTANCIA_GLOBAL = ig.codInstanciaGlobal)".
				 " WHERE f.COD_ARQUIVO = ".quote_smart($codArquivo);
	
	return mysql_query($sql);
}
//======================================================================================================================
function PortLocalAltera($cod_arquivo, $inst, $desc_arquivo_inst, $tipo_caso, $tipo_caso_novo)
{
  $strSQL = "UPDATE arquivo_aluno_instancia SET COD_TIPO_CASO =".quote_smart($tipo_caso_novo).", DESC_ARQUIVO_INSTANCIA = ". quote_smart($desc_arquivo_inst).
    " WHERE COD_ARQUIVO = ". quote_smart($cod_arquivo) ." AND COD_TIPO_CASO=".quote_smart($tipo_caso)." AND  COD_INSTANCIA_GLOBAL=". quote_smart($inst);
  mysql_query($strSQL);	
	
  return (! mysql_errno());
}
//======================================================================================================
function PortLocalRemove($cod_arquivo, $inst, $tipo_caso)
{
  $strSQL = "DELETE FROM arquivo_aluno_instancia WHERE COD_ARQUIVO = ". $cod_arquivo ." AND COD_INSTANCIA_GLOBAL=". $inst . " AND COD_TIPO_CASO=". $tipo_caso;
  mysql_query($strSQL);
  	
  return (! mysql_errno());
}
/**
 * Mostra o formulario de publicacao de portifolio
 */ 
function mostraFormPublicaPortifolio($codArq='') {
	
	echo '<form name="form1" method="post" enctype="multipart/form-data" action="'.$_SERVER['PHP_SELF'].'?acao=A_publica_arquivo&COD_AL='.$_SESSION['COD_AL'].'">',
        
		'<fieldset >',
        '<legend>Publicar novo arquivo no portf&oacute;lio</legend>',
		'<table>',
			 '<tr>',
			  '<td align=left width="60%">',				
				'<p><b> Descrição do Arquivo: </b><br>',
			  '<input type="text" name="DESC_ARQUIVO" value="" size="80">',
        '</p></td></tr>',
				"<tr><td><b>Tipo Caso :</b><br><select name=\"TIPO_CASO\">\n";
				if (permiteArquivoGeral($_SESSION['codInstanciaGlobal'])) {
					 echo "<option value=1>GERAL</option>\n";
				}
        if(permiteArquivoParticular($_SESSION['codInstanciaGlobal'])){
          echo "<option value=2>PARTICULAR</option>\n";
        }
   echo"</select></td></tr>\n",
			  "<tr><td><p><b> Endereço do Arquivo: </b><br> <input type=\"file\" name=\"ARQUIVO\" size=\"60\"> </p>",
        '</td></tr>',
		    '<tr>',
			  '<td align="center">',
				'<input type="button" name="Submit" value="Enviar" onclick="enviaForm();">',
//				'<input type="button" name="voltar" value="Voltar" onclick="window.location.href = \'portifolio.php\';">',
        '</td></tr>',
		'</table>';
		        
        if (!empty($codArq))
				  echo '<input type="hidden" name="COD_ARQUIVO" value="'.$codArq.'">';
		
        echo '</fieldset>',
             '</form>';
	   
}
/**
 * Verifica se o arquivo enviado eh valido
 */ 
function verificaArquivoValido() {
	$erro = false;
	
	if ( $_FILES["ARQUIVO"]["size"] > TAMANHO_MAXIMO_ARQUIVO  )  // 1.000.000 = 1 Mega
	{	echo "<br><br> Arquivo com tamanho maior que o permitido de ".(TAMANHO_MAXIMO_ARQUIVO/1000000)." Mb.";
		$erro = true;
	}
	if ( (! $erro) and ($_FILES["ARQUIVO"]["size"] == 0) ) 
	{	//echo "<br><br> Arquivo não recebido.";
		$erro = true;
	}
		 
	if ((! $erro) and (! is_uploaded_file($_FILES["ARQUIVO"]["tmp_name"])))
	{	echo "<br><br> Não foi feito upload do arquivo.";
		$erro = true;					
	 }
  return !$erro;
}
/**
 * Publica/salva arquivo no portifolio
 */ 
function salvaArquivoPortifolio($codArq='') {
  global $caminhoUpload;
	$desc_arquivo = str_replace("\n", "<br>", $_REQUEST["DESC_ARQUIVO"]);
  if (empty($codArq)) { 
    //publica novo arquivo no portifolio
    
    if (verificaArquivoValido()) {
      $CAMINHO = $caminhoUpload."/portfolio/". confNum($_SESSION["COD_PESSOA"]) . "/";
      $erro = false;
      
      //cria o diretorio se necessario
			if (! file_exists($CAMINHO))
				mkdir($CAMINHO,0777);		
           
			if (file_exists($CAMINHO . $_FILES["ARQUIVO"]["name"]))
			{
				echo "<b>Já existe um arquivo com esse nome.<br>Para colocá-lo nesta instância vá em:<br> Ferramentas de Gerência<br>Portfólio/editar<br>Seus arquivos</b>";
				$erro = true;
			}
      if (!$erro) {
        //faz o upload do arquivo
		
        $erro = !move_uploaded_file($_FILES["ARQUIVO"]["tmp_name"], $CAMINHO . $_FILES["ARQUIVO"]["name"]); 
        if ($erro) {
					echo "<br><br> Erro de upload. ";
				}
				else {
					//******** FTP**************
					$CAMINHO = $caminhoUpload."/portfolio/". confNum($_SESSION["COD_PESSOA"]) . "/";
          
					duplica($CAMINHO .$_FILES["ARQUIVO"]["name"], $_FILES["ARQUIVO"]["name"], "portfolio/". confNum($_SESSION["COD_PESSOA"]) );
					//******* END FTP***********
				  //insere no banco de dados
  				$CAMINHO = "/portfolio/". confNum($_SESSION["COD_PESSOA"]) . "/";
  		
  				$erro = !PortInsere($desc_arquivo, $CAMINHO . $_FILES["ARQUIVO"]["name"], $_FILES["ARQUIVO"]["size"], $_FILES["ARQUIVO"]["type"]);
				  
          if ($erro) {
            echo " ERRO na Inserção";
          }		
          else {
            //agora publica na turma atual            
            $codArquivo = mysql_insert_id();   
			
           $erro = !PortLocalInsere($codArquivo, $_SESSION["codInstanciaGlobal"], $_REQUEST["TIPO_CASO"], $desc_arquivo);          
            
            if ($erro) {
              echo '<br>Erro ao salvar na turma atual.';
            }
      			else {
      				$professoresTurma = listaProfessores();
              
      				while($linha = mysql_fetch_array($professoresTurma))				{
      					$retNovo = PortNovoLeitura($codArquivo, $linha["COD_PROF"],true);
      					if(!$retNovo) 					{
      						print "erro ao atualizar portfolio! ".mysql_error();
      						die();
      					}
      				 }  
      			}
          }
        }				
      }
    }
  }
  else {
    //alteracao de um arquivo previamente publicado
    $codArq = (int) $codArq;
  }
  
  return !$erro;
}
function permiteArquivoGeral($codInstanciaGlobal) {
  
  $retorno=1;
  $sql = "Select permiteArquivoGeral from configuracaoportfolio where codInstanciaGlobal=".quote_smart($codInstanciaGlobal);
  $result = mysql_query($sql);
  $linha = mysql_fetch_assoc($result);
  if (!empty($linha)) {
    $retorno = $linha['permiteArquivoGeral'];
  }
  
  return $retorno;
}
function permiteArquivoParticular($codInstanciaGlobal) {
  
  $retorno=1;
  $sql = "Select permiteArquivoParticular from configuracaoportfolio where codInstanciaGlobal=".quote_smart($codInstanciaGlobal);
  $result = mysql_query($sql);
  $linha = mysql_fetch_assoc($result);
  if (!empty($linha)) {
    $retorno = $linha['permiteArquivoParticular'];
  }
  
  return $retorno;
}
function PortNovoLeitura($codArquivo, $codProfessor, $dt_cadastro=false){
  if( $codArquivo == "" OR 
      $codProfessor == ""
    ){
      print "erro ao atualizar portfolio!";
      die();
    }
  $sqlCons = "select * from arquivo_lido_professor where codArquivo = ".$codArquivo." and codProfessor = ".$codProfessor.";";  
  
  $retSqlCons = mysql_query($sqlCons);
  $numRowsSqlCons = mysql_num_rows($retSqlCons);
  
  if($numRowsSqlCons == 0){    
    if($dt_cadastro){
      //ATUALIZA A DATA DE PUBLICACAO DO ARQUIVO
      $sql  = "update arquivo_aluno_instancia set DT_CADASTRO = NOW() where COD_ARQUIVO = ".$codArquivo.";";
      mysql_query($sql);
    } 
    
    $sql = "insert into arquivo_lido_professor(codArquivo, codProfessor) values(".$codArquivo.",".$codProfessor.");";
    $result = mysql_query($sql);
  }else{
    print "o portf&oacute;lio j&aacute; est&aacute; marcado como nao lido!<br>";
    die();
  }
  
  return $result;
}
/****************************************/
function PortDelLeitura($codArquivo, $codProfessor=""){
  
  if($codArquivo == ""){
      print "erro ao remover portfolio!";
      die();
  }
    
  $sql = "delete from arquivo_lido_professor where codArquivo = ".$codArquivo;
  
  if($codProfessor != "")
    $sql  .=  " and codProfessor = ".$codProfessor;
  
  $sql  .=  ";";
  $result = mysql_query($sql);
  
  if(!$result){
    print "erro ao remover leitura de portfolio.<br>";
  }
  return $result;
}
/*****************************************/
function PortStatus($codProfessor="",$codArquivo="",$codAluno="",$opcao="")
{	 
 
  //conta a quantidade de portifolios para o prof ou para aluno
  if($opcao == "qtde"){
    
        $strSQL .=  "SELECT   count(*) as quantidade          ";
        $strSQL .=  "FROM                                     ";
        $strSQL .=  "      arquivo_aluno_instancia AAT,       "; 
        $strSQL .=  "      arquivo_lido_professor ALP         ";
        $strSQL .=  "WHERE                                    ";
        $strSQL .=  "    AAT.COD_ARQUIVO = ALP.codArquivo     ";
        $strSQL .=  " AND AAT.COD_INSTANCIA_GLOBAL = '".$_SESSION["codInstanciaGlobal"]."'";
        $strSQL .=  " AND ALP.codProfessor = '".$codProfessor."' ";
          if($codAluno != "")
          $strSQL .=  " AND AAT.COD_ALUNO = '".$codAluno."' ";
          
          if($codArquivo != "")
          $strSQL .= "  AND   AAT.COD_ARQUIVO           = '".$codArquivo."' ";
        
        //print $strSQL."<br><br>";
        
        $sqlResult = mysql_query($strSQL); 
        $sqlReturn = mysql_fetch_array($sqlResult);
        return     $sqlReturn["quantidade"];      
  }
  else if($opcao == "detail"){      
      
        $strSQL .= "  SELECT  AAT.COD_ARQUIVO,                                       ";
        $strSQL .= "          AAT.COD_ALUNO,                                         ";
        $strSQL .= "          AAT.COD_TIPO_CASO,                                     ";
        $strSQL .= "          AAT.COD_ALUNO_ARQUIVO,                                 ";
        $strSQL .= "          AAT.DESC_ARQUIVO_INSTANCIA                             ";
        $strSQL .= "  FROM                                                           ";   
        $strSQL .= "          arquivo_aluno_instancia AAT,                           ";
        $strSQL .= "          arquivo_lido_professor  ALP                            ";
        $strSQL .= "  WHERE                                                          ";
        $strSQL .= "        AAT.COD_ARQUIVO           = ALP.codArquivo               ";
        $strSQL .= "  AND   AAT.COD_ARQUIVO           = '".$codArquivo."' ";
        $strSQL .= "  AND   AAT.COD_INSTANCIA_GLOBAL  = '".$_SESSION["codInstanciaGlobal"] . "'";
        $strSQL .= "  AND   ALP.codProfessor          = '".$codProfessor."' ";
        $strSQL .= "  ORDER                                        ";
        $strSQL .= "          BY COD_ARQUIVO                       ";
       
        return mysql_query($strSQL);
   }         
}
//===============================================================================================================
function numeroComentario($codAlunoArquivo)
{
	$strSQL= "SELECT count(*) AS numeroComentario FROM portifolio_comentario ".
			 "WHERE COD_ALUNO_ARQUIVO=".$codAlunoArquivo;
	
	$NComen= mysql_query($strSQL);
	$NComen= mysql_fetch_array($NComen);
	
	return $NComen["numeroComentario"];
}
//===============================================================================================================
function ExAltPortDentroInst($codArquivo)
{
	
					
					echo "	<td align=\"center\" nowrap>\n".
						 "		<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir este arquivo ?')) { window.open('../tools/portifolio_envio.php?COD_AL=".$_SESSION['COD_AL']."&PAGINA=instancia&OPCAO=Remover&COD_ARQUIVO=" .$codArquivo . "','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">\n".
						 "			<img src=\"../imagens/remove.gif\" border=0 alt=\"Remover\">\n".
						 "		</a>\n&nbsp;&nbsp;&nbsp;\n".
						 "		<a href=\"../tools/portifolio_operacao.php?COD_AL=".$_SESSION['COD_AL']."&PAGINA=instancia&OPCAO=Alterar&COD_ARQUIVO=".$codArquivo."\">\n".
						 "			<img src=\"../imagens/edita.gif\" border=0 alt=\"Alterar\">\n".
						 "		</a>".
						 "	</td>\n";
					
		
}
//================================================================================================================
function numeroDePortfolios($codAluno, $codInstanciaGlobal)
{
	$strSQL= " SELECT count(*) as numeroDePortfolios FROM arquivo_aluno_instancia".
			 " WHERE COD_ALUNO=".$codAluno." AND COD_INSTANCIA_GLOBAL=".$codInstanciaGlobal;
$NPort= mysql_query($strSQL);
$NPort= mysql_fetch_array($NPort);
return $NPort["numeroDePortfolios"];
}
/**
 * Mostra o formulario para o Administrado/Professor para possibilidade de liberar GERAL/PARTICULAR 
 */ 
function mostraFormParticularGeralPortifolio() {
	global $url;
	
  $ok = verificaPermissaoParticularGeral($_SESSION['codInstanciaGlobal']);
   
  echo "Controle da permissão de publicação e visualização de portfólios";
  echo "<span><img src='".$url."/imagens/diminui.gif' onClick=\"mostraForm('AlteraGeralParticular'); mudaFigura(this,'".$url."')\" id=\"imagem\"></span>";
  //echo "<span><img src='".$url."/imagens/aumenta.gif' onClick=\"mudaFigura(this,".$url.")\" id=\"imagem\"></span></legend>";
  echo "<div id=\"AlteraGeralParticular\" style=\"display:inline;\">";	 
  echo "<form name=\"particularGeral\" method=\"POST\" action=\"".$_SERVER['PHP_SELF']."?acao=A_particularGeral\" style=\"border:1px #000000 solid;\">".
       "<br><b>Liberar alternativa de Geral/Particular para os arquivos dos alunos no Portifólio</b>".
			 "<br><br><input type=\"checkbox\" name=\"permiteArquivoGeral\" value=\"1\""; 
  if($ok['permiteArquivoGeral']) {echo "checked"; }
  echo "> Liberar a opção da publicação GERAL: todos alunos e professores veêm o arquivo";
  
  echo "<br><input type=\"checkbox\" name=\"permiteArquivoParticular\" value=\"1\"";
  if($ok['permiteArquivoParticular']) {echo "checked";}
  echo "> Liberar a opção da publicação PARTICULAR: apenas o aluno que postou o arquivo e professores ";
  echo "<br><br><center><input type=\"button\" name=\"Submit\" value=\"Enviar\" onclick=\"enviaFormParticularGeral();\"></center>";
  echo "</form></div>";
	   
}
/**
 * Função que ira Permitir Arquivo GERAL OU PARTICULAR
 */ 
function alteraParticularGeralPortfolio($permiteArquivoGeral,$permiteArquivoParticular,$codInstanciaGlobal){

   if (empty($permiteArquivoGeral)) {
       $permiteArquivoGeral="0";
       if(empty($permiteArquivoParticular)){
         echo "As duas opções não podem ser nulas ";
         return false;
       }
   }
   if (empty($permiteArquivoParticular)){
     $permiteArquivoParticular="0";
      if(empty($permiteArquivoGeral)){
         echo "As duas opções não podem ser nulas ";
         return false;
      }
   }
  $ok = verificaPermissaoParticularGeral($codInstanciaGlobal);
  if(empty($ok)){
      $strSQL = "INSERT INTO configuracaoportfolio (codInstanciaGlobal, permiteArquivoGeral, permiteArquivoParticular)" .
                " VALUES ('". $codInstanciaGlobal ."' , '" . $permiteArquivoGeral . "' , '" . $permiteArquivoParticular . "' )";
	    mysql_query($strSQL);		
	    echo"Modificação realizada com sucesso";
      return (! mysql_errno());
  }
  else{
     $strSQL = "UPDATE configuracaoportfolio SET  permiteArquivoGeral=".$permiteArquivoGeral.", permiteArquivoParticular=".$permiteArquivoParticular."".
               " WHERE codInstanciaGlobal= ". $codInstanciaGlobal;
     mysql_query($strSQL);		
	    echo"Modificação realizada com sucesso";
     return (! mysql_errno());
  }
}
/**
*Função q mostra as opções para publicar os arquivos no portfolio GERAL/PARTICULAR
*/
function verificaPermissaoParticularGeral ($codInstanciaGlobal){
  $strSQL = "SELECT codInstanciaGlobal, permiteArquivoGeral,permiteArquivoParticular  FROM configuracaoportfolio WHERE codInstanciaGlobal= '".$codInstanciaGlobal ."'";
  $rsCon= mysql_query($strSQL);
 
  return mysql_fetch_array($rsCon);
}
?>
