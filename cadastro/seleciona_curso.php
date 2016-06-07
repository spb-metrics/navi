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


//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/autenticacao.inc.php");
session_name(SESSION_NAME); session_start(); security();
//include_once("tabela_topo.php");


if ($_SESSION["COD_PESSOA"] != "")
	{
	$msg = "Atenção: para você criar um novo usuário, você deve sair do sistema. Para sair, clique em \"Sair\", no topo da página.";
	echo "<script> alert('"&msg&"');history.back();</script>";
    exit();  
	}
?>

<html>
	<head>
		<title>Cadastro</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<link rel="stylesheet" href="./sca.css" type="text/css">
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
		
			<script language="JavaScript">
				function validaForm()
					{
				var msg = "";
				var obj = "";
				var preen = true;

				// Valida curso de interesse - text
				
	  			<?php										
				$nomeCurso = listaAcesso(1, "", "", "");
	
				if ($linhacurso= mysql_fetch_array($nomeCurso )) 
					echo"if ( ! ( (document.f1.curso". $linhacurso["COD_CURSO"] . ".checked)";
										
		
					
				while ($linhacurso)
				{
					echo " || (document.f1.curso" . $linhacurso["COD_CURSO"] . ".checked)";
					$linhacurso= mysql_fetch_array($nomeCurso );
				}
					
			
				
				echo " ) ) ";								
				?>								
				{
					msg += "=> Curso de interesse não preenchido;\n";
					preen = false;
				 }
				 
				 }
				 
				 	function enviaForm()
			{
				if(validaForm())
				{
					document.f1.action = "./frm_insere_cadastro.php";
					document.f1.submit();
				}
			}

		</script>
	</head>
<form name="f1" method="post" action="">
	<table border="0" cellspacing="0" cellpadding="0" align="center" width="668">
	 	<tr> 
    		<td height="15" colspan="3"><b>Solicito a minha inscrição nos seguintes cursos:</b></td>      
	    </tr>
		
						<tr> 
	    	
     <td height="15" colspan="3"> <br>
		  			<?php					
					
					 $rsConCad = listaAcesso(1, "", "", "");

					if ($rsConCad )
					{
					   if ($linhaCad = mysql_fetch_array($rsConCad))
						{

							while ($linhaCad )
							{
								
									echo"<input type=\"checkbox\" name=\"curso" . $linhaCad["COD_CURSO"] . "\">&nbsp;" . $linhaCad["DESC_CURSO_ORIGEM"] . " - " . $linhaCad ["ABREV_CURSO"] . " - " . $linhaCad["DESC_CURSO"] . "<br>" . "\n";
									// onClick=\"selecionaCurso()\";
								
														
								$linhaCad = mysql_fetch_array($rsConCad);
							}																										
					    }					
					}
					?>
					

					
				<br>
				<br>


          <input type="button" name="OK" value="OK" class="input3" onclick="javascript:enviaForm()">
</table>

</form>
</body>
</html>
	

					

	
	
