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

include($caminhoBiblioteca."/pessoa.inc.php");


//===================================================================================================
function editarCriar($ferramenta, $titleEditar, $titleCriar ) {
  $nivelRelacionamento = getNivelAtual();
  if(Pessoa::podeAdministrar($_SESSION["userRole"],$nivelRelacionamento,$_SESSION['interage']) ){	
    global $url;

    echo "<table align='right' valign='top' ><td width=\"50\" align=\"right\"><a href='".$url."/tools/".$ferramenta.".php?PAGINA=instancia' title='".$titleEditar."'>EDITAR</a></td>". 
    "<td align='center' >|</td>".
    "<td width=\"50\" align=\"left\"> <a href='".$url."/tools/".$ferramenta."_operacao.php?PAGINA=instancia&OPCAO=Inserir' title='".$titleCriar."'>CRIAR</a></td></table><br><br>";
  }
}
//===================================================================================================
function excluiAlteraNaInstancia($ferramenta,$codObjeto,$nomefisicoCodObjeto,$tipoAcessoObjeto,$codPessoa) {
  $nivelRelacionamento = getNivelAtual();
  
  //adm ou professor que seja o dono do arquivo
  if ( 
      (Pessoa::isAdm($_SESSION['userRole']))    //administrador
      ||
      ($codPessoa == $_SESSION['COD_PESSOA'] &&  //professor && dono do arquivo 
        Pessoa::podeAdministrar($_SESSION["userRole"],$nivelRelacionamento,$_SESSION['interage']) 
      )
     ) {  
    GLOBAL $url;
    
    $acaoRemover= $url."/tools/".$ferramenta."_local.php?PAGINA=instancia";
    $acaoRemover.= "&OPCAO=Remover&codInstanciaGlobal=".$_SESSION["codInstanciaGlobal"];
    $acaoRemover.= "&".$nomefisicoCodObjeto."=".$codObjeto."&TIPO_ACESSO=".$tipoAcessoObjeto;
    $acaoRemover.= "&SENT=REMOVER";
  	//echo "	<td align=\"center\" nowrap>\n".
  	echo "	<a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir ?')) { window.open('".$acaoRemover."','Wlocal','top=106,left=160,width=300px,height=200px,toolbar=no,status=yes,menubar=no,scrollbars=yes,scrolling=yes,resizable=yes') }\">\n".
  	
  	 "			<img src=\"".$url."/imagens/remove.gif\" border=0 alt=\"Remover\">\n".
  	 "		</a>\n".
  	 "		<a href=\"".$url."/tools/".$ferramenta."_operacao.php?PAGINA=instancia&OPCAO=Alterar&".$nomefisicoCodObjeto."=".$codObjeto."\">\n".
  	 "			<img src=\"".$url."/imagens/edita.gif\" border=0 alt=\"Alterar\">\n".
  	 "		</a>";
    // "	</td>";
  }
  else{
   echo "<br>";
  }
}
//===================================================================================================

?>