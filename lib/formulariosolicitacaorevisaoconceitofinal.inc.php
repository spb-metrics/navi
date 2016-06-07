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


class formulariosolicitacaorevisaoconceitofinal { 
  /*
  // devolve mes por extenso
  */
  function pegaMesExtenso($mes){
    switch ($mes){
      case 01: $mes = "Janeiro"; break;
      case 02: $mes = "Fevereiro"; break;
      case 03: $mes = "Março"; break;
      case 04: $mes = "Abril"; break;
      case 05: $mes = "Maio"; break;
      case 06: $mes = "Junho"; break;
      case 07: $mes = "Julho"; break;
      case 08: $mes = "Agosto"; break;
      case 09: $mes = "Setembro"; break;
      case 10: $mes = "Outubro"; break;
      case 11: $mes = "Novembro"; break;
      case 12: $mes = "Dezembro"; break;
    }
    //$data = date("d")." de ".$mes." de ".date("Y");
    return $mes;
  }

  /*
  // grava formulario novo
  */  
  function gravaFormulario($codInstanciaGlobal,$cartaoIdentificacao,$codAluno,$conceitoFinal,$polo,$msg,$data,$cidade,$resposta,$justificativa) {
    $sql="insert into formulario_revisaoconceito_aluno (codInstanciaGlobal, cartaoIdentificacao, polo,codAluno, conceitoFinal, msg, data, cidade, resposta, justificativa) 
    values(".quote_smart($codInstanciaGlobal).",".quote_smart($cartaoIdentificacao).",".quote_smart($polo).",".quote_smart($codAluno).",".
    quote_smart($conceitoFinal).",".quote_smart($msg).",".quote_smart($data).",".quote_smart($cidade).",".quote_smart($resposta).",".quote_smart($justificativa).")";

    return mysql_query($sql);
  }

  /*
  // atualiza o formulario
  */
  function atualizaFormulario($codInstanciaGlobal,$cartaoIdentificacao,$codAluno,$conceitoFinal,$polo,$msg,$data,$cidade,$resposta,$justificativa){
    $sql="update formulario_revisaoconceito_aluno set
    cartaoIdentificacao=".quote_smart($cartaoIdentificacao).",
    conceitoFinal=".quote_smart($conceitoFinal).",
    polo=".quote_smart($polo).",
    msg=".quote_smart($msg).",
    data=".quote_smart($data).",
    cidade=".quote_smart($cidade).",
    resposta=".quote_smart($resposta).",
    justificativa=".quote_smart($justificativa)."
    where codInstanciaGlobal=".quote_smart($codInstanciaGlobal)." and codAluno=".quote_smart($codAluno);
//echo$sql;die;
    return mysql_query($sql);
  }

  /*
  // caso seja aluno, pega determinado formulario, se professor, pega todos da instancia
  */
  function pegaFormulario($codInstanciaGlobal,$codAluno=''){
    $sql="select * from formulario_revisaoconceito_aluno where codInstanciaGlobal = ".quote_smart($codInstanciaGlobal);
    if ($codAluno){
      $sql.=" and codAluno=".quote_smart($codAluno);
    }
    $sql.=" order by data";
    return mysql_query($sql);
  }

  /*
  // pega apenas o nome do aluno atraves do codAluno
  */
  function pegaNomeAluno($codAluno){
    $sql="select NOME_PESSOA from pessoa as p, aluno as a where p.COD_PESSOA=a.COD_PESSOA and a.COD_AL=".quote_smart($codAluno);
    return mysql_query($sql);
  }

  /*
  // apaga um formulario
  */
  function deletaFormulario($codInstanciaGlobal,$codAluno){
    $sql="delete from formulario_revisaoconceito_aluno where codInstanciaGlobal = ".quote_smart($codInstanciaGlobal)." and codAluno=".quote_smart($codAluno);
    return mysql_query($sql);
  }
  
  /*
  // consulta o periodo gravado
  */
  function pegaPeriodo($codInstanciaGlobal){
    $sql="select * from formulario_solicitacao_revisao where codInstanciaGlobal=".quote_smart($codInstanciaGlobal);
    return mysql_query($sql);
  }
  
  /*
  // atualiza ou cria um periodo caso nao exista
  */   
    function atualizaPeriodo($codInstanciaGlobal,$dataInicio,$dataFim){
    $sql="update formulario_solicitacao_revisao set dataInicio=".quote_smart($dataInicio).", dataFim=".quote_smart($dataFim)." where codInstanciaGlobal=".quote_smart($codInstanciaGlobal);
    $result = mysql_query($sql);
    
    if(mysql_affected_rows()!=0){
      return $result;
    }
    else{
      $sql="insert into formulario_solicitacao_revisao values(".quote_smart($codInstanciaGlobal).",".quote_smart($dataInicio).",".quote_smart($dataFim).")";
      return mysql_query($sql);
    }
  }
  
}

?>
