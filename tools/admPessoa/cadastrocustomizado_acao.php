<?php
//funcao de insercao no banco
include('cadastroufrgs.inc.php');


if ($_REQUEST['vinculoUfrgs'] == 0) { 
  $pessoaNivelEscolhido[] = cadastroBasico($_REQUEST['nomePessoa'],$_REQUEST['usuarioPessoa'],$_REQUEST['senhaPessoa'],$_REQUEST['emailPessoa'],1);  
}
else if ($_REQUEST['vinculoUfrgs'] == 1) { 
  $pessoaNivelEscolhido[] = cadastroBasicoUfrgs($_REQUEST['nomePessoa'],$_REQUEST['cartaoUfrgs'],$_REQUEST['senhaPessoa'],$_REQUEST['emailPessoa'],$_REQUEST['vinculoUfrgs'],1); 
}

if (!$pessoaNivelEscolhido) {
  if ($erroCadastroBasico == 1) {
    //header("Location:".$_SERVER['PHP_SELF']."?erro=1"); 
    echo "<script> alert('Cadastro não efetivado. Certifique se já não existe uma pessoa com este nome de usuário. Procure pelo registro da pessoa utilizando **Buscar Pessoa**');location.href='./index.php';</script>";
    header('Location: /tools/admPessoa/index.php?acao=');
  } 
}
$titulo="Cadastro na plataforma NAVi";
if ($_REQUEST['vinculoUfrgs'] == 1) {
  $mensagem="Prezado ".$_REQUEST['nomePessoa'].", <br><br>Seu cadastro foi efetuado com sucesso!<br><br> Para acessar a plataforma entre no endereço: https://ead.ufrgs.br/navi <br> Utilize no campo USUÁRIO o nº do CARTÃO UFRGS e no campo SENHA a mesma senha do Portal do Aluno|Servidor <br><br>Atenciosamente, <br>Equipe NAVi";
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= 'From:navi@ead.ufrgs.br' . "\r\n" . 'X-Mailer: PHP/'. phpversion();
  @mail ($_REQUEST['emailPessoa'], $titulo, $mensagem, $headers);
}
else {
  $mensagem="Seu cadastro foi efetuado com sucesso!\n Para acessar a plataforma entre no endereço: https://ead.ufrgs.br/navi e utilize login e senha abaixo:\n";
  sendMail($_REQUEST['emailPessoa'], $_REQUEST['nomePessoa'],$_REQUEST['usuarioPessoa'], $_REQUEST['senhaPessoa'], $mensagem,$titulo);
}
//nao eh necessario redirecionar pois o proximo case fara a inclusao, e neste case nao temos break
//header('Location: /tools/admPessoa/index.php?acao=A_incluiPessoaNivel'); 
