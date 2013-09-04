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
}

?>