<?
include_once("funcoes.php");
  if(!isset($_REQUEST["EMAIL_PESSOA"]))
    $_REQUEST["EMAIL_PESSOA"]="";
  
if(empty($_REQUEST["EMAIL_PESSOA"]) && empty($_REQUEST["USER"]))
  {
  
		$mensagem="Por favor preeencha pelo menos um campo!";
		include "erro.php";
		return;
  }
else{

$ok=testa_login($_REQUEST["EMAIL_PESSOA"],$_REQUEST["USER"]);//retorna e-mail, nome_pessoa,user_pessoa

if(!$ok)	{  
		//$mensagem="E- mail n�o existe.";
    $mensagem = "Seu usu�rio n�o existe ou h� mais de um registro com o mesmo e_mail!<br>".
				"Por favor preencha os dois campos: usu�rio e e_mail!<br> ".
				"Se voc� mesmo assim n�o consiguir alterar a senha entre em contato com o suporte t�cnico (51) 3308-5333 <br>";

		include "erro.php";
		return;
	}
else {  
		if($ok["EMAIL_PESSOA"]=="")	{	
			$mensagem="Por favor contacte a equipe navi para cadastrar seu e_mail, depois tente alterar sua senha";
			include "erro.php";
		}
		if($ok["vinculoUfrgs"]==1)	{	
		  $mensagem="Sua senha n�o pode ser alterada aqui. A senha � a mesma do Portal do Aluno/Servidor do site da UFRGS. Se deseja alterar sua senha, fa�a a altera��o pelo Portal do Aluno/Servidor.";
		  include "erro.php";
    }
		else
		{   
			$modulo="SAIR";
			$rsCon=muda_senha($_REQUEST["EMAIL_PESSOA"], $_REQUEST["USER"]);
			if($rsCon)
				{	
					$rsCon=manda_email($ok["EMAIL_PESSOA"],$ok["NOME_PESSOA"],$ok["USER_PESSOA"], $rsCon);
					$mensagem="Senha alterada com sucesso. Nova senha enviada por e_mail";
					//$mensagem="Senha alterada com sucesso. Sua senha agora � o seu nome de usu�rio.";
					include "erro.php";
					return;
				}
			else{   
          echo $modulo;
					
					$mensagem="dificuldades em alterar a senha, tente novamente ou contacte a equipe Navi";
					include "erro.php";
					return;
				}
		}
	}
}	
?>
