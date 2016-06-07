<?php
session_name('multinavi'); session_start();

//include_once("../funcoes_bd.php");
include_once("../config.php");
include_once($caminhoBiblioteca."/avaliacao.inc.php");

?>
<html>
	<head>
		<title>Avalia&ccedil;&atilde;o</title>	
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>
<body class="bodybg">

<%

	if ( ( $_SESSION["COD_TURMA"] == "" ) OR ( $_REQUEST["COD_ARQUIVO"] == "" ) )
		exit();

//	Dim rsCon
//	Dim perguntaAnt
	$perguntaAnt = "0";
//	Dim mesmaPergunta
	$mesmaPergunta = false;
		
	$rsCon = constroiAval($_REQUEST["COD_AVALIACAO"]);
	
	echo "<form name='avaliacao_01' method='post' action='./corrige.asp'>\n";
	echo "<table width='750' align='center' >\n";
	if ( $rsCon )
	{
		if ( $linha = mysql_fetcjh_array($rsCon) )
		{			
			echo "<tr>". "\n";
			echo "<td width='600' height='50' align='center' colspan='2'>" . "\n";
			echo "<b>" & rsCon("TEXTO_AVALIACAO") . "</b>". "\n";
			echo "</td>". "\n";
			echo "</tr>". "\n";
			
			$perguntaAnt = $linha["COD_PERGUNTA"];
			
			while ( $linha )
			{
				
				echo "<tr>". "\n";
				echo "<td height=20 align=left valign=botton colspan=2 >". "\n";
				echo rsCon("TEXTO_PERGUNTA"). "\n";
				echo "</td>". "\n";
				echo "</tr>". "\n";	
					
				$mesmaPergunta = true;
				while ( $mesmaPergunta )
				{
					if ( $perguntaAnt == $linha["COD_PERGUNTA"] )
					{
						echo "<tr>". "\n";
						echo "<td widht='650' align='left'>". "\n";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type='checkbox' name='resposta' value='" & rsCon("COD_RESPOSTA")& "'> &nbsp;" & rsCon("TEXTO_RESPOSTA")& " ". "\n";
						echo "</td>". "\n";
						echo "</tr>". "\n";
						rsCon.moveNext
						if (rsCon.EOF) then
							mesmaPergunta = false
						end if
					 }
					else
						mesmaPergunta = false
					end if						
				 }
				response.write "<tr> <td height='30' colspan='2'> &nbsp;" & VBCRLF
				response.write "</td></tr>" & VBCRLF 
				if (not rsCon.EOF) then				
					perguntaAnt = rsCon("COD_PERGUNTA")
				end if
				
				$linha = mysql_fetcjh_array($rsCon);				
			 }
		response.write "<tr>"		
		response.write "<td align='center' colspan='2'>"
		response.write "<p> &nbsp; <input type='submit' value='Corrigir' > &nbsp;</p>"
		response.write "<p> &nbsp; <input type='reset' value='Limpar Tudo'> &nbsp; </p>"
		response.write "</td>"
		response.write "</tr>"			
		response.write "</table>"				
		response.write "</form>"
		
		rsCon.Close
		set rsCon = Nothing
		 }
		else
			response.write "Problemas na consulta. Contate a Equipe do NAVI"
				
	 }	
%>

</body>
</html>
