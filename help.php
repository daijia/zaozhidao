<?php
class Help
{
	static function getHtml($url)
	{
		$curl = curl_init();  
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl); 
		curl_close($curl);
		return $data;
	}
	
	static function getStringAfterStr($source, $str)
	{
		$position = mb_stripos($source, $str, 0, "UTF-8") + mb_strlen($str, "UTF-8");
		$result = mb_substr($source, $position, mb_strlen($source, "UTF-8") - $position, 'UTF-8');
		return $result;
	}
	static function getStringAfterAndStr($source, $str)
	{
		return $str.Help::getStringAfterStr($source, $str);
	}
	
	static function getStringBeforeStr($source, $str)
	{
		$position = mb_stripos($source, $str, 0, "UTF-8");
		$result = mb_substr($source, 0, $position, 'UTF-8');
		return $result;
	}
	static function getStringBeforeAndStr($source, $str)
	{
		return Help::getStringBeforeStr($source, $str).$str;
	}
	
	static function getStringBeforeStrTimes($source, $str, $times=1)
	{
		$position = 0;
		for($i = 0; $i < $times; $i ++)
		{
			$position = mb_stripos($source, $str, $postion, "UTF-8");
			$position ++;
		}
		$position --;
		$result = mb_substr($source, 0, $position, 'UTF-8');
		return $result;
	}
	
	static function getStringAfterArr($source, $arr)
	{
		$i = 0;
		$position = 0;
		while ($i < count($arr))
		{
			$position = mb_stripos($source, $arr[$i], $position, "UTF-8") + mb_strlen($arr[$i], "UTF-8");
		}
		$result = mb_substr($source, $position, mb_strlen($source, "UTF-8") - $position, 'UTF-8');
		return $result;
	}
	
	static function getStringAfterPattern($source, $pattern)
	{
		$justMark = "IloveYouForever";
		$source = preg_replace($pattern, $justMark, $source); //mark
		$position = mb_stripos($source, $justMark, 0, "UTF-8") + mb_strlen($justMark, "UTF-8");
		$result = mb_substr($source, $position, mb_strlen($source, "UTF-8") - $position, 'UTF-8');
		return $result;
	}
	
	static function getStringBeforePattern($source, $pattern)
	{
		$justMark = "IloveYouForever";
		$source = preg_replace($pattern, $justMark, $source); //mark
		$position = mb_stripos($source, $justMark, 0, "UTF-8");
		$result = mb_substr($source, 0, $position, 'UTF-8');
		return $result;
	}
}

?>