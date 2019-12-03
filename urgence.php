<?php

	// Connexion à la base de données.
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=hopital_php; charset=utf8', 'user1', 'hcetylop');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	}catch (Exception $e){
        die('Erreur : ' . $e->getMessage());
	}
	
	if(isset($_POST['recuperer'])) {
		$id=32;
		$reponse = $bdd->query('select nom_fichier,contenu FROM document') or die("Error query failed");
		
		$nomDossier = utf8_decode($_POST['nom_dossier']);
		$nomDossier = htmlspecialchars($nomDossier); // pour sécuriser le formulaire contre les intrusions html
		$nomDossier = strip_tags($nomDossier); // pour supprimer les balises html dans la requête
		
		$directoryName = 'C:/'.$nomDossier.'/'; // Windows
		// $directoryName = '/'.$_GET['nom_dossier'].'/'; // Mac OS
		
		// Vérification si le dossier existe déjà.
		if(!is_dir($directoryName)){
			// Si le dossier n'existe pas, on le créé.
			mkdir($directoryName, 0755);
		}
		
		while($donnees = $reponse->fetch()){
			$b=$donnees['contenu'];
			$a=$donnees['nom_fichier'];
			file_put_contents($directoryName.$a,$b);
		}
	}
	
	echo'
	<div id="celluleGauche">
		<h2>Extraction d&apos;urgence des documents de la BD</h2>
		<form action="index.php" method="post" class="formulaire">
			<fieldset>
			</br>
			<h4>Nom du dossier de destination :</h4>
			<input type="text" name="nom_dossier" placeholder="ex : urgence" required/>
			<input type="submit" name="recuperer" value="Récuperer" id="submit">
			</fieldset>
		</form>
	</div>';
	

?>