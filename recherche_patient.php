<?php

	// Connexion à la base de données.
	$connexion = mysqli_connect("localhost", "user1", "hcetylop","hopital_php");
	// Message d'erreur en cas d'erreur de connexion à la base de données.
	if (mysqli_connect_errno()) {
		echo 'Failed to connect to MySQL: ' . mysqli_connect_error(); // utiliser la méthode die() ?
	}

 	// Récupération des motifs d'admission.
	$SelectAllMotifs = mysqli_query($connexion, "SELECT libelle FROM motif ORDER BY libelle ASC");
	// Récupération des noms des pays d'origine.
	$SelectAllPays = mysqli_query($connexion, "SELECT libelle FROM pays ORDER BY libelle ASC");
	// Récupérations des noms, prénoms et codes des patients.
	$requete = "SELECT DISTINCT patient.code, patient.nom, patient.prenom FROM patient, pays, motif WHERE patient.code_pays = pays.code AND patient.code_motif = motif.code ";

	if(isset($_POST['rechercher_patient'])) {
		
		// Récupération des noms, prénoms et codes des patients correspondants grâce à la barre de recherche.
		$nomPatient = utf8_decode($_POST['nomPatient']);
		$nomPatient = htmlspecialchars($nomPatient); // pour sécuriser le formulaire contre les intrusions html
		$nomPatient = strip_tags($nomPatient); // pour supprimer les balises html dans la requête
		if(isset($nomPatient)  && !empty($nomPatient)) {
			$requete = $requete."AND patient.nom LIKE '%".$nomPatient."%'";
		}
		
		// Récupération des noms, prénoms et codes des patients correspondants grâce au motif d'admission.
		$motifAdmission = utf8_decode($_POST['motifAdmission']);
		if($motifAdmission != "vide") {
			$requete = $requete."AND motif.libelle = '".$motifAdmission."' ";
		}
		
		// Récupération des noms, prénoms et codes des patients correspondants grâce au pays d'origine.
		$nomPays = utf8_decode($_POST['nomPays']);
		if($nomPays != "vide") {
			$requete = $requete."AND pays.libelle = '".$nomPays."' ";
		}
		
		// Récupération des noms, prénoms et codes des patients correspondants grâce à l'intervalle des dates de naissances.
		$dateDebut = utf8_decode($_POST['dateDebut']);
		$dateFin = utf8_decode($_POST['dateFin']);
		if($dateDebut != "vide" OR $dateFin != "vide") {
			// Recherche des patients correspondants entre le 1er janvier de la date de début et le 31 décembre de la date de fin.
			$requete = $requete."AND patient.date_naissance BETWEEN '".$dateDebut."-01-01' AND '".$dateFin."-12-31' ";
		}
		
		// Récupération des noms, prénoms et codes des patients correspondants, par ordre alphabétique des noms puis par ordre alphabétique des prénoms (si le nom est identique).
		$resultat_patient = mysqli_query($connexion, $requete."ORDER BY patient.nom, patient.prenom;");
	}

	echo'

	<div id="celluleGauche">
		<h2> Rechercher un patient </h2>
			
		<form action="index.php" method="post" class="formulaire">
			<fieldset>
			
				<legend>Formulaire</legend></br>';

				// Nom du patient
				echo'
				<h4>Nom du patient :</h4>
				<input type="text" name="nomPatient" id="nomPatient" placeholder="ex : DUPONT"/></br>';

				// Motif d'admission
				echo'
				<h4>Motif d\'admission :</h4>
				<select name="motifAdmission" id="motifAdmission">
					<option selected="selected" value="vide">Indifférent</option>';
					while($dataR1 = mysqli_fetch_array($SelectAllMotifs))
					{
						echo'<option value="'.utf8_encode($dataR1["libelle"]).'">'.utf8_encode($dataR1["libelle"]).'</option>';
					}
				echo'
				</select>';

				// Pays d'origine
				echo'
				<h4>Pays d\'origine :</h4>
				<select name="nomPays" id="nomPays">
					<option selected="selected" value="vide">Indifférent</option>';
					while($dataR2 = mysqli_fetch_array($SelectAllPays))
					{
						echo'<option value="'.utf8_encode($dataR2["libelle"]).'">'.utf8_encode($dataR2["libelle"]).'</option>';
					}
				echo'
				</select>';
				
				// Intervalle des dates de naissances
				echo'
				<h4>Intervalle des dates de naissances :</h4>
				<div id="gauche">
					<select name="dateDebut" id="dateDebut">
						<option selected="selected" value="vide">Indifférent</option>';
						for($i = date('Y'); $i >= date('Y') - (date('Y') - 1900); $i--) // pour être sûr que c'est jusqu'à l'an 1900
						{
							echo'<option value="'.$i.'">'.$i.'</option>';
						}
					echo'
					</select>
				</div>
				
				<div id="droite">
					<select name="dateFin" id="dateFin">
						<option selected="selected" value="vide">Indifférent</option>';
						for($i = date('Y'); $i >= date('Y') - (date('Y') - 1900); $i--) // pour être sûr que c'est jusqu'à l'an 1900
						{
							echo'<option value="'.$i.'">'.$i.'</option>';
						}
					echo'
					</select>
				</div>
				
				<input type="submit" name="rechercher_patient" value="Rechercher" id="submit">
				
			</fieldset>
		</form>
	</div>';

?>