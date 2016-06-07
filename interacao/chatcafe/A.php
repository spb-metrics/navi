<html>
<head>
	<link rel="stylesheet" href="././chat.css" type="text/css">

	<script>
		function mover_texto() {
		    if (document.form1.scrool_text.checked) {
		        window.parent.frames[1].scrollBy(0,10000);
		    }
		}

		function submit_exit() {
		    parent.frames[2].document.form1.MESSAGE.value = "/quit";
		    parent.frames[2].document.form1.submit();
		}
	</script>
	
</head>

<body TEXT="#FFFFFF" leftmargin="5" topmargin="5">
	<center>
		<BR>
		<form name='form1' method='post'>
    <table width="100%">
			<tr>
				<td align="center">
			<!--<input type='button' name='reload' value='Atualizar Nomes' onClick="javascript:parent.frames[2].location.href='C.php';">
							<!--&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; -->
			</td>
	<!--		<td align="center">
      <input type='checkbox' name='scrool_text' value='yes' checked> <font face="Verdana, Arial, Helvetica, sans-serif" size="1">Rolagem Automatica</font> 
							<!--&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
			</td>-->
			<td align="center">
     <!-- <input type='button' name='exit' value=' Sair '	onClick="javascript:submit_exit()">-->
      </td>
      </tr>
      </table>
		</form>
    </center>
  </body>

</html>