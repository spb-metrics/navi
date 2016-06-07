<?php
/**
 * Efetua um cadastro basico, apenas salvando os campos
 * customizado de cadastro.inc.php para a UFRGS, no uso institucional
 */ 
function cadastroBasicoUfrgs($nomePessoa,$nomUser,$senha,$email,$vinculo,$ativa=0) {
  $data = date("y-m-d");
  global $erroCadastroBasico;
		
  $strSQL = "INSERT INTO pessoa (NOME_PESSOA,USER_PESSOA, EMAIL_PESSOA, SENHA_PESSOA, DATA_CADASTRO_PESSOA, vinculoUfrgs, ativa)".
    " VALUES (".quote_smart($nomePessoa) .",".quote_smart($nomUser) .",".
      quote_smart($email) .",MD5(".quote_smart($senha)."),".
      quote_smart($data).", ".$vinculo.", ".$ativa.")";
    
  //echo $strSQL; echo mysql_error(); die;
  mysql_query($strSQL);
  
  $lastId=mysql_insert_id();
  
  if ( mysql_errno() )
  { $erroCadastroBasico = 1; return false; }
  else
  { return $lastId;};
	      
}
?>
