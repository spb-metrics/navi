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

/** FUNCOES PARA AUTENTICACAO
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA 
    
    Funcoes : 
    - verificaAdm
    - verificaAluno
    - verificaProf
    - verificaUsuario
    - listaAcesso
    - nivelAcesso
    - nivelAcessoFuturo
**/

//======================================================================================================	
// logon.asp
// Verifica se a pessoa é um administrador e cria a variavel de sessao COD_ADM

function verificaAdm()
{
  $strSQL = "SELECT * FROM administrador AD WHERE AD.COD_PESSOA = '" . $_SESSION["COD_PESSOA"] . "'";
  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);
		
  if ($linha)
    $_SESSION["COD_ADM"] = $linha["COD_ADM"];
}

//======================================================================================================
// logon.asp
// Verifica se a pessoa é um aluno e cria a variavel de sessao COD_AL

function verificaAluno() {
  $strSQL = "SELECT * FROM aluno AL WHERE AL.COD_PESSOA = '" . $_SESSION["COD_PESSOA"] . "'";
  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);
		
  if ($linha)
    $_SESSION["COD_AL"] = $linha["COD_AL"];
}

//======================================================================================================
// logon.asp
// Verifica se a pessoa é um professor e cria a variavel de sessao COD_PROF

function verificaProf()
{
  $strSQL = "SELECT * FROM professor PR WHERE PR.COD_PESSOA = '" . $_SESSION["COD_PESSOA"] . "'";
  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);
		
  if ($linha)
    $_SESSION["COD_PROF"] = $linha["COD_PROF"];
}
 

//======================================================================================================
// existeUsuario
// @param $user, $senha
// Recebe nome de usuario e verifica se o usuario existe 

// Em caso de necessidade específica, diferente da UFRGS, pode-se estender 
// a classe DefaultPage e sobrescrever o método DefaultPage->initCaixaLogin() 
//
// ja coloca o ultimo acesso da pessoa na sessao para que ela possa vê-lo
function existeUsuario($user, $logaUltimoAcesso = 1) {
  $strSQL = "SELECT vinculoUfrgs,ultimoAcesso,USER_PESSOA,COD_PESSOA,NOME_PESSOA,EMAIL_PESSOA,SENHA_PESSOA,ativa FROM pessoa PE WHERE PE.USER_PESSOA = ".quote_smart($user);

  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);
  
  if ($logaUltimoAcesso && !empty($linha['ultimoAcesso'])) { $_SESSION['ultimoAcesso'] = $linha['ultimoAcesso']; }
  
  return $linha;
}
//======================================================================================================
// principal_cursos.asp; curso.asp; curso_disciplina.asp; disciplina.asp
// tools/index.asp; tools/noticias.asp; tools/noticias_local.asp; tools/acervo.asp; tools/apoio.asp
// cadastro/frm_insere_cadastro.asp

// Retorna um ResultSet
// caso  1 - INSCRICAO
// caso  2 - VISITANTE E ADM GERAL          - mostra apenas os cod_curso_origem que tem pelo menos um cod_curso
// caso  3 - ALUNO E PROFESSOR              - não agrupa por curso
// caso  4 - ADM CURSO E PROFESSOR
// caso  5 - ADM CURSO						
// caso  6 - ALUNO E PROFESSOR E ADM CURSO  - não agrupa por curso
// caso  7 - PROFESSOR
// caso  8 - ADM CURSO E PROFESSOR 		   - não agrupa por curso
// caso  9 - PROFESSOR					   - não agrupa por curso
// caso 10 - VISITANTE E ADM GERAL          - Agrupa por cod_curso_origem
// caso 11 - VISITANTE E ADM GERAL          - mostra todos cod_curso_origem tendo cod_curso ou nao
// caso 12 - VISITANTE E ADM GERAL		   - devolve dados do cod_curso ou cod_curso_origem (passados por cod_dis)
// caso 13 - ADM GERAL					   - retorna consualtas ate o nivel de turma
// caso 14 - Igual ao 5 mas mantem as entradas NULL
// caso 15 - especial para ADM GERAL

function listaAcesso($caso, $cod_curso, $cod_dis, $cod_turma)
{
  $strSQL = "";

  if ($caso >=3 AND $caso <=9) {
    //casos 3..9
    
    $soma = $eAluno = $eProf = $eAdm = 0;

    //caso 3,6,7,9
    if ($_SESSION["COD_AL"] != "" AND ($caso == 3 OR $caso == 6 OR $caso == 7 OR $caso == 9) ) {
      $soma = $soma + 1;
      $eAluno = 1;
    }

    //caso 3,4,6,7,8,9
    if ($_SESSION["COD_PROF"] <> "" AND ($caso != 5)) {
      $soma = $soma + 1;
      $eProf = 1;
    }
      
    if ($_SESSION["COD_ADM"] <> ""  AND ($caso == 4 OR $caso == 5 OR $caso == 6 OR $caso == 8 )) {
      $soma = $soma + 1;
      $eAdm = 1;
    }
      
    if ($soma != 0) {
	
      $strSQL = "SELECT T.COD_TURMA, T.NOME_TURMA, D.COD_DIS, D.DESC_DIS, C.COD_CURSO ,C.DESC_CURSO, C.ABREV_CURSO, CO.DESC_CURSO_ORIGEM, CO.COD_CURSO_ORIGEM";
	
      if ($eAluno)
	$strSQL .= ", AT.COD_AL";
	
      if ($eProf)
	$strSQL .= ", PT.COD_PROF";
	
      if ($eAdm)
	$strSQL .= ", ADMC.COD_ADM";
	
      $strSQL .= " FROM turma T, disciplina D, curso C, curso_origem CO";
	
      if ($eAluno)
	$strSQL .= " LEFT JOIN aluno_turma AT     ON T.COD_TURMA = AT.COD_TURMA AND AT.COD_AL = '". $_SESSION["COD_AL"] ."'";
	
      if ($eProf)
	$strSQL .= " LEFT JOIN professor_turma PT ON T.COD_TURMA = PT.COD_TURMA AND PT.COD_PROF = '". $_SESSION["COD_PROF"] ."'";
	
      if ($eAdm)
	$strSQL .= " LEFT JOIN administrador_curso ADMC ON C.COD_CURSO = ADMC.COD_CURSO AND ADMC.COD_ADM = '". $_SESSION["COD_ADM"] ."'";			
	
      $strSQL .= " WHERE T.COD_DIS = D.COD_DIS AND D.COD_CURSO = C.COD_CURSO AND C.COD_CURSO_ORIGEM = CO.COD_CURSO_ORIGEM";
	
      $soma_atual = $soma;					
	
      if ($eAluno)
	{
	  $strSQL .= " AND (AT.COD_AL IS NOT NULL ";
	  $soma_atual = $soma_atual - 1;
	}
	
      if ($eProf)
	{
	  if ($soma_atual != $soma)
	    $strSQL .= "OR";
	  else
	    $strSQL .= " AND (";
	    
	  $strSQL .= " PT.COD_PROF IS NOT NULL ";
	  $soma_atual = $soma_atual - 1;
	}
	
      if ($eAdm)
	{
	  if ($soma_atual != $soma)
	    $strSQL .= "OR";
	  else
	    $strSQL .= " AND (";
	    
	  $strSQL .= " ADMC.COD_ADM IS NOT NULL ";
	  $soma_atual = $soma_atual - 1;
	}
	
      if ( $soma_atual != $soma )
	$strSQL .= " ) ";
	
	
      if ($cod_curso != "")	$strSQL .= " AND C.COD_CURSO='". $cod_curso ."'";
      if ($cod_dis   != "")	$strSQL .= " AND D.COD_DIS  ='". $cod_dis   ."'";
      if ($cod_turma != "")	$strSQL .= " AND T.COD_TURMA='". $cod_turma ."'";
	
      if ($caso != 3 AND $caso != 6 AND $caso != 8 AND $caso != 9)
	$strSQL .= " GROUP BY C.COD_CURSO";
      
      $strSQL .= " ORDER BY CO.COD_CURSO_ORIGEM, C.ABREV_CURSO, C.DESC_CURSO, D.DESC_DIS, T.NOME_TURMA";
    }
  }
  else {
    //casos 1,2,10..15
    switch($caso) {
    case 1:
      $strSQL = "SELECT C.COD_CURSO , C.DESC_CURSO , CO.DESC_CURSO_ORIGEM, C.ABREV_CURSO FROM curso C, curso_origem CO WHERE C.INSCRICAO_ABERTA = '1' AND C.COD_CURSO_ORIGEM = CO.COD_CURSO_ORIGEM GROUP BY COD_CURSO, C.PAGAMENTO ";	
      break;
      
    case 2:
      
      if ($cod_curso != "")
	$strSQL = "SELECT C.COD_CURSO ,CO.DESC_CURSO_ORIGEM, CO.COD_CURSO_ORIGEM, C.DESC_CURSO , C.ABREV_CURSO,".
	  " D.DESC_DIS, T.NOME_TURMA, D.COD_DIS, T.COD_TURMA".
	  " FROM curso C, curso_origem CO,".
	  " disciplina D, turma T".
	  " WHERE CO.COD_CURSO_ORIGEM = C.COD_CURSO_ORIGEM".
	  " AND C.COD_CURSO = D.COD_CURSO AND D.COD_DIS = T.COD_DIS".
	  " AND C.COD_CURSO = '". $cod_curso ."'".
	  " GROUP BY D.COD_DIS".
	  " ORDER BY CO.COD_CURSO_ORIGEM, C.ABREV_CURSO, C.DESC_CURSO, D.DESC_DIS, T.NOME_TURMA";
      else
	$strSQL = "SELECT *".
	  " FROM curso-origem CO LEFT JOIN curso C".
	  " ON CO.COD_CURSO_ORIGEM = C.COD_CURSO_ORIGEM".
	  " WHERE C.COD_CURSO IS NOT NULL";
      
      break;
      
    case 10:
      $strSQL = "SELECT CO.DESC_CURSO_ORIGEM, CO.COD_CURSO_ORIGEM".
	" FROM curso_origem CO".
	" GROUP BY CO.COD_CURSO_ORIGEM".
      " ORDER BY CO.COD_CURSO_ORIGEM";
      break;
      
    case 11:
      $strSQL = "SELECT CO.DESC_CURSO_ORIGEM, CO.COD_CURSO_ORIGEM, C.COD_CURSO, C.DESC_CURSO, C.ABREV_CURSO, C.INSCRICAO_ABERTA, ".
	" FROM curso_origem CO LEFT JOIN curso C".
	" ON CO.COD_CURSO_ORIGEM = C.COD_CURSO_ORIGEM";
      break;
      
    case 12:
      if ( $cod_curso != "" )
	$strSQL = "SELECT * FROM curso WHERE COD_CURSO='". $cod_curso ."'";		
      elseif ( $cod_dis != "" )
	$strSQL = "SELECT * FROM curso_origem WHERE COD_CURSO_ORIGEM='". $cod_dis ."'";		
      break;
      
    case 13:
      $strSQL = "SELECT CO.COD_CURSO_ORIGEM, CO.DESC_CURSO_ORIGEM, C.COD_CURSO, C.DESC_CURSO , C.ABREV_CURSO".
	", D.COD_DIS, D.DESC_DIS, T.COD_TURMA, T.NOME_TURMA".
	" FROM curso_origem CO".
	" LEFT JOIN curso C ON CO.COD_CURSO_ORIGEM = C.COD_CURSO_ORIGEM ";
      
      if ($cod_curso != "")
	$strSQL = $strSQL . " AND C.COD_CURSO = " . $cod_curso;
      
      $strSQL = $strSQL . " LEFT JOIN disciplina D ON C.COD_CURSO = D.COD_CURSO".
	" LEFT JOIN turma T ON D.COD_DIS = T.COD_DIS";							  
      
      if ($cod_curso != "")
	$strSQL = $strSQL . " WHERE D.COD_DIS IS NOT NULL GROUP BY D.COD_DIS";
      
      $strSQL = $strSQL . " ORDER BY CO.COD_CURSO_ORIGEM, C.ABREV_CURSO, C.DESC_CURSO, D.DESC_DIS, T.NOME_TURMA";
      
      break;
      
    case 14:
      $strSQL = "SELECT CO.COD_CURSO_ORIGEM, CO.DESC_CURSO_ORIGEM, C.COD_CURSO, C.DESC_CURSO , C.ABREV_CURSO".
	", D.COD_DIS, D.DESC_DIS, T.COD_TURMA, T.NOME_TURMA".
	" FROM curso_origem CO, administrador_curso ADMC".
	" LEFT JOIN curso C ON CO.COD_CURSO_ORIGEM = C.COD_CURSO_ORIGEM ".
	" LEFT JOIN disciplina D ON C.COD_CURSO = D.COD_CURSO".
	" LEFT JOIN turma T ON D.COD_DIS = T.COD_DIS".
	" WHERE C.COD_CURSO = ADMC.COD_CURSO AND ADMC.COD_ADM = '". $_SESSION["COD_ADM"] ."'";
      
      if ($cod_curso != "")
	$strSQL = $strSQL . " AND C.COD_CURSO = " . $cod_curso . " AND D.COD_DIS IS NOT NULL GROUP BY D.COD_DIS";
      
      $strSQL = $strSQL . " ORDER BY CO.COD_CURSO_ORIGEM, C.ABREV_CURSO, C.DESC_CURSO, D.DESC_DIS, T.NOME_TURMA";	
      
      break;
      
    case 15:
      if ($cod_curso != "")
	$strSQL = "SELECT C.COD_CURSO ,CO.DESC_CURSO_ORIGEM, CO.COD_CURSO_ORIGEM, C.DESC_CURSO , C.ABREV_CURSO,".
	  " D.DESC_DIS, T.NOME_TURMA, D.COD_DIS, T.COD_TURMA".
	  " FROM curso C, curso_origem CO,".
	  " disciplina D, turma T".
	  " WHERE CO.COD_CURSO_ORIGEM = C.COD_CURSO_ORIGEM".
	  " AND C.COD_CURSO = D.COD_CURSO AND D.COD_DIS = T.COD_DIS".
	  " AND C.COD_CURSO = '". $cod_curso ."'".
	  " ORDER BY CO.COD_CURSO_ORIGEM, C.ABREV_CURSO, C.DESC_CURSO, D.DESC_DIS, T.NOME_TURMA";
      else
	$strSQL = "SELECT *".
	  " FROM curso_origem CO LEFT JOIN curso C".
	  " ON CO.COD_CURSO_ORIGEM = C.COD_CURSO_ORIGEM".
	  " WHERE C.COD_CURSO IS NOT NULL";
      break;
    }
  }
  
  if ($strSQL != "")
    return mysql_query($strSQL);	
  else	
    return false;
}	       

//======================================================================================================
// Verifica em qual nivel de acesso a pessoa se encontra de acordo com suas variaveis de sessao
// O nivel de acesso eh de acordo com o curso, turma em que a pessoa esta.
// Pode ser aluno mas em principal.asp vai estar apenas como usuario logado.

// Ler noticias
//	cod_adm, cod_prof, cod_al (estaticos)
// Editar noticias
//	nivel_acesso (dinamico)

// logon.asp; curso.asp; disciplina.asp

function nivelAcesso()
{
  // Mantem o nivel de acordo com a pagina que a pessoa está

  // Manter sempre o privilegio mais alto:
  //	Maior 
  //		1  (ADM Geral) 
  //		2  (ADM Curso) 
  //		3  (Professor) 
  //		0  (Sem privilegios)
  //	Menor

  // logon.asp; É administrador Geral (1)? se nao for entao eh apenas usuario logado (0)
  if ($_SESSION["PAGINA_ATUAL"] == "logon")
    if ($_SESSION["COD_ADM"] != "")
      {
	$strSQL = "SELECT COD_NIVEL_ACESSO FROM administrador WHERE COD_ADM = " . $_SESSION["COD_ADM"]." AND COD_NIVEL_ACESSO = 1";
	$rsCon = mysql_query($strSQL);
			
	if ($rsCon)	
	  {
	    $linha = mysql_fetch_array($rsCon);
			
	    if ($linha)
	      $_SESSION["NIVEL_ACESSO"] = 1;
	    else 
	      $_SESSION["NIVEL_ACESSO"] = 0;					
	  }
	else 
	  $_SESSION["NIVEL_ACESSO"] = 0;
      }

  // curso.asp; É administrador de Curso(2)?
  //	if ($_SESSION["PAGINA_ATUAL"] == "curso" or $_SESSION["PAGINA_ATUAL"] == "disciplina")
  {
    if ( ($_SESSION["NIVEL_ACESSO"] != 1) and ($_SESSION["COD_CURSO"] != "") and ($_SESSION["COD_ADM"] != "") )
      {
	$strSQL = "SELECT ADM.COD_ADM FROM administrador ADM, ADMINISTRADOR_CURSO ADMC".
	  " WHERE ADM.COD_ADM = ADMC.COD_ADM AND ADM.COD_ADM = '" . $_SESSION["COD_ADM"] . "'".
	  " AND ADMC.COD_CURSO = '". $_SESSION["COD_CURSO"] ."' AND ADM.COD_NIVEL_ACESSO = '2'";
	$rsCon = mysql_query($strSQL);	
					 
	if ($rsCon)	
	  {
	    $linha = mysql_fetch_array($rsCon);
			
	    if ($linha)
	      $_SESSION["NIVEL_ACESSO"] = 2;
	    else 
	      $_SESSION["NIVEL_ACESSO"] = 0;					
	  }
	else 
	  $_SESSION["NIVEL_ACESSO"] = 0;
      }
  }
	
  // disciplina.asp; É professor(3)?
  if ($_SESSION["PAGINA_ATUAL"] == "disciplina")
    // Professor
    if ( ($_SESSION["NIVEL_ACESSO"] != 1) and ($_SESSION["NIVEL_ACESSO"] != 2) and ($_SESSION["COD_TURMA"] != "") and ($_SESSION["COD_PROF"] != "") )
      {
	$strSQL = "SELECT COD_PROF FROM professor_TURMA WHERE COD_PROF = '" . $_SESSION["COD_PROF"] . "' AND".
	  " COD_TURMA = '". $_SESSION["COD_TURMA"] ."'";
	$rsCon = mysql_query($strSQL);	
			
	if ($rsCon)	 {
	  $linha = mysql_fetch_array($rsCon);
			
    if ($linha)
      $_SESSION["NIVEL_ACESSO"] = 3;
    else 
      $_SESSION["NIVEL_ACESSO"] = 0;					
  }
	else 
	  $_SESSION["NIVEL_ACESSO"] = 0;
  }
			
  return true;
}

//======================================================================================================
// logon.asp

function nivelAcessoFuturo()
{
  $acesso = 0;   // 1 - ADM Geral
  // 2 - ADM Curso
  // 3 - PROF
  // 4 - aluno
  
  if ($_SESSION["COD_ADM"] != "")
    {
      $strSQL = "SELECT COD_NIVEL_ACESSO FROM administrador WHERE COD_ADM = " . $_SESSION["COD_ADM"];
      $rsCon = mysql_query($strSQL);
			
      if ($rsCon)	
	{							
	  if ($linha = mysql_fetch_array($rsCon))
	    $acesso = $linha["COD_NIVEL_ACESSO"];
	}
    }
  
  if ($acesso != 1 and $acesso != 2) {
    if ($_SESSION["COD_PROF"] != "")
      $acesso = 3;
    elseif ( ($acesso!=3) and ($_SESSION["COD_AL"]!= "" )) {
      $acesso = 4; 
    }
  }
  
  $_SESSION["NIVEL_ACESSO_FUTURO"] = $acesso;
  return $acesso;
}

//guarda ulima entrada no 
function guardaUltimaEntrada($codPessoa) {
  
	$data = date('Y-m-d H:m:s');  
	if (date("I")) { //se horário de verão estiver ativado, substrai uma hora
	  $hora= (substr($data,11,2)-1);
	  if ($hora==-1) { $hora= '00'; }
	  $data = substr($data,0,10)." ".$hora.substr($data,13,6);
  }

  //guarda na tabela pessoa apenas por performance
  $strSQL= "UPDATE pessoa set  ultimoAcesso=now()   where COD_PESSOA = ".quote_smart($codPessoa);
	mysql_query($strSQL);
  //Registra o acesso como log
	$strSQL= "INSERT INTO acessopessoa (codPessoa,acesso) VALUES (".quote_smart($codPessoa).",now());";
	mysql_query($strSQL);

  //Já registra o alive para funcionar o mais rápido possível
  mysql_query('update pessoa SET alive='.time().' Where COD_PESSOA='.quote_smart($codPessoa));
	//if ($codPessoa==3) { echo "<script>alert('".addSlashes($strSQL)."');</script>";  }

  $_SESSION['REMOTE_ADDR']=$_SERVER['REMOTE_ADDR']; 
  $_SESSION['HTTP_USER_AGENT']=$_SERVER['HTTP_USER_AGENT'];
	
	//guarda cookies no usuario para evitar troca de servidores/sessão
	//e forçar login depois de 2 horas (parametro tempo)
	//o ultimo parametro é para ser utlizado apenas em http, para evitar ataques 
	
	//codkie do codPessoa para garantir que é a mesma pessoa
  setcookie('codPessoa',$codPessoa, (time()+10800));	
	//codkie do servidor para garantir que é o mesmo servidor
  setcookie('server',SERVER, (time()+10800));	
	//se o usuario editar os cookies, a conferencia do hash detectará
  setcookie('id',md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'].SERVER.$codPessoa), (time()+10800));	


}

/**
 * Autentica o usuario
 */ 
function autenticaUsuario($nomUser,$senha,$apenasAtivos=1) {
  //ve se o usuario existe
  $pessoa = existeUsuario($_REQUEST["USER_PESSOA"]);
  if (empty($pessoa))
    return 0;
  
  //ve se a senha confere  
  if ($pessoa['SENHA_PESSOA'] != md5($senha)) {
    return 0;
  }
  if ($apenasAtivos && !$pessoa['ativa']) {
    return 0;
  }
      
  $_SESSION['COD_PESSOA'] = $pessoa['COD_PESSOA'];
  $_SESSION['usuarioAtivo'] = $pessoa['ativa'];      
      
  return 1;
}

//======================================================================================================
// Unificação da letra da mensagem que aparece em cada módulo (ex: acesso restrito a alunos cadastrados)!

if (!function_exists("msg")) {
  function msg($msg) {
    echo "<div class=\"mensagemModulo\"><b>" . $msg . "</b></div>";
  }
}


?>
