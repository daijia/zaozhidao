<?php include 'xuanke.php'; ?>
<?php
	$pages = xuanke();// ���µ�Ϊ$pages[0]
	for ($i = 0; $i < count($pages); $i ++)
		echo $pages[$i];
?>
