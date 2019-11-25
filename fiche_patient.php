<?php

	if(isset($_GET['param'])) {
		// Récupération des données du patient sélectionné.
		$selectProfil = mysqli_query($connexion, "SELECT patient.nom, patient.prenom, patient.sexe, patient.date_naissance, patient.num_secu, patient.code_pays, patient.date_prem_entree, motif.libelle FROM patient, motif WHERE patient.code_motif = motif.code AND patient.code = ".$_GET['param']);
		$selectDocuments = mysqli_query($connexion, "SELECT nom_fichier, type, format, date FROM patient, document WHERE patient.code = document.code_patient AND patient.code = ".$_GET['param']);

		echo' 
		<div id="celluleDroite">
			<h2>Fiche détaillée du patient</h2>
			
			<table>
			
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Sexe</th>
					<th>Date de naissance</th>
					<th>Numéro Sécu</th>
					<th>Code pays</th>
					<th>Date première entrée</th>
					<th>Motif</th>
				</tr>';

				// Affichage de la fiche détaillée du patient sélectionné, dont l'identifiant a été passé en paramètre (par URL), sous forme d'un tableau.
				while($dataR4 = mysqli_fetch_array($selectProfil))
				{
					if($dataR4["sexe"] == "M") {
						echo'
						<center>
							<img id=inconnu src="images/inconnu.jpeg">
							<br>
							<br>'
							.$dataR4["nom"].' '.$dataR4["prenom"].'
						</center>
						<br>';
					}

					if($dataR4["sexe"] == "F") {
						echo'
						<center>
							<img id=inconnu src="images/inconnue.png">
							<br>
							<br>'
							.$dataR4["nom"].' '.$dataR4["prenom"].'
						</center>
						<br>';
					}


					echo'
					<tr>
						<td>'.strtoupper(utf8_encode($dataR4["nom"])).'</td> <!-- pour être sûr que le nom est en majuscule -->
						<td>'.utf8_encode($dataR4["prenom"]).'</td>
						<td>'.utf8_encode($dataR4["sexe"]).'</td>
						<td>'.date("d/m/Y", strtotime(utf8_encode($dataR4["date_naissance"]))).'</td> <!-- changement du format de la date -->
						<td>'.utf8_encode($dataR4["num_secu"]).'</td>
						<td>'.utf8_encode($dataR4["code_pays"]).'</td>
						<td>'.date("d/m/Y", strtotime(utf8_encode($dataR4["date_prem_entree"]))).'</td> <!-- changement du format de la date -->
						<td>'.utf8_encode($dataR4["libelle"]).'</td>
					</tr>';
				}
				
			echo'
			</table>
		</div>';


		// Liste des documents
		echo'
		<div id="celluleDroite">
			<h2>Liste des documents</h2>
			<table>
				<tr>
					<th>Nom fichier</th>
					<th>Type</th>
					<th>Format</th>
					<th>Date</th>
					<th>Visualiser</th>
					<th>Télécharger</th>
					<th>Imprimer</th>
					<th>Partager</th>
				</tr>';

		        if (!$selectDocuments) {
				    printf("Error: %s\n", mysqli_error($connexion));
				    exit();
				}

				while($dataR5 = mysqli_fetch_array($selectDocuments)) {
					echo'
					<tr>
						<td>'.utf8_encode($dataR5["nom_fichier"]).'</td>
						<td>'.utf8_encode($dataR5["type"]).'</td>
						<td>'.utf8_encode($dataR5["format"]).'</td>
						<td>'.date("d/m/Y", strtotime(utf8_encode($dataR5["date"]))).'</td>
						<td><a href="http://google.com"><img src="images/preview-file.png" title="Prévisualiser le document" alt="preview-file" height="30px" width="30px"/></a></td>
						<td><a href="http://google.com"><img src="images/download-file.jpg" title="Télécharger le document" alt="download-file" height="25px" width="25px"/></a></td>
						<td><a href="http://google.com"><img src="images/print-file.png" title="Imprimer le document" alt="print-file" height="30px" width="30px"/></a></td>
						<td><a href="http://google.com"><img src="images/share-file.png" title="Partager le document par email" alt="share-file" height="30px" width="30px"/></a></td>
					</tr>';
				}

			echo'
			</table>
		</div>';



		// Enregistrement des document
	    echo'
	    <div id="celluleGauche">
			<h2>Enregistrer un document</h2>
	        <form method="post" enctype="multipart/form-data" class="formulaire">
				<fieldset>
			        <h4>Fichier :</h4>
			        <input type="file" name="file" id="file">

					<h4>Type du document :</h4>
					<select name="type_doc" id="type_doc" width="10p%">
						<option value="">Choisir un type de fichier</option>
						<option value="ordonnance">Ordonnance</option>
						<option value="radio">Radio</option>
						<option value="bilan_sanguin">Bilan sanguin</option>
						<option value="bilan_general">Bilan general</option>
					</select>

			        <input type="submit" name="submit" value="Upload" id="submit">
			        <p><strong>Note:</strong> Seuls les formats .jpg, .jpeg, .gif, .png et .pdf sont autorisés jusqu\'à une taille maximale de 10 Mo.</p>
		        </fieldset>
	    	</form>
		</div>';



		// Vérification si le formulaire a été soumis
		if($_SERVER["REQUEST_METHOD"] == "POST") {
		    // Vérification si le fichier a été enregistré sans erreur.
		    if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
		        $allowed = array("jpg" => "image/jpg", "JPG" => "image/JPG", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png","PNG" => "image/PNG", "pdf" => "application/pdf","PDF" => "application/PDF" );

		        $filename = $_FILES["file"]["name"];
		        $fileformat = $_FILES["file"]["type"];
		        $filesize = $_FILES["file"]["size"];       
		        $data= file_get_contents($_FILES["file"]["tmp_name"]);
		        $filetype=$_POST['type_doc'];

		        // Vérification de l'extension du fichier
		        $ext = pathinfo($filename, PATHINFO_EXTENSION);
		        if(!array_key_exists($ext, $allowed)) { die("Erreur : Veuillez sélectionner un format de fichier valide."); }

		        // Vérification de la taille du fichier (10Mo maximum)
		        $maxsize = 10 * 1024 * 1024;
		        if($fileformat > $maxsize) { die("Erreur : La taille du fichier est supérieure à la limite autorisée."); }

		        // Vérification du type MIME du fichier
		        if(in_array($fileformat, $allowed)) {

		            // Vérification si le fichier existe avant de l'enregistrer.
		            if(file_exists("C:/wamp64/www/hopital_client_serveur_avec_upload/mesDocumentsUploades/" . $_FILES["file"]["name"])) { // Windows
		            //if(file_exists("/Applications/MAMP/htdocs/hopital_client_serveur/mesDocumentsUploades/" . $_FILES["file"]["name"])) { // Mac OS
		                echo $_FILES["file"]["name"] . " existe déjà.";
		            } else {
		                move_uploaded_file($_FILES["file"]["tmp_name"], "C:/wamp64/www/hopital_client_serveur/mesDocumentsUploades/" . $_FILES["file"]["name"]); // Windows
		                //move_uploaded_file($_FILES["file"]["tmp_name"], "/Applications/MAMP/htdocs/hopital_client_serveur/mesDocumentsUploades/" . $_FILES["file"]["name"]); // Mac OS

		                echo'
						<div id="celluleGauche">      
						      <h4> Votre fichier a été enregistré avec succès. </h4>
						</div>';
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
				                $y -> bindParam(3,$_GET['param']);      
				                $y -> bindParam(4,$filetype);
				                $y -> bindParam(5,$fileformat);
				                $y -> bindParam(6,$date_actuelle);
				                $y -> bindParam(7,$data);
						        $y -> execute();
						} catch (Exception $e) {
							die('Erreur : ' . $e -> getMessage());
						}       
					}

		        } else {
		            echo "Erreur : Il y a eu un problème d'enregistrement de votre fichier. Veuillez réessayer."; 
		        }

		    } else {
		        echo "Erreur: " . $_FILES["file"]["error"];
		    }

		}
	   
	}

?>