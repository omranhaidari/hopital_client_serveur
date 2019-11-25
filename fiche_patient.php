<?php

if(isset($_GET['param'])) {
	// RÃ©cupÃ©ration des données du patient sélectionné.
	$selectProfil = mysqli_query($connexion, "SELECT patient.nom, patient.prenom, patient.sexe, patient.date_naissance, patient.num_secu, patient.code_pays, patient.date_prem_entree, motif.libelle FROM patient, motif WHERE patient.code_motif = motif.code AND patient.code = ".$_GET['param']);
	$selectDocuments = mysqli_query($connexion, "SELECT nom_fichier, type, format, date FROM patient, document WHERE patient.code = document.code_patient AND patient.code = ".$_GET['param']);

	echo' 
	<div id="celluleDroite">
		<h2>Fiche dÃ©taillÃ©e du patient</h2>
		
		<table>
		
			<tr>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Sexe</th>
				<th>Date de naissance</th>
				<th>NumÃ©ro Sécu</th>
				<th>Code pays</th>
				<th>Date premiÃ¨re entrÃ©e</th>
				<th>Motif</th>
			</tr>';

			// Affichage de la fiche dÃ©taillée du patient sélectionné, dont l'identifiant a été passé en paramètre (par URL), sous forme d'un tableau.
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
	</div>
	
	<div id="celluleDroite">
		<h2>Liste documents</h2>
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
			while($dataR5 = mysqli_fetch_array($selectDocuments))
			{
				echo'
				<tr>
					<td>'.utf8_encode($dataR5["nom_fichier"]).'</td>
					<td>'.utf8_encode($dataR5["type"]).'</td>
					<td>'.utf8_encode($dataR5["format"]).'</td>
					<td>'.date("d/m/Y", strtotime(utf8_encode($dataR5["date"]))).'</td>
					<td><a href="http://google.com"><img src="images/preview-file.png" title="PrÃ©visualiser le document" alt="preview-file" height="30px" width="30px"/></a></td>
					<td><a href="http://google.com"><img src="images/download-file.jpg" title="TÃ©lÃ©charger le document" alt="download-file" height="25px" width="25px"/></a></td>
					<td><a href="http://google.com"><img src="images/print-file.png" title="Imprimer le document" alt="print-file" height="30px" width="30px"/></a></td>
					<td><a href="http://google.com"><img src="images/share-file.png" title="Partager le document par email" alt="share-file" height="30px" width="30px"/></a></td>
				</tr>';
			}
				
		echo'</table>';
		
	echo'</div>';
        echo'
    <div id="celluleGauche">
		<h2>Uploader un nouveau document</h2>
        <form method="post" enctype="multipart/form-data" class="formulaire">
       <fieldset>
        <h4>Fichier :</h4>
        <input type="file" name="file" id="file">
   <h4>Type du document :</h4>

    <select name="type_doc" id="type_doc" width="10p%">
    <option value="">--Please choose a type--</option>
    <option value="ordonnance">Ordonnance</option>
    <option value="radio">Radio</option>
    <option value="bilan_sanguin">Bilan sanguin</option>
    <option value="bilan_general">Bilan general</option>
   
</select>
        <input type="submit" name="submit" value="Upload" id="submit">
        <p><strong>Note:</strong> Seuls les formats .jpg, .jpeg, .gif, .png et .pdf sont autorisés jusqu\'à une taille maximale de 10 Mo.</p>
        </fieldset>
    </form>
                </table>

</div>';



// Vérifier si le formulaire a été soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Vérifie si le fichier a été uploadé sans erreur.
    if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "JPG" => "image/JPG", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png","PNG" => "image/PNG", "pdf" => "application/pdf","PDF" => "application/PDF" );
        $filename = $_FILES["file"]["name"];
        $fileformat = $_FILES["file"]["type"];
        $filesize = $_FILES["file"]["size"];       
        $data= file_get_contents($_FILES["file"]["tmp_name"]);
        $filetype=$_POST['type_doc'];

        // Vérifie l'extension du fichier
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Erreur : Veuillez sélectionner un format de fichier valide.");

        // Vérifie la taille du fichier - 10Mo maximum
        $maxsize = 10 * 1024 * 1024;
        if($fileformat > $maxsize) die("Error: La taille du fichier est supérieure à la limite autorisée.");

        // Vérifie le type MIME du fichier
        if(in_array($fileformat, $allowed)){
            // Vérifie si le fichier existe avant de le télécharger.
            if(file_exists("C:/wamp64/www/hopital_client_serveur_avec_upload/mesDocumentsUploades/" . $_FILES["file"]["name"])){
                echo $_FILES["file"]["name"] . " existe déjà.";
            } else{
                move_uploaded_file($_FILES["file"]["tmp_name"], "C:/wamp64/www/hopital_client_serveur_avec_upload/MesDocumentsUploadés/" . $_FILES["file"]["name"]);
                //echo "Votre fichier a ete telecharge avec succes.";
                echo'
<div id="celluleGauche">      
  
      <h4> Votre fichier a ete telecharge avec succes. </h4>           

</div>';
            echo $_GET['param'];
              //récuperer la date actuelle 
                  $date_actuelle = date('Y-m-d');
                  //construite une var unique pour le hachage
                  //fonction de hachage 
                  $fonctionmd5 =md5($filename . date("Y-m-d H:i:s"));
                try
{
	$bdd = new PDO('mysql:host=localhost;dbname=hopital_php;charset=utf8', 'user1', 'hcetylop');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         //récuperer le nom et prenom du patient connecté 
        /*$x=$bdd->prepare("SELECT patient.nom, patient.prenom FROM patient where patient.code = ".$_GET['param']);
      
        $donnees = $x->fetch();
            
            $nom=$donnees['nom'];
            $prenom=$donnees['prenom'];
            
            echo $nom;
            echo $prenom;*/
        
                $y = $bdd->prepare("INSERT INTO document(nom_fichier,clef,code_patient,type,format,date,contenu) VALUES(?,?,?,?,?,?,?)")or die(print_r($bdd->errorInfo()));
                $y->bindParam(1,$filename);
                $y->bindParam(2,$fonctionmd5);
                $y->bindParam(3,$_GET['param']);      
                $y->bindParam(4,$filetype);
                $y->bindParam(5,$fileformat);
                $y->bindParam(6,$date_actuelle);
                $y->bindParam(7,$data);
	        $y->execute();
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
             
            } 
        } else{
            echo "Error: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer."; 
        }
    } else{
        echo "Error: " . $_FILES["file"]["error"];
    }
}

        
}

?>