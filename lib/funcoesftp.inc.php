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

function atualizaFoto($origem,$destino,$pastaDestino) {
//Origem, de onde vem, tendo como base o servidor/upload_v2
//Destino � o nome do arquivo de destino
//PastaDestino � a localiza��o, de diret�rios onde ficar� o arquivo copiado

//tem como objetivo copiar uma foto que consta em servidor ftp para a m�quina local

// Dados do servidor que est� online
  global $ftp_server;
  global $ftp_user;
  global $ftp_pass;

//$ftp_server = 'monareta.ea.ufrgs.br';
/*
$ftp_server =  '143.54.31.2'; // Monareta, como o Berlineta far�
$ftp_user   = 'upload_v2';
$ftp_pass   = 'ja53&9k@c1';*/

if (!file_exists($pastaDestino))
mkdir($pastaDestino);

$conn_id = ftp_connect($ftp_server);

$o = '/upload_v2'.$origem;

ftp_login($conn_id, $ftp_user, $ftp_pass);

ftp_pasv($conn_id, true); //isso resolveu o problema, agora c�pia esta ok, m�todo de conex�o com o servidor
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
  die('Funcionalidade de arquivos em manuten��o, em breves instantes voltar� a operar normalmente');
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

//Usado para gerar um nome de arquivo unico a cada postagem nova ou altera��o
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
