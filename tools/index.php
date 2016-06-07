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

//include_once("../funcoes_bd.php");

include_once("../config.php");
include_once($caminhoBiblioteca."/menu.inc.php");
include_once($caminhoBiblioteca."/pessoa.inc.php");
include_once($caminhoBiblioteca."/imagens.inc.php");
session_name(SESSION_NAME); session_start(); security();


$nivel = getNivelAtual();
?>

<html>
<head>
	<title>Ferramentas de Ger�ncia</title>
	<link rel="stylesheet" href="./../cursos.css" type="text/css">
	<link rel="stylesheet" href="<? echo $urlCss;?>/celula.css" type="text/css">
</head>



<body class='bodybg'>
<br>
<div align=center> <font size=4> <b> Ferramentas de Ger�ncia </b> </font> </div>

<?php
	$acesso = $_SESSION["NIVEL_ACESSO_FUTURO"];

	if ( ($acesso != 1) AND ($acesso != 2) AND ($acesso != 3) AND ($acesso != 4) )
	{
		echo "<p align='center'>Acesso Restrito. <BR> <BR> <a href='./../principal.php' target='_parent'>P�gina Principal</a></p>";		exit();
	 }
?>
	<br><br>


	<table  align="center" width="20%">

	
	<tr> <td colspan="3">  </td> </tr>	

	<tr> <td align="center"> Editar </td>
       <td align="center"> Criar </td></tr>

  <tr> </tr>     
	
<?php
	/*if ( $_SESSION["userRole"] == ADM_NIVEL || $_SESSION["userRole"] == ADMINISTRADOR_GERAL || ($_SESSION["userRole"] == PROFESSOR && $nivel->relacionaAlunosProfessores) )
	{*/

	if(pessoa::podeAdministrar($_SESSION['userRole'],$nivel,$_SESSION['interage'])){

		$ItensMenusAtivos=getItensMenuAtivos($_SESSION['codInstanciaGlobal']);
			while ($linha = mysql_fetch_array($ItensMenusAtivos))
				{
				if(!empty($linha['urlToolsEditar'])||!empty($linha['urlToolsCriar']))
					{
					 if($linha['nomeMenu']=='Apresenta��o'||$linha['nomeMenu']=='Apresenta&ccedil;&#259;o'){
					   $nomeMenu="Lembretes";
           }else{
              $nomeMenu=$linha['nomeMenu'];
           }
						echo"<tr>". 

								"<td align=\"center\"><a href='".$linha['urlToolsEditar']."'> <img src='".edita."' border=\"no\"><br></a></td>". 
								"<td align=\"center\"><a href='".$linha['urlToolsCriar']."'><img src='".criar."' border=\"no\"><br></a></td>". 
								"<td class=\"letra\">".$nomeMenu."</td>".

								"</tr>";

				}
			}

	}else{if($_SESSION["userRole"]!=ALUNO)msg('Desculpe mas voc� n�o est� autorizado a gerenciar esta inst�ncia.');}
?>
		<tr> <td colspan='3'> </td> </tr>		

		<?php				
		if ($acesso == 1 or $acesso == 2 or $acesso == 3)
		{
		?>
		<!--<tr> 
			<td width="50" align="center"> <a href='aluno.php'> Editar </a> </td> 
			<td width="50" align="center"> <a href='aluno_operacao.php'> Criar </a> </td> 
			<td> Alunos </td> 
		</tr>-->
		<?php
		 }
		
		if ($acesso == 1 or $acesso == 2)
		{
		?>
		<!--<tr> 
			<td width="50" align="center"> <a href=''> Editar </a> </td> 
			<td width="50" align="center"> <a href=''> Criar </a> </td> 
			<td> Professores </td>
		</tr>	 -->
		<?php
		 }
		
		if ($acesso == 1)
		{
		?>			
		<!--<tr> 
			<td width="50" align="center"> <a href=''> Editar </a> </td> 
			<td width="50" align="center"> <a href=''> Criar </a> </td> 
			<td> Administradores </td>
		</tr>-->
     
		<?php
		}
		if($acesso == 1 or $acesso == 2 or $acesso == 3)
		{
		?>			
		<!--<tr> 
			<td width="50" align="center"> <a href='atualiza_pessoa.php'> Editar </a> </td> 
			<td width="50" align="center"> <a href='pessoas.php'> Criar </a> </td> 
			<td> Pessoas</td>
		</tr> -->
		
		<?php
		 }
		?>
	 <tr> <td colspan='3'></td> </tr>

		<?php
		if ($_SESSION["userRole"]== ALUNO || $_SESSION["userRole"] == ADMINISTRADOR_GERAL) {
		  if (pessoa::podeAdministrar($_SESSION['userRole'],$nivel,$_SESSION['interage'])) {
		?>
		<tr> 

			<td align="center"><a href='portifolio.php'> <img src="<? echo edita?>" border="no"><br>  </a> </td> 
			<td align="center"><a href='portifolio_operacao.php?OPCAO=Inserir'><img src="<? echo criar?>" border="no"><br></a> </td> 
			<td class="letra"> Portfolio </td> 

		</tr>
    <!--
		<tr>
			<td width="50" align="center"> <a href='comunidade.php'>Editar</a></td>
			<td width="50" align="center"> <a href='comunidade.php?acao=A_inclui'>Criar</a></td>
			<td>Comunidade</td>
    </tr>
    -->
    
		<?php
		  }
		  /*
		  EM PRINCIPIO NAO � MAIS NECESS�RIO, POIS OS N�VEIS QUE NAO RELACIONAM TAMBEM 
		  PODEM RECEBER PORTF�LIO
		  else {
			  $nivelRelacionamento = Nivel::getNivelRelacionamentoAlunosProfessores();
		    echo "<tr><td colspan='3'>".
             "Entre em ".$nivelRelacionamento->nome. " para publicar seus portf�lios.".
             "</td></tr>";
		  }
		  */
		}
		?>			
</table>


<?php 
//    Se nivel_acesso=1 then 1, 2, 3, 4, 5, 6, 7, 9		acesso = 1
//    senao
//        Fazer consulta
//            Se � ADM_Curso 2, 3, 4, 5, 7, 9				acesso = 2
//            senao
//                Se Prof 3, 4, 7, 9							acesso = 3
//				  sen�o
//					 Se aluno 10									acesso=4	 

//1. Criar Editar Cursos
//2. Criar Editar Disciplinas / Turmas
//3. Criar Editar Noticias
//   Criar Editar Video-Aula
//   Criar Editar Textos de Apoio
//   Criar Editar Acervo
//   Criar Editar Enquete
//   Criar Editar Exercicios on-line
//   Criar Editar Avalia��o
//4. Criar Editar Alunos - Relacionar com alguma turma
//5. Criar Editar Professores - Relacionar com alguma turma
//6. Criar Editar Administrador - Relacionar com algum curso
//7. Criar Pessoa
//9. Criar Editar Menu
//10.Criar Editar Portifolio - Para alunos colocar seus Arquivos
 ?> 


</body>
</html>                                                
