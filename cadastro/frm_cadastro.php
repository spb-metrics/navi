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

include_once ("../config.php");
include_once ($caminhoBiblioteca."/cadastro.inc.php");
session_name(SESSION_NAME); session_start(); security();
// Por nao sobrescrever a variavel de sessao "pagina_atual" pode colocar um histpry.back()
 if ($_REQUEST["COD_PESSOA"] == "") {
	echo "Você não está logado no sistema. Esta página só pode ser acessada por um usuário logado.";
	exit();
}

$rsConC = pessoaMostrar($_REQUEST["COD_PESSOA"]);

if (! $rsConC)
{
	echo " Problemas no acesso ao banco de dados. ";
	exit();
 }

$linhaC = mysql_fetch_array($rsConC);

?>

<html>
	<head>
		<title>Gradua&ccedil;&atilde;o - Inscri&ccedil;&atilde;o</title>
		<link rel="stylesheet" href="./sca.css" type="text/css">
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	</head>

<body bgcolor="#FFFFFF" text="#000000" class="bodybg">



<h4 align="center">Informa&ccedil;&otilde;es cadastrais:</h4>

  <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td colspan="2" height="15"><b><font color="red">Usu&aacute;rio</font></b></td>
    </tr>
    <tr> 
      <td valign="top" height="28" colspan="2"> 
       <? echo $linhaC["USER_PESSOA"];?>
      </td>
    </tr>
	<tr> 
      <td height="15"><BR><b><font color="red">Nome Completo</font></b></td>
    </tr>
    <tr> 
      <td colspan="3" height="28"> 
       <? echo $linhaC["NOME_PESSOA"];?>
      </td>
    </tr>
    <tr>
	   <td width="200" ><b><font color="red">Sexo</font></b></td>
     </tr>
	 <tr> 
	   <td width="200" > 
			<?if ($linhaC["COD_SEXO"] == 1)
				{echo "Masculino";}
			 else 
				{echo "Feminino";}
			?>
			
	  </td>
   </tr>
   <tr> 
	  <td width="300" height="15"><b><font color="red">E-mail</font></b></td>
   </tr>
	  <tr>	
	  <td width="300" colspan="2" height="28"> 
        <? echo $linhaC["EMAIL_PESSOA"];?>
	</td>
			    
	
    <tr> 
      <td height="15" colspan="3"> 
        <b><font color="red">Endere&ccedil;os</font></b> <BR><BR>
            <?php
			$rsConE = enderecoMostrar($_REQUEST["COD_PESSOA"]);
			
			if ($rsConE)			
			while ($linhaEnd = mysql_fetch_array($rsConE))
			{
				echo "<table width='600' border='1' cellspacing='0' cellpadding='0'><tr><td width='450'>";
				echo $linhaEnd["DESC_TIPO_END"];
				echo ":<br>";
				
				$Usar_ = false;
				
				echo $linhaEnd["DESC_END"];
				if ($linhaEnd["DESC_END"] != "")
					$Usar_ = true;										
				if ($Usar_ = true AND $linhaEnd["BAIRRO_END"] != "")
					echo " - " ;												
				echo $linhaEnd["BAIRRO_END"] . "<br>";
				
				$Usar_ = false;
				
				echo $linhaEnd["CEP_END"];
				if ($linhaEnd["CEP_END"] != "")
					$Usar_ = true;
				if ($Usar_ = true AND $linhaEnd["CIDADE_END"] != "")
					echo " - ";
																
				echo $linhaEnd["CIDADE_END"];
				if ($linhaEnd["CIDADE_END"] != "")
					$Usar_ = true;
				if ($Usar_ = true AND $linhaEnd["UF_END"] != "")
					echo " - " ;
																			
				echo $linhaEnd["UF_END"];
				if ($linhaEnd["UF_END"] != "")
					$Usar_ = true;
				if ($Usar_ = true AND $linhaEnd["PAIS_END"] != "")
					echo " - " ;
											
				echo $linhaEnd["PAIS_END"];
				
				echo "</td>";
				
				echo "</td></tr></table>";
			 }
			?>
          <br> 
       
        <BR> <BR> <b><font color="red">Telefones</font></b>  <BR><BR>
          <?php
			
			$rsConT = telefoneMostrar($_REQUEST["COD_PESSOA"]);
			
		  	if ($rsConT)

			while ($linhaTel = mysql_fetch_array($rsConT))
			{
				echo "<table width='600' border='1' cellspacing='0' cellpadding='0'><tr><td width='450'>";
				echo $linhaTel["DESC_TIPO_FONE"];
				echo ": ";
				if ($linhaTel["COD_INTERNAC_FONE"] != "")
					echo $linhaTel["COD_INTERNAC_FONE"] . " - ";
				
				if ($linhaTel["COD_AREA_FONE"] != "")
					echo "(" . $linhaTel["COD_AREA_FONE"] . ")";

				echo $linhaTel["NRO_FONE"];
				if ($linhaTel["RAMAL_FONE"] != "")
					echo " - Ramal: " . $linhaTel["RAMAL_FONE"];

				echo "</td>";
				
				echo "</tr></table>";
			 }
		?>
          <br>
       
      </td>
    </tr>
    <tr> 
      <td height="15" colspan="2">&nbsp; </td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td height="15" colspan="2">&nbsp;</td>
      <td height="15">&nbsp;</td>
    </tr>
    <tr> 
      <td height="15" colspan="3"> 
        <div align="center">
		    	
			<a href="javascript:history.back()">voltar</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	        
        </div>
      </td>
    </tr>
  </table>
  <BR>
 
</body>
</html>
