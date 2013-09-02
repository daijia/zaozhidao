<?php include 'xuanke.php'; ?>
<?php
	$pages = xuanke();//最新的为pages[0]
	for ($i = 0; $i < count($pages); $i ++)
		echo $pages[$i];
?>
