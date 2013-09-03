<?php
$conn = @mysql_connect("localhost","root","forever");
if (!$conn)
{
	die("连接失败" . mysql_error());
}
mysql_select_db("notice", $conn);
mysql_query("set character set 'utf8'");
mysql_query("set names 'utf8'");
?>