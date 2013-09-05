<?php 
require_once 'xuankeNotice.php'; 
require_once 'sseNotice.php';
require_once 'internationalNotice.php';
set_time_limit(500);  //单位秒
?>

<?php
	$notice = new InternationalNotice();
	$notice->exec();
	function echoArr($arr)
	{
		for($i = 0; $i < count($arr); $i++)
			echo $arr[$i]."<br>";
	}
	function func()
	{
		$checkQuery = mysql_query("select title, intro, url,html  from notice where channelId=11 ");
		while ($result = mysql_fetch_array($checkQuery))
		{
			echo $result["html"]."<br><br>";
		}
	}
?>
