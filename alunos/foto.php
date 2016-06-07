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

//include("../config.php");
include("../online.inc.php");
include("../lib/funcoesftp.inc.php");
//include($caminhoBiblioteca."/perfil.inc.php");
//include("../configarquivos.php");
//apenas a conexao ao BD
mysql_connect(BD_HOST, BD_USER,BD_SENHA);
mysql_select_db(BD_NAME);
$result=mysql_query("SELECT P.FOTO,P.FOTO_REDUZIDA FROM pessoa P WHERE P.COD_PESSOA=".quote_smart($_REQUEST["COD_PESSOA"])); 
$linha = mysql_fetch_assoc($result);

$caminhoUpload .= '/';

switch($_REQUEST["CASE"]) {

  case "FOTO_REDUZIDA":
    //primeiro verifica se a foto nao está 
  	if((substr($linha["FOTO_REDUZIDA"],0,4))== "http"){
  		$caminhoLocal = $linha["FOTO_REDUZIDA"];
  		header("Location: " . $caminhoLocal); exit;
  	}  	
    
  	if(!empty($linha["FOTO_REDUZIDA"])){
  	  $caminhoLocal= $caminhoUpload.$linha["FOTO_REDUZIDA"];
  	  //echo $caminhoLocal;
  	}
    else{
			$caminhoLocal= $caminhoUpload."/fotos/nophoto/nophotoreduzida.jpg";  
  	}
   	
  	if (!file_exists($caminhoLocal))	{
  		if(!empty($linha["FOTO_REDUZIDA"])){

			$caminho = explode("/", $linha["FOTO_REDUZIDA"]);
			$caminhoUpload = $caminhoUpload.$caminho[1].'/'.$caminho[2];
			atualizaFoto($linha["FOTO_REDUZIDA"],$caminho[3], $caminhoUpload);
			
  			$caminhoLocal = $caminhoUpload1.$linha["FOTO_REDUZIDA"];
  		}
      else{
  			$caminhoLocal = $caminhoUpload1."/fotos/nophoto/nophotoreduzida.jpg";
  		}
  	}
    break;

  //o padrao se nao passar nada é a foto com tamanho normal
  default:
  	if((substr($linha["FOTO"],0,4))== "http") 	{
  		$caminhoLocal = $linha["FOTO"];
  		header("Location: " . $caminhoLocal); exit;
  		exit;
  	}

  	if(!empty($linha["FOTO"])){
  	  $caminhoLocal= $caminhoUpload.$linha["FOTO"];
	  
  	}
    else{
  		$caminhoLocal= $caminhoUpload."/fotos/nophoto/nophoto.jpg";
  	}
  
  	if(!file_exists($caminhoLocal)) {  		
  		if(!empty($linha["FOTO"])) {
//  			$caminhoLocal = $caminhoUpload1.$linha["FOTO"];
			$caminho = explode("/", $linha["FOTO"]);

			$caminhoUpload = $caminhoUpload.$caminho[1].'/'.$caminho[2];
			atualizaFoto($linha["FOTO"],$caminho[3], $caminhoUpload);
//			atualizaFoto($linha["FOTO"],$caminho[3],$caminhoUpload); 'c:/upload/'
  			$caminhoLocal = $caminhoUpload.'/'.$caminho[3];

  		}
      else{
  			$caminhoLocal= $caminhoUpload1."/fotos/nophoto/nophoto.jpg";
  		}
  	}
  	
  	break;
}
if (!file_exists($caminhoLocal)) { die; exit; }


$separaNomeArquivo = explode(".",basename($caminhoLocal));
$extensao = $separaNomeArquivo[count($separaNomeArquivo)-1]; //extensao
$tipoArquivo = "image/".$extensao;

//atualizaFoto($caminhoLocal,$caminhoUpload.$linha["FOTO"],'/20');

@set_time_limit(0);
header ("Content-type: ".$tipoArquivo); 
//@readfile($caminhoLocal);

//tentativa de ler as fotos por partes para nao causar problemas de memória

$chunksize = 1*(1024*1024); 
$handle = fopen($caminhoLocal, "rb");
while (!feof($handle)) { echo  fread($handle, $chunksize); }
fclose($handle);
?>
