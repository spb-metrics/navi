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


include("../config.php");
include($caminhoBiblioteca."/formulariosolicitacaorevisaoconceitofinal.inc.php");
session_name(SESSION_NAME); session_start(); security();
//array de pessoas autorizadas a editar o formulário
$gerentes[]=3123; //Artur
$gerentes[]=3123; //Tonia
$gerentes[]=53;   //maria beatriz
$gerentes[]=2306; //usuario dfl, fábio
$gerentes[]=1125; //deise
$gerentes[]=15; //daniel

//se gerente, pega todos da instancia
if ($_SESSION["userRole"]!=ALUNO && !in_array($_SESSION['COD_PESSOA'],$gerentes)) {
  echo "Acesso apenas para alunos e gerentes do curso."; die; 
}

echo'
<head>
	<link rel="stylesheet" href="./../cursos.css" type="text/css">
	<link rel="stylesheet" href="./../celula.css" type="text/css">

  <script>
    /* EXCLUSAO COMENTADA
    function confirmaExclusao(codInstanciaGlobal,codAluno){
      if ( window.confirm( "Confirma a exclusão?" ) ){
        window.location.href="'.$_SERVER['PHP_SELF'].'?acao=excluir&codInstanciaGlobal="+codInstanciaGlobal+"&codAluno="+codAluno;
      }
    }
    */

    function validateAluno(){
      if (document.getElementById("cartaoIdentificacao").value==""){
        alert("Preencha o cartao de identificao!");
        document.getElementById("cartaoIdentificacao").focus();
        return false;
      }
      if (document.getElementById("polo").value==""){
        alert("Preencha o polo!");
        document.getElementById("polo").focus();
        return false;
      }
      if (document.getElementById("conceitoFinal").value==""){
        alert("Preencha o Conceito Final!");
        document.getElementById("conceitoFinal").focus();
        return false;
      }
      if (document.getElementById("msg").value==""){
        alert("Preencha a razao do pedido!");
        document.getElementById("msg").focus();
        return false;
      }
      if (document.getElementById("cidade").value==""){
        alert("Preencha a cidade!");
        document.getElementById("cidade").focus();
        return false;
      }
      
      return true;
    }

    function validateProfessor(){
      if (document.getElementById("resposta").value==""){
        alert("Preencha a resposta!");
        document.getElementById("resposta").focus();
        return false;
      }
      if (document.getElementById("justificativa").value==""){
        alert("Preencha a justificativa!");
        document.getElementById("justificativa").focus();
        return false;
      }
      
      return true;
    }

  </script>
  <style type="text/css">
    #dataInicio,#dataFim,#cidade,#conceitoFinal,#cartaoIdentificacao{ 
      border-bottom:1px solid;
      border-top:0px;
      border-left:0px;
      border-right:0px;
    }

    #resposta,#justificativa{ 
      border:0px;
    }

    @media print {
      .noPrint { 
      display:none; 
    }

  </style>  
</head>';

  $nivelAtual = getNivelAtual();
  $instancia = new InstanciaNivel($nivelAtual,getCodInstanciaNivelAtual());
  
  //$disciplina = $instancia->getPai(); 
  //$polo = $instancia->getAbreviaturaOuNome();

  $form = new formulariosolicitacaorevisaoconceitofinal();


switch($_REQUEST['acao']){
  case'':
    echo"<BR><BR><BR><BR>
         <center>
         <fieldset style='width: 600px;'>
           <legend>
             <b>Formulário de Solicitação de Revisão de Conceito Final</b>
           </legend>";

    $periodo = $form->pegaPeriodo($_SESSION['codInstanciaGlobal']);
    $periodo = mysql_fetch_object($periodo);
    if(empty($periodo)){
      $dataInicio = "";
      $dataFim = "";
    }
    else{
      $dataInicio = strftime("%d/%m/%Y", strtotime($periodo->dataInicio));
      $dataFim = strftime("%d/%m/%Y", strtotime($periodo->dataFim));
    }

//    $hoje=date('d')."/".date('m')."/".date('Y');
    $hoje=date('Y-m-d');

    if($_SESSION["userRole"]==ALUNO){//se aluno, pega apenas formularios criados pelo aluno
      $formularios = $form->pegaFormulario($_SESSION['codInstanciaGlobal'],$_SESSION['COD_AL']);
    }
    else if (in_array($_SESSION['COD_PESSOA'],$gerentes)) { //se gerente, pega todos da instancia
      echo"<form name=form method=post action='".$_SERVER['PHP_SELF']."?acao=atualizarPeriodo'>
           <br>Período: de <input type=text name=dataInicio id=dataInicio size=10 value='".$dataInicio."'> a <input type=text name=dataFim id=dataFim size=10 value='".$dataFim."'> <input type=submit value=Ok><BR><BR>";

      $formularios = $form->pegaFormulario($_SESSION['codInstanciaGlobal']);
    }

//*************************************************************************

if($_SESSION["userRole"]==ALUNO){//se eh aluno

  if(($hoje <= $periodo->dataFim)&&($hoje >= $periodo->dataInicio)){//aluno e dentro do periodo
    $obj = mysql_fetch_object($formularios);
    if(!empty($obj->data)){//aluno, dentro do periodo e ja preencheu
      //mostra tabela com edit 
      echo"<br>
           <table border=1 width=500px>
             <tr align=center>
               <td>Cartão Identificação</td>
               <td>Aluno</td>
               <td>Data</td>
               <td>Resposta</td>
               <td>Justificativa</td>               
               <td  colspan=2>Ações</td>
             </tr>";
      $data = strftime("%d/%m/%Y", strtotime($obj->data));
      $aluno = $form->pegaNomeAluno($obj->codAluno);
      $aluno = mysql_fetch_object($aluno);
      echo"
        <tr>
          <td>".$obj->cartaoIdentificacao."</td>
          <td>".$aluno->NOME_PESSOA."</td>
          <td align=center>".$data."</td>
          <td>";
            if($obj->resposta==0){
              echo" &nbsp; ";
            }
            if($obj->resposta==1){
              echo"<b>Deferido</b>";
            }
            if($obj->resposta==2){
              echo"<b>Não Deferido</b>";
            }
          echo"</select>
        </td>
        <td>";
          $obj->justificativa = trim($obj->justificativa);
          if(!empty($obj->justificativa)) {
            echo $obj->justificativa."</td>";
          }
          else{
            echo "&nbsp;";
            echo "</td>
                 <td align=center><a href='".$_SERVER['PHP_SELF']."?acao=visualizar&codInstanciaGlobal=".$obj->codInstanciaGlobal."&codAluno=".$obj->codAluno."'><img src='../imagens/edita.gif' border=no></a></td>";
          }
     echo "</tr></table><br>";
       /*EXCLUSAO COMENTADA
          <td align=center><a href='#' onClick='confirmaExclusao(".$obj->codInstanciaGlobal.",".$obj->codAluno.");'><img src='../imagens/excluir.gif' border=no></a>
          </td>*/
    }
    else{//ainda nao preencheu , é aluno e ta dentro do periodo
      //mostra icone
      echo"<br><img src='../imagens/criar.gif'><a href='".$_SERVER['PHP_SELF']."?acao=novo'>Preencher Formulário</a><BR><BR>";
    }
  }
  else{//fora do periodo: visualiza retorno do professor

    $obj = mysql_fetch_object($formularios);
    if(!empty($obj->data)){
      //mostra tabela sem edit 

      echo '<br><table border=1 width="500px" cellpadding="1" cellspacing="0"><tr align=center><td>Cartão Identificação</td><td>Aluno</td><td>Data</td><td>Solicita&ccedil;&atilde;o</td><td>Resposta</td><td>Justificativa</td></tr>';
      $data = strftime("%d/%m/%Y", strtotime($obj->data));
      $aluno = $form->pegaNomeAluno($obj->codAluno);
      $aluno = mysql_fetch_object($aluno);
      echo '<tr><td>'.$obj->cartaoIdentificacao."</td><td>".$aluno->NOME_PESSOA."</td><td align=center>".$data.'</td>';
      echo '<td>'.$obj->msg.'</td>'; //solicitacao do alunos
      echo '<td>';
      if($obj->resposta==0)     { echo ' &nbsp; ';          }
      elseif($obj->resposta==1) { echo '<b>Deferido</b>';  }
      elseif($obj->resposta==2) { echo '<b>Não Deferido</b>'; }
      echo '</td><td>'.trim($obj->justificativa).'</td>';
      echo '</tr></table></br></br>';
    }
    else{
        echo"<br><b>Você não possui formulário e está fora do período de preenchimento</b><br>.";
    }
  }
}

else{//entao eh prof
  echo"<table border=1 width=500px>
         <tr align=center>
           <td>Cartão Identificação</td>
           <td>Aluno</td>
           <td>Data</td>
           <td>Resposta</td>
           <td>Justificativa</td>
           <td colspan=2>Ações</td>
         </tr>";

  while ($obj = mysql_fetch_object($formularios)){
    $data = strftime("%d/%m/%Y", strtotime($obj->data));
    $aluno = $form->pegaNomeAluno($obj->codAluno);
    $aluno = mysql_fetch_object($aluno);
    echo"
      <tr>
        <td>".$obj->cartaoIdentificacao."</td>
        <td>".$aluno->NOME_PESSOA."</td>
        <td align=center>".$data."</td>
        <td>";
            if($obj->resposta==0){
              echo" &nbsp; ";
            }
            if($obj->resposta==1){
              echo"<b>Deferido</b>";
            }
            if($obj->resposta==2){
              echo"<b>Não Deferido</b>";
            }
          echo"</select>
        </td>
        <td>";
          if(!empty($obj->justificativa)){
            echo $obj->justificativa;
          }
          else{
            echo"&nbsp;";
          }
          echo"</td>
        <td align=center>
          <a href='".$_SERVER['PHP_SELF']."?acao=visualizar&codInstanciaGlobal=".$obj->codInstanciaGlobal."&codAluno=".$obj->codAluno."'><img src='../imagens/edita.gif' border=no></a>
        </td>
        ";
      /* EXCLUSAO COMENTADA
        <td align=center>
          <a href='#' onClick='confirmaExclusao(".$obj->codInstanciaGlobal.",".$obj->codAluno.");'><img src='../imagens/excluir.gif' border=no></a>
        </td>
      </tr>";
      */
  }
  echo"</table><br><br>";
}


//*************************************************************************


  break;
  
  case 'novo':
    echo"
      <body onload=\"document.getElementById('cartaoIdentificacao').focus();\">
        <table width=100%>
          <tr>
            <td>
              <div align=left class=noPrint><a href='".$_SERVER['PHP_SELF']."'><img src=../imagens/voltar.gif border=0>&nbsp;&nbsp;<BR>Voltar</a></div>
            </td>
            <td>
              <div align=right class=noPrint><a href='#' onclick='window.print();return false;'><img src=../imagens/print.png border=0>&nbsp;&nbsp;<BR>Imprimir</a></div>
            </td> 
          </tr>
        </table>

      <form name='form' method='post' action='".$_SERVER['PHP_SELF']."?acao=gravar' onSubmit='return validateAluno();'>
        <table width=500px align=center>
          <tr>
            <td>
              <B><FONT SIZE=4>Formulário de Solicitação de Revisão de Conceito Final</FONT></B>
              <BR>
              <BR>
              <FONT SIZE=2 STYLE='font-size: 11pt'>Ilma Senhora<BR>
              Marisa Rhoden,<BR>
              Coordenadora do Curso<BR>
              <BR>
            </td>
          </tr>
          <tr>
            <td ALIGN=JUSTIFY>
              <B>".$_SESSION['NOME_PESSOA']."</B>, cartão de identificação <INPUT TYPE=TEXT SIZE=12 NAME='cartaoIdentificacao' id='cartaoIdentificacao'>, regularmente matriculado na disciplina
              <b>".$instancia->getAbreviaturaOuNome()."</b>, Pólo de <input type='text' name='polo' id='polo' max-length='30' size='10'>, vem
              respeitosamente solicitar a Vossa Senhoria que encaminhe ao(s) Professor(es)
              Coordenador(es) desta disciplina o pedido de revisão de conceito final <INPUT TYPE=TEXT SIZE=4 NAME='conceitoFinal' id='conceitoFinal'> obtido.
              <BR>
              Esta inconformidade com o conceito deve-se ao fato de
              <textarea name=msg id=msg cols=90 rows=13></textarea>
       <BR>
      <BR>
    </td>
  </tr>";

  $mes = $form->pegaMesExtenso(date('m'));
  $data = date("d")." de ".$mes." de ".date("Y");
  echo"<tr>
         <td align='right'>
           Nestes termos, pede deferimento.<BR>
           <BR>
           <input type=text name=cidade id='cidade'>, ".$data.".</FONT><BR>
           <BR>
           <BR>
           </td>
         </tr>
       </table>
       <center>
       <input type=submit class=noPrint value='Gravar'><input type=button class=noPrint value='Cancelar' onClick=window.location.href='".$_SERVER['PHP_SELF']."';>
    </form>";
  break;

  case'gravar':

    $data=date(Y)."-".date(m)."-".date(d);

    $form->gravaFormulario($_SESSION['codInstanciaGlobal'],$_REQUEST['cartaoIdentificacao'],$_SESSION['COD_AL'],$_REQUEST['conceitoFinal'],$_REQUEST['polo'],$_REQUEST['msg'],$data,$_REQUEST['cidade'],$_REQUEST['resposta'],$_REQUEST['justificativa']);
    if (!mysql_errno()){
      echo"<BR><BR><BR><center>Formulário gravado com sucesso!";
      echo"<BR><a href='".$_SERVER['PHP_SELF']."'>voltar</a>";
    }
    else{
      echo"<BR><BR><BR><center><font color=red>Você já criou um formulário. Caso contrario, entre em contato com o administrador";
      echo"<BR><a href='".$_SERVER['PHP_SELF']."'>voltar</a>";
    }
  break;

  case'excluir':
    $form->deletaFormulario($_REQUEST['codInstanciaGlobal'],$_REQUEST['codAluno']);
    if (!mysql_errno()){
      echo"<BR><BR><BR><center>Formulário excluido com sucesso!";
      echo"<BR><a href='".$_SERVER['PHP_SELF']."'>voltar</a>";
    }
    else{
      echo"<BR><BR><BR><center><font color=red>Houve um erro. Entre em contato com o administrador";
      echo"<BR><a href='".$_SERVER['PHP_SELF']."?'>voltar</a>";
    }
  break;

  case 'visualizar';

    $formulario = $form->pegaFormulario($_REQUEST['codInstanciaGlobal'],$_REQUEST['codAluno']);
    $formulario = mysql_fetch_object($formulario);

    if($_SESSION["userRole"]==ALUNO){//na edicao, se for aluno, pega a data atual
      $mes = $form->pegaMesExtenso(date('m'));
      $data=date('d')." de ".$mes." de ".date("Y");
    }
    else{//se professor, pega a data do banco
      $nros = explode("-",$formulario->data);
      $mes = $form->pegaMesExtenso(trim($nros[1]));  
      $data=trim($nros[2])." de ".$mes." de ".trim($nros[0]);
    }

    echo"
      <body onload=\"document.getElementById('cartaoIdentificacao').focus();\">
        <table width=100%>
          <tr>
            <td>
              <div align=left class=noPrint><a href='".$_SERVER['PHP_SELF']."'><img src=../imagens/voltar.gif border=0>&nbsp;&nbsp;<BR>Voltar</a></div>
            </td>
            <td>
              <div align=right class=noPrint><a href='#' onclick='window.print();return false;'><img src=../imagens/print.png border=0>&nbsp;&nbsp;<BR>Imprimir</a></div>
            </td>
          </tr>
        </table>
        <form name=form method=post action='".$_SERVER['PHP_SELF']."?acao=atualizar'
           onSubmit='";
    if ($_SESSION['userRole']==ALUNO) {
      echo "return validateAluno();";
    }
    else {
      echo "return validateProfessor();";   
    }          
    echo  "'>
          <input type=hidden name=codAluno id=codAluno value='".$formulario->codAluno."'>
          <table width=500px align=center>
            <tr>
              <td colspan=2>
                <B><FONT SIZE=4>Formulário de Solicitação de Revisão de Conceito Final</FONT></B>
                <BR>
                <BR>
                <FONT SIZE=2 STYLE='font-size: 11pt'>Ilma Senhora<BR>
                Marisa Rhoden,<BR>
                Coordenadora do Curso<BR>
                <BR>
              </td>
            </tr>
            <tr>
              <td ALIGN=JUSTIFY colspan=2>
                <B>";
    if ($_SESSION["userRole"]==ALUNO) {//se for aluno, mostra o nome da sessao
      echo $_SESSION['NOME_PESSOA'];
    }
    else { //mostra o nome do aluno que inseriu
      $aluno=$form->pegaNomeAluno($formulario->codAluno);
      $aluno = mysql_fetch_object($aluno);
      echo $aluno->NOME_PESSOA;
    }
                
                
                echo "</B>, cartão de identificação ";

                if($_SESSION["userRole"]==ALUNO){//se for aluno, permite editar dados pessoais
                  echo"<input type='text' id='cartaoIdentificacao' name=cartaoIdentificacao SIZE=12 value='".$formulario->cartaoIdentificacao."'>";
                }
                else{//deixa no hidden as informacoes
                  echo"<input type='hidden' id='cartaoIdentificacao' name=cartaoIdentificacao SIZE=12 value='".$formulario->cartaoIdentificacao."'>";
                  echo"<B>".$formulario->cartaoIdentificacao."</b>";                
                }
                echo", regularmente matriculado na disciplina
                <b>".$instancia->getAbreviaturaOuNome()."</b>, Pólo de <b>";
                if($_SESSION["userRole"]==ALUNO){//se for aluno, permite editar dados pessoais
                  echo "<input type='text' name='polo' id='polo'  max-length='30' size='10' value='".$formulario->polo."'>";
                }
                else{//deixa no hidden as informacoes
                  echo "<input type='hidden' name='polo' id='polo' max-length='30' size='10' value='".$formulario->polo."'>";
                  echo "<B>".$formulario->polo."</b>";                
                }
                
                echo "</b>, vem
                respeitosamente solicitar a Vossa Senhoria que encaminhe ao(s) Professor(es)
                Coordenador(es) desta disciplina o pedido de revisão de conceito final ";
                if($_SESSION["userRole"]==ALUNO){//se for aluno, permite editar dados pessoais
                  echo"<input type='text' name=conceitoFinal id='conceitoFinal' value='".$formulario->conceitoFinal."' SIZE=4>";
                }
                else{//deixa no hidden as informacoes
                  echo "<input type='hidden' name=conceitoFinal id='conceitoFinal' value='".$formulario->conceitoFinal."' SIZE=4>";
                  echo"<B>".$formulario->conceitoFinal."</b>";
                }
                echo" obtido.
                <BR>
                Esta inconformidade com o conceito deve-se ao fato de <br><br>";
                if($_SESSION["userRole"]==ALUNO){//se for aluno, permite editar dados pessoais                
                  echo"<textarea name='msg' id='msg' cols=90 rows=13>";
                }
                else{//deixa no hidden as informacoes
                  echo"<input type='hidden' name='msg' id='msg' value='".$formulario->msg."'>";                
                }

                echo $formulario->msg;

                if($_SESSION["userRole"]==ALUNO){//se for aluno, permite editar dados
                  echo"</textarea>";
                }
                echo"<BR>
                <BR>
              </td>
            </tr>
            <tr>
              <td align='right' colspan=2>
                Nestes termos, pede deferimento.<BR>
                <BR>";
                if($_SESSION["userRole"]==ALUNO){//se for aluno, permite editar dados pessoais                
                  echo"<input type='text' name='cidade' id='cidade' value='".$formulario->cidade."' >";
                }
                else{//deixa no hidden as informacoes
                  echo"<input type='hidden' name='cidade' id='cidade' value='".$formulario->cidade."' >";                
                  echo $formulario->cidade;
                }
                echo", ".$data."<BR>
                <BR>
                <BR>
              </td>
            </tr>
            <tr> 
              <td><b><u>Resposta:</u></b><br>";
/**********************************************************/
              if($_SESSION["userRole"]==ALUNO){//se for aluno mostra fixo a resposta 
                if($formulario->resposta==0){
                  echo "";
                }
                if($formulario->resposta==1){
                  echo "<b>Deferido</b>";
                }
                if($formulario->resposta==2){
                  echo "<b>Não Deferido</b>";
                }                                
                echo"<input type='hidden' name='resposta' id='resposta' value='".$formulario->resposta."'>";
              }
              else{//se prof, abre resposta para edicao
                echo"<select id='resposta' name='resposta'>";
                echo"<option ";
                if($formulario->resposta==0){
                  echo" selected ";
                }
                echo" value=0>Selecione</option>";

                echo"<option ";
                if($formulario->resposta==1){
                  echo" selected ";
                }
                echo" value=1>Deferido</option>";
         
                echo"<option ";
                if($formulario->resposta==2){
                  echo" selected ";
                }
                echo" value=2>Não Deferido</option>";
              }
/**********************************************************/

            echo"</td>
                 <td><b><u>Justificativa:</u></b><br>";
                 if($_SESSION["userRole"]!=ALUNO){//se nao for aluno permite editar justificativa
                   echo"<textarea name='justificativa' id='justificativa' cols=60 rows=4 style='border:1px solid'>";
                 }
                 else{
                   echo"<input type=hidden name=justificativa id='justificativa' value='".$formulario->justificativa."'>";
                 }
                 if($formulario->justificativa){
                   echo $formulario->justificativa; 
                 }
                 if($_SESSION["userRole"]!=ALUNO){
                   echo"</textarea>";
                 }
                 echo"</td>
               </tr>
             </table> 


             <center><br><input type='submit' class='noPrint' value='Gravar'><input type=button class=noPrint value='Cancelar' onClick=window.location.href='".$_SERVER['PHP_SELF']."';>
        </form>";
  break;

  case'atualizar': //print_r($_REQUEST);die;

    $data=date(Y)."-".date(m)."-".date(d);

    $form->atualizaFormulario($_SESSION['codInstanciaGlobal'],$_REQUEST['cartaoIdentificacao'],$_REQUEST['codAluno'],$_REQUEST['conceitoFinal'],$_REQUEST['polo'],$_REQUEST['msg'],$data,$_REQUEST['cidade'],$_REQUEST['resposta'],$_REQUEST['justificativa']);
    if (!mysql_errno()){
      echo"<BR><BR><BR><center>Formulário atualizado com sucesso!";
      echo"<BR><a href='".$_SERVER['PHP_SELF']."'>voltar</a>";
    }
    else{
      echo"<BR><BR><BR><center><font color=red>Houve um erro. Entre em contato com o administrador";
      echo"<BR><a href='".$_SERVER['PHP_SELF']."'>voltar</a>";
    }
  break;

  case'atualizarPeriodo':

    $nros = explode("/",$_REQUEST['dataInicio']);
    $dataInicio = trim($nros[2])."-".trim($nros[1])."-".trim($nros[0]); 

    $nros = explode("/",$_REQUEST['dataFim']);
    $dataFim = trim($nros[2])."-".trim($nros[1])."-".trim($nros[0]); 


    $form->atualizaPeriodo($_SESSION['codInstanciaGlobal'],$dataInicio,$dataFim);
    if (!mysql_errno()){
      echo"<BR><BR><BR><center>Período atualizado com sucesso!";
      echo"<BR><a href='".$_SERVER['PHP_SELF']."'>voltar</a>";
    }
    else{
      echo"<BR><BR><BR><center><font color=red>Houve um erro. Entre em contato com o administrador";
      echo"<BR><a href='".$_SERVER['PHP_SELF']."'>voltar</a>";
    }
  break;
}
?>
