<?php
	require "include/TagCloudProvider.php";
	$tg = new TagCloudProvider("images/TagCloud.php");
?>
<html>
	<head>
		<title>
			Tag Cloud Demo
		</title>
		<meta charset="utf-8">
	</head>
	<body>
		This is a sample image generated using the TagCloud class.
		<hr>
		<?php 
			$tg->create(300, 200, Array('C','C++','C#','VB 6.0','Java','x86 Assembly','PHP','MS SQL Server','MS-Access','MySQL','XML','Linux'));
		?>
		<hr>
		<code>Code for creating the above Tag Cloud.
			<pre>
&lt;?php
	require &quot;include/TagCloudProvider.php&quot;;
	$tg = new TagCloudProvider(&quot;images/tagCloud.php&quot;);
	$tg-&gt;create(300, 200, Array(&apos;C&apos;,&apos;C++&apos;,&apos;C#&apos;,&apos;VB 6.0&apos;,&apos;Java&apos;,&apos;x86 Assembly&apos;,&apos;PHP&apos;,&apos;MS SQL Server&apos;,&apos;MS-Access&apos;,&apos;MySQL&apos;,&apos;XML&apos;,&apos;Linux&apos;));
?&gt;
			</code>
		</pre>
	</body>
</html>