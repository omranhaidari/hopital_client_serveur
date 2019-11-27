<?php

	header('content-type: text/html; charset=utf-8');
	
	echo'
	<head>
		<meta charset="utf-8"/>
		<link href="css/style.css" rel="stylesheet">
		<title>Hôpital Didier LEFEBVRE</title>
	<head>';
	
	if(isset($_POST['envoyer'])) {
		
		$to = htmlentities($_POST['email']); 
		$subject = htmlentities($_POST['objet']); 
		$message = htmlentities($_POST['message']);
		$url = htmlentities($_POST['url']);
		 
		// clé aléatoire de limite
		$boundary = md5(uniqid(microtime(), TRUE));
		 
		// Headers
		$headers = 'From: Hôpital Didier LEFEBVRE <mail@server.com>'."\r\n";
		$headers .= 'Mime-Version: 1.0'."\r\n";
		$headers .= 'Content-Type: multipart/mixed;boundary='.$boundary."\r\n";
		$headers .= "\r\n";
		 
		// Message
		$msg = $message."\r\n\r\n";
		 
		// Texte
		$msg .= '--'.$boundary."\r\n";
		$msg .= 'Content-type:text/plain;charset=utf-8'."\r\n";
		$msg .= 'Content-transfer-encoding:8bit'."\r\n";
		$msg .= 'Partage de document.'."\r\n";
		 
		// Pièce jointe
		$file_name = 'mesDocumentsUploades/'.$url;
		if (file_exists($file_name))
		{
			$file_type = filetype($file_name);
			$file_size = filesize($file_name);
		 
			$handle = fopen($file_name, 'r') or die('File '.$file_name.'can t be open');
			$content = fread($handle, $file_size);
			$content = chunk_split(base64_encode($content));
			$f = fclose($handle);
		 
			$msg .= '--'.$boundary."\r\n";
			$msg .= 'Content-type:'.$file_type.';name='.$file_name."\r\n";
			$msg .= 'Content-transfer-encoding:base64'."\r\n";
			$msg .= $content."\r\n";
		}
		 
		// Fin
		$msg .= '--'.$boundary."\r\n";
		 
		// Function mail()
		if(mail($to, $subject, $msg, $headers)==true){  //Envoi du mail
			echo "<script language='javascript'>window.close()</script>"; //Fermeture de la fenêtre
		}
		else{
			echo "error";
		}
		
	}
	else{
		echo'
		<html>
			<body>
				<div id="gauche">
					<div id="celluleGauche">
						<h2> Partage de document par e-mail </h2>
						<form action="share.php" method="post" class="formulaire">
							<fieldset></br>
								<legend>Formulaire</legend>
								<input type="email" name="email" placeholder="E-mail" required/>
								<input type="text" name="objet" placeholder="Objet" required/>
								<textarea name="message" rows="2" cols="50" placeholder="Message"></textarea>
								<label>Nom de la pièce jointe : '.$_GET['url'].'</label></br></br>
								<input type="submit" value="Envoyer" name="envoyer" id="submit">
								<input id="url" name="url" type="hidden" value="'.$_GET['url'].'">
							</fieldset>
						</form>
					</div>
				</div>
			</body>
		</html>';
	}



?>