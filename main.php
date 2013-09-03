<?php 
include 'xuankeNotice.php'; 
set_time_limit(120);  //单位秒
?>

<?php
	$notice = new XuankeNotice();	//func2();
	$notice->exec();
	func();

	function func()
	{
		$checkQuery = mysql_query("select title, intro  from notice");
		while ($result = mysql_fetch_array($checkQuery))
		{
			//echo $result["date"]."     ".$result["title"]."      ".$result["url"]."<br>";
			echo $result["title"]."<br>".$result["intro"]."<br><br>";
		}
	}

	function func2()
	{
		$checkQuery = mysql_query("select title, html from notice ");
		while ($result = mysql_fetch_array($checkQuery))
		{
			$strWithTitle = strip_tags($result["html"]);
			$title = $result["title"];
			$position = stripos($strWithTitle, $title);
			$strOutTitle = substr($strWithTitle, strlen($title) + $position);
			echo mb_substr(ltrim($strOutTitle), 0, 200, "utf-8").'<br>';
		}
		//echo strip_tags($checkQuery["html"]);
		//echo $checkQuery["html"];
		//$removeTitle = $checkQuery;
		
	}
	


?>
