<?php require_once 'conn.php'?>
<?php require_once 'help.php'?>
<?php
abstract class Notice
{
	protected $channelId;
	const TitlePattern = '[\x{4e00}-\x{9fa5}A-Za-z0-9\s~!@\?\$%\^&\*\(\)_\+\{\}\|:\"\<\>\-\=\\\[\];\',\.\/《》“”、：！￥（）——【】？，。「」]{1,80}';
	function __construct($channelId = 0)
	{
		$this->channelId = $channelId;
	}
	
	abstract function exec(); //更新数据
	
	function insertNewNotices($newNoticeIds)
	{
		$createDate = date("Y-m-d H:i:s");
		for($i = count($newNoticeIds)-1; $i >= 0; $i --)
		{
			$id = $newNoticeIds[$i];
			$url = $this->getUrl($id);
			$channelId = $this->channelId;
			$title = $this->getTitle($id);
			$intro = $this->getIntro($id);
			$html = addslashes($this->getHtml($id));
			$date = $this->getDate($id);
			mysql_query("insert into notice (url,channelId, title, intro, html, isRead, date, updatedAt, createdAt) 
									 VALUES ('$url', '$channelId', '$title', '$intro','$html', '0', '$date', '$createDate', '$createDate')");
			echo mysql_error(); 
		}
	}
	
	function getNewNoticeIds($noticeIds)
	{
		$newNoticeIds = array();
		for ($i = 0 ; $i < count($noticeIds); $i ++)
		{
			$channelId = $this->channelId;
			$noticeId = $noticeIds[$i];
			$checkQuery = mysql_query("select id from notice where channelId = $channelId and url like '%".$noticeId."%' ");
			$num = 0;
			while ($result = mysql_fetch_array($checkQuery))
			{
				$num ++;
			}
			if ($num == 0)
			{
				array_push($newNoticeIds, $noticeId);
			}
		}
		return $newNoticeIds;//最新的在数组最前面
	}
	abstract function getUrl  ($noticeId);
	abstract function getTitle($noticeId);
	abstract function getIntro($noticeId);
	abstract function getHtml ($noticeId);
	abstract function getDate ($noticeId);
}

?>