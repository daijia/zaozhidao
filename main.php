<?php 
require_once 'xuankeNotice.php'; 
require_once 'sseNotice.php';
require_once 'internationalNotice.php';
require_once 'cdhkNotice.php';
set_time_limit(0);//单位秒
?>

<?php
	$arr = array(new XuankeNotice(), new SSENotice(), new InternationalNotice(), new CDHKNotice());
	for ($i = 0; $i < count($arr); $i ++)
		$arr[$i]->exec();


	function func()
	{
		$checkQuery = mysql_query("select title, intro,date, url,html  from notice order by id desc ");
		while ($result = mysql_fetch_array($checkQuery))
		{
			echo $result["url"]."<br>".$result["title"].$result["date"]."<br>".$result["html"]."<br><br>";
		}
	}
?>
