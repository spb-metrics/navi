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

ini_set("display_errors",1); error_reporting(E_ALL);
include_once ("../config.php");
//include($caminhoBiblioteca."/utils.inc.php");
include($caminhoBiblioteca."/perfil.inc.php");
//include($caminhoBiblioteca."/funcoesftp.inc.php");
session_name(SESSION_NAME); session_start(); //security();
set_time_limit(0);

/*
 * Funções deste arquivo
 */ 
 
//Lista de pessoas com suas fotos

/*
//Atualiza a foto da pessoa
function atualizaBDFotos($campoFoto,$codPessoa,$caminhoFoto=""){
 $sql= "UPDATE pessoa SET $campoFoto=".quote_smart($caminhoFoto)." WHERE COD_PESSOA='".$codPessoa."'";
 mysql_query($sql);
 return (! mysql_errno());
}
*/
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
  else if ($tipoImagem=='bmp'){
  
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
  else if($tipoImagem=='bmp'){
   
  }

}

function confNum($Num)	{
	if ($Num < 10) 
		return "0" . strval($Num);
	else		
		return strval($Num);
} 

$arquivoOrfao=0;
$fotoOrfa=0;
$fotoMiniOrfa=0;
$extensaoDiferente=0;
$extensaoMiniDiferente=0;
$tamanhoDiferente=0;
$tamanhoMiniDiferente=0;
$pessoaSemFoto=0;
$pessoaSemMiniFoto=0;

$verificar = checarFotosPessoas();



foreach($verificar->records as $caminhoLocal){
 
  $caminhoTeste= $caminhoUpload."/fotos/".confNum($caminhoLocal->COD_PESSOA)."/";
  $caminhoFoto= $caminhoUpload.$caminhoLocal->FOTO;
  $caminhoMiniFoto = $caminhoUpload.$caminhoLocal->FOTO_REDUZIDA;

  //testes iniciais
  //verifica se o endereço do arquivo aponta para uma arquivo existente
  if(!file_exists($caminhoFoto)){
   $fotoOrfa=$fotoOrfa+1;
   //echo "<br>".$caminhoFoto;
   
  }
  
  if(!file_exists($caminhoMiniFoto)){
    $fotoMiniOrfa=$fotoMiniOrfa+1;
    //echo "<br>".$fotoMiniOrfa."=>".$caminhoLocal->COD_PESSOA."<BR>";
    
  }
  
  //verifica quais as pessoa sem endereços de foto
 
  if(empty($caminhoLocal->FOTO)){
     $pessoaSemFoto= $pessoaSemFoto+1;
  }
  if(empty($caminhoLocal->FOTO_REDUZIDA)){
     $pessoaSemMiniFoto= $pessoaSemMiniFoto+1;
  }
  
  //testes de verificação dos arquivos exixtentes na plataforma
  
  if((!empty($caminhoLocal->FOTO)) || (!empty($caminhoLocal->FOTO_REDUZIDA))) {
    $achou=0;
    $achouMini=0;

    //TESTA AQRUIVOS QUE ESTÃO NA PASTA MAS NÃO ESTÃO NO BD
    foreach (glob($caminhoTeste."*.*") as $fileName){
      if($fileName==$caminhoFoto){
        $achou=1;
        //checa tamanho da foto
        list($width, $height) = getimagesize($caminhoFoto);
        if(($width!=LARGURA_FOTO) || ($height!=ALTURA_FOTO)){
          $tamanhoDiferente=$tamanhoDiferente+1;
        }
        $nomeArquivo = basename($caminhoFoto);
        $arrayAux = explode(".",$nomeArquivo);
        $tipoImagem = strtolower($arrayAux[count($arrayAux)-1]);
        if(($tipoImagem!="gif") && ($tipoImagem!="jpg") && ($tipoImagem!="jpeg") && ($tipoImagem!="png")){
          
           // unlink($fileName); 
           //atualizaBDFotos("FOTO",$caminhoLocal->COD_PESSOA);
             $extensaoDiferente=$extensaoDiferente+1;
        }
        else {
         //redimensionaImagem($caminhoTeste,$nomeArquivo,LARGURA_FOTO, ALTURA_FOTO);
        }        
      }
      else {
        if($fileName==$caminhoMiniFoto){
          $achouMini=1;
          list($width, $height) = getimagesize($caminhoMiniFoto);
          if(($width!=LARGURA_FOTO_PEQUENA) || ($height!=ALTURA_FOTO_PEQUENA)){
            $tamanhoMiniDiferente=$tamanhoMiniDiferente+1;
            $nomeArquivo = basename($fileName);
            $arrayAux = explode(".",$nomeArquivo);
            $tipoImagem = strtolower($arrayAux[count($arrayAux)-1]);
            if(($tipoImagem!="gif") && ($tipoImagem!="jpg") && ($tipoImagem!="jpeg") && ($tipoImagem!="png")){
                 // $extensaoMiniDiferente=$extensaoMiniDiferente+1;
                 echo "<br>mini com extensão incorreta".$fileName."-".$fileName."=".$tipoImagem."<br>";
                // unlink($fileName); 
                // atualizaBDFotos("FOTO_REDUZIDA",$caminhoLocal->COD_PESSOA);
             }
          
          
          }
        }
        //arquivo não atrelado ao banco
        else{
          $arquivoOrfao=$arquivoOrfao+1;
        }
       
        //else  {
           //redimensionaImagem($caminhoTeste,$nomeArquivo,LARGURA_FOTO_PEQUENA, ALTURA_FOTO_PEQUENA);
        //}

      }
    }
    //nao tem a foto
   // if (!$achou) { }
  }

 
  
}

echo "Arquivos nao atrelados ao banco => ".$arquivoOrfao;

echo "<hr><br><br>Fotos extensao incorreta => ". $extensaoDiferente;
echo "<br>Fotos extensao mini incorreta => ". $extensaoMiniDiferente;

echo "<hr><br><br>Fotos tamanho incorreto=> ".$tamanhoDiferente;
echo "<br>Mini-Fotos tamanho incorreto => ".$tamanhoMiniDiferente;
 
echo "<hr><br><br>Fotos do BD sem o arquivo=> ".$fotoOrfa;
echo "<br>Mini-Fotos do BD sem o arquivo => ".$fotoMiniOrfa;

echo "<hr><br><br>Pessoas sem Foto => ".$pessoaSemFoto;
echo "<br>Pessoas sem Mini-Foto => ".$pessoaSemMiniFoto;




?>
