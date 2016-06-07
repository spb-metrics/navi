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

    /**
	@fn insereMenu
	@brief 
	Insere o menu criado pelo ususario no banco de dados e verifica se algum  menu será alterado
	@param nomeMenu, @param urlMenu, @param urlToolsEditar, @param urlToolsCriar, @paramdescricaoMenu , @param imagem, 
	@param ordem, @param tipoAcesso,@param codMenu 
	@brief
	Parametros utlizados para inserção e identificação do menu elabordo pelo usuário no banco de dados 
	*/
function insereMenu ($nomeMenu, $urlMenu, $urlToolsEditar,$urlToolsCriar, $descricaoMenu, $imagem, $ordem, $tipoAcesso,$codMenu="")
	{
	
	if (!empty($codMenu))
    {
		//Faz o update do menu modificado, verifica se a imagem deve ou não ser atualizada
		  if (empty($imagem))
		  {
		  	$strSQL="UPDATE menuparticular SET nomeMenu =".quote_smart($nomeMenu).", urlMenu=".quote_smart($urlMenu).", urlToolsEditar=".quote_smart($urlToolsEditar).", urlToolsCriar=".quote_smart($urlToolsCriar).", descricaoMenu=".quote_smart($descricaoMenu).", ordem=".quote_smart($ordem).", tipoAcesso=".quote_smart($tipoAcesso)." WHERE codMenu=".$codMenu."";
		  }
		 else 
		  {
			$strSQL="UPDATE menuparticular SET nomeMenu =".quote_smart($nomeMenu).", urlMenu=".quote_smart($urlMenu).", urlToolsEditar=".quote_smart($urlToolsEditar).", urlToolsCriar=".quote_smart($urlToolsCriar).", descricaoMenu=".quote_smart($descricaoMenu).",imagem=".quote_smart($imagem).", ordem=".quote_smart($ordem).", tipoAcesso=".quote_smart($tipoAcesso)." WHERE codMenu=".$codMenu."";
	    }
    }
	  else
    {
    
		//insere novo menu
		$strSQL = "INSERT INTO menuparticular (codInstanciaGlobal, nomeMenu, urlMenu, urlToolsEditar, urlToolsCriar, descricaoMenu,imagem, ordem, tipoAcesso)"." VALUES(".$_SESSION["codInstanciaGlobal"].",".quote_smart($nomeMenu).",
				".quote_smart($urlMenu).", ".quote_smart($urlToolsEditar).", ".quote_smart($urlToolsCriar).", ".quote_smart($descricaoMenu).",".quote_smart($imagem).",".quote_smart($ordem).",".quote_smart($tipoAcesso).")";
    }
		
	  $result = mysql_query($strSQL);
	  return (! mysql_errno());
	}

	/**
	@fn getMenuParticular
	@brief 
	Seleciona um menu criado pelo usuário ( é usada dentro da função remove menu e no arquivo menu.php).
	@param codMenu   
	@brief
	Código do  menu utilizado
	@param codInstanciaGlobal
	@brief
	Código da instancia do menu  	
	*/
function getMenuParticular($codInstanciaGlobal, $codMenu='')
	{
	$sql= " SELECT * FROM menuparticular WHERE codInstanciaGlobal= ".$codInstanciaGlobal." ";
	if(!empty($codMenu)) 
	{$sql.= "and codMenu='".$codMenu."' ";}
	$obj = new RDCLQuery($sql);
	//print_r($obj);die();
	return $obj;
	}
	
	/**
	@fn removeMenu
	@brief 
	Exclui um menu criado pelo usuário
	*/

function removeMenu($codMenu)
	{
	$sql= "DELETE FROM menuparticular WHERE codMenu=$codMenu";
	$result = mysql_query($sql);
	return (! mysql_errno());
	}

	/**
	*  @fn  mostraMenu
	*   @brief 
	*   Mostra na tela todos os dados do menu escolhido para edição
	*   @param codMenu   
	*   @brief
	*   Código do  menu que será alterado dentro do banco de dado
	*/
function mostraMenu($codMenu) 

	{
	$pegaMenu = getMenuParticular($_SESSION["codInstanciaGlobal"], $codMenu);
	
	if($_REQUEST["opcao"]=="mostrar")
		{
		   	foreach($pegaMenu -> records as $objMenu)
			{          
				$vetor["nomeMenu"] = $objMenu ->nomeMenu;
				$vetor["urlMenu"] = $objMenu ->urlMenu;
				$vetor["urlToolsEditar"] = $objMenu -> urlToolsEditar;
				$vetor["urlToolsCriar"] = $objMenu -> urlToolsCriar;
				$vetor["descricaoMenu"] = $objMenu -> descricaoMenu;
				$vetor["imagem"]/*["name"]*/= $objMenu -> imagem;
				$vetor["ordem"] = $objMenu -> ordem;
				$vetor["tipoAcesso"] = $objMenu -> tipoAcesso;
			}
		}
	return $vetor;    
	}  
	
	
function getItensMenuPersonalizado() {
	$strSQL=" SELECT * FROM menuparticular ORDER BY MP.ordem";
//	print_r($strSQL); die();
    return mysql_query($strSQL);	
}

/** 
 *Verifica se todos os campos forma preenchidos corretamente
 *
 *
 */

function verificaCampos($nomeMenu, $urlMenu, $descricaoMenu, $ordem)
{
	if (empty($nomeMenu) or empty($urlMenu) or empty($descricaoMenu) or empty($ordem) )
		{
			echo "<p align=\"center\">Voce não preencheu todos os campos identificados com *.<br /> Por favor, volte e complete o fomrulário.";
			echo "&nbsp&nbsp&nbsp<a href=\"menu.php?acao=criar\"><img src='".voltar."'border =\"no\" alt=\"Voltar para criar ícone\"></a></p>";
			exit();//ok
		}  
		
		elseif ( !eregi ('http://',$urlMenu))
		{
			echo "<p align=\"center\">Voce não preencheu corretamente a url do menu.<br /> Por favor, volte e corrija o endereço.";
			echo "&nbsp&nbsp&nbsp<a href=\"menu.php?acao=criar\"><img src='".voltar."'border =\"no\" alt=\"Voltar para criar ícone\"></a></p>";
			exit();//ok
		} 
		elseif (!eregi('.',$urlMenu))
		{
			echo "<p align=\"center\">Voce não preencheu corretamente a url do menu.<br /> Por favor, volte e corrija o endereço.";
			echo "&nbsp&nbsp&nbsp<a href=\"menu.php?acao=criar\"><img src='".voltar."'border =\"no\" alt=\"Voltar para criar ícone\"></a></p>";
			exit();//ok
		} 
		/*else
		{
			echo "<p align=\"center\">Voce não preencheu corretamente a url do menu.<br /> Por favor, volte e corrija o endereço.";
			echo "&nbsp&nbsp&nbsp<a href=\"menu.php?acao=criar\"><img src='".voltar."'border =\"no\" alt=\"Voltar para criar ícone\"></a></p>";
			exit();
		}*/
		if ( $ordem < 0 )
		{
 			echo "<p align=\"center\">a ordem do menu deve ser um número maior que zero <br /> Por favor, volte e corrija .";
			echo "&nbsp&nbsp&nbsp<a href=\"menu.php?acao=criar\"><img src='".voltar."'border =\"no\" alt=\"Voltar para criar ícone\"></a></p>";
			exit();
		}  
			
}

/*function verificaImagem($_FILES["imagem"]["error"],$_FILES["imagem"]["name"],$_FILES["imagem"]["size"]) 
{
 if ($_FILES["imagem"]["error"] > 0)
       {
			  echo "Erro no arquivo: " . $_FILES["imagem"]["error"] . "<br />";
			  echo " verifique se a firgura está no formato gif, jpg ou png <br />";
			  echo "&nbsp&nbsp&nbsp<a href=\"menu.php?acao=criar\"><img src='".voltar."'border =\"no\" alt=\"Voltar para criar ícone\"></a></p>";
			  exit();
       }
  elseif($_FILES["imagem"]["name"] ){ echo "oi"; }
  
  elseif($_FILES["imagem"]["size"] > 1000000)
       {
        echo "Erro! Tamanho da imagem maior que o permitido!";
       }
}
/*
 * Faz o redimensionamento de uma imagem
 * Atualmente suporta gif, jpg e png  
 */ 
function redimensionaImagem($caminho,$nomeArquivo,$new_width, $new_height,$nomeNovo='') {

  $arquivoImagem = $caminho.$nomeArquivo;
  //usa o mesmo nome da imagem para sobrepor a imagem com tamanho refeito (caso da imagem normal)
  //ou entao grava em uma nova imagem (caso da mini foto)
  if (empty($nomeNovo)) { $nomeNovo=$arquivoImagem; } 
  
  //pega o tipo de imagem
  $arrayAux = explode(".",$nomeArquivo);
 
  $tipoImagem = strtolower($arrayAux[count($arrayAux)-1]);
  
  // Get  as dimensoes atuais
  list($width, $height) = getimagesize($arquivoImagem);
  //nova imagem. Usa imagecreatetruecolor se possivel, i.e., nao for .gif 
  if ($tipoImagem=='gif') {
    $imagemRedimensionada = imagecreate($new_width, $new_height);
  }
  else {
    $imagemRedimensionada = imagecreatetruecolor ($new_width, $new_height);
  }

  //recupera a imagem postada pelo usuario
  if ($tipoImagem=='gif') {
    $image = imagecreatefromgif($arquivoImagem);
  } 
  else if ($tipoImagem =='jpeg' || $tipoImagem =='jpg') {
    $image = imagecreatefromjpeg($arquivoImagem);
  }
  else if ($tipoImagem=='png' ) {
    $image = imagecreatefrompng($arquivoImagem);
  }
  //Redimensiona a imagem  
  imagecopyresampled($imagemRedimensionada, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
  
  //echo "<br>Vou gravar: ".$nomeNovo;
  //Salva a imagem
   if ($tipoImagem=='gif') {
    imagegif($imagemRedimensionada, $nomeNovo);
  } 
  else if ($tipoImagem=='jpeg' || $tipoImagem=='jpg') {
    imagejpeg($imagemRedimensionada, $nomeNovo);
  }
  else if ($tipoImagem=='png' ) {
    imagepng($imagemRedimensionada, $nomeNovo);
  }
}

/*
 * Coloca a imagem no tamanho padrão e gera mini imagem para ser usada 
 */ 
function imagemMenu($caminho,$nomeArquivo) {
  
  if (!function_exists('imagecreate')) {  return false; }
  if (!function_exists('imagecopyresampled')) { return false; }
  
  redimensionaImagem($caminho,$nomeArquivo,LARGURA_IMAGEM,ALTURA_IMAGEM);
  //redimensionaImagem($caminho,$nomeArquivo,LARGURA_FOTO_PEQUENA,ALTURA_FOTO_PEQUENA,$caminho.PREFIXO_MINI_FOTO.$nomeArquivo);
}
	
  /*/Verifica a existência do gd na instalação
      if(!function_exists('imagecreate')&&!function_exists('imagecopyresampled')) {
      $erro = false;
      }*/

?>