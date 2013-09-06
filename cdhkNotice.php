<?php require_once 'Notice.php'; ?>

<?php 
class CDHKNotice extends Notice
{
	private $noticeIdToHtml;
	private $noticeIdToTitle;
	private $noticeIdToDate;
	private $mainPage;
	function __construct()
	{
		parent::__construct(13);
		$this->noticeIdToHtml = array();
		$this->noticeIdToTitle = array();
		$this->noticeIdToDate = array();
	}
	function exec()
	{
		$this->mainPage = $this->getMainPage();
		$this->getMainPageInformation($this->mainPage);
		$newNoticeIds = $this->getNewNoticeIds(array_keys($this->noticeIdToTitle));
		$this->insertNewNotices($newNoticeIds);
	}
	
	function getMainPage()
	{
		return Help::getHtml('http://cdhawjw.blog.163.com/rss');
	}
	
	function getMainPageInformation($mainPage) 
	{
		preg_match_all('/<\/title>[\s]+<link>http:\/\/cdhawjw\.blog\.163\.com\/blog\/static\/([0-9]{17,24})/u', $mainPage, $id);
		preg_match_all('/<item>[\s]*<title>[\s]*<!\[CDATA\[('.self::TitlePattern.')\]\]>/u', $mainPage, $title);
		preg_match_all('/<dcterms:modified>([0-9]{4}\-[0-9]{2}\-[0-9]{2})T[\s\S]{10,20}<\/dcterms:modified>/u', $mainPage, $date);
		for ($i = 0; $i < count($id[0]); $i++)
		{
			$this->noticeIdToTitle[$id[1][$i]] = $title[1][$i];
			$this->noticeIdToDate[$id[1][$i]] = $date[1][$i];
		}
	}
	
	function getIntro($noticeId)
	{
		$html = $this->getHtml($noticeId);
		$content = strip_tags($html);
		$content = strtr($content, array('&nbsp;' =>' '));
		$content = preg_replace('/[\s]+/u', ' ', $content);
		return mb_substr($content, 0, 200, 'UTF-8');
	}
	
	function getHtml($noticeId)
	{
		if (array_key_exists($noticeId, $this->noticeIdToHtml))
			return $this->noticeIdToHtml[$noticeId];
		$html = Help::getStringAfterStr($this->mainPage, "blog/static/$noticeId");
		$html = Help::getStringAfterStr($html, "<![CDATA[");
		$html = Help::getStringBeforeStr($html, "]]>");
		$this->noticeIdToHtml[$noticeId] = $html;
		return $html;
	}

	function getUrl($noticeId)
	{
		return "http://cdhawjw.blog.163.com/blog/static/$noticeId/";
	}
	
	function getTitle($noticeId)
	{
		return $this->noticeIdToTitle[$noticeId];
	}

	function getDate($noticeId)
	{
		return $this->noticeIdToDate[$noticeId];
	}
}
?>

