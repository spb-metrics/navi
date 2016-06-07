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



/** FUNCOES PARA O CADASTRO DA PESSOA / AUTENTICACAO
    SEPARADO NESSE ARQUIVO POR MAICON BRAUWERS DFL CONSULTORIA LTDA 
    Funcoes : 
    - atualiza_cadastro 
    - atualiza_endereco
    - atualiza_telefone
    - atualiza_senha
    - cadastro_espec
    - cadastro_solicit
    - sexo
    - telefone
    - endereco
    - tipo_endereco
    - tipo_telefone
    - pessoa
    - cadastro
    - listaPessoas
	
**/

// cadastro/atualiza_cadastro.asp
include_once ($caminhoBiblioteca."/CLDb.inc.php");
//include_once("../config.php");

function atualiza_cadastro($nome_pessoa, $nome_chat, $data_nasc_pessoa, $cod_sexo, $doc_id_pessoa, $email_pessoa, $cpf_pessoa,$correio_recebe_mail_externo,$correio_recebe_mail_interno,$recado_mail)
{

	$strSQL = "UPDATE pessoa SET ";
	$strSQL = $strSQL . "NOME_PESSOA = '"      . $nome_pessoa .      "', ";
	$strSQL = $strSQL . "NOME_CHAT = '"        . $nome_chat .      "', ";
	$strSQL = $strSQL . "DATA_NASC_PESSOA = '" . $data_nasc_pessoa . "', ";
	$strSQL = $strSQL . "COD_SEXO = '"         . $cod_sexo .         "', ";
	$strSQL = $strSQL . "DOC_ID_PESSOA = '"    . $doc_id_pessoa .    "', ";
	$strSQL = $strSQL . "EMAIL_PESSOA = '"     . $email_pessoa."',"      ;
 	$strSQL = $strSQL . "CPF_PESSOA = '"       . $cpf_pessoa ."',"  ;
  $strSQL = $strSQL . "CORREIO_RECEBE_MAIL_EXTERNO=";
  if (!empty( $correio_recebe_mail_externo)) {  $strSQL .= "1";  }   else {$strSQL .= "0"; }
  $strSQL = $strSQL . ",CORREIO_RECEBE_MAIL_INTERNO=";
  if (!empty( $correio_recebe_mail_interno)) {  $strSQL .= "1";  }   else {$strSQL .= "0"; }
    $strSQL = $strSQL . ",RECADO_MAIL=";
if (!empty($recado_mail)) {  $strSQL .= "1";  }   else {$strSQL .= "0"; }

	$strSQL = $strSQL . " WHERE COD_PESSOA = '" . $_SESSION["COD_PESSOA"] . "'";

  mysql_query($strSQL);
	if ( ! mysql_errno()) {
		$_SESSION["NOME_PESSOA"] = $nome_pessoa;
	}
	return (! mysql_errno());
 }


function atualiza_endereco($acao, $COD_ENDERECO, $COD_TIPO_END, $DESC_END, $BAIRRO_END, $CIDADE_END, $UF_END, $PAIS_END, $CEP_END, $EMPRESA_END, $SETOR_END, $CARGO_END)
{
		
  if ($acao == "atualizar")
    {

      $strSQL = "UPDATE endereco SET ";
      $strSQL = $strSQL . " COD_TIPO_END = '" . $COD_TIPO_END . "'";
      $strSQL = $strSQL . ", DESC_END = '"    . $DESC_END     . "'";
      $strSQL = $strSQL . ", BAIRRO_END = '"  . $BAIRRO_END   . "'";
      $strSQL = $strSQL . ", CIDADE_END = '"  . $CIDADE_END   . "'";
      $strSQL = $strSQL . ", UF_END = '"      . $UF_END       . "'";
      $strSQL = $strSQL . ", PAIS_END = '"    . $PAIS_END     . "'";
      $strSQL = $strSQL . ", CEP_END = '"     . $CEP_END      . "'";
      $strSQL = $strSQL . ", EMPRESA_END = '" . $EMPRESA_END  . "'";
      $strSQL = $strSQL . ", SETOR_END = '"   . $SETOR_END    . "'";
      $strSQL = $strSQL . ", CARGO_END = '"   . $CARGO_END    . "'";
      $strSQL = $strSQL . " WHERE COD_ENDERECO = " . $COD_ENDERECO;
		
      mysql_query($strSQL);
				  
      return (! mysql_errno());
		
    }	
  else
    {
      if ($acao == "inserir")
	{
	
	  $strSQL = "INSERT INTO endereco (";
	  $strSQL = $strSQL . "COD_PESSOA,COD_TIPO_END,DESC_END,BAIRRO_END,CIDADE_END,UF_END,PAIS_END,CEP_END,EMPRESA_END,SETOR_END,CARGO_END";
	  $strSQL = $strSQL . ")";
	  $strSQL = $strSQL . " VALUES (";
	  $strSQL = $strSQL . "'"  . $_SESSION["COD_PESSOA"]. "'";
	  $strSQL = $strSQL . ",'" . $COD_TIPO_END          . "'";
	  $strSQL = $strSQL . ",'" . $DESC_END              . "'";
	  $strSQL = $strSQL . ",'" . $BAIRRO_END            . "'";
	  $strSQL = $strSQL . ",'" . $CIDADE_END            . "'";
	  $strSQL = $strSQL . ",'" . $UF_END                . "'";
	  $strSQL = $strSQL . ",'" . $PAIS_END              . "'";
	  $strSQL = $strSQL . ",'" . $CEP_END               . "'";
	  $strSQL = $strSQL . ",'" . $EMPRESA_END           . "'";
	  $strSQL = $strSQL . ",'" . $SETOR_END             . "'";
	  $strSQL = $strSQL . ",'" . $CARGO_END             . "'";
	  $strSQL = $strSQL . ")";

	  mysql_query($strSQL);	
								
	  return true;
	}
      else
	{
	  if ($acao == "excluir")
	    {
	      $strSQL = "DELETE FROM endereco WHERE COD_ENDERECO = " . $COD_ENDERECO;
		
	      mysql_query($strSQL);
				  
	      return (! mysql_errno());
	    }			
	}	
    }
			
}

//======================================================================================================
// cadastro/atualiza_fone.asp

function atualiza_telefone($acao, $COD_FONE, $COD_TIPO_FONE, $COD_INTERNAC_FONE, $COD_AREA_FONE, $NRO_FONE, $RAMAL_FONE)
{	
	
  if ($acao == "atualizar")
    {
      $strSQL = "UPDATE fone SET ";
      $strSQL = $strSQL . " COD_TIPO_FONE = '"       . $COD_TIPO_FONE     . "'";
      $strSQL = $strSQL . ", COD_INTERNAC_FONE = '"  . $COD_INTERNAC_FONE . "'";
      $strSQL = $strSQL . ", COD_AREA_FONE = '"      . $COD_AREA_FONE     . "'";
      $strSQL = $strSQL . ", NRO_FONE = '"           . $NRO_FONE          . "'";
      $strSQL = $strSQL . ", RAMAL_FONE = '"         . $RAMAL_FONE        . "'";
      $strSQL = $strSQL . " WHERE COD_FONE = "       . $COD_FONE;

      mysql_query($strSQL);
				  
      return (! mysql_errno());
    }

  else
    {
      if ($acao == "inserir")
	{
		
	  $strSQL = "INSERT INTO fone (";
	  $strSQL = $strSQL . "COD_PESSOA, COD_TIPO_FONE, COD_INTERNAC_FONE, COD_AREA_FONE, NRO_FONE, RAMAL_FONE";
	  $strSQL = $strSQL . ")";
	  $strSQL = $strSQL . " VALUES (";
	  $strSQL = $strSQL . " '" . $_SESSION["COD_PESSOA"] . "'";
	  $strSQL = $strSQL . ",'" . $COD_TIPO_FONE          . "'";
	  $strSQL = $strSQL . ",'" . $COD_INTERNAC_FONE      . "'";
	  $strSQL = $strSQL . ",'" . $COD_AREA_FONE          . "'";
	  $strSQL = $strSQL . ",'" . $NRO_FONE               . "'";
	  $strSQL = $strSQL . ",'" . $RAMAL_FONE             . "'";
	  $strSQL = $strSQL . ")";
		
	  mysql_query($strSQL);	
								
	  return true;

	}
      else
	{	
	  if ($acao == "excluir")
	    {
	      $strSQL = "DELETE FROM fone WHERE COD_FONE = " . $COD_FONE;
				
	      mysql_query($strSQL);
						  
	      return (! mysql_errno());
	    }		
	}		
    }
			
}

//======================================================================================================
// cadastro/atualiza_senha.asp	

function atualiza_senha($senha, $frase)
{

  $strSQL = "UPDATE pessoa SET SENHA_PESSOA = MD5('" . $senha . "'), FRASE_SENHA_PESSOA = '" . $frase . "' WHERE COD_PESSOA='" . $_SESSION["COD_PESSOA"] . "'";
			
  mysql_query($strSQL);

  return (! mysql_errno());
}

function cadastro_espec($nome_pai, $nome_mae, $rg_orgao, $rg_data_emissao, $estado_civil, $nacionalidade, $naturalidade, $titulo_eleitor, $reg_prof, $pagamento_forma, $pagamento_condicao, $pagamento_parcela, $pagamento_responsavel, $pagamento_resp_nome, $pagamento_resp_cnpj, $pagamento_resp_razao_social, $formacao_grad, $formacao_esp, $formacao_mestr, $formacao_dout, $experiencia, $motivacao, $user_pessoa )
{				  
  $strSQL = "SELECT COD_PESSOA FROM pessoa WHERE USER_PESSOA = '" . $user_pessoa . "'";
	
  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);
	
  $cod_pessoa = $linha["COD_PESSOA"];
		
  $strSQL = "INSERT INTO pessoa_especializacao (COD_PESSOA, NOME_PAI, NOME_MAE, RG_ORGAO, RG_DATA_EMISSAO," .
    " ESTADO_CIVIL, NACIONALIDADE, NATURALIDADE, TITULO_ELEITOR, REG_PROF, PAGAMENTO_FORMA, PAGAMENTO_CONDICAO, PAGAMENTO_PARCELA, PAGAMENTO_RESPONSAVEL," .
    " PAGAMENTO_RESP_NOME, PAGAMENTO_RESP_CNPJ, PAGAMENTO_RESP_RAZAO_SOC, FORMACAO_GRAD, FORMACAO_ESP, FORMACAO_MESTR," .
    " FORMACAO_DOUT, EXPERIENCIA, MOTIVACAO)".
    " VALUES ('" . $cod_pessoa . "', '" . $nome_pai . "', '" . $nome_mae . "', '" . $rg_orgao . "', '" . $rg_data_emissao . "',".
    " '" . $estado_civil . "', '" . $nacionalidade . "', '" . $naturalidade . "', '" . $titulo_eleitor . "', '". $reg_prof . "', '" . $pagamento_forma . "', '" . $pagamento_condicao . "', '" . $pagamento_parcela . "', ".
    " '" . $pagamento_responsavel . "', '" . $pagamento_resp_nome . "', '" . $pagamento_resp_cnpj . "', '" . $pagamento_resp_razao_social . "', ".
    " '" . $formacao_grad . "', '" . $formacao_esp . "', '" . $formacao_mestr . "', '" . $formacao_dout . "', '" . $experiencia . "', '" . $motivacao . "' ".
    ")";
			 			

  mysql_query($strSQL);
  if ( mysql_errno() ) return false;	
	
  return true;
}

//======================================================================================================
// Não está em uso

function cadastro_solicit($user_pessoa, $cod_curso)				  
{
  $strSQL = "SELECT COD_PESSOA FROM pessoa WHERE USER_PESSOA = '" . $user_pessoa . "'";
	
  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);
	
  $cod_pessoa = $linha["COD_PESSOA"];
		
  $strSQL = "INSERT INTO solicitacao_matricula VALUES (" . $cod_pessoa . ",". $cod_curso .",0)" ;
  mysql_query($strSQL);
	
  return (! mysql_errno());
}

function sexo($COD_SEXO)
{
  $strSQL = "SELECT * FROM tipo_sexo WHERE 1=1";

  if ($COD_SEXO != "")
    $strSQL .= " AND COD_SEXO = '" . $COD_SEXO . "'";

  return mysql_query($strSQL);
}


//======================================================================================================
// cadastro/atualiza_fone.asp; cadastro/frm_fone.asp; cadastro/frm_atualiza_cadastro.asp;

function telefone($cod_fone)
{
  if ($cod_fone == "")
    $strSQL = "SELECT * FROM fone F, tipo_fone TF WHERE F.COD_TIPO_FONE = TF.COD_TIPO_FONE AND F.COD_PESSOA = '" . $_SESSION["COD_PESSOA"] . "'";
  else
    $strSQL = "SELECT * FROM fone F, tipo_fone TF WHERE F.COD_TIPO_FONE = TF.COD_TIPO_FONE AND F.COD_FONE = '" . $cod_fone . "'";
			
  return mysql_query($strSQL);
}

function endereco($cod_endereco)
{
  if ($cod_endereco == "")
    $strSQL = "SELECT * FROM endereco E, tipo_endereco TE WHERE E.COD_TIPO_END = TE.COD_TIPO_END AND E.COD_PESSOA = '" . quote_smart($_SESSION["COD_PESSOA"]) . "'";
  else
    $strSQL = "SELECT * FROM endereco E, tipo_endereco TE WHERE E.COD_TIPO_END = TE.COD_TIPO_END AND E.COD_ENDERECO = '" . $cod_endereco . "'";
	
  return mysql_query($strSQL);			
}

//======================================================================================================
// cadastro/frm_endereco.asp

function tipo_endereco()
{
  $strSQL = "SELECT * FROM tipo_endereco";

  return mysql_query($strSQL);
}

//======================================================================================================
// cadastro/frm_fone.asp

function tipo_telefone()
{
  $strSQL = "SELECT * FROM tipo_fone";

  return mysql_query($strSQL);
}
//======================================================================================================
// 1. Verifica se um determinado login já existe no sistema ou
// 2. Retorna os dados de uma determinada pessoa a partir de seu Session("COD_PESSOA")

// cadastro/insere_cadastro; cadastro/frm_atualiza_cadastro; cadastro/frm_atualiza_senha;

function pessoa($user_pessoa)
{
  if ($user_pessoa != "")
    $strSQL = "SELECT USER_PESSOA FROM pessoa WHERE USER_PESSOA =".quote_smart($user_pessoa )."";	
  else
    $strSQL = "SELECT COD_PESSOA, NOME_PESSOA, NOME_CHAT, USER_PESSOA, SENHA_PESSOA, FRASE_SENHA_PESSOA, DATE_FORMAT(DATA_NASC_PESSOA, '%d/%m/%Y') as DATA_NASC, ".
      "COD_SEXO, EMAIL_PESSOA, DOC_ID_PESSOA, CPF_PESSOA, DATA_CADASTRO_PESSOA,CORREIO_RECEBE_MAIL_EXTERNO, CORREIO_RECEBE_MAIL_INTERNO, RECADO_MAIL, CURSO_INTERESSE, vinculoUfrgs FROM pessoa WHERE COD_PESSOA = " . $_SESSION["COD_PESSOA"];

	 
	
  return mysql_query($strSQL);
}

//======================================================================================================
// cadastro/atualiza_cadastro.asp; cadastro/insere_cadastro.asp;

function cadastro($user_pessoa, $nome_pessoa, $data_nasc_pessoa, $cod_sexo, $doc_id_pessoa, $email_pessoa, $cpf_pessoa, $senha_pessoa, $frase_senha_pessoa, $desc_end, $bairro_end, $cidade_end, $uf_end, $pais_end, $cep_end, $cod_internac_fone, $cod_area_fone, $nro_fone, $ramal_fone, $ativa=0)
{

  $data = date("y-m-d");
		
  $strSQL = "INSERT INTO pessoa (USER_PESSOA, NOME_PESSOA, DATA_NASC_PESSOA, COD_SEXO, DOC_ID_PESSOA, EMAIL_PESSOA,".
    " CPF_PESSOA, SENHA_PESSOA, FRASE_SENHA_PESSOA, DATA_CADASTRO_PESSOA, CURSO_INTERESSE, ATIVA)".
    " VALUES ('". $user_pessoa ."', '". $nome_pessoa ."', '". $data_nasc_pessoa ."', '". $cod_sexo ."', '". $doc_id_pessoa ."',".
    " '". $email_pessoa ."', '". $cpf_pessoa ."', MD5('". $senha_pessoa ."'), '". $frase_senha_pessoa ."',".
    " '". $data ."', '0',".$ativa." )";
	
	//echo $strSQL;
	
  mysql_query($strSQL);		

  if ( mysql_errno() ) return false;
	
  $strSQL = "SELECT COD_PESSOA FROM pessoa WHERE USER_PESSOA = '" . $user_pessoa . "'";
	
  $rsCon = mysql_query($strSQL);
  $linha = mysql_fetch_array($rsCon);
	
  $cod_pessoa = $linha["COD_PESSOA"];

  $strSQL = "INSERT INTO endereco (COD_PESSOA , COD_TIPO_END, DESC_END, BAIRRO_END,  CIDADE_END,  UF_END,".
    " PAIS_END, CEP_END)".
    " VALUES (". $cod_pessoa .", 1, '". $desc_end ."', '". $bairro_end ."', '". $cidade_end ."',".
    " '". $uf_end ."', '". $pais_end ."', '". $cep_end ."')";

  //echo $strSQL;  

  mysql_query($strSQL);
  if ( mysql_errno() ) return false;	
	
  $strSQL = "INSERT INTO fone (COD_PESSOA, COD_TIPO_FONE, COD_INTERNAC_FONE, COD_AREA_FONE,  NRO_FONE, RAMAL_FONE)".
    " VALUES (". $cod_pessoa .", 1, '". $cod_internac_fone ."', '". $cod_area_fone ."', '". $nro_fone ."',".
    " '". $ramal_fone ."')";

  mysql_query($strSQL);
  if ( mysql_errno() ) return false;
	
  return true;
}

/**
 * Efetua um cadastro basico, apenas salvando os campos
 * nome de usuario, senha, email e data do cadastro
 * Utilizado por tools/inscricao.php?acao=A_carregaListaUsuariosSalva  
 */ 
function cadastroBasico($nomePessoa,$nomUser,$senha,$email,$ativa=0) {
  $strSQL = 'select 1 from  pessoa where USER_PESSOA='.quote_smart($nomUser);    
  $result = mysql_query($strSQL);
	if ( mysql_errno() || mysql_num_rows($result)) { return false; } 	
  
  //faz a inserção propriamente dita
  $data = date("y-m-d");  
  $strSQL = "INSERT INTO pessoa (NOME_PESSOA,USER_PESSOA, EMAIL_PESSOA, SENHA_PESSOA, DATA_CADASTRO_PESSOA, ativa)".
    " VALUES (".quote_smart($nomePessoa) .",".quote_smart($nomUser) .",".
      quote_smart($email) .",MD5(".quote_smart($senha)."),".
      quote_smart($data).", ".$ativa.")";
    
    //echo $strSQL; echo mysql_error();
  mysql_query($strSQL);
  
  $lastId=mysql_insert_id();
  
  if ( mysql_errno() ){return false;} else{ return $lastId;};
	      
}

// tools/aluno_operacao.p
function listaPessoas()
{
  $strSQL = "SELECT COD_PESSOA, NOME_PESSOA FROM pessoa";

  return mysql_query($strSQL);
}
//==================================================================================================
/*As funções abaixo foram criadas para fazer a pgna de cadastro para mostar para os outros alunos*/
function pessoaMostrar($cod_pessoa)
{
  if ($user_pessoa != "")
    $strSQL = "SELECT USER_PESSOA FROM pessoa WHERE COD_PESSOA = '" . $cod_pessoa . "'";	
  else
    $strSQL = "SELECT COD_PESSOA, NOME_PESSOA, USER_PESSOA, SENHA_PESSOA, FRASE_SENHA_PESSOA, DATE_FORMAT(DATA_NASC_PESSOA, '%d/%m/%Y') as DATA_NASC, ".
      "COD_SEXO, EMAIL_PESSOA, DOC_ID_PESSOA, CPF_PESSOA, DATA_CADASTRO_PESSOA, CURSO_INTERESSE FROM pessoa WHERE COD_PESSOA = " . $cod_pessoa;
	
  return mysql_query($strSQL);
}
//======================================================================================================
function enderecoMostrar($cod_pessoa)
{
  
    $strSQL = "SELECT * FROM endereco E, tipo_endereco TE WHERE E.COD_TIPO_END = TE.COD_TIPO_END AND E.COD_PESSOA = '" . $cod_pessoa. "'";
  
	
  return mysql_query($strSQL);			
}
//======================================================================================================
function telefoneMostrar($cod_pessoa)
{
  if ($cod_fone == "")
    $strSQL = "SELECT * FROM fone F, tipo_fone TF WHERE F.COD_TIPO_FONE = TF.COD_TIPO_FONE AND F.COD_PESSOA = '" . $cod_pessoa . "'";
  else
    $strSQL = "SELECT * FROM fone F, tipo_fone TF WHERE F.COD_TIPO_FONE = TF.COD_TIPO_FONE AND F.COD_FONE = '" . $cod_fone . "'";
			
  return mysql_query($strSQL);
}

/**
 * Constroi o formulario de cadastro
 */ 
function formCadastro($acao) {
?>
    
    <script type="text/javascript">

		function validaForm()
		{
			var msg = "";
			var obj = "";
			var preen = true;

			// Valida senha - text
			if ((document.f1.SENHA_PESSOA.value == null) || (document.f1.SENHA_PESSOA.value == ""))
			{
				msg += "=> Senha não preenchida;\n";
				preen = false;
			 }

			if ((document.f1.SENHA_PESSOA2.value == null) || (document.f1.SENHA_PESSOA2.value == ""))
			{
				msg += "=> Confirmação de senha não preenchida;\n";
				preen = false;
			 }

			if (preen) 
			{
				if (document.f1.SENHA_PESSOA.value != document.f1.SENHA_PESSOA2.value)
				{
					msg += "=> Confirme novamente a sua senha;\n";
				 }
			 }

			// Valida nome - text
			if ((document.f1.NOME_PESSOA.value == null) || (document.f1.NOME_PESSOA.value == ""))
			{
				msg += "=> Nome não preenchido;\n";
				preen = false;
			 }

			// Verifica data_nasc (se não é vazia) - text
			if ((document.f1.DATA_NASC_PESSOA.value == null) || (document.f1.DATA_NASC_PESSOA.value == ""))
			{
				msg += "=> Data de nascimento não preenchida;\n";
				preen = false;
			 }

			// Valida data_nasc - text
			if (!isDate(document.f1.DATA_NASC_PESSOA.value))
			{
				msg += "=> Data de nascimento no formato incorreto. Preencha no formato 'dd/mm/aaaa';\n";		
				preen = false;
			 }

			// Valida sexo - radio
			if ((document.f1.COD_SEXO[document.f1.COD_SEXO.selectedIndex].value == null) || (document.f1.COD_SEXO[document.f1.COD_SEXO.selectedIndex].value == ""))
			{
				msg += "=> Sexo não selecionado;\n";
			 }

			// Valida doc_id - text
			if ((document.f1.DOC_ID_PESSOA.value == null) || (document.f1.DOC_ID_PESSOA.value == ""))
			{
				msg += "=> Documento de Identidade não preenchido;\n";
				preen = false;
			 }  

			// Valida e-mail - text
			if ((document.f1.EMAIL_PESSOA.value == null) || (document.f1.EMAIL_PESSOA.value == ""))		{
				msg += "=> E-mail não preenchido;\n";
				preen = false;
			 }

			/* Valida cpf - text
			if ((document.f1.CPF_PESSOA.value == null) || (document.f1.CPF_PESSOA.value == "")) 
			{
				msg += "=> CPF não preenchido;\n";
				preen = false;
			 }
			*/

			if (!checaCPF(document.f1.CPF_PESSOA.value))	{
				msg += "=> CPF inválido;\n";
				preen = false;
			 }

			// Valida DESC_END - endereço
			if ((document.f1.DESC_END.value == null) || (document.f1.DESC_END.value == ""))		{
				msg += "=> Endereço não preenchido;\n";		preen = false;
      }
			// Valida BAIRRO_END - bairro
			if ((document.f1.BAIRRO_END.value == null) || (document.f1.BAIRRO_END.value == ""))		{
				msg += "=> Bairro não preenchido;\n";		preen = false;
      }
			// Valida CIDADE_END - cidade
			if ((document.f1.CIDADE_END.value == null) || (document.f1.CIDADE_END.value == ""))		{
				msg += "=> Cidade não preenchida;\n";		preen = false;
      }
			// Valida UF
			if ((document.f1.UF_END.value == null) || (document.f1.UF_END  .value == ""))		{
				msg += "=> UF não preenchida;\n";		preen = false;
      }
			// Valida pais
			if ((document.f1.PAIS_END.value == null) || (document.f1.PAIS_END.value == ""))		{
				msg += "=> País não preenchido;\n";		preen = false;
      }
			// Valida cep
			if ((document.f1.CEP_END.value == null) || (document.f1.CEP_END.value == ""))		{
				msg += "=> CEP não preenchido;\n";		preen = false;
      }
			// Valida codigo de area do telefone
			if ((document.f1.COD_AREA_FONE.value == null) || (document.f1.COD_AREA_FONE.value == ""))		{
				msg += "=> Código de -rea do Telefone não preenchido;\n";		preen = false;
      }
			// Valida telefone
			if ((document.f1.NRO_FONE.value == null) || (document.f1.NRO_FONE.value == ""))		{
				msg += "=> Telefone não preenchido;\n";		preen = false;
      }

			if (msg != "")	{
				msg = "Ocorreram os seguintes erros:\n\n" + msg;
				alert (msg);
				return false;
			}
			else {
				return true;
			}
		}//validaForm

		function checaCPF (CPF) 
		{
			if (CPF.length != 11 || CPF == "00000000000" || CPF == "11111111111" ||
				CPF == "22222222222" ||	CPF == "33333333333" || CPF == "44444444444" ||
				CPF == "55555555555" || CPF == "66666666666" || CPF == "77777777777" ||
				CPF == "88888888888" || CPF == "99999999999")
			{
				return false;
			 }

			soma = 0;
		
			for (i=0; i < 9; i ++)
				soma += parseInt(CPF.charAt(i)) * (10 - i);
		
			resto = 11 - (soma % 11);
		
			if (resto == 10 || resto == 11)
				resto = 0;
		
			if (resto != parseInt(CPF.charAt(9)))
				return false;
		
			soma = 0;

			for (i = 0; i < 10; i ++)
				soma += parseInt(CPF.charAt(i)) * (11 - i);

			resto = 11 - (soma % 11);

			if (resto == 10 || resto == 11)
				resto = 0;

			if (resto != parseInt(CPF.charAt(10)))			
				return false;

			return true;
		 }
     		
		function confereUser(teclapres) {
			var tecla = teclapres.keyCode;
			vr = document.f1.USER_PESSOA.value;
			vr = vr.toLowerCase();
			for(i=0; i<vr.length; i++) {
  			if (vr.charCodeAt(i)>122 || vr.charCodeAt(i)<97 && (vr.charCodeAt(i)<48 || vr.charCodeAt(i)>57) ){
  				vr = vr.replace(vr.charAt(i),"")
  			}
			}
			document.f1.USER_PESSOA.value = vr;
		}
		
		function isDate(val)
		 {
		  // created by jignesh gandhi jignesh@hotbiz.com
		  // returns true if the string passed is a valid date.
		  // formato adaptado: dd/mm/aaaa
		  var sep1 = parseInt(val.indexOf("/"));
		  var sep2 = parseInt(val.indexOf("/",sep1+1));
		  var len  = parseInt(val.length);
		  
		  var dd = parseInt(val.substr(0,sep1),10);
		  var mm = parseInt(val.substr(sep1+1,sep2-sep1-1),10);
		  var yy = parseInt(val.substr(sep2+1,len-sep2-1),10);
		  if (isNaN(dd) || isNaN(mm) || isNaN(yy)) return false;
		  if (yy<1900) yy+=2000;
		  var leap = ((yy == (parseInt(yy/4) * 4)) && !(yy == (parseInt(yy/100) * 100)));
		  if (!((mm >= 1) && (mm <= 12))) return false;
		  if ((mm == 2) && (leap)) dom = 29;
		  if ((mm == 2) && !(leap)) dom = 28;
		  if ((mm == 1) || (mm == 3) || (mm == 5) || (mm == 7) || (mm == 8) || (mm == 10) || (mm == 12)) dom = 31;
		  if ((mm == 4) || (mm == 6) || (mm == 9) || (mm == 11)) dom = 30;
		  if (dd > dom) return false;
		  return true;
		 }

	</script>

  <?
    if ($_REQUEST['erro'] == 1) {
      echo '<div class="erroFormCadastro">O nome de usu&aacute;rio que voc&ecirc; escolheu j&aacute;'.
           ' existe. Por favor digite outro nome de usu&aacute;rio.</div><br>';     
    
      //le as variaveis passadas por GET para o espaco de nomes atual de variaveis
      $campos = array("USER_PESSOA","NOME_PESSOA","DATA_NASC_PESSOA","COD_SEXO",
                      "DOC_ID_PESSOA","EMAIL_PESSOA","CPF_PESSOA",
                      "FRASE_SENHA_PESSOA","DESC_END","BAIRRO_END","CIDADE_END",
                      "UF_END","PAIS_END","CEP_END","COD_INTERNAC_FONE","COD_AREA_FONE",
                      "NRO_FONE","RAMAL_FONE");
      
      foreach($campos as $campo) {
        //presta atencao nas variaveis-variaveis
        $$campo = $_REQUEST[$campo];
      }
                  
    }
  
  ?>

  <form name="f1" method="post" action="<?=$acao?>" onSubmit="return validaForm()">
  	<table border="0" cellspacing="0" cellpadding="0" align="center" width="668">
  		
  		<tr>
        		<td width="160" height="15"><b>Nome Completo* </b></td>
  		    <td width="310">&nbsp;</td>
  		    <td width="334">&nbsp;</td>
  		</tr>
  		
  		<tr> 
        		<td colspan="3" height="28"> 
          		<input type="text" name="NOME_PESSOA" class="input1" value="<?=$NOME_PESSOA?>" size='50' maxlength='100'>
        		</td>
      	</tr>
  
  		<tr> 
        		<td height="15" width="200"><b>Data de Nascimento* <br> (dd/mm/aaaa)</b>
  			</td>
        		<td><b>Sexo*</b></td>
        		<td width="334"><b>Documento de Identifica&ccedil;&atilde;o (RG)* </b></td>
      	</tr>
  
  	    <tr> 
        		<td height="28" width="160"> 
          		<input type="text" name="DATA_NASC_PESSOA" class="input3"  value="<?=$DATA_NASC_PESSOA?>" size='10' maxlength='10'>
        		</td>
        		<td> 
          		<select name="COD_SEXO" class="input3">
            			<option value=""></option>
  					<?php
  					 $rsConCad = sexo("");
  
  					if ($rsConCad )
  					{
  						if($linhaCad = mysql_fetch_array($rsConCad))
  						 {
  						
  							while($linhaCad)
  							{
                  if ($linhaCad['COD_SEXO'] == $COD_SEXO) $sel = "selected";
                  else $sel = "";
  								echo"<option value='" . $linhaCad["COD_SEXO"] . "' ".$sel.">" . $linhaCad["DESC_SEXO"] . "</option>";
  								
  								$linhaCad = mysql_fetch_array($rsConCad);
  							}																				
  
  					
  						}					
  					}													
  					?>
  				</select>
  			</td>
  			
  			<td width="334"> 
          		<input type="text" name="DOC_ID_PESSOA" class="input2" value="<?=$DOC_ID_PESSOA?>" size='15' maxlength='15'>
  		    </td>
  		</tr>
  
      <tr> 
        <td height="15" width="160" colspan=2><b>E-mail*</b></td>
        <td width="334"><b>CPF (apenas n&uacute;meros, sem barras ou tra&ccedil;os)* 
          </b></td>
      </tr>
      <tr> 
        <td colspan="2" height="28"> 
          <input type="text" name="EMAIL_PESSOA" class="input3" value="<?=$EMAIL_PESSOA?>" size='30' maxlength='50'>
        </td>
        <td width="334"> 
          <input type="text" name="CPF_PESSOA" class="input2" value="<?=$CPF_PESSOA?>" size='11' maxlength='11'>
        </td>
      </tr>
      <tr> 
        <td height="15" colspan="3">&nbsp;</td>
      </tr>
      <tr> 
        <td colspan="2" height="15"><b>Nome de Usu&aacute;rio ou apelido (somente 
          letras min&uacute;sculas)*</b></td>
        <td width="334">&nbsp;</td>
      </tr>
      <tr> 
        <td valign="top" height="28" colspan="2"> 
          <input type="text" class="input2" name="USER_PESSOA" onKeyUp="confereUser(event)" size='40' maxlength='50'>
        </td>
        <td valign="top" width="334">&nbsp;</td>
      </tr>
      <tr> 
        <td colspan="2" height="15"><b>Digite uma senha*</b></td>
        <td width="334"><b>Confirme a sua senha* </b></td>
      </tr>
      <tr> 
        <td colspan="2" height="15"> 
          <input type="password" name="SENHA_PESSOA" class="input3" size='20' maxlength='20'>
        </td>
        <td width="334"> 
          <input type="password" name="SENHA_PESSOA2" class="input3" size='20' maxlength='20'>
        </td>
      </tr>
      <tr> 
        <td colspan="3" height="15"><b>Digite uma frase para lembrar da sua senha</b> 
        </td>
      </tr>
      <tr> 
        <td colspan="3" height="15"> 
          <input type="text" name="FRASE_SENHA_PESSOA" class="input1" value="<?=$FRASE_SENHA_PESSOA?>" size='40' maxlength='100'>
        </td>
      </tr>
      <tr> 
        <td colspan="2" height="40"> 
  	  	<b>Endereço</b><br>
          <input type="text" name="DESC_END" class="input2" value="<?=$DESC_END?>" size='40' maxlength='150'>
        </td>
        <td width="334"> 
  	  	<b>Bairro</b><br>
          <input type="text" name="BAIRRO_END" class="input2" value="<?=$BAIRRO_END?>" size='40' maxlength='50'>
        </td>
      </tr>
      <tr> 
        <td colspan="2" height="40"> 
  	  	<b>Cidade</b><br>
          <input type="text" name="CIDADE_END" class="input2" value="<?=$CIDADE_END?>" size='40' maxlength='100'>
        </td>
        <td width="334"> 
  	  <table  border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
  	  	<tr><td>
  	  	<b>Estado</b><br>
          <input type="text" name="UF_END" class="input3" size="2" maxlength="2" value="<?=$UF_END?>" size='2' maxlength='2'>
  		</td><td>
  	  	<b>País</b><br>
          <input type="text" name="PAIS_END" class="input3" value="<?=$PAIS_END?>" size='35' maxlength='35'>
  		</td></tr>
  	  </table>
        </td>
      </tr>
      <tr> 
        <td colspan="3" height="40"> 
  	  	<b>CEP</b><br>
          <input type="text" name="CEP_END" class="input2" value="<?=$CEP_END?>" size='8' maxlength='8'>
        </td>
      </tr>
      <tr> 
        <td height="30" colspan="3" valign="bottom"> 
          <b>Informa&ccedil;&otilde;es sobre Telefone</b><br>
        </td>
      </tr>
      <tr> 
        <td height="40"> 
  	  	Codigo Internacional<br>
          <input type="text" name="COD_INTERNAC_FONE" class="input3" value="<?=$COD_INTERNAC_FONE?>" size='5' maxlength='5'>
        </td>
        <td> 
  	  	Codigo de Area<br>
          <input type="text" name="COD_AREA_FONE" class="input3" value="<?=$COD_AREA_FONE?>" size='5' maxlength='5'>
        </td>
        <td width="334"> 
  	  <table  border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
  	  	<tr><td>
  	  	Telefone<br>
          <input type="text" name="NRO_FONE" class="input3" value="<?=$NRO_FONE?>" size='15' maxlength='15'>
  		</td><td>
  	  	Ramal<br>
          <input type="text" name="RAMAL_FONE" class="input3" value="<?=$RAMAL_FONE?>" size='5' maxlength='5'>
  		</td></tr>
  	  </table>
        </td>
      </tr>
 	
      <tr>
        <td height="15" colspan="3"> 
  	  <br><br>
          <div align="center"> 
            <input type="submit" name="OK" value="OK" class="input3">&nbsp;&nbsp;&nbsp;
            <input type="button" name="CANCELAR" value="CANCELAR" class="input3" onClick="javascript:history.back()" style='color:darkred;'>
            <br>
            <br>
            <br>
            <b>* campos obrigat&oacute;rios<br>
            <br>
  		
  		  
            </b></div>
        </td>
      </tr>
    </table>
    
    <input type="hidden" name="DATA_CADASTRO_PESSOA" value="<?=date();?>">
    <input type="hidden" name="COD_PESSOA" value="">
    <input type="hidden" name="NRO_ITEM_END">
    <input type="hidden" name="NRO_ITEM_FONE"> 
  </form>
  
<?

  if ($_REQUEST['erro'] == 1) {
    echo '<script type="text/javascript">document.f1.USER_PESSOA.focus();</script>';
  }
  else {
    echo '<script type="text/javascript">document.f1.NOME_PESSOA.focus();</script>';  
  }

}

function sendMail($email_pessoa, $nome_pessoa,$user_pessoa, $senha, $mensagem,$titulo){

  //ini_set("SMTP",SERVIDOR_SMTP);
 
	$body="\t\tPrezado ".$nome_pessoa."\n\n";
	$body.="\t".$mensagem."\n\n";
	$body.="\tUsuario:".$user_pessoa."\n";		
	$body.="\tSenha:".$senha."\n";	  
		  
				
	$body .= "\n\n\n".
		  	 " \tAtenciosamente\n".
		  	 " \t Equipe NAVi";	
	
	@mail ($email_pessoa,$titulo,$body, 
			'From: navi@ufrgs.br \r\n' . 
			'X-Mailer: PHP/'. phpversion());
}

?>
