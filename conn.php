<?php
$conn = @mysql_connect("localhost","root","forever");
if (!$conn)
{
	die("连接数据库失败：" . mysql_error());
}
mysql_select_db("notice", $conn);
//mysql_query("set character set 'gb2312'");
//mysql_query("set names 'gb2312'");

function getNewNotices($noticeIds)
{
	$newNotices = array();
	for ($i = 0 ; $i < count($noticeIds); $i ++)
	{
		$checkQuery = mysql_query("select * from xuanke where value = $noticeIds[$i]");
		$num = 0;
		while ($result = mysql_fetch_array($checkQuery))
		{
			$num ++;
		}
		if ($num == 0)
		{
			array_push($newNotices, $noticeIds[$i]);
		}
	}
	return $newNotices;// 最新的消息在数组最前面
}

function insertNewNotices($newNoticesIds)
{
	for($i = count($newNoticesIds)-1; $i >= 0; $i --)
		mysql_query("insert into xuanke (value) VALUES ($newNoticesIds[$i])");
}
?>