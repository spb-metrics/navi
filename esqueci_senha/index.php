<html>
	<head>
		<title>Esqueci Minha Senha</title>
		<link rel="stylesheet" href="./../cursos.css" type="text/css">
	 </head>
<body bgcolor="#FFFFFF" text="#000000" class="bodybg">

<? 
if (file_exists ('msg.html')) {  //mensagem propria da instalação
 echo file_get_contents('msg.html');
} 
else { //mostra uma mensagem 
?>

  <table width="81%" border="0" valign="center" height="100%" >
    <tr> 
	    	<td align="center" >  
			<?  			
				if(!isset($_REQUEST["MODULO"]))
					$_REQUEST["MODULO"]="";
			
				$modulo=$_REQUEST["MODULO"];
				if($modulo=="")
				{
					include "r_dados.php";
				}
				else{if($modulo=="SENHA_NOVA")
						{	
							include "senha_nova.php";
							if($modulo=="SAIR")
								{
									?><input type="reset" value="Sair" onclick="location.href='../index.php'"><?
								}
								else
								include "r_dados.php";	
						}	
					}		
				
			?>
			</td>
		</tr>
	</table>
<?  
}  //FIM DO ELSE
?>

</body>
</html>
