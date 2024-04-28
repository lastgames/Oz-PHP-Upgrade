<?php
$file="report.xls";

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");

echo "<table><tr><td colspan=3><strong>".$_POST['year']." ".$_POST['name']." Report</strong></td></tr></table>\r\n<table><tr><td>Date: ".$_POST['date']."</td><td colspan=3>Total Records: ".$_POST['count']."</td></tr></table>\r\n<table>".$_POST['header']."</table>\r\n<table>".$_POST['body']."</table>";
?>      