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

class LinkImagem {

  var $link,$imagem,$texto,$quebraLinhaTexto;

  function LinkImagem($param) {
    $this->link=$param["link"];
    $this->imagem=$param["imagem"];
    $this->texto=$param["texto"];
    $this->quebraLinhaTexto=$param["quebraLinhaTexto"];
    $this->prop = $param["prop"];
  }

  function addProp($nome,$valor) {
    $this->prop[$nome] = $valor;
  }

  function imprime() {
    global $urlImagem;
    $ret="";
    if (!empty($this->link)) { $ret = "<a href=\"".$this->link."\">"; } 
    $ret.= "<img src=\"".$urlImagem."/".$this->imagem."\" ";
    //Insere propriedades aqui, como eventos
    if (!empty($this->prop)) {
      foreach($this->prop as $nome => $valor) {
        $ret .= " ".$nome."=\"".$valor."\"";
      }
    }
    $ret .= " border=\"no\">";
    $ret .= $this->quebraLinhaTexto.$this->texto;
    if (!empty($this->link)) { $ret.="</a>"; }

    return $ret;
  }
}

class Voltar extends LinkImagem {

  var $link,$imagem,$texto,$quebraLinhaTexto;

  function Voltar($link="",$texto="",$imagem="voltar.gif",$quebraLinhaTexto="<br>") {
    $param["link"]=$link;
    $param["imagem"]=$imagem;
    $param["texto"]=$texto;
    $param["quebraLinhaTexto"]=$quebraLinhaTexto;
    $this->LinkImagem($param);
  }

}


?>