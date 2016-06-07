<?php
include_once ("./../../config.php");
include_once($caminhoBiblioteca."/forum.inc.php");
include_once ($caminhoBiblioteca."/perfil.inc.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
          "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="<?=$url?>/js/ASCIIMathML.js"></script>
<link rel="stylesheet" href="<?=$url?>/css/asciimath.css" type="text/css">
<link rel="stylesheet" href="<?=$url?>/css/cursos.css" type="text/css"> 
</head>
<script language="javascript">
function verificaMathml(){
	var gif=document.getElementById('exeMathmlGif');
	var tabela= document.getElementById('exeMathmlTabela');
	
  if (navigator.appName.slice(0,8)=="Netscape") 
    if (navigator.appVersion.slice(0,1)>="5") {//retornar para ver visualizar tabela
		gif.style.display='none';
		tabela.style.display='inline';
	}
	else{//retornar para visualizar imagem
		gif.style.display='inline';
		tabela.style.display='none';
	} 
  else if (navigator.appName.slice(0,9)=="Microsoft")
    try {//retorna para visualizar tabela
        var ActiveX = new ActiveXObject("MathPlayer.Factory.1");
       	gif.style.display='none';
		tabela.style.display='inline';
		
	 } catch (e) {
       //retornar para visualizar imagem
		gif.style.display='inline';
		tabela.style.display='none';
		
    }
  else { //retornar para visualizar imagem
  gif.style.display='inline';
  tabela.style.display='none';
  }
 }
</script>
<body onload="verificaMathml()">
<h2 id="title" align="center"> Nota&ccedil;&atilde;o para visualiza&ccedil;&atilde;o 
  em Mathml</h2>
	     
				
      
<p><u><b>Usu&aacute;rios do Internet Explorer(IE):</b></u></p>
<p>Para visualiza&ccedil;&atilde;o correta das f&oacute;rmulas matem&aacute;ticas 
        &eacute; necess&aacute;rio baixar o pluggin MathPlayer.<br>
        Para isto v&aacute; em: <a target=\"_blanck\" href='http://www.dessci.com/en/products/mathplayer/welcome.asp'>http://www.dessci.com/en/products/mathplayer/welcome.asp</a><br>
        e clique em <b>Download MathPlayer Setup</b></p>
				
      
<p><u><b>Usu&aacute;rios firefox/mozilla:</b></u></p>
				
      
<p>Para Visualiza&ccedil;&atilde;o correta das f&oacute;rmulas matem&aacute;ticas 
  &eacute; necess&aacute;rio instalar fontes para o mathml.<br>
  Leia o quadro Fontes to Install em <a target=\"_blanck\" href='http://www.mozilla.org/projects/mathml/fonts/'>http://www.mozilla.org/projects/mathml/fonts/</a></p>

<div id="exeMathmlGif" style="display:inline;">
<p><font color="#000000"><b>Ap&oacute;s a aquisi&ccedil;&atilde;o do pluggin ou 
  das fontes voc&ecirc; visualizar&aacute; as f&oacute;rmulas como nos exemplos 
  abaixo:</b></font></p>

  <img src="<?=$urlImagem?>/exemploMath1.GIF"><br>
  <img src="<?=$urlImagem?>/exemploMath2.GIF">
</div>	
	
<div id="exeMathmlTabela" style="display:inline;">
<p><font color="#000000"><b>Exemplos de ASCIImathml abaixo:</b></font></p>

	<table id="examples" border="5" cellpadding="10">
<tr>
    <th>Sintaxe</th>
    <th>Visualiza&ccedil;&atilde;o</th>
</tr>
<tr>
<td>\`x^2+y_1+z_12^34\`</td>
<td>`x^2+y_1+z_12^34`</td>
</tr>
<tr>
<td>\`sin^-1(x)\`</td>
<td>`sin^-1(x)`</td>
</tr>
<tr>
<td>\`d/dxf(x)=lim_(h->0)(f(x+h)-f(x))/h\`</td>
<td>`d/dxf(x)=lim_(h->0)(f(x+h)-f(x))/h`</td>
</tr>
<tr>
<td>\$\frac{d}{dx}f(x)=\lim_{h\to 0}\frac{f(x+h)-f(x)}{h}\$</td>
<td>$\frac{d}{dx}f(x)=\lim_{h\to 0}\frac{f(x+h)-f(x)}{h}$</td>
</tr>
<tr>
<td>\`f(x)=sum_(n=0)^oo(f^((n))(a))/(n!)(x-a)^n\`</td>
<td>`f(x)=sum_(n=0)^oo(f^((n))(a))/(n!)(x-a)^n`</td>
</tr>
<tr>
<td>\$f(x)=\sum_{n=0}^\infty\frac{f^{(n)}(a)}{n!}(x-a)^n\$</td>
<td>$f(x)=\sum_{n=0}^\infty\frac{f^{(n)}(a)}{n!}(x-a)^n$</td>
</tr>
<tr>
<td>\`int_0^1f(x)dx\`</td>
<td>`int_0^1f(x)dx`</td>
</tr>
<tr>
<td>\`[[a,b],[c,d]]((n),(k))\`</td>
<td>`[[a,b],[c,d]]((n),(k))`</td>
</tr>
<tr>
<td>\`x/x={(1,if x!=0),(text{undefined},if x=0):}\`</td>
<td>`x/x={(1,if x!=0),(text{undefined},if x=0):}`</td>
</tr>
<tr>
<td>\`a//b\`</td>
<td>`a//b`</td>
</tr>
<tr>
<td>\`(a/b)/(c/d)\`</td>
<td>`(a/b)/(c/d)`</td>
</tr>
<tr>
<td>\`a/b/c/d\`</td>
<td>`a/b/c/d`</td>
</tr>
<tr>
<td>\`((a*b))/c\`</td>
<td>`((a*b))/c`</td>
</tr>
<tr>
<td>\`sqrtsqrtroot3x\`</td>
<td>`sqrtsqrtroot3x`</td>
</tr>
<tr>
<td>\`(:a,b:) and {:(x,y),(u,v):}\`</td>
<td>`(:a,b:) and {:(x,y),(u,v):}`</td>
</tr>
<tr>
<td>\`(a,b]={x in RR : a < x <= b}\`</td>
<td>`(a,b]={x in RR : a < x <= b}`</td>
</tr>
<tr>
<td>\`abc-123.45^-1.1\`</td>
<td>`abc-123.45^-1.1`</td>
</tr>
<tr>
<td>\`hat(ab) bar(xy) ulA vec v dotx ddot y\`</td>
<td>`hat(ab) bar(xy) ulA vec v dotx ddot y`</td>
</tr>
<tr>
<td>\`bb{AB3}.bbb(AB].cc(AB).fr{AB}.tt[AB].sf(AB)\`</td>
<td>`bb{AB3}.bbb(AB].cc(AB).fr{AB}.tt[AB].sf(AB)`</td>
</tr>
<tr>
<td>\`stackrel"def"= or \stackrel{\Delta}{=}" "("or ":=)\`</td>
<td>`stackrel"def"= or \stackrel{\Delta}{=}" "("or ":=)`</td>
</tr>
<tr>
<td>\`{::}_(\ 92)^238U\`</td>
<td>`{::}_(\ 92)^238U`</td>
</tr>
</table>

	

<h4> Mais exemplos:</h4>(&eacute; necess&aacute;rio pluggim mathplayer(IE) ou fontes para o mathml(mozilla/firefox) 
  para visualiza&ccedil;&atilde;o) <br>
  exemplo de matrizes <tt>\`{(S_(11),...,S_(1n)),(vdots,ddots,vdots),(S_(m1),...,S_(mn))]\`</tt> 
  visualiza&ccedil;&atilde;o-&gt; `{(S_(11),...,S_(1n)),(vdots,ddots,vdots),(S_(m1),...,S_(mn))]`. 
  <br/>
<p>
Letras Gregas:
alpha `alpha`
beta `beta`
chi `chi`
delta `delta`
Delta `Delta`
epsilon `epsilon`
varepsilon `varepsilon`
eta `eta`
gamma `gamma`
Gamma `Gamma`
iota `iota`
kappa `kappa`
lambda `lambda`
Lambda `Lambda`
mu `mu`
nu `nu`
omega `omega`
Omega `Omega`
phi `phi`
varphi `varphi`
Phi `Phi`
pi `pi`
Pi `Pi`
psi `psi`
Psi `Psi`
rho `rho`
sigma `sigma`
Sigma `Sigma`
tau `tau`
theta `theta`
vartheta `vartheta`
Theta `Theta`
upsilon `upsilon`
xi `xi`
Xi `Xi`
zeta `zeta`
</p>

<table border="5" cellpadding="10">
<tr valign="top"><td>
Operadores:
<table border="5" cellpadding="10">
<tr>
          <th>Sintaxe</th>
          <th>Visualiza&ccedil;&atilde;o</th>
        </tr>
<tr>
          <td>\`+\`</td>
          <td>`+`</td></tr>
<tr>
          <td>\`-\`</td>
          <td>`-`</td></tr>
<tr>
          <td>\`*\`</td>
          <td>`*`</td></tr>
<tr>
          <td>\`**\`</td>
          <td>`**`</td></tr>
<tr>
          <td>\`//\`</td>
          <td>`//`</td></tr>
<tr>
          <td>\`\\\`</td>
          <td>`\\ `</td></tr>
<tr>
          <td>\`xx\`</td>
          <td>`xx`</td></tr>
<tr>
          <td>\`-:\`</td>
          <td>`-:`</td></tr>
<tr>
          <td>\`@\`</td>
          <td>`@`</td></tr>
<tr>
          <td>\`o+\`</td>
          <td>`o+`</td></tr>
<tr>
          <td>\`ox\`</td>
          <td>`ox`</td></tr>
<tr>
          <td>\`o.\`</td>
          <td>`o.`</td></tr>
<tr>
          <td>\`sum\`</td>
          <td>`sum`</td></tr>
<tr>
          <td>\`prod\`</td>
          <td>`prod`</td></tr>
<tr>
          <td>\`^^\`</td>
          <td>`^^`</td></tr>
<tr>
          <td>\`^^^\`</td>
          <td>`^^^`</td></tr>
<tr>
          <td>\`vv\`</td>
          <td>`vv`</td></tr>
<tr>
          <td>\`vvv\`</td>
          <td>`vvv`</td></tr>
<tr>
          <td>\`nn\`</td>
          <td>`nn`</td></tr>
<tr>
          <td>\`nnn\`</td>
          <td>`nnn`</td></tr>
<tr>
          <td>\`uu\`</td>
          <td>`uu`</td></tr>
<tr>
          <td>\`uuu\`</td>
          <td>`uuu`</td></tr>
</table>
</td>
    <td> S&iacute;mbolos Relacionais 
      <table border="5" cellpadding="10">
<tr>
          <th>Sintaxe</th>
          <th>Visualiza&ccedil;&atilde;o</th>
        </tr>
<tr>
          <td>\`=\`</td>
          <td>`=`</td></tr>
<tr>
          <td>\`!=\`</td>
          <td>`!=`</td></tr>
<tr>
          <td>\`< \`</td>
          <td>`<`</td></tr>
<tr>
          <td>\`>\`</td>
          <td>`>`</td></tr>
<tr>
          <td>\`<=\`</td>
          <td>`<=`</td></tr>
<tr>
          <td>\`>=\`</td>
          <td>`>=`</td></tr>
<tr>
          <td>\`-<\`</td>
          <td>`-<`</td></tr>
<tr>
          <td>\`>-\`</td>
          <td>`>-`</td></tr>
<tr>
          <td>\`in\`</td>
          <td>`in`</td></tr>
<tr>
          <td>\`!in\`</td>
          <td>`notin`</td></tr>
<tr>
          <td>\`sub\`</td>
          <td>`sub`</td></tr>
<tr>
          <td>\`sup\`</td>
          <td>`sup`</td></tr>
<tr>
          <td>\`sube\`</td>
          <td>`sube`</td></tr>
<tr>
          <td>\`supe\`</td>
          <td>`supe`</td></tr>
<tr>
          <td>\`-=\`</td>
          <td>`-=`</td></tr>
<tr>
          <td>\`~=\`</td>
          <td>`~=`</td></tr>
<tr>
          <td>\`~~\`</td>
          <td>`~~`</td></tr>
<tr>
          <td>\`prop\`</td>
          <td>`prop`</td></tr>
</table>
</td>
    <td> S&iacute;mbolos L&oacute;gicos 
      <table border="5" cellpadding="10">
<tr>
          <th>Sintaxe</th>
          <th>Visualiza&ccedil;&atilde;o</th>
        </tr>
<tr>
          <td>\`and\`</td>
          <td>`and`</td></tr>
<tr>
          <td>\`or\`</td>
          <td>`or`</td></tr>
<tr>
          <td>\`not\`</td>
          <td>`not`</td></tr>
<tr>
          <td>\`=>\`</td>
          <td>`=>`</td></tr>
<tr>
          <td>\`if \`</td>
          <td>`if `</td>
        </tr>
<tr>
          <td>\`iff \`</td>
          <td>`iff `</td>
        </tr>
<tr>
          <td>\`AA\`</td>
          <td>`AA`</td></tr>
<tr>
          <td>\`EE\`</td>
          <td>`EE`</td></tr>
<tr>
          <td>\`_|_\`</td>
          <td>`_|_`</td></tr>
<tr>
          <td>\`TT\`</td>
          <td>`TT`</td></tr>
<tr>
          <td>\`|--\`</td>
          <td>`|--`</td></tr>
<tr>
          <td>\`|==\`</td>
          <td>`|==`</td></tr>
</table>
<p>
Grupos de delimitadores
<table border="5" cellpadding="10">
<tr>
          <th>Sintaxe</th>
          <th>Visualiza&ccedil;&atilde;o</th>
        </tr>
<tr>
          <td>\`(\`</td>
          <td>`(`</td></tr>
<tr>
          <td>\`)\`</td>
          <td>`)`</td></tr>
<tr>
          <td>\`[\`</td>
          <td>`[`</td></tr>
<tr>
          <td>\`]\`</td>
          <td>`]`</td></tr>
<tr>
          <td>\`{\`</td>
          <td>`{`</td></tr>
<tr>
          <td>\`}\`</td>
          <td>`}`</td></tr>
<tr>
          <td>\`(:\`</td>
          <td>`(:`</td></tr>
<tr>
          <td>\`:)\`</td>
          <td>`:)`</td></tr>
<tr>
          <td>\`{:\`</td>
          <td>`{:`</td></tr>
<tr>
          <td>\`:}\`</td>
          <td>`{::}`</td></tr>
</table>

</td>
    <td> v&aacute;rios Tipos 
      <table border="5" cellpadding="10">
<tr>
          <th>Sintaxe</th>
          <th>Visualiza&ccedil;&atilde;o</th>
        </tr>
<tr>
          <td>\`int\`</td>
          <td>`int`</td></tr>
<tr>
          <td>\`oint\`</td>
          <td>`oint`</td></tr>
<tr>
          <td>\`del\`</td>
          <td>`del`</td></tr>
<tr>
          <td>\`grad\`</td>
          <td>`grad`</td></tr>
<tr>
          <td>\`+-\`</td>
          <td>`+-`</td></tr>
<tr>
          <td>\`O/\`</td>
          <td>`O/`</td></tr>
<tr>
          <td>\`oo\`</td>
          <td>`oo`</td></tr>
<tr>
          <td>\`aleph\`</td>
          <td>`aleph`</td></tr>
<tr>
          <td>\`/_\`</td>
          <td>`/_`</td></tr>
<tr>
          <td>\`:.\`</td>
          <td>`:.`</td></tr>
<tr>
          <td>\`|...|\`</td>
          <td>|`...`|</td></tr>
<tr>
          <td>\`|cdots|\`</td>
          <td>|`cdots`|</td></tr>
<tr>
          <td>\`vdots\`</td>
          <td>`vdots`</td></tr>
<tr>
          <td>\`ddots\`</td>
          <td>`ddots`</td></tr>
<tr>
          <td>\`|\ |\`</td>
          <td>|`\ `|</td></tr>
<tr>
          <td>\`|quad|\`</td>
          <td>|`quad`|</td></tr>
<tr>
          <td>\`diamond\`</td>
          <td>`diamond`</td></tr>
<tr>
          <td>\`square\`</td>
          <td>`square`</td></tr>
<tr>
          <td>\`|__\`</td>
          <td>`|__`</td></tr>
<tr>
          <td>\`__|\`</td>
          <td>`__|`</td></tr>
<tr>
          <td>\`|~\`</td>
          <td>`|~`</td></tr>
<tr>
          <td>\`~|\`</td>
          <td>`~|`</td></tr>
<tr>
          <td>\`CC\`</td>
          <td>`CC`</td></tr>
<tr>
          <td>\`NN\`</td>
          <td>`NN`</td></tr>
<tr>
          <td>\`QQ\`</td>
          <td>`QQ`</td></tr>
<tr>
          <td>\`RR\`</td>
          <td>`RR`</td></tr>
<tr>
          <td>\`ZZ\`</td>
          <td>`ZZ`</td></tr>
</table>
</td>
    <td> Fun&ccedil;&otilde;es Tradicionais 
      <table border="5" cellpadding="10">
<tr>
          <th>Sintaxe</th>
          <th>Visualiza&ccedil;&atilde;o</th>
        </tr>
<tr>
          <td>\`sin\`</td>
          <td>`sin`</td></tr>
<tr>
          <td>\`cos\`</td>
          <td>`cos`</td></tr>
<tr>
          <td>\`tan\`</td>
          <td>`tan`</td></tr>
<tr>
          <td>\`csc\`</td>
          <td>`csc`</td></tr>
<tr>
          <td>\`sec\`</td>
          <td>`sec`</td></tr>
<tr>
          <td>\`cot\`</td>
          <td>`cot`</td></tr>
<tr>
          <td>\`sinh\`</td>
          <td>`sinh`</td></tr>
<tr>
          <td>\`cosh\`</td>
          <td>`cosh`</td></tr>
<tr>
          <td>\`tanh\`</td>
          <td>`tanh`</td></tr>
<tr>
          <td>\`log\`</td>
          <td>`log`</td></tr>
<tr>
          <td>\`ln\`</td>
          <td>`ln`</td></tr>
<tr>
          <td>\`det\`</td>
          <td>`det`</td></tr>
<tr>
          <td>\`dim\`</td>
          <td>`dim`</td></tr>
<tr>
          <td>\`lim\`</td>
          <td>`lim`</td></tr>
<tr>
          <td>\`mod\`</td>
          <td>`mod`</td></tr>
<tr>
          <td>\`gcd\`</td>
          <td>`gcd`</td></tr>
<tr>
          <td>\`lcm\`</td>
          <td>`lcm`</td></tr>
<tr>
          <td>\`min\`</td>
          <td>`min`</td></tr>
<tr>
          <td>\`max\`</td>
          <td>`max`</td></tr>
</table>
<p>
Acentos
<table border="5" cellpadding="10">
<tr>
          <th>Sintaxe</th>
          <th>Visualiza&ccedil;&atilde;o</th>
        </tr>
<tr>
          <td>\`hat x\`</td>
          <td>`hat x`</td></tr>
<tr>
          <td>\`bar x\`</td>
          <td>`bar x`</td></tr>
<tr>
          <td>\`ul x\`</td>
          <td>`ul x`</td></tr>
<tr>
          <td>\`vec x\`</td>
          <td>`vec x`</td></tr>
<tr>
          <td>\`dot x\`</td>
          <td>`dot x`</td></tr>
<tr>
          <td>\`ddot x\`</td>
          <td>`ddot x`</td></tr>
</table>

</td><td>
Setas
<table border="5" cellpadding="10">
<tr>
          <th>Sintaxe</th>
          <th>Visualiza&ccedil;&atilde;o</th>
        </tr>
<tr>
          <td>\`uarr\`</td>
          <td>`uarr`</td></tr>
<tr>
          <td>\`darr\`</td>
          <td>`darr`</td></tr>
<tr>
          <td>\`rarr\`</td>
          <td>`rarr`</td></tr>
<tr>
          <td>\`->\`</td>
          <td>`->`</td></tr>
<tr>
          <td>\`|->\`</td>
          <td>`|->`</td></tr>
<tr>
          <td>\`larr\`</td>
          <td>`larr`</td></tr>
<tr>
          <td>\`harr\`</td>
          <td>`harr`</td></tr>
<tr>
          <td>\`rArr\`</td>
          <td>`rArr`</td></tr>
<tr>
          <td>\`lArr\`</td>
          <td>`lArr`</td></tr>
<tr>
          <td>\`hArr\`</td>
          <td>`hArr`</td></tr>
</table>
<p>
Fonte
<table border="5" cellpadding="10">
<tr>
          <th>Sintaxe</th>
          <th>Visualiza&ccedil;&atilde;o</th>
        </tr>
<tr>
          <td>\`bb A\`</td>
          <td>`bb A`</td></tr>
<tr>
          <td>\`bbb A\`</td>
          <td>`bbb A`</td></tr>
<tr>
          <td>\`cc A\`</td>
          <td>`cc A`</td></tr>
<tr>
          <td>\`tt A\`</td>
          <td>`tt A`</td></tr>
<tr>
          <td>\`fr A\`</td>
          <td>`fr A`</td></tr>
<tr>
          <td>\`sf A\`</td>
          <td>`sf A`</td></tr>
</table>

</td></tr>
</table>
</div>
<hr/>
</body>
</html>

