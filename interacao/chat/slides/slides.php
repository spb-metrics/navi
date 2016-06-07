<?
/* EM TESTE
 *
 * objetivo: mostrar o slide atualmente sendo visualizado pelo professor durante uma aula ao vivo
 */

echo "<link rel=stylesheet href=\"slides.css\" type=text/css>";
//Configurações dos arquivos que compõem os slides
$slideNamePrefix="Slide";
$slideNameSufix="JPG";
$caminho = "./apres_plataforma_navi";
//css
$classePadrao="slide";
$classeSlideAtual="slideAtual";
//controle de exibicao
$totalSlides=19;
$slideInicial=1;
$largura=100;
$altura=100;
echo "<body>";
for($i=$slideInicial;$i<=$totalSlides;$i++) {
  if ($i==$slideInicial) { $classe=$classeSlideAtual; } else { $classe=$classePadrao; }
  echo "<div id=\"slide{$i}\" class=\"{$classe}\"><img src=\"{$caminho}/{$slideNamePrefix}{$i}.{$slideNameSufix}\" width=\"{$largura}\" height=\"{$altura}\"></div>";
}
echo "<div class=\"botoes\">";
echo "\n<input type=\"button\" name=\"nextSlide\" onClick=\"javascript:nextSlide();\" value=\"Proximo\">\n";
echo "</div>";

echo "<script>";

echo "function nextSlide() { \n";
echo " var slideAtual=".$slideInicial.";\n";
echo " var totalSlides=".$totalSlides.";\n";
echo " var classePadrao='".$classePadrao."';\n";
echo " var classeSlideAtual='".$classeSlideAtual."';\n";
echo " if (slideAtual==totalSlides) { return false; }\n ";
echo " alert('Slide aAtual: '+slideAtual);\n";
echo " //document.getElementById('slide'+slideAtual).style=' position:relative; left:0px; top:0px; display:none;';\n";
echo " document.getElementById('slide1').class=' slide';\n";
echo " slideAtual=slideAtual+1;\n";
echo " //document.getElementById('slide'+slideAtual).class='slideAtual;';\n";
echo " document.getElementById('slide2').style=' position:relative; left:0px; top:0px; display:none;';\n";
echo " } ";
?>
</script>
</body>