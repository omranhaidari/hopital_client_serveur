<?php

	header('content-type: text/html; charset=utf-8');
	
	echo'
	<head>
		<meta charset="utf-8"/>
		<link href="./../css/style.css" rel="stylesheet">
		<title>Hôpital Didier LEFEBVRE</title>
	<head>';
	
	if(isset($_POST['envoyer'])) {

		$to = htmlentities($_POST['email']); 
		$subject = htmlentities($_POST['objet']); 
		$message = htmlentities($_POST['message']);
		$url = htmlentities($_POST['url']);
		 
		// Création d'une clé aléatoire de limite
		$boundary = md5(uniqid(microtime(), TRUE));
		 
		// En-tête du mail
		$headers = 'From: Hôpital Didier LEFEBVRE <mail@server.com>'."\r\n";
		$headers .= 'Mime-Version: 1.0'."\r\n";
		$headers .= 'Content-Type: multipart/mixed;boundary='.$boundary."\r\n";
		$headers .= "\r\n";
		 
		// Message du mail
		$msg = '--'.$boundary."\r\n";
		$msg .= 'Content-type:text/plain;charset=utf-8'."\r\n";
		$msg .= 'Content-transfer-encoding:8bit'."\r\n";
		$msg .= $message."\r\n";
		 
		// Document en pièce jointe du mail
		$file_name = './../mesDocumentsUploades/'.$url;
		if (file_exists($file_name))
		{
			$file_type = filetype($file_name);
			$file_size = filesize($file_name);
		 
			$handle = fopen($file_name, 'r') or die('File '.$file_name.'can t be open'); // ouverture du document
			$content = fread($handle, $file_size); // lecture du document (en binaire)
			$content = chunk_split(base64_encode($content)); // scinde une chaîne de contenu du document
			$f = fclose($handle); // fermeture du document
		 
			$msg .= '--'.$boundary."\r\n";
			$msg .= 'Content-type:'.$file_type.';name='.$url."\r\n";
			$msg .= 'Content-transfer-encoding:base64'."\r\n";
			$msg .= $content."\r\n";
		}
		 
		// Fin du mail
		$msg .= '--'.$boundary."\r\n";
		 
		// Envoi du mail
		if(mail($to, $subject, $msg, $headers) == true){ // envoi du mail
			echo "<script language='javascript'>window.close()</script>"; // fermeture de la fenêtre
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