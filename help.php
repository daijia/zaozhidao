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
	static function getStringAfterPattern($source, $pattern)
	{
		$justMark = "IloveYouForever";
		$source = preg_replace($pattern, $justMark, $source); //mark
		$position = mb_stripos($source, $justMark, 0, "UTF-8") + mb_strlen($justMark, "UTF-8");
		$result = mb_substr($source, $position, mb_strlen($source, "UTF-8") - $position, 'UTF-8');
		return $result;
	}
}

?>