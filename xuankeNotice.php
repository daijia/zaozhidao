<?php include 'Notice.php'; ?>

<?php 
class XuankeNotice extends Notice
{
	private $cookie;
	function __construct()
	{
		parent::__construct(1);
		$this->cookie = tempnam("/tmp", "cookie");
	}
	function exec()
	{
		$mainPage = $this->getMainPage();
		$noticeIds = $this->getNoticeIds($mainPage);
		$newNoticeIds = $this->getNewNoticeIds($noticeIds);
		//for ($i = 0; $i < count($newNoticeIds); $i ++)
			//echo $newNoticeIds[$i]."<br>";
		$this->insertNewNotices($newNoticeIds);
	}
	
	function getMainPage()
	{
		$username = "102886"; 
		$password = "124441"; 
		$url = "http://tjis2.tongji.edu.cn:58080/amserver/UI/Login"; 
		$postdata = "Login.Token1=".urlencode($username)."&Login.Token2=".urlencode($password); 
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); 
		curl_setopt($ch, CURLOPT_HEADER, true); 
		//curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );  
		curl_setopt($ch, CURLOPT_TIMEOUT, 500); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); // 使用自动跳转
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); 
		curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
		//curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id()); 
		//curl_setopt($ch, CURLOPT_REFERER, $url); 
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); 
		$result = curl_exec ($ch); 
		curl_close($ch);

		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, "http://xuanke.tongji.edu.cn/portal.jsp?portalNum=p6");
		curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie);
		curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . session_id()); 
		curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie); 
		curl_setopt($curl, CURLOPT_HEADER, TRUE);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
		$data = curl_exec($curl);
		curl_close($curl);
		$encodeData = mb_convert_encoding($data, "utf-8", "gb2312");
		return $encodeData;
	}
	
	function getNoticeIds($mainPage)
	{
		preg_match_all('/showNotice\(\'([0-9]+)\'\)/', $mainPage, $resultArr);
		return $resultArr[1];
	}
	
	function getNewNoticeIds($noticeIds)
	{
		$newNotices = array();
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
				array_push($newNotices, $noticeId);
			}
		}
		return $newNotices;//最新的在数组最前面
	}
	
	function getTwo($newNotices)
	{
		$arr = array($newNotices[0], $newNotices[1]);
		return $arr;
	}
	
	function getHtml($noticeId)
	{
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $this->getUrl($noticeId));
		curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie);
		curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . session_id()); 
		curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie); 
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
		$data = curl_exec($curl);
		curl_close($curl);
		$data = mb_convert_encoding($data, "utf-8", "gb2312");
		return $data;
	}
	
	function getHtmlUrlTitleIntroDate($noticeId, &$html, &$url, &$title, &$intro, &$date)
	{
		$html = getHtml($noticeId);
		$url = getUrl($noticeId);
		preg_match('/<font face=\"隶书\">([\x{4e00}-\x{9fa5}A-Za-z0-9~!@\$%\^&\*\(\)_\+\{\}\|:\"\<\>\-\=\\\[\];\',\.\/《》“”、！￥（）——【】？，。]{1,80})<\/font>/u', $html, $result);
		$title = $result[1];
	}

	function getUrl($noticeId)
	{
		return "http://xuanke.tongji.edu.cn/tj_public/jsp/tongzhi.jsp?id=$noticeId";
	}
	
	function getTitle($noticeId)
	{
		$html = $this->getHtml($noticeId);
		preg_match('/<font face=\"隶书\">([\x{4e00}-\x{9fa5}A-Za-z0-9~!@\$%\^&\*\(\)_\+\{\}\|:\"\<\>\-\=\\\[\];\',\.\/《》“”、！￥（）——【】？，。]{1,80})<\/font>/u', $html, $result);
		return $result[1];
	}
	function getIntro($noticeId)   ///need to change
	{
		$html = $this->getHtml($noticeId);
		$strWithTitle = strip_tags($html);
		$strWithTitle = strtr($strWithTitle, array('&nbsp;' => ''));
		$title = $this->getTitle($noticeId);
		$position = mb_stripos($strWithTitle, $title, 0, "UTF-8") + mb_strlen($title, "UTF-8");
		$strOutTitle = mb_substr($strWithTitle, $position, mb_strlen($strWithTitle, "UTF-8") - $position, 'UTF-8');
		return mb_substr($strOutTitle, 0, 200, 'UTF-8');
	}
	function getDate($noticeId)
	{
		return substr($noticeId, 0, 4)."-".substr($noticeId, 4, 2)."-".substr($noticeId, 6, 2);
	}
}
?>

