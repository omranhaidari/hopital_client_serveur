<?php

	$param=$_POST['param'];

	// Vérification si le fichier a été enregistré sans erreur.
	if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
		$allowed = array("jpg" => "image/jpg", "JPG" => "image/JPG", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png","PNG" => "image/PNG", "pdf" => "application/pdf","PDF" => "application/PDF" );

		$filename = $_FILES["file"]["name"];
		$fileformat = $_FILES["file"]["type"];
		$filesize = $_FILES["file"]["size"];       
		$data= file_get_contents($_FILES["file"]["tmp_name"]);
		$filetype=$_POST['type_doc'];
		$msg=null;

		// Vérification de l'extension du fichier
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if(!array_key_exists($ext, $allowed)) {
			$msg="Erreur : Veuillez sélectionner un format de fichier valide.";
		}

		// Vérification de la taille du fichier (10Mo maximum)
		$maxsize = 10 * 1024 * 1024;
		if($fileformat > $maxsize) {
			$msg="Erreur : La taille du fichier est supérieure à la limite autorisée.";
		}

		// Vérification du type MIME du fichier
		if(in_array($fileformat, $allowed)) {

			// Vérification si le fichier existe avant de l'enregistrer.
			if(file_exists("C:/wamp64/www/hopital_client_serveur/mesDocumentsUploades/" . $_FILES["file"]["name"])) { // Windows
			//if(file_exists("/Applications/MAMP/htdocs/hopital_client_serveur/mesDocumentsUploades/" . $_FILES["file"]["name"])) { // Mac OS
				$msg="Erreur : ".$_FILES["file"]["name"] . " existe déjà.";
			} else {
				move_uploaded_file($_FILES["file"]["tmp_name"], "C:/wamp64/www/hopital_client_serveur/mesDocumentsUploades/" . $_FILES["file"]["name"]); // Windows
				//move_uploaded_file($_FILES["file"]["tmp_name"], "/Applications/MAMP/htdocs/hopital_client_serveur/mesDocumentsUploades/" . $_FILES["file"]["name"]); // Mac OS

				// Récupération de la date actuelle 
				$date_actuelle = date('Y-m-d');
				// Fonction de hachage 
				$fonctionmd5 = md5($filename . date("Y-m-d H:i:s"));

				// Enregistrement des éléments (d'indexage) du document enregistré.
				try {
					// Connexion à la base de données
					$bdd = new PDO('mysql:host=localhost;dbname=hopital_php;charset=utf8', 'user1', 'hcetylop');
					// Création de la requête d'insertion des éléments caractéristiques du document
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$y = $bdd -> prepare("INSERT INTO document(nom_fichier,clef,code_patient,type,format,date,contenu) VALUES(?, ?, ?, ?, ?, ?, ?)") or die(print_r($bdd -> errorInfo()));
						$y -> bindParam(1,$filename);
						$y -> bindParam(2,$fonctionmd5);
						$y -> bindParam(3,$param);      
						$y -> bindParam(4,$filetype);
						$y -> bindParam(5,$fileformat);
						$y -> bindParam(6,$date_actuelle);
						$y -> bindParam(7,$data);
						$y -> execute();
				} catch (Exception $e) {
					$msg="Erreur : " . $e -> getMessage();
				}       
			}

		} else {
			$msg="Erreur : Il y a eu un problème d'enregistrement de votre fichier. Veuillez réessayer."; 
		}

	} else {
		$msg="Erreur : " . $_FILES["file"]["error"];
	}
	
	header ("location: index.php?param=".$param."&msg=".$msg);

?>