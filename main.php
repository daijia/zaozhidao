<?php 
include 'xuankeNotice.php'; 
set_time_limit(500);  //单位秒
?>

<?php

	$notice = new XuankeNotice();	//func2();
	$notice->exec();
	func();

	function func()
	{
		$checkQuery = mysql_query("select title, intro, url  from notice");
		while ($result = mysql_fetch_array($checkQuery))
		{
			echo $result["title"]."<br>".$result["intro"]."<br><br>";
		}
	}
?>
