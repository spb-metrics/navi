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

//error_reporting(E_ALL);
include("../config.php");
//include("../configarquivos.php");
include($caminhoBiblioteca."/perfil.inc.php");
include($caminhoBiblioteca."/funcoesftp.inc.php");
session_name(SESSION_NAME); session_start(); security();
if (!podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) { die; }

define('PREFIXO_MINI_FOTO','mini_');

?>
 <html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>

		<link rel="stylesheet" href=".././cursos.css" type="text/css">
		<script language="JavaScript" src=".././funcoes_js.js"></script>
	</head>

<body link="#6699CC" vlink="#6699CC" alink="#6699CC">

<table width="90%" cellspacing="0" border="0">
  <tr valign="top"> 
    <td>

<?php

function confNum($Num)	{
	if ($Num < 10) 
		return "0" . strval($Num);
	else		
		return strval($Num);
}


if ( !isset($_REQUEST["CAMINHO_FOTO"]) )
	$_REQUEST["CAMINHO_FOTO"] = "";

if ( !isset($_REQUEST["CAMINHO_FOTO_REDUZIDA"]) )
	$_REQUEST["CAMINHO_FOTO_REDUZIDA"] = "";

if ( !isset($_REQUEST["DESC_PERFIL"]) )
	$_REQUEST["DESC_PERFIL"] = "";

if ( !isset($_REQUEST["LINK_PERFIL"]) )
	$_REQUEST["LINK_PERFIL"] = "";

if ( !isset($_REQUEST["LINK_PERFIL_2"]) )
	$_REQUEST["LINK_PERFIL_2"] = "";

$camFotoReduzida = $_REQUEST["CAMINHO_FOTO_REDUZIDA"];
$camFoto   = $_REQUEST["CAMINHO_FOTO"]; 
$descPerfil  = $_REQUEST["DESC_PERFIL"];
$linkPerfil= $_REQUEST["LINK_PERFIL"];
if($_REQUEST["LINK_PERFIL_2"]!=""){
  $linkPerfil.=";".$_REQUEST["LINK_PERFIL_2"];
}

if (!empty($_FILES["ARQUIVO_NOVO"]["name"])) {
  $arrayAux = explode(".",$_FILES["ARQUIVO_NOVO"]["name"]);
  $tipoTesteImagem = strtolower($arrayAux[count($arrayAux)-1]);
  
  if ($tipoTesteImagem!="gif" && $tipoTesteImagem!="jpg" && $tipoTesteImagem!="jpeg" && $tipoTesteImagem!="png") {
     echo "<p align=\"center\"><font color=\"red\"><br><br>ERRO</font>, as fotos devem ser de extensão gif, jpg ou png.<br>".
  	     	"<a href=\"javascript:history.back()\">Voltar</a></p>";
  	     	exit();
  }
}
$erro = false;

/* 
// 500KB										
if ( $_FILES["ARQUIVO_NOVO"]["size"] > 500000  ) {
	echo "<br><br><div color='darkred'>Arquivo com tamanho maior que o permitido de 500 KB.</div>";
	$erro = true;
}
*/						
						
//faz o upload e conversão da foto
if ( ($_FILES["ARQUIVO_NOVO"]["size"] > 0) && !$erro)	{
  //Caminhos
  $caminhoRelativo = "fotos/". confNum($_SESSION["COD_PESSOA"]) . "/"; 
	$caminho = $caminhoUpload."/".$caminhoRelativo. "/";
  //nomes de arquivo
  $nomeArquivoTemporario = $_FILES["ARQUIVO_NOVO"]["tmp_name"];	
  $nomeArquivo = $_FILES["ARQUIVO_NOVO"]["name"];
	
  //cria o diretório se necessário
	if (! file_exists($caminho)) { mkdir($caminho); }
	
	  if (move_uploaded_file($nomeArquivoTemporario, $caminho . $nomeArquivo)) {	
    fotosPessoa($caminho,$nomeArquivo); 
   // duplica($caminho.$nomeArquivo, $nomeArquivo, $caminhoRelativo);
    // duplica($caminho.PREFIXO_MINI_FOTO.$nomeArquivo, PREFIXO_MINI_FOTO.$nomeArquivo, $caminhoRelativo);

    //Apagar arquivos antigos, somente se for diferente do anterior
		if ($camFoto!= "" && $camFoto!=$caminho.$nomeArquivo ) {		
      //echo "<br>Vou deletar: ".$caminhoUpload.$camFoto;
      unlink($caminhoUpload.$camFoto);    	
    	if (!empty($caminhoUpload1)) {
        delete_via_ftp($camFoto);
			}
      //deleta a mini foto antiga
      if (!empty($camFotoReduzida)) {
      	$variavelAuxiliar = $caminhoUpload.$camFotoReduzida;
  			unlink( $variavelAuxiliar);
  			if (!empty($caminhoUpload1)) {
  			  delete_via_ftp($camFotoReduzida);
  			}
			}
	  }
	  $camFoto=$caminhoRelativo.$nomeArquivo;
	  $camFotoReduzida=$caminhoRelativo.PREFIXO_MINI_FOTO.$nomeArquivo;
  }
	else {
		echo "<br><br> Erro de upload. ";
		$erro = true;
	}
}

//Verifica a existência do gd na instalação
if(!function_exists('imagecreate')&&!function_exists('imagecopyresampled')) {
  $erro = false;
}
/* POR ENQUANTO
// 30KB										
if ( $_FILES["MINI_FOTO"]["size"] > 30000  ) {
	echo "<br><br><div color='darkred'>Arquivo com tamanho maior que o permitido de 30 KB.</div>";
	$erro = true;
}
*/					

///COmentado o trecho abaixo para verificaÆo de problemas
					
//faz o upload duplicação da mini foto fornecida manualmente, 
//caso a instalação do gd nao esteja disponível ou entao
//se o script tenha forçado a mini foto manualmente
/*
if ( ($_FILES["MINI_FOTO"]["size"] > 0) && !$erro)	{  
  $caminhoRelativo = "/fotos/". confNum($_SESSION["COD_PESSOA"]) . "/";
	$caminho = $caminhoUpload.$caminhoRelativo;
  //cria o diretório se necessário
	if (! file_exists($caminho)) { mkdir($caminho); }
	
	if (move_uploaded_file($_FILES["MINI_FOTO"]["tmp_name"], $caminho .PREFIXO_MINI_FOTO. $_FILES["MINI_FOTO"]["name"])) {	
    duplica($caminho . $_FILES["MINI_FOTO"]["name"], PREFIXO_MINI_FOTO.$_FILES["MINI_FOTO"]["name"], "fotos/". confNum($_SESSION["COD_PESSOA"]) . "/");
    //Apagar arquivos antigos, somente se for diferente do anterior
		if ($camFotoReduzida!= "" && $camFotoReduzida!=$caminho.PREFIXO_MINI_FOTO.$_FILES["MINI_FOTO"]["name"] ) {		
      //echo "<br>Vou deletar: ".$camFoto;
        unlink($camFotoReduzida);    	
    	if (!empty($caminhoUpload1)) {
        $variavelAuxiliar = $caminhoUpload1.$camFotoReduzida;
        delete_via_ftp($variavelAuxiliar);
			}
	  }
	  
	  $camFotoReduzida=$caminhoRelativo.PREFIXO_MINI_FOTO.$_FILES["MINI_FOTO"]["name"];  
  }
	else {
		echo "<br><br> Erro de upload. ";
		$erro = true;
	}						
}
*/

/* CODIGO DA FOTO REDUZIDA
$erro2=false;	
if ( $_FILES["ARQUIVO_REDUZIDO"]["size"] > 0)
//if ( (! $erro) and ($_FILES["ARQUIVO_REDUZIDO"]["size"] > 0) )
{
	$caminho_RE = $caminhoUpload."/fotos/". confNum($_SESSION["COD_PESSOA"]) . "/";

	if (! file_exists($caminho_RE))
		mkdir($caminho_RE);		
	// Faz upload local do arquivo

	if(!$erro)		{
			if (move_uploaded_file($_FILES["ARQUIVO_REDUZIDO"]["tmp_name"], $caminho_RE . $_FILES["ARQUIVO_REDUZIDO"]["name"]))				{	
        resizeImagem($caminho_RE . $_FILES["ARQUIVO_REDUZIDO"]["name"], "PEQUENA");
        duplica($caminho_RE . $_FILES["ARQUIVO_REDUZIDO"]["name"], $_FILES["ARQUIVO_REDUZIDO"]["name"], "fotos/". confNum($_SESSION["COD_PESSOA"]) . "/");
				echo "<br><br> O arquivo foi carregado com sucesso. ";

        //	Apagar arquivo antigo, somente se for diferente do novo
				if($camFotoReduzida!= "" && $camFotoReduzida!="/fotos/". confNum($_SESSION["COD_PESSOA"]) . "/". $_FILES["ARQUIVO_REDUZIDO"]["name"] ) {
					$variavelAuxiliar = $caminhoUpload.$camFotoReduzida;
					unlink($variavelAuxiliar);
					delete_via_ftp($camFotoReduzida);
				}

				$caminho_RE=$caminho_RE.$_FILES["ARQUIVO_REDUZIDO"]["name"];
			//	$caminho_2_RE = str_replace("\\", "\\\\", $caminho_RE);	
				$camFotoReduzida=$caminho_RE;
			}	
			else 	{
				echo "<br><br> Erro de upload. ";
				$erro2 = true;
			}						
		}
	}
*/

if (!$erro) {	
	$sucesso = AlterarPerfil($camFoto,$camFotoReduzida, $descPerfil,$linkPerfil, $_SESSION["COD_PESSOA"]);
	
  if($sucesso) {echo "<script> location.href=\"./index.php\"</script>";}
}
else 	{
	echo "ERRO na Alteracao<br>".
		"<a href=\"javascript:history.back()\">Voltar</a>";
}
?>
	</td>
	</tr>
	</table>
</body>
</html>

<?
/*
 * Coloca a foto da pessoa no tamanho padrão e 
 * gera mini foto para ser usada nos recursos (forum, chat, etc) 
 */ 
function fotosPessoa($caminho,$nomeArquivo) {
  
  if (!function_exists('imagecreate')) {  return false; }
  if (!function_exists('imagecopyresampled')) { return false; }
  
  redimensionaImagem($caminho,$nomeArquivo,LARGURA_FOTO,ALTURA_FOTO);
  redimensionaImagem($caminho,$nomeArquivo,LARGURA_FOTO_PEQUENA,ALTURA_FOTO_PEQUENA,$caminho.PREFIXO_MINI_FOTO.$nomeArquivo);
}
/*
 * Faz o redimensionamento de uma imagem
 * Atualmente suporta gif, jpg e png  
 */ 
function redimensionaImagem($caminho,$nomeArquivo,$new_width, $new_height,$nomeNovo='') {

  $arquivoImagem = $caminho.$nomeArquivo;
  //usa o mesmo nome da imagem para sobrepor a imagem com tamanho refeito (caso da imagem normal)
  //ou entao grava em uma nova imagem (caso da mini foto)
  if (empty($nomeNovo)) { $nomeNovo=$arquivoImagem; } 
  
  //pega o tipo de imagem
  $arrayAux = explode(".",$nomeArquivo);
 
  $tipoImagem = strtolower($arrayAux[count($arrayAux)-1]);
  
  // Get  as dimensoes atuais
  list($width, $height) = getimagesize($arquivoImagem);
  //nova imagem. Usa imagecreatetruecolor se possivel, i.e., nao for .gif 
  if ($tipoImagem=='gif') {
    $imagemRedimensionada = imagecreate($new_width, $new_height);
  }
  else {
    $imagemRedimensionada = imagecreatetruecolor ($new_width, $new_height);
  }

  //recupera a imagem postada pelo usuario
  if ($tipoImagem=='gif') {
    $image = imagecreatefromgif($arquivoImagem);
  } 
  else if ($tipoImagem=='jpeg' || $tipoImagem=='jpg') {
    $image = imagecreatefromjpeg($arquivoImagem);
  }
  else if ($tipoImagem=='png' ) {
    $image = imagecreatefrompng($arquivoImagem);
  }
  //Redimensiona a imagem  
  imagecopyresampled($imagemRedimensionada, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
  
  //echo "<br>Vou gravar: ".$nomeNovo;
  //Salva a imagem
   if ($tipoImagem=='gif') {
    imagegif($imagemRedimensionada, $nomeNovo);
  } 
  else if ($tipoImagem=='jpeg' || $tipoImagem=='jpg') {
    imagejpeg($imagemRedimensionada, $nomeNovo);
  }
  else if ($tipoImagem=='png' ) {
    imagepng($imagemRedimensionada, $nomeNovo);
  }
}
?>
	Œ3þ	!