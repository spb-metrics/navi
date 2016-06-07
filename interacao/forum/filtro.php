<?php
session_name('multinavi');
session_start();
include_once ("./../../config.php");
include($caminhoBiblioteca."/pessoa.inc.php");
include_once ($caminhoBiblioteca."/forum.inc.php");
include_once ($caminhoBiblioteca."/linkimagem.inc.php");

?>
<html>
	<head>
		<title>F&Oacute;RUM</title>
		<style>
			@import url( <?=$url?>/css/dropdown.css );
		</style>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="<?=$url?>/cursos.css" type="text/css">
		<link rel="stylesheet" media="screen" href="<?=$url?>/css/dynCalendar.css" />
		<script language="JavaScript" type="text/javascript">
		<?php geraArrayLocalPessoasForm($_SESSION["codInstanciaGlobal"]);?>
		 </script>
		<script language="javascript" type="text/javascript" src="<?=$url?>/js/browserSniffer.js"></script>
		<script language="javascript" type="text/javascript" src="<?=$url?>/js/dynCalendar.js"></script>
		<script src="<?=$url?>/js/busca/mobrowser.js"></script>
		<script src="<?=$url?>/js/busca/modomevent3.js"></script>
		<script src="<?=$url?>/js/busca/modomt.js"></script>
		<script src="<?=$url?>/js/busca/modomext.js"></script>
		<script src="<?=$url?>/js/busca/tabs2.js"></script>
		<script src="<?=$url?>/js/busca/getobject2.js"></script>
		<script src="<?=$url?>/js/busca/xmlextras.js"></script>
		<script src="<?=$url?>/js/busca/acdropdown.js"></script>
		<script language="javascript" src="<?=$url?>/js/busca/shCore.js" ></script>
		<script language="javascript" src="<?=$url?>/js/busca/shBrushXML.js" ></script>
		<script language="JavaScript" src="<?=$url?>/js/dateFormat.js"></script>

		<script language="JavaScript" type="text/javascript">
			function formatHidden(dateI,dateF){
				if(dateI.length >0 && dateF.length >0){
				document.forms['calendario'].dataInicio.value =dateI.charAt(6)+dateI.charAt(7)+dateI.charAt(8)+dateI.charAt(9)+"-"+dateI.charAt(3)+dateI.charAt(4)+"-"+dateI.charAt(0)+dateI.charAt(1);
				document.forms['calendario'].dataFim.value =dateF.charAt(6)+dateF.charAt(7)+dateF.charAt(8)+dateF.charAt(9)+"-"+dateF.charAt(3)+dateF.charAt(4)+"-"+dateF.charAt(0)+dateF.charAt(1);
				}
			}
		</script>
	    </head>

<body>

 
    <?php
          $obj = new Voltar("forum.php?COD_SALA=".$_REQUEST["COD_SALA"],"Voltar");
          echo $obj->imprime();
		$nomeTopico= getNomeTopico($_REQUEST["COD_SALA"]);  
		 echo "<p align=\"center\"><b>".$nomeTopico."</b></p>";

	?>


<form name="calendario" action="./forum.php?acao=filtro" method='GET'>
<fieldset style="background-color: #F4F4F4;"> 
 <legend> <b>Filtrar Por:</b></legend>
<table width="100%" align="center" border="0">
	<table valign="top" align="center"  width="75%"  border="0">
	<tr>
	 <td align="right"><strong>Data Inicio:</strong></td>
	 <td>
		<input type="text"  maxlength="10"  name="dataInicioValue" onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" value="" style="width: 250px;">
		<input type="hidden" name="dataInicio" value="<?=$_REQUEST["dataInicio"]?>">
		<script language="JavaScript" type="text/javascript">
		// <!--
		 /**
		 * Example callback function
		 */
		 function getDataInicio(date, month, year)
		 {
		   if (String(month).length == 1) {
			      month = '0' + month;
			   }
    
			 if (String(date).length == 1) {
			      date = '0' + date;
			 }    
			document.forms['calendario'].dataInicioValue.value = date + '/' + month + '/' +year ;
			 document.forms['calendario'].dataInicio.value = year + '-' + month + '-' +date ;
		}
		 calendar1 = new dynCalendar('calendar1', 'getDataInicio','../../imagens/');
		 calendar1.setMonthCombo(true);
		 calendar1.setYearCombo(true);
		 //-->
		 </script><br>

	</td></tr>
	<tr><td align="right"><strong>Data Fim:</strong></td>
	<td>
		<input type="text" name="dataFimValue" maxlength="10"  onFocus="javascript:vDateType='3'" onKeyUp="DateFormat(this,this.value,event,false,'3')" onBlur="DateFormat(this,this.value,event,true,'3')" style="width: 250px;" value="" >
		<input type="hidden" name="dataFim" value="">
		<script language="JavaScript" type="text/javascript">
		// <!--
		  /**
			  * Example callback function
		 */
		  function getDataFim(date, month, year)
		 {
		    if (String(month).length == 1) {
		         month = '0' + month;
		    }
    
		   if (String(date).length == 1) {
		       date = '0' + date;
		   }    
		  document.forms['calendario'].dataFimValue.value = date + '/' + month + '/' + year;
		  document.forms['calendario'].dataFim.value = year + '-' + month + '-' + date;

		 }
		 calendar2 = new dynCalendar('calendar2', 'getDataFim','../../imagens/');
		  calendar2.setMonthCombo(true);
		 calendar2.setYearCombo(true);
       //-->
		 </script>
	</td></tr>
	<tr><td align="right" width="30%"><strong>Em todo o Fórum:</strong></td>
	<td>
	<input type="checkbox"  name="total" value="1"  onClick="if(this.checked){document.forms['calendario'].topico.disabled=true;}else{document.forms['calendario'].topico.disabled=false;}" >
	</td></tr>
	<tr><td align="right"><strong>Neste Tópico: </strong></td>
	<td>
	<input type="checkbox"   name="topico"  value="<?=$_REQUEST["COD_SALA"]?>" onClick="if(this.checked){document.forms['calendario'].total.disabled=true;}else{document.forms['calendario'].total.disabled=false;}">
	</td></tr>
	<tr>
	<td align="right" valign="top"><strong>Pessoa: </strong></td>
	<td width="50%" valign="top">
	<input name="namePessoa"  autocomplete="off" id="resultName" style="width: 250px;" acdropdown="true" autocomplete_list="array:pessoas" autocomplete_list_sort="true" autocomplete_matchsubstring="true">
	 <input type="hidden" name="COD_PESSOA"  id="resultCod" value="">
 	 <input type="hidden" name="COD_SALA" value="<?=$_REQUEST["COD_SALA"]?>">
 	 <input type="hidden" name="paginaForum"   value="1">

	</td><td></td>
	</tr>
	<br><br><br>

	<tr><td colspan="3" align="center">
		 <input type="hidden" name="acao" value="filtro">
		<input type="button" style="background-color: #c5d3f8" onclick="formatHidden(document.forms['calendario'].dataInicioValue.value,document.forms['calendario'].dataFimValue.value);submit();" value="Buscar">
	</td></tr>
   </table>
</table>
</fieldset>

</form>
</body>
</html>

