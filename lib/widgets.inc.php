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


function gauge($percentual) {
  $percentual = round($percentual,2);
  $colunaVazia=100-$percentual;
  
  $quad1=75;
  $quad2=50;
  $quad3=25;
  $quad4=0;
  
  //cores diferentes de acordo com o percentil
  //por hora separado em quatro quadrantes  
  if ($percentual>=$quad1)      { $bgColor = "navy";      }  
  else if ($percentual>=$quad2) { $bgColor = "darkgreen"; }  
  else if ($percentual>=$quad3) { $bgColor = "yellow";    }  
  else if ($percentual>$quad4)  { $bgColor = "darkred";   }  
  else {    $bgColor = "gray";   }
  
    
  echo "<table cellpadding='0' cellspacing='0'><tr><td>";
  echo "<table style='border:1px solid #000000; height:15px;' cellpadding='0' cellspacing='0' width=600><tr>";  
  echo "<td bgcolor='".$bgColor."'  width=\"".$percentual."%\" style=\"text-align: right;\"></td>";
  echo "<td bgcolor='white' width=\"".$colunaVazia."%\"></td>";
  echo "</tr></table>"; 
  echo "<td style=' color:#000000; font-size:12px; font-weight:bold;'>&nbsp;".$percentual."%</td>";
  echo "</tr></table>"; 
  
}

?>
