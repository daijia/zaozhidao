<?php require_once 'Notice.php'; ?>

<?php 
class SSENotice extends Notice
{
	private $noticeIdToDate;
	private $noticeIdToTitle;
	function __construct()
	{
		parent::__construct(11);
		$this->noticeIdToDate = array();
		$this->noticeIdToTitle = array();
	}
	function exec()
	{
		$this->getIntro("1001948");
		$mainPage = $this->getMainPage();
		$this->getMainPageInformation($mainPage);
		$newNoticeIds = $this->getNewNoticeIds(array_keys($this->noticeIdToDate));
		$this->insertNewNotices($newNoticeIds);
	}
	
	function getMainPage()
	{
		return Help::getHtml('http://sse.tongji.edu.cn/InfoCenter/Lastest_Notice.aspx');
	}
	
	function getMainPageInformation($mainPage) //建立主页面，notice ，id与date，title 的关联关系
	{
		$datePattern = '<span id\=\"GridView1_PublishTime_[0-9]{1,2}\"\sclass\=\"news_date\">([0-9\-]{8,10})<\/span>';
		preg_match_all('/Notice\/([0-9]{6,8})\">('.self::TitlePattern.')<\/a>/u', $mainPage, $resultArr);//get id and title
		preg_match_all('/'.$datePattern.'/u', $mainPage, $resultArr2); //get date
		for ($i = 0; $i < count($resultArr[0]); $i++)
		{
			$this->noticeIdToTitle[$resultArr[1][$i]] = $resultArr[2][$i];  
			$this->noticeIdToDate[$resultArr[1][$i]] = $resultArr2[1][$i];
		}
	}
	
	function getHtml($noticeId)
	{
		$html = Help::getHtml($this->getUrl($noticeId));
		$html = '<base href="http://sse.tongji.edu.cn/Notice/" />'.$html;
		$head = Help::getStringBeforeStr($html, "</head>");
		$content = Help::getStringAfterStr($html, "已阅读");
		$content = Help::getStringAfterStr($content, '</div>');
		$content = "</head><body>".Help::getStringBeforeStr($content, ("InstanceEndEditable"))."</body></html>";
		$html= $head.$content;
		//$content = "<div class=\"detail_head\">";
		//$html = '<base href="http://sse.tongji.edu.cn/Notice/" />'.$html;
		return $html;
	}

	function getUrl($noticeId)
	{
		return "http://sse.tongji.edu.cn/Notice/$noticeId";
	}
	
	function getTitle($noticeId)
	{
		return $this->noticeIdToTitle[$noticeId];
	}
	function getIntro($noticeId)
	{
		$html = $this->getHtml($noticeId);
		$strWithTitle = strip_tags($html);
		$strWithTitle = strtr($strWithTitle, array('&nbsp;' =>' '));
		$strWithTitle = preg_replace('/[\s]+/u', ' ', $strWithTitle);//remove multispace
		$result = mb_substr($strWithTitle, 0, 200, 'UTF-8');
		return $result;
	}
	function getDate($noticeId)
	{
		return $this->noticeIdToDate[$noticeId];
	}
}
?>

