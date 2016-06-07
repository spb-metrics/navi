<?php
echo        "<b>COM</b> v&iacute;nculo UFRGS: <input type=\"radio\"  name=\"vinculoUfrgs\" value=\"1\" id=\"vinculoUfrgsSim\" onclick=\"esconde();\" checked><br><b>SEM</b> v&iacute;nculo UFRGS: <input type=\"radio\"  name=\"vinculoUfrgs\" id=\"vinculoUfrgsNao\" value=\"0\" onclick=\"mostra();\" ><br><br>";	
echo        "Nome da Pessoa:  <br><input type=\"text\"      style=\"width: 200px; \" name=\"nomePessoa\"><br>";
echo        "<div id='userPessoa' style='display:none'>Usu&aacute;rio:         <br><input type=\"text\"      style=\"width: 200px; \" name=\"usuarioPessoa\" maxlength='8' onKeyUp=\"confereUserBasico(event);\"><br></div>"; 	
echo        "<div id='cartaoUfrgs' style='display:block'>Cart&atilde;o UFRGS:         <br><input type=\"text\"      style=\"width: 200px; \" name=\"cartaoUfrgs\" maxlength='8' onKeyUp=\"confereNumeros(event);\"><br></div>"; 		
echo        "<div id='senhaPessoa' style='display:none'>Senha:           <br><input type=\"password\"  style=\"width: 200px; \" name=\"senhaPessoa\"><br></div>";
echo        "<div id='senhaPessoa2' style='display:none'>Confirme senha:  <br><input type=\"password\"  style=\"width: 200px; \" name=\"senhaPessoa2\"><br></div>";
echo        "Email:           <br><input type=\"text\"      style=\"width: 200px; \" name=\"emailPessoa\"><br>";	
?>

<script type="text/javascript">
function mostra()
  {
    //seta valor 0 para vinculoUfrgs
    document.form.vinculoUfrgs.value = 0;
    document.form.vinculoUfrgsNao.value = 0;
    //document.getElementById("vinculoUfrgsNao").value = 0; 
    //exibe campo senhaPessoa no form
    document.getElementById("senhaPessoa").style.display = "block";
    //exibe campo senhaPessoa2 (confirmação de senha) no form
    document.getElementById("senhaPessoa2").style.display = "block";
    //exibe campo userPessoa no form
    document.getElementById("userPessoa").style.display = "block";
    //oculta campo cartaoUfrgs no form
    document.getElementById("cartaoUfrgs").style.display = "none";
    //seta campo cartaoUfrgs sem valor
    document.form.cartaoUfrgs.value = ""; 
  }

  function esconde()
  {
    //seta valor 1 para vinculoUfrgs
    document.form.vinculoUfrgs.value = 1;
    document.form.vinculoUfrgsSim.value = 1;
    //document.getElementById("vinculoUfrgsSim").value = 1; 
    //oculta campo senhaPessoa no form
    document.getElementById("senhaPessoa").style.display = "none";
    //oculta campo senhaPessoa2 (confirmação de senha) no form
    document.getElementById("senhaPessoa2").style.display = "none";
    //oculta campo userPessoa no form
    document.getElementById("userPessoa").style.display = "none";
    //exibe campo cartaoUfrgs no form
    document.getElementById("cartaoUfrgs").style.display = "block";
    //seta senhaPessoa sem valor
    document.form.senhaPessoa.value = ""; 
    //seta senhaPessoa2 sem valor
    document.form.senhaPessoa2.value = ""; 
    //seta userPessoa sem valor
    document.form.userPessoa.value = ""; 
  }
  function confereNumeros(valorTeclado) 
  {
		var tecla = valorTeclado.keyCode;
		vr = document.form.cartaoUfrgs.value;
		for(i=0; i<vr.length; i++)
		if (vr.charCodeAt(i)<48 || vr.charCodeAt(i)>57)
    {
			vr = vr.replace(vr.charAt(i),"")
		}
		document.form.cartaoUfrgs.value = vr;
	}		
	function confereUserBasico(teclapres) 
  {
		var tecla = teclapres.keyCode;
		vr = document.form.usuarioPessoa.value;
		vr = vr.toLowerCase();
		for(i=0; i<vr.length; i++) {
  		if ( (vr.charCodeAt(i)>122 || vr.charCodeAt(i)<97) && (vr.charCodeAt(i)<48 || vr.charCodeAt(i)>57) )   {
  			vr = vr.replace(vr.charAt(i),"")
  		}
  	}
		document.form.usuarioPessoa.value = vr;
	}
</script>