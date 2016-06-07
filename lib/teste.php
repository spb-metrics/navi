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

  $data = date('Y-m-d H:i:s');
  echo $data;
  echo "<br>".substr($data,0,10)." ".(substr($data,11,2)-1).substr($data,13,6);
	if (date("I")) { //se horário de verão estiver ativado, substrai uma hora
	  $hora= (substr($data,11,2)-1);
	  if ($hora==-1) { $hora= '00'; }
	  $data = substr($data,0,10)." ".$hora.substr($data,13,6);
	  echo "<br>teste:".$data;
  }
  
  echo "<BR>Dia da Semana: ".date('l');
  echo "<BR>HV: ".date('I');
?>
