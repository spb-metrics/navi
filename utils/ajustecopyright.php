<?php
$searchString='<?php
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
?>';

//$searchString='';


scanFile('/cursosnavi/fabio/multinavi2007');

function scanFile($dir) {
  global $searchString;
  
  foreach (glob($dir."/"."*") as $filename) {
    if ($filename!='.' && $filename!='..' && (filetype($filename)=='dir' ) ) {     
      scanFile($filename);
    }
  }
/*
  foreach (glob($dir."/"."*.js") as $filename) {
    echo $filename."<br/>";
    $changed = str_replace($searchString,"",file_get_contents($filename));
    
    $handle = fopen($filename,'wb');
    fwrite($handle,$changed);
    fclose($handle);
        
  }*/
}

?>
