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
include_once($caminhoBiblioteca."/autenticacao.inc.php");
session_name(SESSION_NAME); session_start(); security();
//include_once("tabela_topo.php");


if ($_SESSION["COD_PESSOA"] != "")
	{
	$msg = "Aten��o: para voc� criar um novo usu�rio, voc� deve sair do sistema. Para sair, clique em \"Sair\", no topo da p�gina.";
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
					msg += "=> Curso de interesse n�o preenchido;\n";
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
    		<td height="15" colspan="3"><b>Solicito a minha inscri��o nos seguintes cursos:</b></td>      
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
	

					

	
	
