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
//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
include_once ($caminhoBiblioteca."/cadastro.inc.php")
session_name(SESSION_NAME); session_start(); security();

$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
?>

<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		<script language="JavaScript" src="./../funcoes_js.js"></script>
		<script language="JavaScript">
			<!--
			function MM_reloadPage(init) {  //reloads the window if Nav4 resized
			  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
				document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
			  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
			}
			MM_reloadPage(true);
			// -->
		</script>
		<script>
			var alunos = new Array(0);
			
			function adiciona(valor)
			{
				alunos.push(valor);
			 }
			function remove(valor)
			{
				for ( i=0; i < alunos.lenght; i++ )
					if ( alunos[i] = valor )
						alunos[i] = NULL;
			 }
		</script>
	</head>
<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<center><br>
  <table cellpadding="10" cellspacing="0" border="0" width="85%"  align="center">
    <tr> 
      <td colspan=2>
        <center>
          <font size="4"><b>Alunos</b></font> 
        </center>
      </td>
    </tr>
    <tr> 
      <td  align=left><a href="aluno_operacao.php">Inserir Novo Aluno</a></td>
      <td align=right><a href="./../ferramentas.php" target="_parent">Ferramentas 
        de Gerência</a> - <a href="javascript:history.back()">Voltar</a></td>
    </tr>
    <tr> 
      <td colspan=2>
        <center>
          A - B - C - D - E - F - G - H - I - J - K - L - M - N - O - P - Q - 
          R - S - T - U - V - W - X - Y - Z - TODOS 
        </center>
      </td>
    </tr>
    <tr> 
      <td colspan=2 height="203"> 
        <form name="consulta" method="get" action="">
          <p align="left">Curso/Turma : <br>
            <select name="CURSO" onChange="submit();" >
              <option value="">Todos</option>
              <?php
				
				if ( !isset($_REQUEST["CURSO"]) )
					$_REQUEST["CURSO"] = "";
				
					$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];
				
					if ( $acesso == 1 )
						$rsConC = listaAcesso(15, "", "", "");
					else
					{
						if ( $acesso == 2 )
							$rsConC = listaAcesso(4, "", "", "");
						else
						{
							if ( $acesso == 3 )
								$rsConC = listaAcesso(7, "", "", "");
						 }
					 }
						 
					if ( $rsConC ) // lista os cursos
						while ( $linhaC = mysql_fetch_array($rsConC) )
						{
							echo "<option value=" . $linhaC["COD_CURSO"];
							if ( $_REQUEST["CURSO"] == strval($linhaC["COD_CURSO"]) )
								echo (" selected");
								
							echo ">" . $linhaC["DESC_CURSO"] ." - ". $linhaC["ABREV_CURSO"] ."</option>";
						 } // final da listagem dos cursos
					?>
            </select>
            <?php
					$COD_CURSO = $_SESSION["COD_CURSO"];
					mysql_data_seek($rsConC, 0);
					
					if ( !isset($_REQUEST["COD_CURSO"]) )
						$_REQUEST["COD_CURSO"] = "";
						
					if ( !isset($_REQUEST["TURMA" . $_REQUEST["COD_CURSO"]]) )
						$_REQUEST["TURMA".$_REQUEST["COD_CURSO"]] = "";
				
					if ( $rsConC )
						while ( $linhaC = mysql_fetch_array($rsConC) )
						{
							$_SESSION["COD_CURSO"] = $linhaC["COD_CURSO"];
							$rsConT = listaDisciplina("ADM");
				
							echo "<div id=\"cur". $linhaC["COD_CURSO"] ."\"  style=\"z-index: 1; overflow: visible; visibility: ";
							
							if ( $_REQUEST["CURSO"] == strval($linhaC["COD_CURSO"]) )
								echo "visible";
							else
								echo "hidden";
							echo ";\">";
							
							if ( $rsConT ) // listas as disciplinas e turmas
							{
								echo "<select name=\"TURMA". $linhaC["COD_CURSO"] ."\" onChange=\"cursochange();\">".
									 "<option value=\"\"> -- Escolha uma turma -- </option>\n"; 							
								while ( $linhaT = mysql_fetch_array($rsConT) )
								{
									echo "<option value=" . $linhaT["COD_TURMA"];
									if ( $_REQUEST["TURMA" . $_REQUEST["COD_CURSO"]] == strval($linhaT["COD_TURMA"]) )
										echo (" selected");
									echo ">" . $linhaT["DESC_DIS"] . " - Turma: " . strtoupper($linhaT["NOME_TURMA"]) . "</option>";
								 }
								echo "</select>";
							 }
							else
								echo "Nenhuma disciplina disponivel para o curso";
					
							echo "</div>";
						 }
					$_SESSION["COD_CURSO"] = $COD_CURSO;
					?>
            <script>
					function cursochange()
					{
						var i;
						for(i=0; i<consulta.curso.length; i++)
							eval('cur'+consulta.curso.options[i].value).style.visibility = 'hidden';
						eval('cur'+consulta.curso.options[consulta.curso.selectedIndex].value).style.visibility = 'visible';
					}
					</script>
            Pessoa: <br>
            <?php
	$rsConPes = listaPessoas();
	
	while ( $linhaPes = mysql_fetch_array($rsConPes) )
	{
//		echo "<input type=\"checkbox\" name=".$linhaPes["NOME_PESSOA"]." value=".$linhaPes["COD_PESSOA"].">" . $linhaPes["NOME_PESSOA"] . "<br>";
		echo "<input type=\"checkbox\" name=\"ALUNO\" value=\"".$linhaPes["COD_PESSOA"]."\" onClick=\" if(this.checked){adiciona(this.value)} else {remove(this.value)} \">" . $linhaPes["NOME_PESSOA"] . "<br>";
	 }
	?>
            <br>
            <br>
            <input type="submit" name="submit2" class="input3" value="Criar Aluno">
          </p>
          </form>
	<?php
	?>
      </td>
    </tr>
  </table>
  <?php



  ?>
</center>
</body>
</html>

