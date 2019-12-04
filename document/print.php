<?php

	header('content-type: text/html; charset=utf-8');
	
	$url = htmlentities($_GET['url']);

	echo'
	<head>
		<meta charset="utf-8"/>
		<title>Hôpital Didier LEFEBVRE</title>
	<head>';
	
	echo'
	<html>
		<body>
			<iframe name="zone" id="zone" src="./../mesDocumentsUploades/'.$url.'" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"></iframe>
			<script>zone.focus();zone.print();</script>
		</body>
	</html>';

?>