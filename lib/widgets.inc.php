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
