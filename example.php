<?php
	require "include/TagCloudProvider.php";
	$tg = new TagCloudProvider("images/tagCloud.php");
?>
<html>
	<head>
		<title>
			Tag Cloud Demo
		</title>
	</head>
	<body>
		This is a sample image generated using the TagCloud class.
		<br/>
		<?php 
			$tg->create(300, 200, Array('C','C++','C#','VB 6.0','Java','x86 Assembly','PHP','MS SQL Server','MS-Access','MySQL','XML','Linux'));
		?>
	</body>
</html>