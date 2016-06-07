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
ini_set("display_errors",1); error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

include("../config.php");
include($caminhoBiblioteca."/curso.inc.php");
include($caminhoBiblioteca."/noticia.inc.php");
include($caminhoBiblioteca."/perfil.inc.php");
include($caminhoBiblioteca."/portfolio.inc.php");
include($caminhoBiblioteca."/apresentacao.inc.php");
session_name(SESSION_NAME); session_start(); security();

?>
 <html>
	<head>
		<title>NAVi - N&uacute;cleo de Aprendizagem Virtual</title>

		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" >

		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		<script src='<?=$urlJs?>/checkbox.js'></script>
    </style>
   		
	</head>


<body bgcolor="#FFFFFF" text="#000000">

<table width="" border="0" cellspacing="0" cellpadding="0">
	<tr> 
    <td width="2">&nbsp;  </td>
		<td width="200" valign="top">&nbsp;
    <?php  include("../noticias/noticias_menu_esq_turma.php");   ?>
		</td>
      <?php            
      $nivelAtual = getNivelAtual();
      $codInstanciaNivel= getCodInstanciaNivelAtual();
      $instanciaAtual = new InstanciaNivel($nivelAtual,$codInstanciaNivel);
      ?>
	    <td valign="top" class="colunaDireitaNoticias"> 

       <table width="" border="0" cellspacing="0" cellpadding="2" style='width:540; '>
       <tr><td><br><br></td></tr>     
       <tr valign='bottom' align='center' >
           <td align='left'>
           <img src='foto.php?COD_PESSOA=<?=$_SESSION["COD_PESSOA"]?>&CASE='' border='none' width='<? echo LARGURA_FOTO;?>'  height='<? echo ALTURA_FOTO;?>'>
           Bem-vindo <? echo printNome($_SESSION["COD_PESSOA"]); ?>!
           </td>
           <td>
           <?php if (podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) {?>
             <a href="alterar_perfil.php?COD_PESSOA=<?=$_SESSION["COD_PESSOA"]?>" title='Atualizar Perfil: foto, descrição, etc'>
             <img src='<?=$urlImagem?>/atualizaperfil.jpg'  border='no'>
             </a>
           <?php } ?>
           </td>
           <td>
           <?php if (podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) {?>
             <a href="../cadastro/frm_atualiza_cadastro.php" title='Atualizar Cadastro: nome, senha, etc'>
               <img src='<?=$urlImagem?>/atualizacadastro.jpg' border='no'>
             </a>
            <?php } ?>
           </td>
           <td>
           <?php if (podeInteragir($_SESSION['userRole'],$_SESSION['interage'])) {?>          
             <a href="recados.php?COD_PESSOA_RECEBE=<?=$_SESSION['COD_PESSOA']?>" title='Seus recados'>
               <img src='<?=$urlImagem?>/recados.jpg' border='no'>
             </a>
            <?php } ?>
           </td>    
      </tr>
      <tr><td colspan='4'> 
           <span>
           <?
           $numeroNovosRecados = getNovosRecados($_SESSION["COD_PESSOA"]);           
           $numeroNovosEmails  = getNovosEmails($_SESSION["COD_PESSOA"]);
           if ($numeroNovosRecados>0) {
             echo "<br><a href='recados.php?COD_PESSOA_RECEBE=".$_SESSION["COD_PESSOA"]."' >"; //o numero de recados novos ja   um link para os recados                
             echo "<br><img src='".$urlImagem."/recado-nao-lida.gif'  border='no'>&nbsp;";  
             echo "<b>".$numeroNovosRecados."</b> ";
             if ($numeroNovosRecados==1) { echo " recado n&atilde;o lido."; } else { echo " recados n&atilde;o lidos.";}
             echo "</a>";
           }
           
           if ($numeroNovosEmails>0) {
             echo "<br><a href='".$url."/interacao/correio/index.php'  >"; //o numero de msg novas ja   um link para o correio
	             echo "<br><img src='".$urlImagem."/mensagem-nao-lida.png'  border='no'>&nbsp;";  
             echo "<b>".$numeroNovosEmails. "</b> ";
             if ($numeroNovosEmails==1) { echo " mensagem n&atilde;o lida."; } else { echo " mensagens n&atilde;o lidas.";}
             echo " de correio.";
             echo "</a>";
           } 
           if($_SESSION['userRole']==PROFESSOR){
                $retPortStatusQtde= PortStatus($_SESSION["COD_PROF"],"","","qtde");
                if($retPortStatusQtde>0)
                print "<a href='".$url."/portifolio/'  ><br><strong>"  . 
                $retPortStatusQtde . "</strong> novo(s) arquivo(s) publicado(s) no portf&oacute;lio.</a>";
           } 

           ?>
           </span>
      
        </div>
        </td>
        </tr>

        <!-- integrantes do nivel -->   
        <tr><td colspan="4">	
        <b>
        <?php         
        // fotos começam pequenas por padrao. Aumentam caso usuario queira
        // 0-foto pequena | 1-foto grande
        if (isset($_REQUEST['tamanhoFoto'])) { $tamanhoFoto = (int)$_REQUEST['tamanhoFoto']; } else { $tamanhoFoto = 0;  }

        //niveis de relacionamento das pessoas, tanto formais quanto informais, 
        //exibem todos usuarios, por padrao
        if ($instanciaAtual->relacionaPessoas() ) {          
          if (!isset($_REQUEST['onLine'])) { $onLine = 0; }
          else { $onLine = (int)$_REQUEST['onLine']; }
          $rsConN = listaProfessores($nivelAtual->isNivelComunidade(),$onLine);          
        }
        //demais niveis exibem apenas onLine, por padrao
        else {
          if (!isset($_REQUEST['onLine'])) { $onLine = 1; }
          else { $onLine =(int)$_REQUEST['onLine']; }  

          $rsConN = listaTodosIntegrantes(new Professor(),0,$onLine);          
        }

        $numProfessores= mysql_num_rows($rsConN);

        if ($onLine==0) {
          echo "  <a href='index.php?onLine=1&tamanhoFoto=".$tamanhoFoto."'>Apenas Online</a>";
        }
        else {
          echo "<a href='index.php?onLine=0&tamanhoFoto=".$tamanhoFoto."'>Mostrar Todos<a/></a>";        
        }
        echo " | ";
        if (!$tamanhoFoto) {
          $altura = ALTURA_FOTO_PEQUENA; $largura = LARGURA_FOTO_PEQUENA;
          $tipoFoto="FOTO_REDUZIDA"; //prepara para exibição do arquivo correto
          echo"<a href='index.php?tamanhoFoto=1&onLine=".$onLine."'>Aumentar fotos<a/></a><br><br>";
        }
        else {
          $tipoFoto=""; //prepara para exibição do arquivo correto
          $altura = ALTURA_FOTO; $largura = LARGURA_FOTO;
          echo"<a href='index.php?tamanhoFoto=0&onLine=".$onLine."'>Diminuir fotos<a/></a><br><br>";        
        }
        echo "<br><br>";
        echo $nivelAtual->nome.": ";
  
        if($nivelAtual->isNivelComunidade()) {
          echo " Moderador";
        } 
        else { echo " Professor"; }
        //acerta o plural e mostra o número
        if ($numProfessores>1) { echo "es: ".$numProfessores; }
        echo "</b>";
        echo "<span style='position:relative; left:340;'><a href='".$url."/blog/index.php?codInstanciaGlobal=".$_SESSION['codInstanciaGlobal']."'><b>Blog ".$nivelAtual->nome."</b></a></span>"; 
        ?> 
         <hr /> </td>

				</tr>
				<?php
        
        //busca o timestamp uma vez para o tempo de execução do php nao influir
        //durante a exibição dos registros
        $tempo = time();
        $pessoa= new Pessoa(); //instancia apenas uma vez o objeto para performance
        //linha par/impar
        $par=0;
        if (!empty($rsConN)) {
          while ($linha = mysql_fetch_array($rsConN)) { 
            if ($par) { $par=0; $classe='CelulaEscura';} else { $par=1; $classe='CelulaClara';}  
            $pessoa->setAtributos($linha);
              echo "<tr valign='bottom' align='center' class='".$classe."'><td align='left'>";
              echo "<span style='height:21;'>";
              //comentado temporariamente no eavirtual
              echo "<img src='foto.php?COD_PESSOA=".$linha["COD_PESSOA"]."&CASE=".$tipoFoto."' height=".$altura." width=".$largura." border='none'>";
              if ($instanciaAtual->relacionaPessoas()) {
                echo Professor::iconeTipoProfessor($linha["codTipoProfessor"],$linha["descTipoProfessor"])."&nbsp;";            
              }
              echo $pessoa->isOnline($tempo);
              echo "<span title='".$linha["descTipoProfessor"]."'>". $linha["NOME_PESSOA"]."</span>";
              echo "</span></a></td>";
              echo "<td><a href='".$url. "/consultar.php?BUSCA_PESSOA=".$linha["COD_PESSOA"]."' target='_blank'  >" . "Perfil </a></td>";
              echo "<td><a href='../cadastro/frm_cadastro.php?COD_PESSOA=" . $linha["COD_PESSOA"] . "'   >" . "Dados Cadastrais </a></td>";
              echo "<td><a href='recados.php?COD_PESSOA_RECEBE=".$linha["COD_PESSOA"]."'  >"."Recados</a></td>";
              
              echo "<td><a href='".$url."/blog/index.php?codPessoa=".$linha["COD_PESSOA"]."'>Blog</a></td></tr>";
  
            }     
        }
        
        echo '<tr bgcolor="white" >';
        echo '<td colspan="4"><br>';
				
        //trata a exibicao das pessoas de acordo com o tipo de nivel
        if ($instanciaAtual->relacionaPessoas()) {
          $numAlunos = numeroAlunosTurma($nivelAtual->isNivelComunidade());
          if ($numAlunos>PAGINA_ALUNOS) {
            if (empty($_REQUEST["numPagina"]) )  { $numPagina=1;  } 
            else { $numPagina=$_REQUEST["numPagina"];  }
          }
          else { $numPagina=0;}
          
          $rsConN = listaAlunos($numPagina,$nivelAtual->isNivelComunidade(),$onLine);
           
        }
        else {
          //essa chamada da função serve para primeiro obter o total de alunos
          $result = listaTodosIntegrantes(new Aluno(),0,$onLine,1);
          //resultado dá o subtotal de cada ramo, precisamos somar         
          while ($consulta = mysql_fetch_object($result)) {
            $numAlunos += $consulta->numAlunos; 
          }
          if ($numAlunos>PAGINA_ALUNOS) {
            if (empty($_REQUEST["numPagina"]) )  { $numPagina=1;  } 
            else { $numPagina=$_REQUEST["numPagina"];  }
          }
          else { $numPagina=0;}
          
          $rsConN = listaTodosIntegrantes(new Aluno(),0,$onLine,0,$numPagina);   

        }

        echo "<b>";
        if($nivelAtual->isNivelComunidade()) { echo "Participantes"; } 
        else { echo "Alunos";  }
        if ($numAlunos>0) { echo ": ".$numAlunos; }        
        if (Pessoa::podeAdministrar($_SESSION['userRole'],$nivelAtual,$_SESSION['interage'])) {
          echo ' <a href="'.$url.'/alunos/listachamada.php"  title="Impressao da lista de chamada." ><img src="'.$urlImagem.'/impressaochamada.gif" border="no"></a>';
        }
        echo "<a href='".$url."/alunos/registropresenca.php'  title='Registro da presenca dos alunos.'><img src='".$urlImagem."/registropresenca.jpg' border='no'></a>";
        /*
        if($_SESSION['codInstanciaGlobal']==773){
          echo "&nbsp;&nbsp;<a href='".$url."/reuniao/index.php'  title='Reunião.'  ><img src='".$urlImagem."/reuniao.gif' border='no'></a>";
				}*/

        //Paginacao        
        if ($numAlunos>PAGINA_ALUNOS) {   
          mostraPaginacao($numAlunos,$numPagina,$tamanhoFoto,$onLine);    
        }
        echo '</tr>';

        $par=0;
        if (!empty($rsConN)) {

          while ($linha = mysql_fetch_array($rsConN)) {
            if ($par) { $par=0; $classe='CelulaEscura';} else { $par=1; $classe='CelulaClara';}  
            $pessoa->setAtributos($linha);
            echo "<tr valign='bottom' align='center' class='".$classe."'><td valign='middle' align='left'>";
            //comentado temporariamente no eavirtual
            echo "<img src='foto.php?COD_PESSOA=".$linha["COD_PESSOA"]."&CASE=".$tipoFoto."' height=".$altura." width=".$largura." border='none'>";
    
            echo $pessoa->isOnline($tempo);
            
            echo $linha['NOME_PESSOA'].'</td>';
            echo '<td><a href="'.$url.'/consultar.php?BUSCA_PESSOA='.$linha['COD_PESSOA'].'" target="_blank">Perfil </a></td>';
            echo '<td><a href="../cadastro/frm_cadastro.php?COD_PESSOA='.$linha['COD_PESSOA'] .'">Dados  Cadastrais </a></td>';
            echo '<td><a href="recados.php?COD_PESSOA_RECEBE='.$linha['COD_PESSOA'].'">Recados</a></td>';
            
            echo '<td><a href="'.$url.'/blog/index.php?codPessoa="'.$linha["COD_PESSOA"].'">Blog</a></td></tr>';
          }
        } 
          
        echo '</table>';
        //Paginacao        
        if ($numAlunos>PAGINA_ALUNOS) {  mostraPaginacao($numAlunos,$numPagina,$tamanhoFoto,$onLine);   }
         
    		echo '</td></tr></table>';

        echo "<div style=\"position:absolute; width:150; top:5; left:600; border:0px z-index:3;\">";
        if($nivelAtual->isNivelComunidade()) {
          if (Pessoa::podeAdministrar($_SESSION['userRole'],$nivelAtual,$_SESSION['interage'])) {
            echo "<a href=\"".$url."/alunos/acoes_comunidade.php?acao=excluirComunidade\"><img src=\"".$urlImagem."/remove.gif\" border=\"no\"><br>Excluir Comunidade</a><br>";            
            //echo "<a href=\"../tools/instanciasNiveis.php?OPCAO=Excluir&frm_codInstanciaNivel=".$codInstanciaNivel."&voltar=../alunos/index.php\"><img src=\"".$urlImagem."/remove.gif\" border=\"no\"><br>Excluir Comunidade</a><br>";
          }
          echo "<a href=\"".$url."/alunos/acoes_comunidade.php?acao=sairComunidade\"><img src=\"".$urlImagem."/remove.gif\" border=\"no\"><br>Sair Comunidade</a><br>";
          //echo "<br><a href=\"../tools/instanciasNiveis.php?OPCAO=sairComunidade&frm_codInstanciaNivel=".$codInstanciaNivel."\"><img src=\"".$urlImagem."/saircomunidade.jpg\" border=\"no\"><br>Deixar de Participar da Comunidade</a>";
        } 
        echo "</div>"; 
  

function mostraPaginacao($numAlunos,$paginaAtual,$tamanhoFoto,$onLine) { 
  
  //pode ser limitado o numero maximo de paginas
  $numPaginas=intval($numAlunos / PAGINA_ALUNOS);
  if ($numAlunos % PAGINA_ALUNOS) { $numPaginas++;  }

  
  $salto=1;
  //maximo de páginas
  if ($numPaginas>10) {
    $salto=intval($numPaginas/10);
  }
  $ant = $paginaAtual-1;   $prox = $paginaAtual+1;
  
  echo "&nbsp;Pagina: ";
  if ($ant>0) {
    echo "<a href=".$_SERVER["PHP_SELF"]."?numPagina=".$ant."&tamanhoFoto=".$tamanhoFoto."&onLine=".$onLine."'>&nbsp;<&nbsp;</a>";
  }
  $pag=1;
  
  for($i=1;$i<=$numPaginas;$i+=$salto) {
    if ($i==$paginaAtual) { $iniPag="<strong><big>"; $fimPag="</big></strong>";  } 
    else { $iniPag=""; $fimPag=""; }
    echo "<a href='".$_SERVER["PHP_SELF"]."?numPagina=".$i."&tamanhoFoto=".$tamanhoFoto."&onLine=".$onLine."'>".$iniPag.$i.$fimPag."</a>";
    if ($i<$numPaginas) { echo "&nbsp;&nbsp;|&nbsp;&nbsp;"; }
    //Se a pagina atual nao estiver entre as exibidas, entao a exibe
    if ($i<$paginaAtual && (($i+$salto)>$paginaAtual)  ) {
      echo "<a href='".$_SERVER["PHP_SELF"]."?numPagina=".$paginaAtual."&tamanhoFoto=".$tamanhoFoto."&onLine=".$onLine."'><strong><big>".$paginaAtual."</big></strong></a>&nbsp;&nbsp;|&nbsp;&nbsp;";    
    }    
  }
  //dependendo do salto, a ultima pagina pode nao ter sido exibida
  if (($i-$salto)!=$numPaginas) {
    echo "<a href='".$_SERVER["PHP_SELF"]."?numPagina=".$numPaginas."&tamanhoFoto=".$tamanhoFoto."&onLine=".$onLine."'>".$numPaginas."</a>";  
  }

  if ($prox<=$numPaginas) { //verifica se deve ser mostrado o link de proxima pagina
    echo "<a href='".$_SERVER["PHP_SELF"]."?numPagina=".$prox."&tamanhoFoto=".$tamanhoFoto."&onLine=".$onLine."'>&nbsp;>&nbsp;</a>";
  }
   
}
?> 
</body>
</html>