<?php include "conf.php";
header('content-type:text/html');
?>
<table border=1 cellspacing=0><tr>
<?php 
if(isset($_REQUEST['clear']))
{
$db->query("delete from logs");
$db->query("delete from response");
}
$logs=$db->select("response","*","order by id desc limit 10");
//Reading fields
$flds=$logs['fields'];
for($i=0;$i<count($flds);$i++){
echo "<td>".$flds[$i]."</td>";
}
echo "</tr>";
for($i=0;$i<$logs['rows'];$i++){
echo "<tr>";
for($j=0;$j<count($logs[$i]);$j++){
echo "<td>".$logs[$i][$j]."</td>";
}
echo "</tr>";
}
?></table>
