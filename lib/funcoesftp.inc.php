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

function atualizaFoto($origem,$destino,$pastaDestino) {
//Origem, de onde vem, tendo como base o servidor/upload_v2
//Destino é o nome do arquivo de destino
//PastaDestino é a localização, de diretórios onde ficará o arquivo copiado

//tem como objetivo copiar uma foto que consta em servidor ftp para a máquina local

// Dados do servidor que está online
  global $ftp_server;
  global $ftp_user;
  global $ftp_pass;

//$ftp_server = 'monareta.ea.ufrgs.br';
/*
$ftp_server =  '143.54.31.2'; // Monareta, como o Berlineta fará
$ftp_user   = 'upload_v2';
$ftp_pass   = 'ja53&9k@c1';*/

if (!file_exists($pastaDestino))
mkdir($pastaDestino);

$conn_id = ftp_connect($ftp_server);

$o = '/upload_v2'.$origem;

ftp_login($conn_id, $ftp_user, $ftp_pass);

ftp_pasv($conn_id, true); //isso resolveu o problema, agora cópia esta ok, método de conexão com o servidor
   				 //'C:/upload/Beaver.bmp' 'fotos/4/Beaver.bmp'
ftp_get($conn_id, $pastaDestino.'/'.$destino, $o ,FTP_BINARY);

ftp_close($conn_id);

}

//duplica os arquivos de um lugar para o outro
function duplica($origem,$destino,$pastaDestino)	{
  global $ftp_server;
  global $ftp_user;
  global $ftp_pass;
  if ($ftp_server=='') { return 1; }

  $ret=1;
  $conn_id = ftp_connect($ftp_server);

  if (!@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    $ret=0; 
  }
  else  { //faz a copia
    @ftp_mkdir($conn_id,$pastaDestino);
	  if(!@ftp_put($conn_id, $pastaDestino."/".$destino,$origem,FTP_BINARY))    {    $ret=0;   }
  }
		
  @ftp_close($conn_id);
  /*
  echo "<!--";
  echo "<br>PASTA DESTINO ".$pastaDestino; 
  echo "<br>DESTINO ".$destino; 
  echo "<br>ORIGEM ".$origem; 
  echo "retorno: ".$ret;
  echo " -->";
  die('Funcionalidade de arquivos em manutenþÒo, em breves instantes voltarß a operar normalmente');
  */
  
  return $ret;
}

function delete_via_ftp($file)	{
  global $ftp_server;
  global $ftp_user;
  global $ftp_pass;
  if ($ftp_server=='') { return 1; }

  $ret=1;
	$conn_id = @ftp_connect($ftp_server);
	if (!@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
	  $ret=0;
	}
	else {
	  while ($file{0}=='/') { $file=substr($file,1,strlen($file)); }
	  if (!@ftp_delete($conn_id, $file)) { $ret=0;  }
  }
  @ftp_close($conn_id);

  /*
  echo "<br>FILE ".$file;
  echo "retorno: ".$ret;
  die();
  */
  return $ret;
}

//Usado para gerar um nome de arquivo unico a cada postagem nova ou alteração
function fileNameFromMicrotime() { 
  list($usec, $sec) = explode(" ", microtime()); 
  $u  =explode(".",$usec); 
  
  return $sec.$u[1]; 
} 


//copia a foto do servidor para o local 


/*
///==============================================================================================================
	function apagarArquivo($caminhoArquivo)
	{	GLOBAL 	$caminhoUpload;
		GLOBAL  $caminhoUpload1;
			$caminhoArquivo= str_replace("\\\\", "\\",$caminhoArquivo);
			$erro = false;
				if(file_exists($caminhoUpload.$caminhoArquivo))
				{
					
					if(!unlink( $caminhoUpload.$caminhoArquivo))
					{
						$erro = true;
					}
					
				}
				
			if(!delete_via_ftp($caminhoArquivo))
			{
				echo " Erro no deletar arquivo no outro servidor";
				
			}

			if($erro)
			{
				return false;
			}
			else
			{	
				return true;
			}
		
	}
//===================================================================================================
*/

?>
