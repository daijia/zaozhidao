<?php require_once 'Notice.php'; ?>

<?php 
class InternationalNotice extends Notice
{
	private $noticeIdToHtml;
	private $noticeIdToTitle;
	function __construct()
	{
		parent::__construct(12);
		$this->noticeIdToHtml = array();
		$this->noticeIdToTitle = array();
	}
	function exec()
	{
		$mainPage = $this->getMainPage();
		$this->getMainPageInformation($mainPage);
		$newNoticeIds = $this->getNewNoticeIds(array_keys($this->noticeIdToTitle));
		$this->insertNewNotices($newNoticeIds);
	}
	
	function getMainPage()
	{
		return Help::getHtml('http://www.tongji-uni.com/newslist.aspx?intclass=1');
	}
	
	function getMainPageInformation($mainPage) //搜索id， id和title 的关联关系
	{
		preg_match_all('/sn\=([0-9]{1,5})\">('.self::TitlePattern.')<\/a>/u', $mainPage, $result);
		for ($i = 0; $i < count($result[0]); $i++)
		{
			$this->noticeIdToTitle[$result[1][$i]] = $result[2][$i];
			//echo $this->getIntro($result[1][$i])."<br><br>";
		}
	}
	
	function getRealHtml($noticeId)
	{
		if (array_key_exists($noticeId, $this->noticeIdToHtml))
			return $this->noticeIdToHtml[$noticeId];
		$html =  '<base href="http://www.tongji-uni.com/" />'.Help::getHtml($this->getUrl($noticeId));
		$this->noticeIdToHtml[$noticeId] = $html;
		return $html;
	}
	
	function getHtml($noticeId)
	{
		$html = $this->getRealHtml($noticeId);
		$head = Help::getStringBeforeStr($html, "<body>");
		$content = Help::getStringAfterStr($html, 'mainwh>');
		$content = preg_replace('/【返回】[\s\S]*同济大学美国校友会/u', '', $content);//remove bottom
		return ('<base href="http://www.tongji-uni.com/" />'.$content);
	}

	function getUrl($noticeId)
	{
		return "http://www.tongji-uni.com/newsshow.aspx?sn=$noticeId";
	}
	
	function getTitle($noticeId)
	{
		return $this->noticeIdToTitle[$noticeId];
	}
	function getIntro($noticeId)
	{
		$html = $this->getRealHtml($noticeId);
		$strWithTitle = strip_tags($html);
		$strWithTitle = strtr($strWithTitle, array('&nbsp;' =>' '));
		$strWithTitle = preg_replace('/[\s]+/u', ' ', $strWithTitle);//remove multispace
		$pattern = "/\([0-9\-]{8,10}\s[0-9:]{8}\)/u";
		$strOutTitle = Help::getStringAfterPattern($strWithTitle, $pattern);
		$strOutTitle = preg_replace('/【返回】[\s\S]*同济大学美国校友会/u', '', $strOutTitle);//remove bottom
		return mb_substr($strOutTitle, 0, 200, 'UTF-8');
	}
	function getDate($noticeId)
	{
		$html = $this->getRealHtml($noticeId);
		preg_match_all('/([0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2})\s[0-9]{2}:/u', $html, $result);
		return $result[1][0];
	}
}
?>

