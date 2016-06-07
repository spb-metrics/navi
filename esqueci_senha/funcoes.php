<?

include ("../config.php");


//===============================================================================================================	
function testa_login($email_pessoa, $user)
{

$strSQL="SELECT".
		" USER_PESSOA, NOME_PESSOA, EMAIL_PESSOA, vinculoUfrgs". 
		" FROM".
		" pessoa".
		" WHERE";
		if(!empty($email_pessoa)){
			$strSQL.=" EMAIL_PESSOA='".$email_pessoa."'";
		}
		if(!empty($email_pessoa)&&!empty($user)){
			$strSQL.=" AND ";
		}
		if(!empty($user)){
			$strSQL.= " USER_PESSOA='".$user."'";

		}
		$rsCon=mysql_query($strSQL);

$error=mysql_errno();

		if(mysql_num_rows($rsCon)>1){
			$error=1;
		}else
			if($linha=mysql_fetch_array($rsCon))
				return $linha;
			else
				return $error;
}
//=============================================================================================================
function muda_senha($email_pessoa='',$user='')
{
	$senha = cria_senha();
	
	$strSQL = "UPDATE pessoa SET SENHA_PESSOA = MD5('" ;
	
	foreach($senha as $valor)
		$strSQL .= $valor;
		
	$strSQL .= "') WHERE	";
	
	if(!empty($email_pessoa)){
		$strSQL.=" EMAIL_PESSOA='".$email_pessoa."'";
	}
	if(!empty($email_pessoa)&&!empty($user)){
		$strSQL.=" AND ";
	}
	if(!empty($user)){
		$strSQL.= " USER_PESSOA='".$user."'";
	}
	
	/*$strSQL = "UPDATE PESSOA SET SENHA_PESSOA = PASSWORD(USER_PESSOA) " ;
	$strSQL .= " WHERE EMAIL_PESSOA='".$email_pessoa ."'";	*/
	

  mysql_query($strSQL);

	if(!mysql_errno())
		return $senha;
	else 
		return false;	
}

//===============================================================================================================	
function testa_login_BB($user_pessoa)
{

$strSQL="SELECT".
		" USER_PESSOA, NOME_PESSOA, EMAIL_PESSOA". 
		" FROM".
		" pessoa".
		" WHERE".
		" USER_PESSOA='".$user_pessoa."'";
		
		$rsCon=mysql_query($strSQL);
		
		if($linha=mysql_fetch_array($rsCon))
			return $linha;
		else
			return mysql_errno();
}
//=============================================================================================================
function muda_senha_BB($user_pessoa)
{

	/*
	$strSQL = "UPDATE PESSOA SET SENHA_PESSOA = PASSWORD('" ;
	
	foreach($senha as $valor)
		$strSQL .= $valor;
		
	$strSQL .= "') WHERE EMAIL_PESSOA='".$email_pessoa ."'";	
	*/
	$strSQL = "UPDATE pessoa SET SENHA_PESSOA = MD5(".quote_smart($user_pessoa).") " ;
	$strSQL .= " WHERE USER_PESSOA=".quote_smart($user_pessoa);	
	
  //echo $strSQL;
  mysql_query($strSQL);

	if(!mysql_errno())
		return true;
	else 
		return false;	
}

//=============================================================================================================
function cria_senha()
{
$senha=Array();
for($i=1;$i<8;$i=$i+2)
{	$senha[$i-1]= chr(rand(97,122));
	$senha[$i]= rand(0,9);	
	
}

return $senha;
}
//===========================================================================================================

function manda_email($email_pessoa, $nome_pessoa,$user_pessoa, $senha){

  ini_set("SMTP",SERVIDOR_SMTP);
 
	$body="\t\tPrezado ".$nome_pessoa."\n\n".
			"\tSua senha foi alterada com sucesso!\n".
			"\tPara acessar a plataforma NAVi utilize:\n".
		  "\tUsuário: ".$user_pessoa."\n".
		  "\tSenha: ";
		  
	foreach($senha as $valor)		  
		$body .= $valor;
			
	$body .= "\n\n\n".
		  	 " \tAtenciosamente\n".
		  	 " \t Equipe NAVi";	
	
	@mail ($email_pessoa,
			"Sua senha foi Alterada com sucesso!", 
			$body, 
			"From:suporte@navi.com.br\r\n" . 
			"X-Mailer: PHP/". phpversion());

}
?>
