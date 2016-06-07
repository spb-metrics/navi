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
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include_once("../config.php");
include_once($caminhoBiblioteca."/blog.inc.php");
include_once($caminhoBiblioteca."/utils.inc.php");
include_once($caminhoBiblioteca."/imagens.inc.php");
include_once($caminhoBiblioteca."/pessoa.inc.php");

if ($_REQUEST['opcao']!='muraldeRecados') {
  session_name(SESSION_NAME); session_start();   security();
}

//flag para utilização do blog para pessoa
if($_SESSION['codPessoaBlog']!=$_REQUEST['codPessoa'] && !empty($_REQUEST['codPessoa'])){
 $_SESSION['codPessoaBlog']=$_REQUEST['codPessoa'];
 $_SESSION["paginaBlog"] = 1;
 unset($_SESSION['codInstanciaGlobalBlog']);
}

//fleg para utlizar o blog da instancia
if($_SESSION['codInstanciaGlobalBlog']!=$_REQUEST['codInstanciaGlobal'] && !empty($_REQUEST['codInstanciaGlobal'])){

 $_SESSION['codInstanciaGlobalBlog']=$_REQUEST['codInstanciaGlobal'];
 $_SESSION['paginaBlog'] = 1;
 unset($_SESSION['codPessoaBlog']);
}

//fleg paginação
if (isset($_REQUEST['paginaBlog'])) {
     $_SESSION['paginaBlog'] = $_REQUEST['paginaBlog'];
}
$threadsPerPage = 5; //numero de threads por pagina
$maxPaginas = 10;  //numero maximo de paginas

//fleg para order a visualização dos posts
if(empty($_SESSION['orderBy']) || !empty($_REQUEST['orderBy'])){
$_SESSION['orderBy']=$_REQUEST['orderBy'];
}


/**
 *  se for passado o primeiro parametro vai ser inicializado o blog da pessoa;
 *  se for passado o segundo parametro vai ser inicializado o blog da instancia;
 *  $blog=new Blog(<inicializar blog pessoa>,<inicializar blog instancia>);
 */ 

$nivel=getNivelAtual();
$blog=new Blog($_SESSION['codPessoaBlog'],$_SESSION['codInstanciaGlobalBlog']);


switch($_REQUEST["opcao"]){
/**layout do blog:
 *lista todos  os posts do autor do blog
 */
case "":

  $blog->cabecalho(); 
  
  echo '<div id="Layergeral" style="position:relative; width:95%;z-index:1; left: 34px; ">';
  
  echo '<div class="LayerDireita">';//abre layerDireita
  echo '<br><br><br><br><div id="post" class="post" ><br>';
  
  echo ' &nbsp;&nbsp;<u><b>Editor html:</b></u>';
  echo ' <UL>';
  echo '    <LI>'. ativaDesativaEditorHtml();
  echo ' </UL>';
  echo ' &nbsp;&nbsp;<u><b>Listar:</b></u>';
  echo ' <UL>'.
       '    <LI><a href="'.$_SERVER['PHP_SELF'].'?orderBy=DESC">Recentes</a>'.
  		 '    <LI><a href="'.$_SERVER['PHP_SELF'].'?orderBy=ASC">Antigos</a>'.
  		 ' </UL>';
  
  if ($blog->permissaoAdmBlog($nivel) && podeInteragir($_SESSION['userRole'],$_SESSION['interage']) )  {
     $blog->formIncluirPost();
  }
  
  echo '</div><br>';
  echo '</div>';//fecha LayerDireita
    	 
  echo '<div></div>';
  echo '<div class="LayerEsquerda">';//abre LayerEsquerda
  
  echo ' <div id="tituloBlog" class="tituloBlog" >';
          if($_REQUEST['acao']=='formANomeBlog'){
            $blog->formAlterarNomeBlog($blog->getNomeBlog());
          }else{
  echo      $blog->getNomeBlog();
             if($blog->permissaoAdmBlog($nivel)){
  echo '      <a href="'.$_SERVER['PHP_SELF'].'?acao=formANomeBlog"><img src="'.edita.'" border="no"></a>' ;         
             }
           }
  
  echo ' </div>';
      
      $limiteIniMsg=$blog->calcIniMsg($_SESSION["paginaBlog"]);
      $listPost=$blog->listPost($_SESSION["NUM_MESSAGES"],$limiteIniMsg,$threadsPerPage,$_SESSION['orderBy']);
      while($post=mysql_fetch_array($listPost)){
        if($post['codPost']!=$blog->getcodMuraldeRecados()){
echo ' <div id="post" class="post" >';
          if($_REQUEST['acao']=='formAPost' && $post['codPost']==$_REQUEST['codPost']){
               $blog->formAlterarPost($post['titulo'],nl2br($post['post']),$post['numeroComentarios'],$post['codPost']);
          }else{
echo '      <div  id="tituloPost" class="tituloPost" >'.$post['titulo'].'</div>'.
	   '      <div id="data" class="data" >'.date('d/m/Y H:i:s',strtotime($post['data'])).'</div>';
echo '      <div  style="position:relative; width:80%;z-index:1; left:84px">'. nl2br($post['post']).'</div><br><br>';

          }
         
      /**mostrar comentários*/
          if($_REQUEST['acao']=="mostrarComentario" && $_REQUEST['codPost']==$post['codPost']){
            $listComentario=$blog->listComentario($post['codPost']);
//abre div dos comentarios
echo '      <div  style="position:relative; width:80%;z-index:1; left:84px">';
echo '       <legend><b>Comentario(s):</b></legend><br><br>';
            while($comentario=mysql_fetch_array($listComentario)){
echo '       <div class="data">';
              if($blog->permissaoAdmBlog($nivel)){
echo '      <a href="'.$_SERVER['PHP_SELF'].'?opcao=excluirComentario&codComentario='.$comentario['codComentario'].'"><img src="'.remove.'" border="no" style="width:15px; height: 15px;"></a>' ;             

              }
echo           $blog->getNomePessoa($comentario['codPessoa']).' escreveu em: '.date('d/m/Y H:i:s',strtotime($comentario['data'])).'</div>'; 
echo '       <div class="comentario" >'.nl2br($comentario['comentario']).'<br><br></div>';       
            }
     
              if ($blog->getNumeroComentPost($post['codPost'])<$post['numeroComentarios']|| $post['numeroComentarios']==-1){
                $blog->formIncluirComentario($_SESSION['COD_PESSOA'],$post['codPost']);
              }else{
echo '          <p><b>Comentários encerrados</b></p>';              
              }
//fecha div dos comentários
echo '      </div>';
echo '  <br><br>';

         }
//fecha div post
echo ' </div>'; 
      

echo ' <div align="center" >';
        if($blog->permissaoAdmBlog($nivel)){
echo '  <span ><a href="'.$_SERVER['PHP_SELF'].'?acao=formAPost&codPost='.$post['codPost'].'">Alterar</a>|</span>';
echo '  <span><a href="'.$_SERVER['PHP_SELF'].'?opcao=excluirPost&codPost='.$post['codPost'].'">Excluir</a>|</span>';
        }
echo '  <span><a href="'.$_SERVER['PHP_SELF'].'?acao=mostrarComentario&codPost='.$post['codPost'].'">Comentários ('.$blog->getNumeroComentPost($post['codPost']).')</a></span>'; 
echo ' </div>';
         }
      }
//div da paginação      
echo ' <div id="paginacaoRodape" class="paginacaoRodape" >';

if ($_SESSION["NUM_MESSAGES"] > $threadsPerPage) {  $blog->imprimePaginacao($_SESSION["paginaBlog"]); }
//fecha divi da paginação
echo ' </div>';

//fecha LayerEsquerda
echo ' </div>';    

//fecha layerGeral
echo '</div>';

  $blog->rodape();
break;

/**
 * Mural de Recados
 * espaço destinado aos alunos
 *  permite um deixar um recado para a turma;
 *  obs:mural de recados não é um tipo novo de blog é apenas um post dentro do blog da instancia. 
 */
case "muraldeRecados":

$blog->cabecalho(); 

echo '<div  id="tituloPost" class="tituloPost" >Mural de Recados</div>';
            $listComentario=$blog->listComentario($blog->getcodMuraldeRecados());


$float='left';
 while($comentario=mysql_fetch_array($listComentario)){
    $recados[$float][]=$comentario;
    if($float=='left'){$float='right';}else{$float='left';}
 }

 
echo '<div style="width:100%;   margin-right:20px;">';
echo '<div style="float:left ; width:50% ">';     
if (!empty($recados['left'])) {
  foreach($recados['left'] as $recadosLeft){
    echo ' <div class="layerRecadoSuperior">';
    echo ' </div>';
    echo ' <div  class="layerRecadoMeio"><div class="recadoDe">';
            if($blog->permissaoAdmBlog($nivel)){
    echo '      <a href="'.$url.'/blog/index.php?opcao=excluirComentario&muralRecados=1&codComentario='.$recadosLeft['codComentario'].'"><img src="'.remove.'" border="no" style="width:15px; height: 15px;"></a>' ;             
             }
    echo '   <b>De:</b>'.$blog->getNomePessoa($recadosLeft['codPessoa']).'</div>';
    echo '   <div style="margin-left:20;margin-right:20;"><b>Mensagem:</b>'.nl2br($recadosLeft['comentario']).'</div>';
    echo ' </div>';
    echo ' <div class="layerRecadoInferior">';
    echo '    <div class="data" align="right" style="margin-right:20;">Escreveu em: '.date('d/m/Y H:i:s',strtotime($recadosLeft['data'])).'</div>';
    echo '  </div>';
  }
}
echo '</div>';

echo '<div style="float:right ; width:50%">';     
if (!empty($recados['right'])) {
  foreach($recados['right'] as $recadosRight){
    echo ' <div class="layerRecadoSuperior">';
    echo ' </div>';
    echo ' <div  class="layerRecadoMeio"><div class="recadoDe">';
                
            if($blog->permissaoAdmBlog($nivel)){
    echo '      <a href="'.$url.'/blog/index.php?opcao=excluirComentario&muralRecados=1&codComentario='.$recadosRight['codComentario'].'"><img src="'.remove.'" border="no" style="width:15px; height: 15px;"></a>' ;             
             }
    echo '   <b>De:</b>'.$blog->getNomePessoa($recadosRight['codPessoa']).'</div>';
    echo '   <div style="margin-left:20; margin-right:20;"><b>Mensagem:</b>'.nl2br($recadosRight['comentario']).'</div>';
    echo ' </div>';
    echo ' <div class="layerRecadoInferior">';
    echo '    <div class="data" align="right" style="margin-right:20;">Escreveu em: '.date('d/m/Y H:i:s',strtotime($recadosRight['data'])).'</div>';
    echo '  </div>';
  }
}
echo '</div>';

echo '</div>';

$codMural=$blog->getcodMuraldeRecados();

if(!empty($codMural)){
echo  ativaDesativaEditorHtml();
echo '<br><br><div >'.$blog->formIncluirComentario($_SESSION['COD_PESSOA'],$blog->getcodMuraldeRecados(),1).'</div>';

}           



$blog->rodape();
break;
/**
 *incluirPost:
 *inclui um novo post na base de dados 
 */
case "incluirPost":
$blog->incluirPost($_REQUEST['post'], $_REQUEST['titulo'] , $_REQUEST['numeroComentarios']);
echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
break;

/**
 *alterarPost:
 *alterar um post na base de dados  
 */
case "alterarPost":
$blog->alterarPost($_REQUEST['titulo'],$_REQUEST['post'],$_REQUEST['numeroComentarios'],$_REQUEST['codPost']);
echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
break;

/**
 *excluirPost:
 *exclui um post da base de dados 
 */
case "excluirPost":
$blog->excluirPost($_REQUEST['codPost']);
echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
break;

/**
 *incluirComentario:
 *permiti qq pessoa incluir um comentário em um determinado post 
 */
case "incluirComentario":

$blog->incluirComentario($_REQUEST['codPost'],$_REQUEST['comentario'],$_REQUEST['codPessoaComentario']);
if($_REQUEST['muralRecados']==1){
  echo '<script>window.location.href="'.$url.'/noticias/noticias.php";</script>';
}
else{
  echo '<script>window.location.href="'.$_SERVER['PHP_SELF']."?acao=".$_REQUEST['acao']."&codPost=".$_REQUEST['codPost'].'";</script>';;
}


break;
/**
 *excluirComentario:
 *permiti excluir, na  base de dados, o comentário  
 */
case "excluirComentario":

$blog->excluirComentario($_REQUEST['codComentario']);

if($_REQUEST['muralRecados']==1){
  echo '<script>window.location.href="'.$url.'/noticias/noticias.php";</script>';
}else{
  echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
}
break;
/**
 *alterarComentario:
 *permiti alterar, na  base de dados, o comentário
 */
case "alterarComentario":
$blog->alterarComentario($_REQUEST['comentario'],$_REQUEST['codComentario']);
echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
break;
/**
 *alterarNomeBlog:
 *permiti alterar, na  base de dados, o nome do blog (descrição de entrada do blog) 
 */
case "alterarNomeBlog":
$blog->alterarNomeBlog($_REQUEST['nomeBlog']);
echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
break;

}
?>