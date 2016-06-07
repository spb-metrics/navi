<?
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

//

include("../config.php");
$arquivos = new RDCLQuery("SELECT * FROM arquivo ORDER BY COD_ARQUIVO DESC"); 
$titulo = array("COD_ARQUIVO","COD_PESSOA","CAMINHO_LOCAL_ARQUIVO"
                ,"TAMANHO_ARQUIVO","TIPO_ARQUIVO","DESC_ARQUIVO"    );

$tagIniTitulo = "<TH>" ; 
$tagFimTitulo = "</TH>" ;
$tagIniDado = "<TD>";
$tagFimDado = "</TD>";
$contSerlocal=0;
$contServRemoto=0;
echo "<table border='1'><tr>";
foreach($titulo as $t)  { echo $tagIniTitulo.$t.$tagFimTitulo; }
echo $tagIniTitulo."ARQUIVO LOCAL".$tagFimTitulo;
echo $tagIniTitulo."ARQUIVO NO SERVIDOR".$tagFimTitulo;
echo "</tr>";
                 
foreach($arquivos->records as $arquivo) {
 
  echo "<tr>";
  //dados do arquivo
  foreach($titulo as $t)  { echo $tagIniDado.$arquivo->$t.$tagFimDado; }
 
  //arquivo local 
  echo $tagIniDado;
  if (!file_exists($caminhoUpload.$arquivo->CAMINHO_LOCAL_ARQUIVO)) {
    echo "X";
    $contSerlocal=$contSerlocal+1;
    echo $contSerlocal ;
  } else { echo "OK"; echo $contSerlocal;}
  echo $tagFimDado;

  //arquivo no outro servidor
  echo $tagIniDado;
 
  $handle = @fopen($caminhoUpload1.$arquivo->CAMINHO_LOCAL_ARQUIVO, 'r');
  if (!$handle) {
    echo "X";
    $contServRemoto=$contServRemoto+1;
    echo $contServRemoto;
  } 
  else { 
    echo "OK"; echo $contServRemoto; fclose($handle);
    
  }
 
  echo $tagFimDado;

  echo "</tr>";
}
echo $contSerlocal;
echo $contServRemoto;
echo "</table>";
echo "<table border='1'><tr><td>Numero de arquivos que faltam no servidor local:".$contSerlocal."<br>numero de arquivos que faltam no servidor remoto:".$contServRemoto."</td></tr></table>";
?>
