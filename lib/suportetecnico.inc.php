<?
/*include("../config.php");*/

/*include($caminhoBiblioteca."/CLDb.inc.php");*/

function getChamadosSetor($codGrupo,$codPessoa,$estado) {

  $result = new RDCLQuery("SELECT * FROM chamados C INNER JOIN pessoa P ON (P.COD_PESSOA=C.codPessoa) where C.estado=".quote_smart($estado)." AND C.codGrupoAtual=".quote_smart($codGrupo)."ORDER BY C.dataAbertura");

  return $result;
}

/****************************************************************************************/
function inserirChamado($chamado, $codSetorAtual, $codInstanciaNivel) {
  if ($chamado!=""){
   $estado="1";
   //se já existe um chamado aberto para a turma em questão, recupera-se o codAtendente
   $sql = "SELECT * FROM chamados WHERE estado=1 AND codTurma = ".$codInstanciaNivel."LIMIT 1";
   $rsCon = mysql_query($sql);
   $linha = mysql_fetch_array($rsCon);
   $num = mysql_num_rows($rsCon);
   if ($num>0) {
    $codPessoaAtendente = $linha["codPessoaAtendente"];
   }
   else {
    $sql = "SELECT COD_PESSOA FROM `pessoa` WHERE COD_SETOR = ".$codSetorAtual." AND ativaSuporte = 1 ORDER BY rand( ) LIMIT 1";
    $rsCon = mysql_query($sql);
    $linha = mysql_fetch_array($rsCon);
    if ($linha["COD_PESSOA"]) { $codPessoaAtendente = $linha["COD_PESSOA"]; }
    else { $codPessoaAtendente = ""; } 
   }

   $strSQL = "INSERT INTO chamados (codPessoa,codSetorAtual,estado,chamado, dataFechamento, dataAbertura, codPessoaAtendente, codTurma)".
             " VALUES (".$_SESSION["COD_PESSOA"].", ".$codSetorAtual.",".$estado.",".quote_smart($chamado).", NULL, now(), ".$codPessoaAtendente.", ".$codInstanciaNivel.")";
   mysql_query($strSQL);
   $codChamado = mysql_insert_id();
   //busca cod_prof do atendente
   $sql = "SELECT COD_PROF FROM professor WHERE COD_PESSOA=".$linha["COD_PESSOA"];
   $rsCon = mysql_query($sql);
   $linha = mysql_fetch_array($rsCon);
   if ($linha["COD_PROF"]) {   
    //insere atendente na turma em questão
    $strSQL = "INSERT INTO professor_turma (COD_TURMA, COD_PROF, codTipoProfessor) VALUES (".$codInstanciaNivel.", ".$linha["COD_PROF"].", 9)";
	  mysql_query($strSQL);
	  }
	 notificaEmailSuporte($codChamado, 'novo');
  }
  return ;
}
/****************************************************************************************/
function ExcluirChamado($codChamado) {

  excluiAtendenteTurma($codChamado);
  $strSQL = "DELETE FROM chamados WHERE codChamado = " . $codChamado;
  mysql_query($strSQL);	
  return (! mysql_errno());
  
}
/****************************************************************************************/
function listaChamados() {
   
    $result = new RDCLQuery("SELECT C.codChamado,C.codSetorAtual,DATE_FORMAT(C.dataAbertura, '%d/%m/%Y %T') AS dataAbertura,C.estado, C.novidadeUser FROM chamados C WHERE C.codPessoa=".$_SESSION["COD_PESSOA"]."  ORDER BY C.dataAbertura");
    
  return $result;
}
/****************************************************************************************/
function recebeGrupoAtual(){
 
  $strSQL = "SELECT COD_SETOR FROM pessoa P where P.COD_PESSOA=".$_SESSION["COD_PESSOA"]."";
   return mysql_query($strSQL);
  
}
/****************************************************************************************/
function listaHistoricoChamados($codChamado){
	$result = new RDCLQuery("SELECT P.NOME_PESSOA,H.COD_CHAMADO,H.COD_PESSOA, DATE_FORMAT(DATA, '%d/%m/%Y %T') AS DATA,H.DESC_RESPOSTA,H.COD_SETOR FROM historico_chamados H,pessoa P WHERE P.COD_PESSOA=H.COD_PESSOA AND H.COD_CHAMADO=".$codChamado." ORDER BY H.DATA");

	
	return $result;
}
/****************************************************************************************/
Function inserirResposta($descResposta,$codChamado,$codSetor){
      if(!empty($descResposta)){
        sinalizaNovaInteracao($codChamado, 'nova');
        if (empty($codSetor)){
           $strSQL = "INSERT INTO historico_chamados (COD_PESSOA,DESC_RESPOSTA,COD_CHAMADO)".
                  " VALUES (".$_SESSION["COD_PESSOA"].",".quote_smart($descResposta).",".$codChamado.")";
        }
        else {
        
        $strSQL = "INSERT INTO historico_chamados (COD_PESSOA,COD_SETOR,DESC_RESPOSTA,COD_CHAMADO)".
                  " VALUES (".$_SESSION["COD_PESSOA"].",".$codSetor.",".quote_smart($descResposta).",".$codChamado.")";     
        }
     mysql_query($strSQL);
     
    notificaEmailSuporte($codChamado, 'resposta'); 
    }
	  
  return ;
			
}
/****************************************************************************************/

function updateEstado($codChamado,$estado){

 if (empty($estado)){
  $strSQL = "UPDATE chamados SET  estado=".$estado.", dataFechamento=now()" .
          " WHERE codChamado= ". $codChamado;
  excluiAtendenteTurma($codChamado); 
  notificaEmailSuporte($codChamado, 'fecha');        
 }
 if (!empty($estado)) {
  $sql = "SELECT * FROM chamados WHERE codChamado =".$codChamado;
  $rsCon = mysql_query($sql);
  $linha = mysql_fetch_array($rsCon);
  if($linha["estado"] != $estado) {
    $strSQL = "UPDATE chamados SET  estado=".$estado.", dataFechamento=NULL".
          " WHERE codChamado= ". $codChamado;    
    incluiAtendenteTurma($codChamado);
    if($_SESSION["COD_PESSOA"] == $linha["codPessoa"]) { notificaEmailSuporte($codChamado, 'reabre'); }
  }
 }
 mysql_query($strSQL);
 
 return (! mysql_errno());
}


/****************************************************************************************/
function listaChamadosAbertos(){
	 
	 $result = new RDCLQuery("SELECT C.codChamado,C.codSetorAtual,DATE_FORMAT(C.dataAbertura, '%d/%m/%Y %T') AS dataAbertura,C.estado,C.novidadeAtendente,P.COD_SETOR,P.COD_PESSOA, P.NOME_PESSOA FROM chamados C, pessoa P  WHERE C.estado=1 AND C.codSetorAtual=P.COD_SETOR AND P.COD_PESSOA=C.codPessoaAtendente AND C.codPessoaAtendente=".$_SESSION["COD_PESSOA"]." ORDER BY C.dataAbertura");
    	

  return $result;

}
/****************************************************************************************/
function listaChamadosFechados(){
	 
	 $result = new RDCLQuery("SELECT C.codChamado,C.codSetorAtual,DATE_FORMAT(C.dataAbertura, '%d/%m/%Y %T') AS dataAbertura,C.estado,P.COD_SETOR,P.COD_PESSOA FROM chamados C, pessoa P WHERE C.estado=0 AND C.codSetorAtual=P.COD_SETOR AND P.COD_PESSOA=C.codPessoaAtendente AND C.codPessoaAtendente=".$_SESSION["COD_PESSOA"]." ORDER BY C.dataAbertura");
    	

  return $result;

}
/****************************************************************************************/
function listaChamadosTodos($setores=""){
	 
	 $sql = "SELECT C.codChamado,DATE_FORMAT(C.dataAbertura, '%d/%m/%Y %T') AS dataAbertura,C.estado,P.NOME_PESSOA,C.codSetorAtual,C.novidadeAtendente FROM chamados C, pessoa P WHERE ";
          if(empty($setores))
          $sql.= " C.codSetorAtual=(SELECT COD_SETOR FROM pessoa WHERE COD_PESSOA = ".$_SESSION["COD_PESSOA"].") AND";
          $sql.= " P.COD_PESSOA=C.codPessoaAtendente ORDER BY C.dataAbertura";
  $result = new RDCLQuery($sql);
  return $result;

}
/****************************************************************************************/
function descobreSetor($codSetor){
  $strSQL = "SELECT DESC_SETOR, COD_SETOR FROM setor  WHERE COD_SETOR=".$codSetor."";
  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);

  return $linha["DESC_SETOR"];
  

}
/****************************************************************************************/
function imprimiChamado($codChamado){

	$result = new RDCLQuery("SELECT C.codSetorAtual,DATE_FORMAT(C.dataAbertura, '%d/%m/%Y %T') AS dataAbertura,DATE_FORMAT(C.dataFechamento, '%d/%m/%Y %T') AS dataFechamento,C.estado ,C.chamado, C.codTurma, C.codPessoaAtendente, P.NOME_PESSOA FROM chamados C, pessoa P WHERE C.codChamado=".$codChamado." AND C.codPessoa=P.COD_PESSOA");

  return $result;

}
/****************************************************************************************/
function listaSetores(){
	$result = new RDCLQuery("SELECT * FROM setor");
	return $result;
}
/****************************************************************************************/
function updateSetorAtual($codSetorAtual,$codChamado){

 $sql = "SELECT codSetorAtual, codTurma FROM chamados WHERE codChamado = ".$codChamado;
 $result = mysql_query($sql);
 $linha = mysql_fetch_array($result);

 if ($codSetorAtual != "" && $linha["codSetorAtual"] != $codSetorAtual) {
  //necessária a troca de atendente
  trocaAtendente($codChamado, $codSetorAtual, $linha["codTurma"]);
  $strSQL = "UPDATE chamados SET  codSetorAtual=".$codSetorAtual."".
			       " WHERE codChamado= ". $codChamado."";
	mysql_query($strSQL);
 }
	return (! mysql_errno());	
}
/****************************************************************************************/
function notificaEmailSuporte($codChamado, $tipoNotificacao) {
 ini_set("SMTP",SERVIDOR_SMTP);
 GLOBAL $url;

 $sql = "SELECT P1.COD_PESSOA AS 'cod_pessoa_user', P1.EMAIL_PESSOA AS 'mail_user', P1.NOME_PESSOA AS 'nome_user',". 
        " P2.COD_PESSOA AS 'cod_pessoa_atende', P2.EMAIL_PESSOA AS 'mail_atende', P2.NOME_PESSOA AS 'nome_atendente'".
        " FROM pessoa P1 , pessoa P2, chamados C".
        " WHERE C.codPessoa = P1.COD_PESSOA AND C.codPessoaAtendente = P2.COD_PESSOA".
        " AND C.codChamado = ".$codChamado;
 $rsCon = mysql_query($sql);
 $linha = mysql_fetch_array($rsCon);
 
 if($tipoNotificacao == 'novo' && $linha['mail_atende'] != '') {
  $assunto = "NAVi - Novo Chamado no Suporte Técnico";
  $body.=" Olá, " .$linha['nome_atendente'].",\n\n".
  "Existe um novo Chamado no Suporte Técnico atribuído para você.\n\n".
  "Código do chamado: ".$codChamado."\n\n";
  $email_pessoa = $linha['mail_atende'];
 }
 if($tipoNotificacao == "resposta") {
  if ($_SESSION["COD_PESSOA"] == $linha['cod_pessoa_user'] && $linha['mail_atende'] != '') {
    $email_pessoa = $linha['mail_atende']; 
    $nomePessoa = $linha['nome_atendente']; }
  if ($_SESSION["COD_PESSOA"] != $linha['cod_pessoa_user'] && $linha['mail_user'] != '') {
    $email_pessoa = $linha['mail_user']; 
    $nomePessoa = $linha['nome_user']; }
  $assunto = "NAVi - Nova Interação no Suporte Técnico";
  $body.=" Olá, " .$nomePessoa.",\n\n".
  "Uma nova interação foi adicionada ao Chamado aberto no Suporte Técnico.\n\n".
  "Código do chamado: ".$codChamado."\n\n";
 }
 if($tipoNotificacao == "reabre") {
  $email_pessoa = $linha['mail_atende']; 
  $nomePessoa = $linha['nome_atendente'];
  $assunto = "NAVi - Chamado Reaberto no Suporte Técnico";
  $body.=" Olá, " .$nomePessoa.",\n\n".
  "Um chamado foi reaberto pelo usuário no Suporte Técnico.\n\n".
  "Código do chamado: ".$codChamado."\n\n";
 }
  if($tipoNotificacao == "fecha" && $_SESSION["COD_PESSOA"] != $linha['cod_pessoa_user']) {
  $email_pessoa = $linha['mail_user']; 
  $nomePessoa = $linha['nome_user'];
  $assunto = "NAVi - Chamado fechado no Suporte Técnico";
  $body.=" Olá, " .$nomePessoa.",\n\n".
  "Seu chamado foi FECHADO por um atendente no Suporte Técnico.\n".
  "Caso seu problema ainda não tenha sido resolvido e/ou você queira voltar a interagir neste chamado, você pode optar por REABRÍ-LO.\n\n".
  "Código do chamado: ".$codChamado."\n\n";
 }

 $body.= "Acesse a Plataforma NAVi em: ".$url.
		  	 "\n\n\nAtenciosamente\n".
		  	 "Equipe NAVi";	

      @mail ($email_pessoa,
			$assunto, 
			$body, 
			"From:navi@ufrgs.br\r\n" . 
			"X-Mailer: PHP/". phpversion());
			
}
/****************************************************************************************/
function excluiAtendenteTurma($codChamado) {
  $strSQL = "SELECT * FROM `chamados` WHERE codTurma = (SELECT codTurma FROM chamados WHERE codChamado=".$codChamado.") AND codPessoaAtendente = (SELECT codPessoaAtendente FROM chamados WHERE codChamado=".$codChamado.")";
  $result = mysql_query($strSQL);
  $linha = mysql_fetch_array($result);
  $num_rows = mysql_num_rows($result);
  if ($num_rows == 1) {
    $sql = "DELETE FROM professor_turma WHERE COD_TURMA = ".$linha['codTurma']." AND COD_PROF = (SELECT COD_PROF FROM professor WHERE COD_PESSOA = ".$linha['codPessoaAtendente'].")";
    mysql_query($sql);
    //deletar registro na instancia inicial do atendente quando sua última visita ter sido a turma em questão
    $sql = "DELETE FROM instanciainicial WHERE codInstanciaNivel= ".$linha['codTurma']." AND codNivel=6 AND codPessoa=".$linha['codPessoaAtendente'];
    $result = mysql_query($sql);
  }
  return;  
}
/****************************************************************************************/
function incluiAtendenteTurma($codChamado) {
  $strSQL = "SELECT * FROM `professor_turma` WHERE cod_turma = (SELECT codTurma FROM chamados WHERE codChamado = ".$codChamado.") AND cod_prof = (SELECT cod_prof FROM professor WHERE cod_pessoa = (SELECT codPessoaAtendente FROM chamados WHERE codChamado = ".$codChamado."))";
  $result = mysql_query($strSQL);
  $linha = mysql_fetch_array($result);
  $num_rows = mysql_num_rows($result);
  if ($num_rows == 0) {
    $strSQL = "SELECT C.codTurma, C.codSetorAtual, C.codPessoaAtendente, P.COD_PROF FROM chamados C, professor P WHERE C.codPessoaAtendente = P.COD_PESSOA AND codChamado = ".$codChamado;
    $result = mysql_query($strSQL);
    $linha = mysql_fetch_array($result);
    $consulta = "SELECT * FROM pessoa WHERE COD_PESSOA = ".$linha["codPessoaAtendente"];
    $resulta = mysql_query($consulta);
    $pik = mysql_fetch_array($resulta);
    if ($pik["ativaSuporte"] == 1) {
      $sql = "INSERT INTO professor_turma (COD_TURMA, COD_PROF, codTipoProfessor) VALUES (".$linha["codTurma"].", ".$linha["COD_PROF"].", 9)";
      mysql_query($sql);
    }
    else {
      trocaAtendente($codChamado, $linha["codSetorAtual"], $linha["codTurma"]); 
    }
    
  }
  return;  
}
/****************************************************************************************/
function trocaAtendente($codChamado, $codSetorAtual, $codTurma) {

  excluiAtendenteTurma($codChamado);  //exclui da turma atendente anterior
  
  $sql = "SELECT COD_PESSOA FROM `pessoa` WHERE COD_SETOR = ".$codSetorAtual." AND ativaSuporte = 1 ORDER BY rand( ) LIMIT 1";
  $rsCon = mysql_query($sql);
  $linha = mysql_fetch_array($rsCon);
  if ($linha["COD_PESSOA"]) { $codPessoaAtendente = $linha["COD_PESSOA"]; }
  else { $codPessoaAtendente = ""; } 
  $strSQL = "UPDATE chamados SET codPessoaAtendente = ".$codPessoaAtendente." WHERE codChamado = ".$codChamado;
  mysql_query($strSQL);
  //busca cod_prof do novo atendente
  $sql = "SELECT COD_PROF FROM professor WHERE COD_PESSOA=".$linha["COD_PESSOA"];
  $rsCon = mysql_query($sql);
  $linha = mysql_fetch_array($rsCon);
  if ($linha["COD_PROF"]) {   
    //insere atendente na turma em questão
    $strSQL = "INSERT INTO professor_turma (COD_TURMA, COD_PROF, codTipoProfessor) VALUES (".$codTurma.", ".$linha["COD_PROF"].", 9)";
    mysql_query($strSQL);
	}
	notificaEmailSuporte($codChamado, 'novo');
  return;  
}
/****************************************************************************************/
function verificaAtendente($codPessoa) {
  $sql = "SELECT * FROM pessoa WHERE COD_PESSOA = ".$codPessoa;
  $rsCon = mysql_query($sql);
  $linha = mysql_fetch_array($rsCon);
  return $linha;
}
/****************************************************************************************/
function sinalizaNovaInteracao($codChamado, $acao) {
  $sql = "SELECT codPessoa AS 'user', codPessoaAtendente AS 'atendente' FROM chamados WHERE codChamado = ".$codChamado;
  $rsCon = mysql_query($sql); 
  $linha = mysql_fetch_array($rsCon);
  if ($linha["user"] == $_SESSION["COD_PESSOA"]) {
    if ($acao == 'nova') {
      $sql = "UPDATE chamados SET novidadeAtendente = 1 WHERE codChamado = ".$codChamado;
    }
    if ($acao == 'lida') {
      $sql = "UPDATE chamados SET novidadeUser = 0 WHERE codChamado = ".$codChamado;
    } 
    mysql_query($sql); 
  }
  if ($linha["user"] != $_SESSION["COD_PESSOA"]) {
    if ($acao == 'nova') {
      $sql = "UPDATE chamados SET novidadeUser = 1 WHERE codChamado = ".$codChamado;
    }
    if ($acao == 'lida') {
      $sql = "UPDATE chamados SET novidadeAtendente = 0 WHERE codChamado = ".$codChamado;
    } 
    mysql_query($sql); 
  }
}
/****************************************************************************************/
function afastamentoAtendente($codPessoa) {
  $sql = "UPDATE pessoa SET ativaSuporte = 0 WHERE COD_PESSOA = ".$codPessoa; 
  mysql_query($sql);
  
  $result = new RDCLQuery("SELECT * FROM chamados WHERE estado=1 AND codPessoaAtendente = ".$codPessoa);
  foreach($result->records as $linha) {
    trocaAtendente($linha->codChamado, $linha->codSetorAtual, $linha->codTurma);  
  }
}
/****************************************************************************************/
function retornoAtendente($codPessoa) {
  $sql = "UPDATE pessoa SET ativaSuporte = 1 WHERE COD_PESSOA = ".$codPessoa; 
  mysql_query($sql);
}
?>
