<table width="100%">
	<tr>
		<td>
			<p align="center">
				<font color="red"> <b>
					<?
					if (gettype($mensagem) == "array") {
						foreach($mensagem as $msg)
							echo $msg . "<br>";
					} else {
						echo $mensagem;
					}
					?>
				</b> </font> <br><br>
			</p>
		</td>
	</tr>
</table>
