<?
ini_set("display_errors",0);

//define(BD_HOST,"192.168.100.1"); berlineta
/*
define(BD_HOST,"192.168.101.1"); //monareta
define(BD_USER,"cursosnaviuser");
define(BD_SENHA,"hgt4f*j5$");
define(BD_NAME,"cursosnavi");
*/

define(BD_HOST,"localhost");
define(BD_USER,"root");
define(BD_SENHA,"");
define(BD_NAME,"cursos_2");

define(TIMEOUT_ALIVE,30);


// Quote variable to make safe 
function quote_smart ($value )
{
 // Stripslashes 
 if (get_magic_quotes_gpc()) { 
   $value = stripslashes ($value );
 } 
 // Quote if not integer 
 if (! is_numeric ($value )) { 
   $value ="'" .mysql_real_escape_string ($value ) . "'" ;
 } 
 return $value ;
}
?>
