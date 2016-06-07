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
include_once($caminhoBiblioteca."/arquivo.inc.php");
session_name(SESSION_NAME); session_start(); security();
?>
<html>
	<head>
		<title>Texto</title>	
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="../cursos.css" type="text/css">
	
    <script language="javascript">
      function encaminha(){
        parent.document.location.href="./../portifolio/index.php?COD_ARQUIVO=<?=$_REQUEST["COD_ARQUIVO"]?>&COD_AL=<?= $_REQUEST["COD_AL"]?>";
      }
    </script>
    
  </head>
  <!--onLoad="setInterval('encaminha()',2000)"-->
<body >


  <?php
  if ( !isset($_REQUEST["COD_ARQUIVO"]) )
  	$_REQUEST["COD_ARQUIVO"] = "";
  
	if ( ( $_SESSION["COD_PESSOA"] == "" ) OR ( $_SESSION["codInstanciaGlobal"] == "" ) OR ( $_REQUEST["COD_ARQUIVO"] == "" ) )
		exit();
	

	
		$rsCon = apoioCaminhoAluno($_REQUEST["COD_ARQUIVO"],$_REQUEST["COM"]);
	

		$mostrar = false;
		
		if ( $rsCon )
		{
			while ( ( $linha = mysql_fetch_array($rsCon) ) AND ( $mostrar == false) ){
				if ( $linha["COD_INSTANCIA_GLOBAL"] == $_SESSION["codInstanciaGlobal"] ){
					$mostrar = true;
					$descricao= $linha["DESC_ARQUIVO_INSTANCIA"];
						
					}
					if($_REQUEST["COM"]){
					$mostrar = true;
					
					$descricao=$linha["DESC_ARQUIVO"];
					}
			}
		 }
		
		if ( $mostrar == true )
		{	
			if ( isset($linha["CAMINHO_LOCAL_ARQUIVO"]) )
				if ( (!file_exists($caminhoUpload.$linha["CAMINHO_LOCAL_ARQUIVO"])) OR (!file_exists($caminhoUpload1.$linha["CAMINHO_LOCAL_ARQUIVO"])))
				{?>
					<table width="700" align="center" >
						<tr>
							<td align="center">
								<b>	Arquivo n�o Existe </b>
							</td>
							<td align="right">
							 <a class="menu" href="javascript:top.close();" >Fechar arquivo</a>

								<!--<a class="menu" href="javascript:parent.location.href='./index.php?COD_AL=<?= $_REQUEST["COD_AL"] ?>'">Voltar</a>-->
							</td>
						</tr>
					</table>
					<?	
									
					exit();
				 }
			?>
			<table width="700" align="center" >
				<tr>
					<td align="center">
						<b> <?echo $descricao;?> </b>
					</td>
					<td align="right">
					 <a class="menu" href="javascript:top.close();" >Fechar arquivo</a>

						<!--<a class="menu" href="javascript:parent.location.href='./index.php?COD_AL=<?= $_REQUEST["COD_AL"] ?>'">Voltar para Portf�lio</a>-->
						
					</td>
				</tr>
			</table>
			<?php
		 }
	?>
</body>
</html>
