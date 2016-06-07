<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de d�vidas e/ou sugest�es, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, port�o K. Porto Alegre - RS. CEP: 90035-003

Este programa � software livre; voc� pode redistribu�-lo e/ou modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme publicada pela Free Software Foundation; tanto a vers�o 2 da Licen�a, como (a seu crit�rio) qualquer vers�o posterior.

    Este programa � distribu�do na expectativa de que seja �til, por�m, SEM NENHUMA GARANTIA;
    nem mesmo a garantia impl�cita de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.
    Consulte a Licen�a P�blica Geral do GNU para mais detalhes.
    

    Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU junto com este programa;
    se n�o, escreva para a Free Software Foundation, Inc., 
    no endere�o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
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
    //primeiro verifica se a foto nao est� 
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

  //o padrao se nao passar nada � a foto com tamanho normal
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

//tentativa de ler as fotos por partes para nao causar problemas de mem�ria

$chunksize = 1*(1024*1024); 
$handle = fopen($caminhoLocal, "rb");
while (!feof($handle)) { echo  fread($handle, $chunksize); }
fclose($handle);
?>
