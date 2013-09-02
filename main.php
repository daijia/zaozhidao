<?php include 'xuanke.php'; ?>
<?php
	$pages = xuanke();// 最新的为$pages[0]
	for ($i = 0; $i < count($pages); $i ++)
		echo $pages[$i];
?>
