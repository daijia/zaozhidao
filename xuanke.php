<?php include 'conn.php'; ?>

<?php 
	function xuanke()
	{
		$cookie = tempnam("/tmp", "cookie");
		$mainPage = getMainPage($cookie);
		$noticeIds = getNoticeIds($mainPage);
		//echo getNoticePage($noticeIds[0], $cookie);
		//echo $noticeIds[0];
		$newNoticeIds = getNewNotices($noticeIds);
		//for ($i = 0; $i < count($newNoticeIds); $i ++)
		//	echo $newNoticeIds[$i].'<br>';
		insertNewNotices($newNoticeIds);
		
		$newPages = array();
		for($i = 0; $i < count($newNoticeIds); $i ++)
			array_push($newPages, getNoticePage($newNoticeIds[$i], $cookie));
		return $newPages;
	}
	
	function getMainPage($cookie)
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
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer 
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); 
		curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
		//curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id()); 
		//curl_setopt($ch, CURLOPT_REFERER, $url); 
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); 
		$result = curl_exec ($ch); 
		curl_close($ch);


		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, "http://xuanke.tongji.edu.cn/portal.jsp?portalNum=p6");
		curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie);
		curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . session_id()); 
		curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); 
		curl_setopt($curl, CURLOPT_HEADER, TRUE);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
	
	function getNoticeIds($data)
	{
		preg_match_all('/showNotice\(\'([0-9]+)\'\)/', $data, $resultArr);
		/*for ($i = 0; $i < count($resultArr[1]); $i ++)
		{
			echo $resultArr[1][$i]. "            ".$i;
			echo '<br>';
		}*/
		return $resultArr[1];
	}
	
	
	function getNoticePage($noticeId, $cookie)
	{
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, "http://xuanke.tongji.edu.cn/tj_public/jsp/tongzhi.jsp?id=$noticeId");
		curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie);
		curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . session_id()); 
		curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); 
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
	
?>

