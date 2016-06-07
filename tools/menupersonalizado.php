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

//ini_set("display_errors",1);
 
include_once("../config.php");
include_once($caminhoBiblioteca."/defaultpage.inc.php");
include_once($caminhoBiblioteca."/menupersonalizado.inc.php");
include_once($caminhoBiblioteca."/funcoesftp.inc.php");
include_once($caminhoBiblioteca."/imagens.inc.php");
include_once($caminhoBiblioteca."/instancianivel.inc.php");
@session_name(SESSION_NAME); @session_start(); security();

function confNum($Num)	{
	if ($Num < 10) 
		return "0" . strval($Num);
	else		
		return strval($Num);
	}

    /**
     Recebe  dados do formulário 
    */
	
    $nomeMenu = $_REQUEST['nomeMenu'];
    $urlMenu = $_REQUEST['urlMenu'];
    $urlToolsEditar = $_REQUEST['urlToolsEditar'];
    $urlToolsCriar = $_REQUEST['urlToolsCriar'];
    $descricaoMenu = $_REQUEST['descricaoMenu'];
    $ordem = $_REQUEST['ordem'];
    $tipoAcesso = $_REQUEST['tipoAcesso'];
    
    //$enviar = $_REQUEST['sub'];
	  //$enviarEditar = $_REQUEST['submeter'];
	
	  $imagem = $_FILES["imagem"]/*["name"]*/;
	
  	$codMenu = $_REQUEST["codMenu"];
   
    $acao = $_REQUEST["acao"];
    
  	
	  
   
   /**
	  *    Controla a ação escolhida pelo usuário: criar novo menu, editar menu, excluir menu
    */
  switch($acao) 
  {
	// default: entra na tela dos ícones a criar
	case"":
		/**
		*     		Verifica se todos os campos obrigatórios foram preenchidos, 
		*/   
		 
    verificaCampos($nomeMenu, $urlMenu, $descricaoMenu, $ordem);        
	
    if (($_FILES["imagem"]["type"] == "image/gif") || ($_FILES["imagem"]["type"] == "image/jpeg") || ($_FILES["imagem"]["type"] == "image/jpg")|| ($_FILES["imagem"]["type"] == "image/png") && ($_FILES["imagem"]["size"] > 0))
    {
       if ($_FILES["imagem"]["error"] > 0)
       {
			  echo "Erro no arquivo: " . $_FILES["imagem"]["error"] . "<br />";
			  echo " verifique se a firgura está no formato gif, jpg ou png <br />";
			  echo "&nbsp&nbsp&nbsp<a href=\"menu.php?acao=criar\"><img src='".voltar."'border =\"no\" alt=\"Voltar para criar ícone\"></a></p>";
			  exit();
       }
      /*verificaImagem($_FILES["imagem"]["error"],$_FILES["imagem"]["name"],$_FILES["imagem"]["size"]); */
		   else
	     {
		    $caminhoRelativo = "/menusParticulares/". confNum($_SESSION["codInstanciaGlobal"]) . "/";
		    $caminho = $caminhoImagem.$caminhoRelativo;//$caminhoUpload
        
		    DEFINE ("ALTURA_IMAGEM",'30'); 
		    DEFINE ("LARGURA_IMAGEM",'25'); 
		    //$nomeArquivo = $_FILES["imagem"]["name"]; //$imagem =
      	
		    //   	cria o diretório  para a imagem se necessário
		    if (! file_exists($caminho)) { mkdir($caminho); }
     
			  if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho.$_FILES["imagem"]["name"])) 
			  {	
			   imagemMenu($caminho,$_FILES["imagem"]["name"]);
			    
				 //tirar a linha duplica quando gravar no ead
				 duplica($caminho . $_FILES["imagem"]["name"], $_FILES["imagem"]["name"], "imagem/". confNum($_SESSION["codInstanciaGlobal"]) . "/");  
				 $ok = insereMenu($nomeMenu, $urlMenu, $urlToolsEditar,$urlToolsCriar, $descricaoMenu, $caminhoRelativo.$_FILES["imagem"]["name"], $ordem, $tipoAcesso,$codMenu);	
			  }
        else
		    {
			   $ok = insereMenu($nomeMenu, $urlMenu, $urlToolsEditar,$urlToolsCriar, $descricaoMenu, $caminhoRelativo.$_FILES["imagem"]["name"], $ordem, $tipoAcesso,$codMenu);
		    }
		   }
    }    
    else
	  {
		 echo "Voce precisa inserir uma imagem para a criação do ícone, nos formatos jpg, gif ou png";
	  }
     
     
     if($_REQUEST['sub'])  //if ($enviarEditar)
     {
       echo "<script>location.href=\"menu.php?acao=criar&codMenu=".$ok."\"</script>";
	   //print_r($_REQUEST['sub']); die();
     }
     if($_REQUEST['submeter'])//if($enviar) 
	   {
        echo "<script>top.location.href='".$url."';</script>";
     }
    
   break;
       
      
	 case "excluir":
       removeMenu($_REQUEST["codMenu"]);
       echo "<script> location.href=\"menu.php?acao=criar\"</script>";
        //echo "header(location:  ".$url."/tools/menu.php?acao=criar)";
        //echo "<script>top.location.href='".$url."';</script>";
   break;
     
  } 
       
     
 ?>