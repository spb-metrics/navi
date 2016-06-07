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

Class Blog extends RDCLRow{
   var $codBlog;
  
     
   
  /**
   *  Construtor da classe blog
   */    
  function Blog($codPessoa="",$codInstancia=""){
   if(!empty($codPessoa)){
       if($this->getBlogPessoa($codPessoa)=='')
         $this->initBlogPessoa($codPessoa);
        
    }else{
      if(!empty($codInstancia)){
        if($this->getBlogInstancia($codInstancia)==''){
          $this->initBlogInstancia($codInstancia);
          $this->initMuraldeRecados();
        }else{
          if($this->getcodMuraldeRecados()==0){
             $this->initMuraldeRecados();
          }
        }
      }
   }
  }
  
  /**
   *  retorna/inicializa variavel $codBlog da pessoa
   */   
  function getBlogPessoa($codPessoa){
    $sql="SELECT B.codBlog FROM blog B".
         " INNER JOIN blogpessoa BP ON (BP.codBlog=B.codBlog)".
         " WHERE BP.codPessoa=".$codPessoa;
         
    $rsCon=mysql_query($sql);
    
    if(mysql_num_rows($rsCon)>0){
      $codBlog=mysql_fetch_array($rsCon); 
      $this->codBlog=$codBlog['codBlog'];
    }else 
      $this->codBlog='';
      
      return $this->codBlog;
  }
  /**
   *  retorna/inicializa variavel $codBlog da instancia
   */   
  function getBlogInstancia($codInstanciaGlobal){
     $sql="SELECT B.codBlog FROM blog B".
         " INNER JOIN bloginstancia BI ON (BI.codBlog=B.codBlog)".
         " WHERE BI.codInstanciaGlobal=".$codInstanciaGlobal;
     
    $rsCon=mysql_query($sql);
    
    if(mysql_num_rows($rsCon)>0){
      $codBlog=mysql_fetch_array($rsCon); 
      $this->codBlog=$codBlog['codBlog'];
    }else 
      $this->codBlog='';
      
      return $this->codBlog;
    
  }
  /**
   *  retorna o nome do Blog
   */     
  function getNomeBlog(){
    $sql="SELECT nomeBlog FROM blog WHERE codBlog=".$this->codBlog;
    
    $rsCon=mysql_query($sql);
    $codBlog=mysql_fetch_array($rsCon); 
    return $codBlog['nomeBlog'];
  }
  /**
   *  retorna o nome da pessoa solicitado
   */     
  function getNomePessoa($codPessoa){
    $sql="SELECT NOME_PESSOA FROM pessoa WHERE COD_PESSOA=".$codPessoa;
    $rsCon=mysql_query($sql);
    $nomePessoa=mysql_fetch_array($rsCon); 
    return $nomePessoa['NOME_PESSOA'];
  }
  /**
   *  retorna o numero total de comentarios existem em um post
   */   
  function getNumeroComentPost($codPost){
    $sql="SELECT count(*) AS numComent FROM blogcomentariopost WHERE codPost=".$codPost; 
    $rsCon=mysql_query($sql);
    $numPost=mysql_fetch_array($rsCon); 
    return $numPost['numComent'];
  }
  /**
   *  retorna o numero de posts de um determinado blog
   */     
  function getNumeroPost(){
    $sql="SELECT count(*) AS numPost FROM blogpost WHERE codBlog=".$this->codBlog; 
    $rsCon=mysql_query($sql);
    $numPost=mysql_fetch_array($rsCon); 
    return $numPost['numPost'];
  }
  /**
   *  retorna o codigo do mural
   */   
  function getcodMuraldeRecados(){
    $sql=" SELECT codMuralRecados FROM bloginstancia WHERE codBlog=".$this->codBlog;
     
     $rsCon=mysql_query($sql);
     $codMural=mysql_fetch_array($rsCon);
     return  $codMural['codMuralRecados'];
  }   
    
  /**
   *    inicializa o blog caso ele não exista
   */   
  function initBlogPessoa($codPessoa){
    $sql="INSERT INTO blog (nomeBlog) VALUES ('Bem Vindo!')";
    mysql_query($sql);
    
    $lastId=mysql_insert_id();
    $this->codBlog=$lastId;
    
    $sql="INSERT blogpessoa (codBlog, codPessoa) VALUES (".$lastId.",".$codPessoa.")";
    
     mysql_query($sql);
     return (!mysql_errno()); 
  }
  /**
   *   inicializa o blog caso ele não exista;
   */   
  function initBlogInstancia($codInstancia){
    $sql="INSERT INTO blog (nomeBlog) VALUES ('Bem Vindo!')";
    mysql_query($sql);
    
    $lastId=mysql_insert_id();
    $this->codBlog=$lastId;
    
    $sql="INSERT bloginstancia (codBlog,codInstanciaGlobal) VALUES (".$lastId.",".$codInstancia.")";
    
     mysql_query($sql);
     return (!mysql_errno()); 
      
  }
  /**
   *  essa função inicializa o mural de recados que é um post dentro do blog da instancia
   *  porém ele é mostrado dentro de notícias.   
   */       
  function initMuraldeRecados(){
    $this->incluirPost('','Mural de Recados',-1);
    $lastId=mysql_insert_id();
    $sql=" UPDATE bloginstancia SET  codMuralRecados=".$lastId;
    $sql.=" WHERE codBlog=".$this->codBlog;
     mysql_query($sql);
    return (!mysql_errno());
  }
  /**
   *   está função é do form de incluir novo post em um blog
   */   
  function formIncluirPost(){
  GLOBAL $url;
    echo'<div align=center><form name="f1" method="post"  action="'.$_SERVER['PHP_SELF'].'?opcao=incluirPost">',
        '<fieldset>';
    echo"<legend><u><b>Adicionar Post</b></u><img src='".$url."/imagens/diminui.gif' onClick=\"mostraForm('formPost');mudaFigura(this,'".$url."')\" id=\"imagem\" style=\"cursor:pointer\"></legend>";
    echo'<div id="formPost" align="center" style=\"display:inline;\">',	
        'Título<br><input type="text" name="titulo" value="" size="20%"><br>',				
			  '<textarea class="alterarPost" name="post" style="width: 90%; height:300px;"></textarea><br>',
			  'Nº. comentários:<input type="text" name="numeroComentarios" value="" size="5%"><br>',
        '  Digite <font color="red">0</font> para encerrar<br>',
        '  Digite <font color="red">-1</font> deixar livre<br>',
				'<input type="button" name="Submit" value="Enviar" onclick="document.f1.submit();"><br>',
		    '</div>',
	      '</fieldset>',
	      '</form></div>';
  }
  /**
   *  formulário para incluir um comentário
   */     
  function formIncluirComentario($codPessoa,$codPost,$muralRecados=""){
  GLOBAL $url;
    if($muralRecados==1){
      $comentario='Deixe seu Recado:';
      $action=$url.'/blog/index.php?opcao=incluirComentario';
    }else{
      $comentario='Deixe seu comentário:';
      $action=$_SERVER['PHP_SELF'].'?opcao=incluirComentario';
    }
  
  
    echo '<br><br><div align="center">';
    echo ' <form name="incluirComentario" method="post"  action="'.$action.'">';
    echo '  <b>'.$comentario.'</b><br><textarea name="comentario" style="width: 90%; height:100px;"></textarea><br>';
    echo '  <input type="hidden" name="codPost" value="'.$codPost.'">';
    echo '  <input type="hidden" name="acao" value="mostrarComentario">';
    echo '  <input type="hidden" name="muralRecados" value="'.$muralRecados.'">';
    echo '  <input type="hidden" name="codPessoaComentario" value="'.$codPessoa.'">';
    echo '  <input type="button" name="Submit" value="Enviar" onclick="document.incluirComentario.submit();">';
    echo ' </form>';
    echo '</div>'; 

  }
  /**
   *  form para alterar post
   */     
  function formAlterarPost($titulo,$post,$numeroComentario,$codPost){
    echo '<div align="center">';
    echo ' <form name="alterarPost" method="post"  action="'.$_SERVER['PHP_SELF'].'?opcao=alterarPost">';
    echo '  <input type="text" name="titulo" value="'.$titulo.'" size="80">';
    echo '  <textarea name="post" style="width: 700px; height:100px;">'.$post.'</textarea><br>';
    echo '   Nº. comentários:<input type="text" name="numeroComentarios" value="'.$numeroComentario.'" size="5%"><br>';
    echo '  Digite <font color="red">0</font> para encerrar.<br>';
    echo '  Digite <font color="red">-1</font> deixar livre.<br>';
    echo '  <input type="hidden" name="codPost" value="'.$codPost.'">';
    echo '  <input type="button" name="Submit" value="Enviar" onclick="document.alterarPost.submit();">';
    echo ' </form>';
    echo '</div>';  
  } 
  /**
   *  alterar nome do blog
   */   
  function formAlterarNomeBlog($nomeBlog){
    echo '<div align=center>';
    echo ' <form name="alterarNomeBlog" method="post"  action="'.$_SERVER['PHP_SELF'].'?opcao=alterarNomeBlog">';
    echo '  <input type="text" name="nomeBlog" value="'.$nomeBlog.'" size="80">';
    echo '  <input type="button" name="Submit" value="Enviar" onclick="document.alterarNomeBlog.submit();">';
    echo ' </form>';
    echo '</div>';  
  } 
  
  
  
  /**
   *  incluir novo post no blog (da pessoa ou da instancia)
   */   
  function incluirPost($post, $titulo , $numeroComentarios){
    $sql="INSERT INTO blogpost (codBlog, data , post, titulo , numeroComentarios)".
         " VALUES(".$this->codBlog.",now(),".quote_smart($post).",".quote_smart($titulo).",".quote_smart($numeroComentarios).")";
    
    mysql_query($sql);
    return (!mysql_errno());
  }
   /**
   *  incluir comentario em um determinado post
   */       
  function incluirComentario($codPost,$comentario,$codPessoa){
    $sql=" INSERT INTO blogcomentariopost (codPost,data,comentario,codPessoa)".
         " VALUES(".$codPost.",now(),".quote_smart($comentario).",".$codPessoa.")";
  
    mysql_query($sql);
    return (!mysql_errno());
    
  }
  /**
   *  lista todos os comentarios de um post
   */     
  function listComentario($codPost){
  $sql="SELECT * FROM blogcomentariopost BCP ".
         " WHERE BCP.codPost=".$codPost;
        
         return mysql_query($sql);
  
  }
  /**
   *  lista todos os posts de um determinado blog independentemente de  ser da pessoa ou da instancia
   */           
  function listPost(&$numTotalMsg,$limiteIniMsg,$threadsPerPage,$orderby){
    
      $numTotalMsg=$this->getNumeroPost();
    
    
    $sql="SELECT * FROM blogpost BPO ".
         " WHERE BPO.codBlog=".$this->codBlog;
    
    if(!empty($orderby)){
      $sql.=" ORDER BY BPO.data ".$orderby;
    }
    if ($numTotalMsg > $threadsPerPage ){    
      $sql.= " LIMIT {$limiteIniMsg},{$threadsPerPage} ";
    }  
  
         return mysql_query($sql);
  }
  /**
   *  Altera conteudo do comentário
   */     
  function alterarComentario($comentariom,$codComentario){
    $sql="UPDATE blogcomentariopost SET comentario=".$comentario;
    $sql.=" WHERE codComentario=".$codComentario;
    
    mysql_query($sql);
    return (!mysql_errno());
    
  }
  /**
   *  Altera conteúdo do post
   */     
  function alterarPost($titulo,$post,$numeroComentarios,$codPost){
    $sql="UPDATE blogpost SET post=".quote_smart($post).", titulo=".quote_smart($titulo).", numeroComentarios=".quote_smart($numeroComentarios);
    $sql.=" WHERE codPost=".$codPost;
    
    mysql_query($sql);
    return (!mysql_errno());
  }
  function alterarNomeBlog($nomeBlog){
    $sql="UPDATE blog SET nomeBlog=".quote_smart($nomeBlog);
    $sql.=" WHERE codBlog=".$this->codBlog;
    
    mysql_query($sql);
    return (!mysql_errno());
  }
  /**
   *  exclui um comentario de um post de um determinado blog
   */     
  function excluirComentario($codComentario){
    $sql="DELETE FROM blogcomentariopost WHERE codComentario=".$codComentario;
    
    mysql_query($sql);
     return (!mysql_errno()); 
  }
  /**
   *  exclui da base de dados um post de um determinado blog
   */   
  function excluirPost($codPost){
  $sql="DELETE FROM blogpost WHERE codPost=".$codPost;
  
  mysql_query($sql);
  return (!mysql_errno()); 
  }
  /**
   *  abre html
   */   
  function cabecalho(){
  GLOBAL $urlJs;
  GLOBAL $urlCss;
    echo"<html>".
	     	"	 <head>".
	     	"		<title>Blog</title>".
    		"		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">".
	     	"		<link rel=\"stylesheet\" href=\"./../cursos.css\" type=\"text/css\">". 
	     	"		<link rel=\"stylesheet\" href=\"".$urlCss."/blog.css\" type=\"text/css\">". 
	     	"		<script language=\"JavasScript\" type=\"text/javascript\" src=\"".$urlJs."/utils.js\"></script>".
	     	"		<script language=\"JavasScript\" type=\"text/javascript\" src=\"".$urlJs."/editor.js\"></script>".
	     	"		<script language=\"JavasScript\" type=\"text/javascript\" src=\"".$urlJs."/tiny_mce/tiny_mce.js\"></script>".
        "	</head>".
	     	" <body>";
  }
  /**
   *  fecha html
   */     
  function rodape(){
    echo "</body>".
         "</html>";
  }
  /**
   *  imprime a paginacao
   */   
  function imprimePaginacao($paginaAtual) {
    global $threadsPerPage,$maxPaginas;
    $numPaginas = ceil($_SESSION["NUM_MESSAGES"] / $threadsPerPage);

    //calcula e mostra anterior, atual e posterior
    echo "P&aacute;ginas Anterior, Atual e Pr&oacute;xima:&nbsp;"; 
    $paginaAnterior=$paginaAtual-1; $paginaProxima=$paginaAtual+1;
    if ($paginaAnterior==0) { $paginaAnterior=1; }   if ($paginaProxima>$numPaginas) { $paginaProxima=$numPaginas; }
    echo "<a href=\"".$_SERVER["PHP_SELF"]."?paginaBlog=".$paginaAnterior."\" style=\"font-size: 14px;\">".$paginaAnterior."</a>&nbsp;";
    echo "<a href=\"".$_SERVER["PHP_SELF"]."?paginaBlog=".$paginaAtual."\" style=\"font-size: 14px; font-weight: bold\"><big>".$paginaAtual."</big></a>&nbsp;";
    echo "<a href=\"".$_SERVER["PHP_SELF"]."?paginaBlog=".$paginaProxima."\" style=\"font-size: 14px;\">".$paginaProxima."</a>&nbsp;";
    echo "<br>";
    //Mostra as paginas com um salto ajustavel ao numero total de páginas
    echo "P&aacute;gina:&nbsp;";
    $passo=floor($numPaginas/$maxPaginas);
    if ($passo==0) { $passo=1; }
    for($i=1; $i<=$numPaginas; $i+=$passo) {
      $lastPage=$i;
      if ($paginaAtual == $i)
       echo "<a href=\"".$_SERVER["PHP_SELF"]."?paginaBlog=".$i."\" style=\"font-size: 14px; font-weight: bold\"><big>".$i."</big></a>&nbsp;";
      else
        echo "<a href=\"".$_SERVER["PHP_SELF"]."?paginaBlog=".$i."\" style=\"font-size: 14px\">".$i."</a>&nbsp;";
    }
    //Imprimimos a ultima página sempre, caso ela não tenha sido impressa
    if ($lastPage<$numPaginas) { 
      echo "<a href=\"".$_SERVER["PHP_SELF"]."?paginaBlog=".$numPaginas."\" style=\"font-size: 14px\">".$numPaginas."</a>&nbsp;";
    }

   
  }
  /**
   *  verifica permissão de administração do blog
   */   
  function permissaoAdmBlog($nivel){

     if($_SESSION['COD_PESSOA']==$_SESSION['codPessoaBlog'] && !empty($_SESSION['COD_PESSOA'])&& !empty($_SESSION['codPessoaBlog'])){
         return true;
    }else{
    
          if(!empty($_SESSION['codInstanciaGlobalBlog'])&& pessoa::podeAdministrar($_SESSION['userRole'],$nivel,$_SESSION['interage'])){
            return true;
          }else{  return false;}
          
           
        } 
  }
  /**
   *  calcula a mensagem que deve ser retonada dado um determinado numero de pagina
   */     
  function calcIniMsg($paginaBlog) {
   global $threadsPerPage;
    return ($paginaBlog-1)*$threadsPerPage; 
  }
}
?>
