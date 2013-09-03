<?php include 'conn.php'?>
 
<?php
abstract class Notice
{
	public $channelId;
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
			$html = $this->getHtml($id);
			$date = $this->getDate($id);
			mysql_query("insert into notice (url,channelId, title, intro, html, isRead, date, updatedAt, createdAt) 
									 VALUES ('$url', '$channelId', '$title', '$intro','$html', '0', '$date', '$createDate', '$createDate')");
		}
	}
	
	abstract function getUrl  ($noticeId);
	abstract function getTitle($noticeId);
	abstract function getIntro($noticeId);
	abstract function getHtml ($noticeId);
	abstract function getDate ($noticeId);
}

?>