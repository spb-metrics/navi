<?
// USAR QUANDO CHEGAR A HORA DE OTIMIZAR!!!!!
// Windows workaround para memory_get_usage()
$output = array();      
//exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);            
/*exec('tasklist /FO LIST', $output);            

echo "PID DESTE SCRIPT:".getmypid();
*/
echo date("d/m/Y");
exec('dir', $output);            
echo "<PRE>"; print_r($output);
?>