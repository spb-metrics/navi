<?

include("../config.php");
include ($caminhoBiblioteca."/suportetecnico.inc.php");
include($caminhoBiblioteca."/linkimagem.inc.php");
session_name('multinavi'); session_start(); security();

$codPessoa = $_SESSION["COD_PESSOA"];
$userRole = $_SESSION['userRole'];

$instGlobal = new InstanciaGlobal($_SESSION["codInstanciaGlobal"]);
$nivelAtual = $instGlobal->codNivel;
$codInstanciaNivel= getCodInstanciaNivelAtual();

$nivel = getNivelAtual();
$instanciaAtual = new InstanciaNivel($nivel,$codInstanciaNivel);
$nomeTurma = $instanciaAtual->getAbreviaturaOuNomeComPai();

if (empty($_REQUEST["acao"])){
	$_REQUEST["acao"]= " ";
}
else
	$acao = $_REQUEST["acao"];

if (empty($_SESSION["COD_PESSOA"])) {
 die("acesso negado");
}
$atendente = verificaAtendente($codPessoa);
if ($nivelAtual!=6 && $atendente["COD_SETOR"] == 0) {
die("Você precisa estar dentro de uma turma para abrir chamados no Suporte Técnico."); 
}

//desenha o inicio do html
function printHeader($params="") {
  global $urlCss;
  
  echo "<html>";
  echo "<head>";
  //echo "<link rel=\"stylesheet\" href=\"../../cursos.css\" type=\"text/css\">";
  echo "<link rel=\"stylesheet\" href=\"".$urlCss."/suporte.css\" type=\"text/css\">";
  if (!empty($params["titulo"]))
  echo "<title>".$params["titulo"]."</title>";
  echo "</head>";
  echo "<body ".$params["body"]." class='bodybg'>";
  echo "<h3 class=\"titulo\">".$params["tituloPagina"]."</h3>";
}



switch($acao) {  
//se nao tiver passado nada lista os chamados aberto do meu grupo se pertencer a algum grupo.
//se for aluno mostra os chamados dele em aberto e ja abre o novo chamado
  case "":
		$rsCon=recebeGrupoAtual();
    $linha = mysql_fetch_array($rsCon);
    $testaSetor=$linha["COD_SETOR"];
    
    
		//imprime o cabecalho html
	  $params["tituloPagina"]="Suporte Técnico - Abrir Chamado";
	  printHeader($params);
	  if(empty($testaSetor)){
		 echo "<table class=\"tabelaChamado\" align=\"center\">".
		     "<form name=\"form1\" action=\"".$_SERVER["PHP_SELF"]."?acao=insereChamado\" method=\"POST\">".
			 "<tr>".
			 	"<td colspan=\"2\">".
			    "<p><b>Abrir Chamado:</b></p>".
			    "<p align='justify'><b>OBS:</b> este chamado será aberto para a turma atual (".$nomeTurma.") e um consultor técnico/pedagógico ganhará acesso à turma para prestar-lhe suporte.<br>".
			    "<br>Caso queira abrir chamado para outra turma, acesse-a primeiro e depois acesse esta ferramenta de cadastro de Suporte Técnico.<br>".
			    "<br><b>Descreva o mais detalhadamente possível o seu problema, sugestão ou dúvida quanto à Plataforma NAVi:</b></p>".
		        "</td>".
				"<td>".
			 "<tr>".
				"<td>".
				"<textarea name=\"CHAMADO\" cols=\"90\" rows=\"5\" ></textarea>".
				"</td>".
				"<td aling=\"center\">".
				" <a href=\"#\" onclick=\"javascript:form1.submit();\" ><img src=\"./imagens/enviarMensagem.jpg\" border=\"no\"><br>Enviar Chamado</a>".
				"</td>".
			"</tr>".
			"</form>".
			"</table><br>";
			echo "<table  align=\"center\" cellspacing=\"0\" class=\"tabelaFundo2\"><tr><td>";
			echo "<table  align=\"center\" cellpadding=\"3\" cellspacing=\"1\"class=\"tabelaSuporte\">";
			
	      $chamados = listaChamados();
         echo "<tr class=\"tabelaTitulo\"><td>Setor  Atual</td><td>Código do Chamado</td><td>Data de Abertura</td><td>Situação</td><td>Excluir chamado</td><td>Novas Interações</td></tr>";
				foreach($chamados->records as $linha)
				{	
					if (!empty($linha->codSetorAtual)){
					 $setor= descobreSetor($linha->codSetorAtual);
					}
       
          if(empty($linha->estado)){$estado="Fechado";} else{$estado="Aberto";}
					if ($par) { $classeLinha="linhaPar"; $par=0; } else { $classeLinha="linhaImpar"; $par=1; }
          echo "<tr class=\"{$classeLinha}\"><td><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$setor."</a></td>";
					echo"<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$linha->codChamado."</a></td>";
					echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$linha->dataAbertura."</a></td>";
					echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$estado."</a></td>";
					echo "<td><a href=\"#\" onClick=\"if (confirm('Deseja mesmo excluir este chamado ?')) { window.location.href = '".$_SERVER["PHP_SELF"]."?acao=excluiChamado&codChamado=".$linha->codChamado."'}\"><img src=\"./imagens/excluirChamado.gif\" border=\"no\"></a></td>";
					if ($linha->novidadeUser == 1) {
            echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\"><img src=\"./imagens/icone_exclamacao2.gif\" border=\"no\"></a></td>"; 
          }
          else { echo "<td></td>"; }
          echo "</tr>";
				}

		   echo "</table></td></tr></table>";
		     echo "</body>";
         echo "</html>";
	
	  }
	  if(!empty($testaSetor)){
      echo "<script>location.href=\"".$_SERVER["PHP_SELF"]."?acao=listar&ver=abertos\";</script>";
	}	
	  echo "</body>";
    echo "</html>";
	break;

  case "abreChamado":
    sinalizaNovaInteracao($_REQUEST["codChamado"], 'lida');
	  $rsCon=recebeGrupoAtual();
    $linha = mysql_fetch_array($rsCon);
    $testaSetor=$linha["COD_SETOR"];
	  $params["tituloPagina"]="Suporte Tecnico - Chamado ".$_REQUEST["codChamado"]."";
	  printHeader($params);
	  echo"<table width=\"60%\" >".
         " <tr><td align=\"left\">";
			$obj = new Voltar("./index.php","Voltar");
		echo	$obj->imprime();
	 echo"</td></tr><table>".
		  "<form name=\"form1\" action=\"".$_SERVER["PHP_SELF"]."?acao=insereResposta\" method=\"POST\">";
	 echo"<table class=\"opcaoEstadoSetor\" align=\"center\">".
		    "<tr><td>Estado:";
		?>
			<select name="estado" onChange="submit()">
			<option value=1 <?if ($_REQUEST["estado"]== 1) echo "selected";?>>Aberto</option>
			<option value=0 <?if ($_REQUEST["estado"]==0) echo "selected";?>>Fechado</option>
			</select>
		<?
		echo "</td>";
		/*ATUALIZAR SETOR ATUAL*/
		
		if(!empty($testaSetor)){
			echo "<td>";
			echo "Enviar  Chamado para o Setor:";
			$setores= listaSetores();
			echo "<select name=\"codSetorAtual\" onChange=\"submit()\">";
			foreach($setores->records as $linha){
				echo "<option value=". $linha->COD_SETOR; 
				if ( $_REQUEST["codSetorAtual"] == strtoupper($linha->COD_SETOR))
				echo " selected";
				echo ">".$linha->DESC_SETOR."</option>\n";
   
			}
		echo "</td>";
		}
		
   		echo"</tr></table><br>";
		
    echo "<table  align=\"center\" cellspacing=\"0\" class=\"tabelaFundo2\"><tr><td>";
		echo "<table  align=\"center\" cellpadding=\"3\" cellspacing=\"1\"  class=\"tabelaSuporte\">";
				
			 	echo "<tr class=\"tabelaTitulo\"><td>Setor Atual</td><td>Chamadao Aberto por:</td><td>Aberto em:</td><td>Fechado em:</td><td> Chamado</td><td> Local (disciplina/turma)</td><td> Atendente</td></tr>";
				$chamados= imprimiChamado($_REQUEST["codChamado"]);
				foreach($chamados->records as $linha)
				{		
					if (!empty($linha->codSetorAtual)){
					$setor=descobreSetor($linha->codSetorAtual);			
					}
					$instanciaTurma = new InstanciaNivel(getNivelAtual(),$linha->codTurma);
          $imprimiTurma = $instanciaTurma->getAbreviaturaOuNomeComPai();
					$chamado= str_replace("\n", "<br>", $linha->chamado);
					$nomeAtendente = verificaAtendente($linha->codPessoaAtendente);
					echo "<tr class=\"linhaImpar\" ><td align=\"center\">".$setor."</td>";
					echo "<td align=\"center\"><a href='../consultar.php?BUSCA_PESSOA=".$linha->NOME_PESSOA."' title='Visualizar perfil'>".$linha->NOME_PESSOA."</a></td>";
					echo "<td align=\"center\">".$linha->dataAbertura."</td>";
					echo "<td align=\"center\">".$linha->dataFechamento."</td>";
					echo "<td align=\"center\">".$chamado."</td>";
					echo "<td align=\"center\">";
					if (($linha->estado == 1 && $linha->codPessoaAtendente == $codPessoa) || $_SESSION['userRole']==ADMINISTRADOR_GERAL) {
					 echo "<a href='".$url."/index.php?&codNivel=6&codInstanciaNivel=".$linha->codTurma."&userRole=".$userRole."' target='_top' title='Entrar na turma'>";
          }
          echo $imprimiTurma;
          if (($linha->estado == 1 && $linha->codPessoaAtendente == $codPessoa) || $_SESSION['userRole']==ADMINISTRADOR_GERAL) {
          echo "</a>";
          }
          echo "</td><td>".$nomeAtendente["NOME_PESSOA"]."</td></tr>";
				
				}
			echo "</table></tr></td></table><br><br>";	
			
			echo"<table align=\"center\" width=\"600px\">";
			if($_REQUEST["estado"]==1){
			echo "<tr><td><b>Inserir Resposta:</b></td></tr>".
			"<tr><td>".
			"<textarea name=\"descResposta\" cols=\"80\" rows=\"3\" ></textarea>".
			"</td>".
			"<td>".
			" <a href=\"#\" onclick=\"javascript:form1.submit();\" ><img src=\"./imagens/enviarMensagem.jpg\" border=\"no\"><br>Enviar Resposta</a>";
			echo "</td></tr>".
			"</table>";
			}
			echo "<input type=\"hidden\" name=\"codChamado\" value=\"".$_REQUEST["codChamado"]."\">";
				

			echo "<table align=\"center\">";
			echo "<tr><td colspan=\"5\" align=\"center\">";
			echo "<b>Histórico do Chamado</b>";        
			echo " </td></tr></table>";
			echo "<table  align=\"center\" cellspacing=\"0\" class=\"tabelaFundo2\"><tr><td>";
			echo "<table  align=\"center\" cellpadding=\"3\" cellspacing=\"1\" class=\"tabelaSuporte\">";
			echo "<tr class=\"tabelaTitulo\"><td>Setor</td><td>Resposta enviada por:</td><td>Enviado em:</td><td>Resposta</td></tr>";
			$chamados = listaHistoricoChamados($_REQUEST["codChamado"]);
				
			foreach($chamados->records as $linha)
			{		
 
				if (!empty($linha->COD_SETOR))
					{$setor=descobreSetor($linha->COD_SETOR);}
				else {$setor=" ";}
				if ($par) { $classeLinha="linhaPar"; $par=0; } else { $classeLinha="linhaImpar"; $par=1; }
				$descResposta= str_replace("\n", "<br>", $linha->DESC_RESPOSTA);
				echo "<tr class=\"{$classeLinha}\"><td aling=\"center\">".$setor."</td>";
				echo "<td align=\"center\">".$linha->NOME_PESSOA."</td>";
				echo "<td align=\"center\">".$linha->DATA."</td>";
				echo "<td align=\"center\">".$descResposta."</td></tr>";
			
			}
	
			echo "</table></td></tr></table>";
	echo "</form>";
    echo "</body>";
    echo "</html>";
	 
    break;
 
  case "insereResposta":
	$descResposta = str_replace("<br>", "\n", $_REQUEST["descResposta"]);
	updateSetorAtual($_REQUEST["codSetorAtual"],$_REQUEST["codChamado"]);
	updateEstado($_REQUEST["codChamado"],$_REQUEST["estado"]);
	$rsCon=recebeGrupoAtual();
	$linha = mysql_fetch_array($rsCon);
	$testaSetor=$linha["COD_SETOR"];
	inserirResposta($descResposta,$_REQUEST["codChamado"], $testaSetor );
	echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?acao=abreChamado&codChamado='.$_REQUEST["codChamado"].'&estado='.$_REQUEST["estado"]."&codSetorAtual=".$_REQUEST["codSetorAtual"].'";</script>';
  //header("Location:".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$_REQUEST["codChamado"]."&estado=".$_REQUEST["estado"]."&codSetorAtual=".$_REQUEST["codSetorAtual"]."");  
  break;

  case "alteraChamado":
    break;

  case "excluiChamado":
		excluirChamado($_REQUEST["codChamado"]);
		echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'?acao=";</script>';
		//header("Location:".$_SERVER["PHP_SELF"]."?acao=");
		break;
 
  case "insereChamado":
   
    $chamado = str_replace("<br>", "\n", $_REQUEST["CHAMADO"]);
	  // $cahamdo = str_replace("\"" ,"&quot;", $_REQUEST["CHAMADO"]);
    inserirChamado($chamado, GRUPO_PADRAO, $codInstanciaNivel);
    echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
    //header("Location: ".$_SERVER["PHP_SELF"]."");
    break;
  
  case "listar": //recebe parametro de qual mostrar
	//imprime o cabecalho html
	   $params["tituloPagina"]="Suporte Tecnico - Listar";
	   printHeader($params);
	echo"<table  align=\"center\">";
	echo "<tr><td><a href=\"".$_SERVER["PHP_SELF"]."?acao=listar&ver=abertos\">ABERTOS |</a></td>".
		"<td><a href=\"".$_SERVER["PHP_SELF"]."?acao=listar&ver=fechados\">FECHADOS |</a></td>".
		"<td><a href=\"".$_SERVER["PHP_SELF"]."?acao=listar&ver=todos\">TODOS |</a>";
	echo"</td>";
	if($_SESSION['userRole']==ADMINISTRADOR_GERAL)
	  echo "<td><a href=\"".$_SERVER["PHP_SELF"]."?acao=listar&ver=todos&todos=1\">TODOS SETORES |</a></td>";
	if ($atendente["COD_SETOR"] > 0 && $atendente["ativaSuporte"] == 1) {
    echo "<td><a href=\"#\" onClick=\"if (confirm('Os chamados atribuídos para você serão repassados para outro atendente. Confirma esta operação?')) { window.location.href = '".$_SERVER["PHP_SELF"]."?acao=afastamento'}\">SINALIZAR AFASTAMENTO</a>";
  }
  if ($atendente["COD_SETOR"] > 0 && $atendente["ativaSuporte"] == 0) {
    echo "<td><a href=\"#\" onClick=\"if (confirm('A partir de agora novos chamados no Suporte Técnico poderão ser atribuídos para você. Confirma esta operação?')) { window.location.href = '".$_SERVER["PHP_SELF"]."?acao=retorno'}\">SINALIZAR RETORNO</a>";
  }
  echo "</tr>";
	echo"</table>";
    echo"<br><br>";
	if ($_REQUEST["ver"]=="" OR $_REQUEST["ver"]=="abertos" ){
			echo "<table  align=\"center\" class=\"tabelaFundo2\" cellspacing=\"0\"><tr><td>";
			echo "<table align=\"center\" cellpadding=\"3\" cellspacing=\"1\" class=\"tabelaSuporte\">";
			$chamados = listaChamadosAbertos();
			echo "<tr class=\"tabelaTitulo\"><td>Setor  Atual</td><td>Código do Chamado</td><td>Data de Abertura</td><td>Situação</td><td>Atendente</td><td>Novas Interações</td></tr></a>";
			foreach($chamados->records as $linha)
			{			
				if (!empty($linha->codSetorAtual)){
					 $setor= descobreSetor($linha->codSetorAtual);
					
					}
				if(empty($linha->estado)){$estado="Fechado";} else{$estado="Aberto";}
				if ($par) { $classeLinha="linhaPar"; $par=0; } else { $classeLinha="linhaImpar"; $par=1; }
				echo "<tr  class=\"{$classeLinha}\"><td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$setor."</a></td>";
				echo"<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$linha->codChamado."</a></td>";
				echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$linha->dataAbertura."</a></td>";
				echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$estado."</a></td>";
				echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$linha->NOME_PESSOA."</a></td>";
				if ($linha->novidadeAtendente == 1) {
          echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\"><img src=\"./imagens/icone_exclamacao2.gif\" border=\"no\"></a></td>"; 
        }
        else { echo "<td></td>"; }
        echo "</tr>";
			}
			echo "</table></tr></td></table>";
		}
		if ($_REQUEST["ver"]==fechados ){
			echo "<table  align=\"center\" class=\"tabelaFundo2\" cellspacing=\"0\"><tr><td>";
      echo "<table align=\"center\" cellpadding=\"3\" cellspacing=\"1\" class=\"tabelaSuporte\">";
			$chamados = listaChamadosFechados();
			echo "<tr class=\"tabelaTitulo\"><td>Setor Atual</td><td>Código do Chamado</td><td>Data de Abertura</td><td>Situação</td><td>Atendente</td></tr></a>";
			foreach($chamados->records as $linha)
			{			
				if (!empty($linha->codSetorAtual)){
					 $setor= descobreSetor($linha->codSetorAtual);
					
					}
        if(empty($linha->estado)){$estado="Fechado";} else{$estado="Aberto";}
				if ($par) { $classeLinha="linhaPar"; $par=0; } else { $classeLinha="linhaImpar"; $par=1; }
				echo "<tr  class=\"{$classeLinha}\"><td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$setor."</a></td>";
				echo"<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$linha->codChamado."</a></td>";
				echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$linha->dataAbertura."</a></td>";
				echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$estado."</a></td>";
				echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$linha->NOME_PESSOA."</a></td></tr>";
			}
			
			echo "</table></tr></td></table>";
		}
		if ($_REQUEST["ver"]=="todos" ){
			echo "<table  align=\"center\" class=\"tabelaFundo2\" cellspacing=\"0\"><tr><td>";
      echo "<table align=\"center\" cellpadding=\"3\" cellspacing=\"1\" class=\"tabelaSuporte\">";
			$chamados = listaChamadostodos($_REQUEST['todos']);
			echo "<tr class=\"tabelaTitulo\"><td>Setor  Atual</td><td>Código do Chamado</td><td>Data de Abertura</td><td>Situação</td><td>Atendente</td><td>Novas Interações</td></tr></a>";
			foreach($chamados->records as $linha)
			{			
				if (!empty($linha->codSetorAtual)){
					 $setor= descobreSetor($linha->codSetorAtual);
					
					}
        if(empty($linha->estado)){$estado="Fechado";} else{$estado="Aberto";}
				if ($par) { $classeLinha="linhaPar"; $par=0; } else { $classeLinha="linhaImpar"; $par=1; }
				echo "<tr  class=\"{$classeLinha}\"><td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$setor."</a></td>";
				echo"<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$linha->codChamado."</a></td>";
				echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$linha->dataAbertura."</a></td>";
				echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$estado."</a></td>";
				echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\">".$linha->NOME_PESSOA."</a></td>";
        if ($linha->novidadeAtendente == 1) {
          echo "<td aling=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?acao=abreChamado&codChamado=".$linha->codChamado."&estado=".$linha->estado."&codSetorAtual=".$linha->codSetorAtual."\"><img src=\"./imagens/icone_exclamacao2.gif\" border=\"no\"></a></td>"; 
        }
        else { echo "<td></td>"; }
        echo "</tr>";  
			}
			
			echo "</table></tr></td></table>";
		}
	  echo "</body>";
    echo "</html>";
	break;
	
	case "afastamento":
	afastamentoAtendente($_SESSION["COD_PESSOA"]);
	echo "Chamados transferidos para outros atendentes.";
  break;
  
  case "retorno":
	retornoAtendente($_SESSION["COD_PESSOA"]);
	echo "Suporte Técnico configurado para aceitar Chamados.";
  break;

}

?>
