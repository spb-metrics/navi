<?php
ini_set('allow_call_time_pass_reference','On');
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
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include("config.php");
include("lib/pessoa.inc.php");
include("lib/aluno.inc.php");
//include("alunos/foto.php");
include("lib/professor.inc.php");
session_name(SESSION_NAME); session_start(); security(); session_write_close();
//somente usuários cadastrados


function printHeader() {
  global $url;
  
  echo "<link rel=\"stylesheet\" href=\"".$url."/cursos.css\" type=\"text/css\">";
  echo "<link rel=\"stylesheet\" href=\"".$url."/indicadoresaluno/indicadoresaluno.css\" type=\"text/css\">";
  
  echo "<center>";
  
  echo "<big><big><b>Consulta por Pessoas</big></big></b><br>";
  echo "<a href='#' onClick='window.close();'>Fechar Janela</a><br><br>";
}

$pessoa = new Pessoa();
$busca = $pessoa->buscaPessoa($_REQUEST["BUSCA_PESSOA"]);

if ($busca===1) {  //pessoa identificada


  printHeader();
  echo $pessoa->isOnline()."<big><big><big><b>".$pessoa->NOME_PESSOA."</big></big></big></b></a>";
  echo "&nbsp;&nbsp;<a href='".$url."/alunos/recados.php?linkExterno=1&COD_PESSOA_RECEBE=".$pessoa->COD_PESSOA."' title='Clique para ver e enviar recados para ".$pessoa->NOME_PESSOA."'>";
  echo "<img src='".$urlImagem."/recados.jpg' border='0'></a>";
  echo "<table cellspacing='0px' cellpadding='5px' border='0' style='border:1px outset; width:100%'>";
  echo "<tr>";
  //mostra foto
  echo "<td align='left' valign='top' width='20%'><img src='alunos/foto.php?COD_PESSOA=".$pessoa->COD_PESSOA."' height='".ALTURA_FOTO."' width='".LARGURA_FOTO."' border='1'></td>";
  //exibe descricao do perfil
  echo "<td align='left' valign='top'  width='50%'>".nl2br($pessoa->DESC_PERFIL)."</td>";


  echo "<td align='left'  width='30%'>"; 
  //exibir perfil e cursos/disciplinas/turmas, comunidades  
  //exibe atuação como aluno
  $codAluno = $pessoa->isAluno();
  if ($codAluno) {
    echo "<h4>Atuação como Aluno</h4>";
    $aluno = new Aluno($codAluno);    
    mostraPartipacaoPessoa($aluno);
  }

  //exibe atuação como professor
  $codProfessor = $pessoa->isProfessor();
  if ($codProfessor) {
    echo "<h4>Atuação como Professor</h4>";
    $professor = new Professor($codProfessor);    
    mostraPartipacaoPessoa($professor);
  }
  // repetir o codigo acima para administrador de nivel, caso deseje-se exibir
  
  echo "</td></tr></table>"; 
  
}
else if(!empty($busca->records)) { //pessoa nao identificada, exibir listagem de pessoas com link do codPessoa
  printHeader();
  
  echo "Foram encontradas ".count($busca->records). " pessoas. <br>";
  echo "Clique na pessoa para ver o perfil ou refaça a busca. <br><br><br>";
  //colocar aqui outro formulario para enviar a busca novamente
  
  foreach($busca->records as $p) {
    echo "<a href='consultar.php?BUSCA_PESSOA=".$p->COD_PESSOA."'>";
    echo $p->NOME_PESSOA;
    
    echo "</a><br>";
  }
}
else {
  echo "<script> alert('Pessoa não encontrada!'); window.close();</script>";
}


function mostraPartipacaoPessoa($papelPessoa) {
  
  $niveisFormais = $papelPessoa->getInstanciasRelacionamento();
  
  echo "<b>Atuação Formal</b><br>";
  if (!empty($niveisFormais)) {
    while(list(, $hierarquia) = each($niveisFormais) ) {      
      while(list(, $instancias) = each($hierarquia[1]) ) {
         $str='';
         foreach($instancias as $campo=>$nome) {
           if (substr($campo,0,4)=='nome') {     
             $str .= $nome ." - ";
           }
         }
         echo rtrim($str," - ")."<br>";
      }
      
    }    
  }
  else {
    echo "Sem relacionamentos formais.";
  }
  
  $comunidades = $papelPessoa->getComunidades();
  echo "<br><b>Comunidades Temáticas</b><br>";
  if (!empty($comunidades->records) ) {    
    while(list(, $obj) = each($comunidades->records) ) {
      echo $obj->nome."<br>";
    }    
  }
  else {
    echo "Não participa de nenhuma comunidade.";
  }
}

/*

if ( mysql_num_rows($consultaAlunos)==0 || mysql_errno()) { 
  echo "<center>N&atilde;o foram encontradas pessoas.</center>";
}
else {
  echo "<table cellpadding=\"3\" cellspacing=\"1\"><tr>";
  echo "<th>Foto</th>";
  echo "<th>Nome</th>";
  echo "<th>Atuação</th>";
  echo "<th>Curso</th>";
  echo "<th>Disciplina</th>";
  echo "<th>Turma</th>";
  echo "</tr>";

  while($linha = mysql_fetch_array($consultaAlunos) ){
    echo "<tr>";
    echo "<td>";
    if ($linha["Codigo Aluno"] != null){
      $tipoPessoa = "Aluno";
    }else{

    $tipoPessoa = $linha["Codigo Professor"] != null ? "Professor" : "Administrador";
    }
    if  ($linha["foto"] != null && $linha["foto"] != ''){
      echo "<img src='alunos/foto.php?COD_PESSOA=".$linha["Codigo Pessoa"]."&CASE=FOTO_REDUZIDA' height='30' width='40' border='none'>";
  
     
  
    }
    echo "</td>";
    echo "<td>".$linha["Nome Pessoa"]."&nbsp;</td>";
    echo "<td>".$tipoPessoa."&nbsp;</td>";   
   // echo "<td>".$linha["Curso do ".$tipoPessoa."]."&nbsp;</td>";
    echo "<td>".$linha["Curso do ".$tipoPessoa]."&nbsp;</td>";
    echo "<td>".$linha["Disciplina do ".$tipoPessoa]."&nbsp;</td>";
    echo "<td>".$linha["Nome da Turma do ".$tipoPessoa]."&nbsp;</td>";
    echo "</tr>";
  }
  echo "</table>";
}
*/
?>
