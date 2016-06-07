<style>
.calendario {
     width: 80%;
     border-collapse: collapse;
     border: 1px solid #333;
     background-color: #FBFBFB;
     text-align: center;
}

caption {
     padding: 5px 0 5px 0;
     font: small-caps bold 11px verdana, arial, tahoma;
     background-color: #999;
     border: 1px solid #333;
}

th {
     background: #F4F4F4;
}

th, td {
     padding: 3px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>
<?php
     $domingo  = "style=color:#C30;";
     $hoje     = "style=color:#CC0;";

     $mes  = date("m");
     $dia  = date("d");
     $ano  = date("Y");
     $ano_ = substr($ano,-2);

     function meses($a)  {
              switch($a) {
                     case 1:  $mes = "janeiro";   break;
                     case 2:  $mes = "fevereiro"; break;
                     case 3:  $mes = "março";     break;
                     case 4:  $mes = "abril";     break;
                     case 5:  $mes = "maio";      break;
                     case 6:  $mes = "junho";     break;
                     case 7:  $mes = "julho";     break;
                     case 8:  $mes = "agosto";    break;
                     case 9:  $mes = "setembro";  break;
                     case 10: $mes = "outubro";   break;
                     case 11: $mes = "novembro";  break;
                     case 12: $mes = "dezembro";  break;
              }
              return $mes;
     }
?>
<table border="0" summary="Calendário" class="calendario">
     <caption><?php echo "".meses($mes)." ".$ano.""; ?></caption>
     <thead>
     <tr>
         <th abbr="Domingo" title="Domingo"><b <?php echo("$domingo");?>>D</b></th>
         <th abbr="Segunda" title="Segunda"><b>S</b></th>
         <th abbr="Terça"   title="Terça"><b>T</b></th>
         <th abbr="Quarta"  title="Quarta"><b>Q</b></th>
         <th abbr="Quinta"  title="Quinta"><b>Q</b></th>
         <th abbr="Sexta"   title="Sexta"><b>S</b></th>
         <th abbr="Sábado"  title="Sábado"><b>S</b></th>
     </tr>
     </thead>
     <tbody>
 <?php
 //include($caminhoBiblioteca."lib/agenda.inc.php");
     
          $Data = strtotime($mes."/".$dia."/".$ano_);
          $Dia  = date('w',strtotime(date('n/\0\1\/Y',$Data)));
          $Dias = date('t',$Data);
          for ($i=1,$d=1;$d<=$Dias;) {
               echo("<tr>");
               for ($x=1;$x<=7 && $d <= $Dias;$x++,$i++) {
                    if ($i > $Dia) {
            $destaque = '';
                        if ($x == 1)    { $destaque = $domingo; }
                        if ($d == $dia) { $destaque = $hoje; }
                        if (($x == 1) && ($d == $dia)) { $destaque = $hoje; }
                        //echo("<td ".$destaque.">".$d++."</td>");
						echo ("<td ".$destaque."><a href='http://ead.tjrs.jus.br/navi/agenda#".$d."/".$mes."/".$ano_."' target='_parent'>".$d++."</a></td>");
						//echo ("<td ".$destaque."><span style='cursor:pointer;' onclick='changeIframeSrc('recurso','http://www.eavirtual.ea.ufrgs.br/redesan/agenda/#".$d."/".$mes."/".$ano_."'); recursoAtivado(this);'>".$d++."</span></td>");
                    }
                    else { echo("<td> </td>"); }
               }
               for (;$x<=7;$x++) { echo("<td> </td>"); }
               echo("</tr>");
           }
		 		
     ?>
     </tbody>
</table>
