<?php

	// Connexion à la base de données.
	$connexion = mysqli_connect("localhost", "user1", "hcetylop","hopital_php");
	// Message d'erreur en cas d'erreur de connexion à la base de données.
	if (mysqli_connect_errno()) {
		echo 'Failed to connect to MySQL: ' . mysqli_connect_error(); // utiliser la méthode die() ?
	}

 	// Récupération des types de documents.
	$SelectAllTypes = mysqli_query($connexion, "SELECT DISTINCT type FROM document ORDER BY type ASC");
	// Récupération des formats de documents.
	$SelectAllFormats = mysqli_query($connexion, "SELECT DISTINCT format FROM document ORDER BY format ASC");
	// Récupération des noms, types, formats et dates d'enregistrement.
	$requete = "SELECT DISTINCT document.nom_fichier, document.type, document.format, document.date FROM patient, document WHERE patient.code = document.code_patient ";

	if(isset($_POST['rechercher_document'])) {
		
		// Récupération des noms, types, formats et dates d'enregistrement des documents correspondants grâce au type de document.
		$nomDocument = utf8_decode($_POST['nomDocument']);
		$nomDocument = htmlspecialchars($nomDocument); // pour sécuriser le formulaire contre les intrusions html
		$nomDocument = strip_tags($nomDocument); // pour supprimer les balises html dans la requête
		
		if($nomDocument != null) {
			$requete = $requete."AND document.nom_fichier LIKE '".$nomDocument."%' ";
		}
		
		// Récupération des noms, types, formats et dates d'enregistrement des documents correspondants grâce au type de document.
		$typeDocument = utf8_decode($_POST['typeDocument']);
		if($typeDocument != "vide") {
			$requete = $requete."AND document.type = '".$typeDocument."' ";
		}
		
		// Récupération des noms, types, formats et dates d'enregistrement des documents correspondants grâce au format de document.
		$formatDocument = utf8_decode($_POST['formatDocument']);
		if($formatDocument != "vide") {
			$requete = $requete."AND document.format = '".$formatDocument."' ";
		}
		
		// Récupération des noms, types, formats et dates d'enregistrement des documents correspondants grâce à l'intervalle des dates d'enregistrement.
		$dateDebut = utf8_decode($_POST['dateDebut']);
		$dateFin = utf8_decode($_POST['dateFin']);
		if($dateDebut != "vide" OR $dateFin != "vide") {
			// Recherche des documents enregistrés entre le 1er janvier de la date de début et le 31 décembre de la date de fin.
			$requete = $requete."AND document.date BETWEEN '".$dateDebut."-01-01' AND '".$dateFin."-12-31' ";
		}
		
		// Récupération des noms, types, formats et dates d'enregistrement des documents correspondants, par ordre alphabétique des noms de documents.
		$resultat_document = mysqli_query($connexion, $requete."ORDER BY document.nom_fichier;");
	}

	echo'

	<div id="celluleGauche">
		<h2> Rechercher un document </h2>
			
		<form action="index.php" method="post" class="formulaire">
			<fieldset>
			
				<legend>Formulaire</legend></br>';
				// Nom du document
				echo'
				<h4>Nom du document :</h4>
				<input type="text" name="nomDocument" id="nomDocument" placeholder="ex : bulletin1"/></br>';

				// Type de document
				echo'
				<h4>Type de document :</h4>
				<select name="typeDocument" id="typeDocument">
					<option selected="selected" value="vide">Indifférent</option>';
					while($dataR1 = mysqli_fetch_array($SelectAllTypes))
					{
						echo'<option value="'.utf8_encode($dataR1["type"]).'">'.utf8_encode($dataR1["type"]).'</option>';
					}
				echo'
				</select>';

				// Format de document
				echo'
				<h4>Format de document :</h4>
				<select name="formatDocument" id="formatDocument">
					<option selected="selected" value="vide">Indifférent</option>';
					while($dataR2 = mysqli_fetch_array($SelectAllFormats))
					{
						echo'<option value="'.utf8_encode($dataR2["format"]).'">'.utf8_encode($dataR2["format"]).'</option>';
					}
				echo'
				</select>';
				
				// Intervalle des dates d'enregistrement
				echo'
				<h4>Intervalle des dates d\'enregistrement :</h4>
				<div id="gauche">
					<select name="dateDebut" id="dateDebut">
						<option selected="selected" value="vide">Indifférent</option>';
						for($i = date('Y'); $i >= date('Y') - (date('Y') - 2000); $i--) // pour être sûr que c'est jusqu'à l'an 2000
						{
							echo'<option value="'.$i.'">'.$i.'</option>';
						}
					echo'
					</select>
				</div>
				
				<div id="droite">
					<select name="dateFin" id="dateFin">
						<option selected="selected" value="vide">Indifférent</option>';
						for($i = date('Y'); $i >= date('Y') - (date('Y') - 2000); $i--) // pour être sûr que c'est jusqu'à l'an 2000
						{
							echo'<option value="'.$i.'">'.$i.'</option>';
						}
					echo'
					</select>
				</div>
				
				<input type="submit" name="rechercher_document" value="Rechercher" id="submit">
				
			</fieldset>
		</form>
	</div>';

?>